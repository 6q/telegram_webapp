<?php
/*
Plugin Name: Dokan EasyPost Shipping - Edibly
Description: Let sellers add EasyPost shipping options
Version: 1.0
Author: Luminary
Author URI: http://luminary.ws/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EasyPostShipping' ) ) :
	class EasyPostShipping {
		protected static $_instance = null;
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}


		public function __construct() {
			require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

			// EasyPost API key tied to this email: anthony@edibly.co
			\EasyPost\EasyPost::setApiKey( 'DcPrUSgZF3CU9ei1cKWKpw' );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'plugins_loaded', array( $this, 'plugins_are_loaded' ) );
			add_action( 'dokan_process_product_meta', array( $this, 'save_product_shipping_from_post' ), 1 );
			add_filter( 'ot_shipping_options_for_product', array( $this, 'add_shipping_options' ), 10, 3 );
			add_action( 'dokan_order_detail_before_order_items', array( $this, 'load_easypost_ordering_panel' ), 10, 1 );
			add_action( 'wp_ajax_easypost_generate_label', array( $this, 'generate_label' ), 10, 2 );
			add_action( 'wp_ajax_nopriv_easypost_generate_label', array( $this, 'generate_label' ), 10, 2 );
			add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_meta' ), 10, 1 );
			add_action( 'woocommerce_order_item_meta_end', array( $this, 'add_tracking_link_to_order_completed_emails' ), 10, 3 );
			add_filter( 'dokan_can_post', array( $this, 'require_shop_meta' ), 10, 1 );
			add_action( 'dokan_can_post_notice', array( $this, 'can_post_notice' ) );
		}

		public function enqueue_scripts() {

			if ( dokan_get_option( 'dashboard', 'dokan_pages' ) == get_queried_object_id() ) {
				$this->load_scripts();
			} elseif ( is_singular('product') && dokan_is_user_seller( get_current_user_id() ) || 'administrator' == $this->get_user_role() ) {
				$this->load_scripts();
			}
		}

		public function load_scripts() {

			wp_enqueue_script( 'dokan-easypost', plugin_dir_url( __FILE__ ) . 'assets/js/dokan-easypost-shipping.min.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) .  'assets/js/dokan-easypost-shipping.min.js' ) );
			wp_enqueue_style( 'dokan-easypost', plugin_dir_url( __FILE__ ) . 'assets/css/dokan-easypost-shipping.css', array(), filemtime( plugin_dir_path( __FILE__ ) .  'assets/css/dokan-easypost-shipping.css' ) );
		}

		/*
		 * Get user's role
		 *
		 * If $user parameter is not provided, returns the current user's role.
		 * Only returns the user's first role, even if they have more than one.
		 *
		 * @param int|object $user User ID or object
		 * @return string $role The User's role
		 */
		protected function get_user_role( $user = false ) {

			if ( is_object( $user ) ) {
				return $user->roles[0];
			} elseif ( is_int( $user ) ) {
				$user_data = get_userdata( $user );
				$roles     = $user_data->roles;
				return $roles[0];
			} else {
				$user_data = get_userdata( get_current_user_id() );
				$roles     = $user_data->roles;
				return $roles[0];
			}
		}

		function plugins_are_loaded() {
			remove_action( 'dokan_product_options_shipping', array( OneThirteenShipping::instance(), 'load_product_shipping_template' ), 1 );
			add_action( 'dokan_product_options_shipping', array( $this, 'load_product_shipping_template' ), 1 );
			add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'load_variable_product_shipping_template' ), 10, 3 );
		}

		function require_shop_meta($can_sell){
			$profile_info 	 = dokan_get_store_info( get_current_user_id() );
			$address         = isset( $profile_info['address'] ) ? $profile_info['address'] : '';
			$storename		 = isset( $profile_info['store_name'] ) ? esc_attr( $profile_info['store_name'] ) : '';
			$address_street1 = isset( $profile_info['address']['street_1'] ) ? $profile_info['address']['street_1'] : '';
			$address_street2 = isset( $profile_info['address']['street_2'] ) ? $profile_info['address']['street_2'] : '';
			$address_city    = isset( $profile_info['address']['city'] ) ? $profile_info['address']['city'] : '';
			$address_zip     = isset( $profile_info['address']['zip'] ) ? $profile_info['address']['zip'] : '';
			$address_country = isset( $profile_info['address']['country'] ) ? $profile_info['address']['country'] : '';
			$address_state   = isset( $profile_info['address']['state'] ) ? $profile_info['address']['state'] : '';
			$phone      	 = isset( $profile_info['phone'] ) ? esc_attr( $profile_info['phone'] ) : '';
			if ( empty( $address ) || empty( $storename ) || ( empty( $address_street1 ) && empty( $address_street2 ) ) || empty( $address_city ) || empty( $address_zip ) || empty( $address_country ) || empty( $address_state ) || empty( $phone ) ) {
				return false;
			}

			return $can_sell;
		}

		function can_post_notice() {
			echo 'You do not have the ability to create products yet! Please make sure you have filled out the address and phone number in your store settings.';
		}

		function hide_meta( $meta_to_hide ) {
			$output = $meta_to_hide;
			array_push( $output, '_shipping', '_postage_label', '_tracking_code' );

			return $output;
		}

		function generate_label() {
			$shipment_id = sanitize_text_field( $_POST['shipment'] );
			$rate_id     = sanitize_text_field( $_POST['rate'] );
			$item_id     = sanitize_text_field( $_POST['item'] );
			$shipment    = \EasyPost\Shipment::retrieve( $shipment_id );

			$selected_rate = $shipment->lowest_rate();
			foreach ( $shipment['rates'] as $key => $rate ) {
				if ( $rate->id == $rate_id ) {
					$selected_rate = $rate;
				}
			}

			$shipment->buy( $selected_rate );
			$shipment->label( array( 'file_format' => 'pdf' ) );

			wc_add_order_item_meta( $item_id, '_postage_label', $shipment->postage_label->label_pdf_url );
			wc_add_order_item_meta( $item_id, '_tracking_code', $shipment->tracking_code );

			echo json_encode( array(
				'label' => $shipment->postage_label->label_pdf_url,
			    'tracking_code' => $shipment->tracking_code,
			) );

			wp_die();
		}

		/*
		 * Add View Tracking link to customer order completed emails
		 */
		function add_tracking_link_to_order_completed_emails( $item_id, $item, $order ){

			$tracking_code = woocommerce_get_order_item_meta( $item_id, '_tracking_code' );

			if ( ! $tracking_code ) {
				return;
			}

			$product_id   = $item['product_id'];
			$variation_id = $item['variation_id'];
			$perishable   = get_post_meta( $product_id, '_dokan_easypost_perishable', true );

			if ( $variation_id > 0 ) {
				$shipping_option = $this->get_shipping_option( $variation_id );
			} else {
				$shipping_option = $this->get_shipping_option( $product_id );
			}

			if ( 'no' === $perishable && 'priority-flat-rate' === $shipping_option || 'priority-flat-rate' === $shipping_option ) : ?>
				<br><a href="https://tools.usps.com/go/TrackConfirmAction.action?tLabels=<?php echo esc_attr( $tracking_code ); ?>" target="_blank">View Tracking</a>
			<?php else : ?>
				<br><a href="https://www.fedex.com/apps/fedextrack/?tracknumbers=<?php echo esc_attr( $tracking_code ); ?>" target="_blank">View Tracking</a>
			<?php endif;
		}

		function load_easypost_ordering_panel($order){
			?>
			<script>
				function generateLabel(shipment, rate, item){
					jQuery.post("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", {action: "easypost_generate_label", shipment: shipment, rate: rate, item: item}, function(data){
						location.reload();
					});
					return false;
				}
			</script>
			<div class="" style="100%">
                <div class="dokan-panel dokan-panel-default">
                    <div class="dokan-panel-heading"><strong><?php _e( 'Postage Purchasing', 'dokan' ); ?></strong></div>
                    <div class="dokan-panel-body">
	                    <table cellpadding="0" cellspacing="0" class="dokan-table order-items">
                            <thead>
                                <tr>
                                    <th class="item"><?php _e( 'Item', 'dokan' ); ?></th>

                                    <th class=""><?php _e( 'Carrier', 'dokan' ); ?></th>

                                    <th class=""><?php _e( 'Type', 'dokan' ); ?></th>
                                    
                                    <th class=""><?php _e( 'Postage', 'dokan' ); ?></th>
                                </tr>
                            </thead>
                            <tbody id="order_items_list">

                                <?php
                                    // List order items
                                    $order_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', array( 'line_item' ) ) );
                                    foreach ( $order_items as $item_id => $item ) {
                                        $_product   = $order->get_product_from_item( $item );
                                        $item_meta  = $order->get_item_meta( $item_id );
										
										echo '<tr>';
										echo '<td>';
										if ( $_product ) : ?>
											<a target="_blank" href="<?php echo esc_url( get_permalink( $_product->id ) ); ?>">
												<?php echo esc_html( $item['name'] ); ?>
											</a>
										<?php else : ?>
											<?php echo esc_html( $item['name'] ); ?>
										<?php endif; 
										echo '</td>';
										$shipping_type = woocommerce_get_order_item_meta($item_id, '_shipping');
										$shipping_type = explode(" ", $shipping_type);
										echo '<td>';
										echo $shipping_type[0];
										echo '</td>';
										echo '<td>';
										echo $shipping_type[1];
										echo '</td>';



	                                    echo '<td id="shipping-tools-' . $item_id . '">';
	                                    array_filter( $shipping_type );
	                                    if ( !empty($shipping_type) ) {

	                                    	if($shipping_type[1] == 'Pickup' || $shipping_type[1] == 'Delivery') {
	                                    		echo 'No Postage';
	                                    	}
	                                    	else {

			                                    $product_id   = $item['product_id'];
			                                    $variation_id = $item['variation_id'];

			                                    if ( $variation_id > 0 ) {
				                                    $shipping_option = $this->get_shipping_option( $variation_id );
			                                    } else {
				                                    $shipping_option = $this->get_shipping_option( $product_id );
			                                    }

			                                    $postage_label = woocommerce_get_order_item_meta( $item_id, '_postage_label' );
			                                    $tracking_code = woocommerce_get_order_item_meta( $item_id, '_tracking_code' );

			                                    if ( $postage_label ) { ?>
				                                    <a href="<?php echo esc_url( $postage_label ); ?>" target="_blank">View Label</a>
				                                    <?php if ( $tracking_code ) : ?>
					                                    <?php if ( 'priority-flat-rate' == $shipping_option || 'priority-flat-rate' == $shipping_option ) : ?>
						                                    <br><a href="https://tools.usps.com/go/TrackConfirmAction.action?tLabels=<?php echo esc_attr( $tracking_code ); ?>" target="_blank">View Tracking</a>
					                                    <?php else : ?>
					                                        <br><a href="https://www.fedex.com/apps/fedextrack/?tracknumbers=<?php echo esc_attr( $tracking_code ); ?>" target="_blank">View Tracking</a>
					                                    <?php endif; ?>
				                                    <?php endif;
			                                    } else {
				                                    if ( $variation_id > 0 ) {
					                                    $variation = new WC_Product_Variation( $variation_id );
					                                    $length    = round( floatval( $variation->get_length() ), 1 );
					                                    $width     = round( floatval( $variation->get_width() ), 1 );
					                                    $height    = round( floatval( $variation->get_height() ), 1 );
					                                    $weight    = round( floatval( $variation->get_weight() ), 1 );
					                                    $box_size  = $this->get_box_size( $variation_id, $shipping_option );
				                                    } else {
					                                    $length   = round( floatval( get_post_meta( $product_id, '_length', true ) ), 1 );
					                                    $width    = round( floatval( get_post_meta( $product_id, '_width', true ) ), 1 );
					                                    $height   = round( floatval( get_post_meta( $product_id, '_height', true ) ), 1 );
					                                    $weight   = round( floatval( get_post_meta( $product_id, '_weight', true ) ), 1 );
					                                    $box_size = $this->get_box_size( $product_id, $shipping_option );
				                                    }

				                                    // Convert weight to ounces
				                                    if ( ! empty( $weight ) ) {
					                                    $weight = $weight * 16;
				                                    }

				                                    // Get To & From shipping data
				                                    $to_shipping_data   = $this->get_to_shipping_data_from_order( $order );
				                                    $from_shipping_data = $this->get_from_shipping_data( $product_id );

				                                    // Get EasyPost shipping object
				                                    $shipment = $this->get_easypost_shipment( $to_shipping_data, $from_shipping_data, $length, $width, $height, $weight, $box_size );

				                                    $selected_rate = $shipment->lowest_rate();
				                                    if ( is_array( $shipment['rates'] ) ) {
					                                    foreach ( $shipment['rates'] as $key => $rate ) {
						                                    if ( $rate->service == $shipping_type[1] && $rate->carrier == $shipping_type[0] ) {
							                                    $selected_rate = $rate;
						                                    }
					                                    }
				                                    }

				                                    ?>
				                                    <a href="#" onclick="return generateLabel('<?php echo $shipment['id']; ?>', '<?php echo $selected_rate['id']; ?>', '<?php echo $item_id; ?>')">Generate Label</a>
				                                    <?php
			                                    }
			                                }

	                                    }
	                                    echo '</td>';
										echo '</tr>';
                                    }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
			<?php
		}

		// Seller product shipping UI
		function load_product_shipping_template() {
			load_template( plugin_dir_path( __FILE__ ) . 'templates/template-product-shipping.php' );
		}

		function load_variable_product_shipping_template( $loop, $variation_data, $variation ) {
			include( locate_template( plugin_dir_path( __FILE__ ) ) . 'templates/template-variable-shipping.php' );
		}

		function save_product_shipping_from_post() {

			$product_id   = $_POST['dokan_product_id'];
			$product_type = empty( $_POST['_product_type'] ) ? 'simple' : sanitize_title( stripslashes( $_POST['_product_type'] ) );

			update_post_meta( $product_id, '_dokan_easypost_perishable', sanitize_text_field( $_POST['easypost_perishable'] ) );

			if ( 'simple' == $product_type ) {
				$this->save_simple_product_shipping_from_post( $product_id );
			} elseif ( 'variable' == $product_type ) {
				$this->save_variable_product_shipping_from_post();
			}
		}

		function save_simple_product_shipping_from_post( $product_id ) {

			update_post_meta( $product_id, '_dokan_easypost_shipping_option', sanitize_text_field( $_POST['easypost_shipping_option_simple'] ) );
			update_post_meta( $product_id, '_dokan_easypost_flat_rate_box', sanitize_text_field( $_POST['easypost_flat_rate_box_simple'] ) );
			update_post_meta( $product_id, '_dokan_easypost_regional_rate_box', sanitize_text_field( $_POST['easypost_regional_rate_box_simple'] ) );
		}

		function save_variable_product_shipping_from_post() {

			$variable_post_id           = isset( $_POST['variable_post_id'] ) ? $_POST['variable_post_id'] : array();
			$easypost_shipping_option   = isset( $_POST['easypost_shipping_option'] ) ? $_POST['easypost_shipping_option'] : array();
			$easypost_flat_rate_box     = isset( $_POST['easypost_flat_rate_box'] ) ? $_POST['easypost_flat_rate_box'] : array();
			$easypost_regional_rate_box = isset( $_POST['easypost_regional_rate_box'] ) ? $_POST['easypost_regional_rate_box'] : array();

			$max_loop = max( array_keys( $_POST['variable_post_id'] ) );

			for ( $i = 0; $i <= $max_loop; $i++ ) {

				if ( ! isset( $variable_post_id[ $i ] ) ) {
					continue;
				}

				$variation_id      = absint( $variable_post_id[ $i ] );
				$shipping_option   = $easypost_shipping_option[ $i ];
				$flat_rate_box     = $easypost_flat_rate_box[ $i ];
				$regional_rate_box = $easypost_regional_rate_box[ $i ];

				update_post_meta( $variation_id, '_dokan_easypost_shipping_option', $shipping_option );
				update_post_meta( $variation_id, '_dokan_easypost_flat_rate_box', $flat_rate_box );
				update_post_meta( $variation_id, '_dokan_easypost_regional_rate_box', $regional_rate_box );

			}
		}

		function add_shipping_options( $options, $product_id, $variation_id ) {

			$length       = '';
			$width        = '';
			$height       = '';
			$box_size     = '';
			$product_id   = sanitize_text_field( $product_id );
			$variation_id = sanitize_text_field( $variation_id );
			$perishable   = get_post_meta( $product_id, '_dokan_easypost_perishable', true );

			if ( $variation_id > 0 ) {
				$variation = new WC_Product_Variation( $variation_id );
				$weight    = round( floatval( $variation->get_weight() ), 1 );

				if ( 'yes' === $perishable ) {
					$length = round( floatval( $variation->get_length() ), 1 );
					$width  = round( floatval( $variation->get_width() ), 1 );
					$height = round( floatval( $variation->get_height() ), 1 );
				} else {
					$shipping_option = $this->get_shipping_option( $variation_id );
					$box_size        = $this->get_box_size( $variation_id, $shipping_option );
				}
			} else {
				$weight = round( floatval( get_post_meta( $product_id, '_weight', true ) ), 1 );

				if ( 'yes' === $perishable ) {
					$length = round( floatval( get_post_meta( $product_id, '_length', true ) ), 1 );
					$width  = round( floatval( get_post_meta( $product_id, '_width', true ) ), 1 );
					$height = round( floatval( get_post_meta( $product_id, '_height', true ) ), 1 );
				} else {
					$shipping_option = $this->get_shipping_option( $product_id );
					$box_size        = $this->get_box_size( $product_id, $shipping_option );
				}
			}

			// If it's perishable but we don't have the dimensions & weight necessary, don't add any shipping options
			// If it's nonperishable and we don't have the box size necessary, don't add any shipping options
			if ( 'yes' === $perishable ) {
				if ( empty( $length ) || empty( $width ) || empty( $height ) || empty( $weight ) ) {
					return $options;
				}
			} else {
				if ( empty( $box_size ) ) {
					return $options;
				}
			}

			// Convert weight to ounces
			if ( ! empty( $weight ) ) {
				$weight = $weight * 16;
			}

			$post_data = array();
			if ( isset( $_POST['post_data'] ) ) {
				parse_str( $_POST['post_data'], $post_data );
			}

			// Get To & From shipping data
			$to_shipping_data   = $this->get_to_shipping_data( $post_data );
			$from_shipping_data = $this->get_from_shipping_data( $product_id );

			// Get Easypost shipment object
			$shipment = $this->get_easypost_shipment( $to_shipping_data, $from_shipping_data, $length, $width, $height, $weight, $box_size );

			// Add any applicable shipping options
			foreach ( $shipment['rates'] as $key => $rate ) {
				if ( 'no' === $perishable ) {
					if ( 'USPS' == $rate->carrier && 'Priority' == $rate->service ) {
						$options[] = $this->add_shipping_option( $rate );
					}
				} else {
					if ( 'FedEx' == $rate->carrier && 'FEDEX_2_DAY' == $rate->service ) {
						$options[] = $this->add_shipping_option( $rate );
					} elseif ( 'FedEx' == $rate->carrier && 'PRIORITY_OVERNIGHT' == $rate->service ) {
						$options[] = $this->add_shipping_option( $rate );
					}
				}
			}

			// Set the cost of shipping additional quantities to be the same as the base shipping price
			foreach ( $options as $option ) {
				$option->additional_price = $option->price;
			}
	 		
			return $options;
		}

		protected function get_shipping_option( $id ) {
			return get_post_meta( $id, '_dokan_easypost_shipping_option', true );
		}

		protected function get_box_size( $id, $shipping_option ) {

			$box_size = '';

			if ( 'priority-flat-rate' == $shipping_option ) {
				$box_size = get_post_meta( $id, '_dokan_easypost_flat_rate_box', true );
			} elseif( 'priority-regional-rate' == $shipping_option ) {
				$box_size =  get_post_meta( $id, '_dokan_easypost_regional_rate_box', true );
			}

			// Translate the names of the box size into Easypost USPS terminology
			// https://www.easypost.com/service-levels-and-parcels
			switch ( $box_size ) {
				case 'small' :
					return 'SmallFlatRateBox';
				case 'medium-1' :
				case 'medium-2' :
					return 'MediumFlatRateBox';
				case 'large' :
					return 'LargeFlatRateBox';
				case 'a-side' :
				case 'a-top' :
					return 'RegionalRateBoxA';
				case 'b-side' :
				case 'b-top' :
					return 'RegionalRateBoxB';
				case 'c' :
					return 'RegionalRateBoxC';
			}

			return '';
		}

		protected function get_to_shipping_data( $post_data = '' ) {

			$to_shipping_data = array(
				'first_name' => $this->get_to_shipping_data_from_post_data( $post_data, 'shipping_first_name', 'billing_first_name' ),
				'last_name'  => $this->get_to_shipping_data_from_post_data( $post_data, 'shipping_last_name', 'billing_last_name' ),
				'street_1'   => $this->get_to_shipping_data_from_form( 's_address', 'shipping_address_1', 'billing_address_1', 'address' ),
				'street_2'   => $this->get_to_shipping_data_from_form( 's_address_2', 'shipping_address_2', 'billing_address_2', 'address_2' ),
				'city'       => $this->get_to_shipping_data_from_form( 's_city', 'shipping_city', 'billing_city', 'city' ),
				'state'      => $this->get_to_shipping_data_from_form( 's_state', 'shipping_state', 'billing_state', 'state' ),
				'postcode'   => $this->get_to_shipping_data_from_form( 's_postcode', 'shipping_postcode', 'billing_postcode', 'postcode' ),
				'country'    => $this->get_to_shipping_data_from_form( 's_country', 'shipping_country', 'billing_country', 'country' ),
				'phone'      => $this->get_to_shipping_data_from_post_data( $post_data, 'shipping_phone', 'billing_phone' ),
				'email'      => $this->get_to_shipping_data_from_post_data( $post_data, 'shipping_email', 'billing_email' ),
			);

			return $to_shipping_data;
		}

		protected function get_to_shipping_data_from_order( $order ) {

			$to_shipping_data = array(
				'first_name'  => $order->shipping_first_name,
				'last_name'   => $order->shipping_last_name,
				'street_1'    => $order->shipping_address_1,
				'street_2'    => $order->shipping_address_2,
				'city'        => $order->shipping_city,
				'state'       => $order->shipping_state,
				'postcode'    => $order->shipping_postcode,
				'country'     => $order->shipping_country,
				'phone'       => sanitize_text_field( get_post_meta( $order->id, '_billing_phone', true ) ),
				'email'       => '',
				'residential' => true
			);

			return $to_shipping_data;
		}

		protected function get_to_shipping_data_from_post_data( $post_data, $primary_source, $fallback_source ) {

			if ( ! empty( $post_data[ $primary_source ] ) ) {
				return sanitize_text_field( $post_data[ $primary_source ] );
			} elseif ( ! empty( $_POST[ $primary_source ] ) ) {
				return sanitize_text_field( $_POST[ $primary_source ] );
			} elseif ( ! empty( $_POST[ $fallback_source ] ) ) {
				return sanitize_text_field( $_POST[ $fallback_source ] );
			} elseif ( ! empty( $post_data[ $fallback_source ] ) ) {
				return sanitize_text_field( $post_data[ $fallback_source ] );
			}

			return '';
		}

		protected function get_to_shipping_data_from_form( $primary_source, $fallback_source_2, $fallback_source_3, $fallback_source_4 ) {

			$sources = array( $primary_source, $fallback_source_2, $fallback_source_3, $fallback_source_4 );

			foreach ( $sources as $source ) {
				if ( ! empty( $_POST[ $source ] ) ) {
					return sanitize_text_field( $_POST[ $source ] );
				}
			}

			return '';
		}

		protected function get_from_shipping_data( $product_id ) {

			$product_post = get_post( $product_id );
			$profile_info = dokan_get_store_info( $product_post->post_author );

			$from_shipping_data = array(
				'storename'       => isset( $profile_info['store_name'] ) ? esc_attr( $profile_info['store_name'] ) : '',
				'address_street1' => isset( $profile_info['address']['street_1'] ) ? $profile_info['address']['street_1'] : '',
				'address_street2' => isset( $profile_info['address']['street_2'] ) ? $profile_info['address']['street_2'] : '',
				'address_city'    => isset( $profile_info['address']['city'] ) ? $profile_info['address']['city'] : '',
				'address_zip'     => isset( $profile_info['address']['zip'] ) ? $profile_info['address']['zip'] : '',
				'address_country' => isset( $profile_info['address']['country'] ) ? $profile_info['address']['country'] : '',
				'address_state'   => isset( $profile_info['address']['state'] ) ? $profile_info['address']['state'] : '',
				'phone'           => isset( $profile_info['phone'] ) ? esc_attr( $profile_info['phone'] ) : '',
			);

			return $from_shipping_data;
		}

		protected function get_easypost_shipment( $to_shipping_data = array(), $from_shipping_data = array(), $length = null, $width = null, $height = null, $weight = null, $box_size = null ) {

			if($to_shipping_data['street_1'] && $to_shipping_data['city'] && $to_shipping_data['state'] && $to_shipping_data['postcode']) {

				$shipment = \EasyPost\Shipment::create( array(
					"to_address" => array(
						'name'        => $to_shipping_data['first_name'] . ' ' . $to_shipping_data['last_name'],
						'street1'     => $to_shipping_data['street_1'],
						'street2'     => $to_shipping_data['street_2'],
						'city'        => $to_shipping_data['city'],
						'state'       => $to_shipping_data['state'],
						'zip'         => $to_shipping_data['postcode'],
						'country'     => $to_shipping_data['country'],
						'phone'       => $to_shipping_data['phone'],
						'email'       => $to_shipping_data['email'],
						'residential' => true,
					),
					"from_address" => array(
						'name'        => $from_shipping_data['storename'],
						'street1'     => $from_shipping_data['address_street1'],
						'street2'     => $from_shipping_data['address_street2'],
						'city'        => $from_shipping_data['address_city'],
						'state'       => $from_shipping_data['address_state'],
						'zip'         => $from_shipping_data['address_zip'],
						'country'     => $from_shipping_data['address_country'],
						'phone'       => $from_shipping_data['phone'],
						'residential' => true,
					),
					"parcel" => array(
						'predefined_package' => ! empty( $box_size ) ? $box_size : null,
						'length'             => ! empty( $length ) ? $length : null,
						'width'              => ! empty( $width ) ? $width : null,
						'height'             => ! empty( $height ) ? $height : null,
						'weight'             => ! empty( $weight ) ? $weight : null,
					)
				) );

				return $shipment;

			}
			else {
				return false;
			}
		}

		protected function add_shipping_option( $rate ) {

			return json_decode( json_encode( array(
				'label'            => $rate->carrier . ' ' . $rate->service,
				'price'            => floatval( $rate->rate ),
				'additional_price' => 0.0,
				'id'               => sprintf( "%s-%s", $rate->carrier, $rate->service )
			) ), false );
		}
	}
endif;

/*
 * Instantiate the EasyPostShipping class
 */
function EPShipping() {
	return EasyPostShipping::instance();
}
EPShipping();