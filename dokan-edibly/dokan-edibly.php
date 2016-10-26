<?php
/*
Plugin Name: Dokan - Edibly
Plugin URI: http://luminary.ws/
Description: Location based changes to Dokan for Edibly
Version: 1.0
Author: Luminary
Author URI: http://luminary.ws/
*/

function remove_store_support_settings(){
	global $dss;
	remove_action('dokan_settings_form_bottom', array($dss, 'add_support_btn_title_input'), 13);
}
add_action('init', 'remove_store_support_settings');

function edibly_save_local_shipping_from_post(){
	update_user_meta(get_current_user_id(), '_edibly_local_delivery', (isset($_POST['local-delivery']) ? "active" : "inactive"));
	update_user_meta(get_current_user_id(), '_edibly_local_pickup', (isset($_POST['local-pickup']) ? "active" : "inactive"));
	update_user_meta(get_current_user_id(), '_edibly_local_delivery_zipcodes', explode(',', $_POST['delivery-zipcodes']));
	update_user_meta(get_current_user_id(), '_edibly_local_delivery_price', $_POST['delivery-price']);
	update_user_meta(get_current_user_id(), '_edibly_local_delivery_additional_price', $_POST['delivery-additional-price']);
	update_user_meta(get_current_user_id(), '_edibly_local_pickup_zip', explode(',', $_POST['pickup-zip']));
	update_user_meta(get_current_user_id(), '_edibly_local_pickup_price', $_POST['pickup-price']);
	update_user_meta(get_current_user_id(), '_edibly_local_pickup_additional_price', $_POST['pickup-additional-price']);
}


add_action( 'wp_ajax_edibly_get_zip_from_location', 'edibly_get_zip_from_location' );
add_action( 'wp_ajax_nopriv_edibly_get_zip_from_location', 'edibly_get_zip_from_location' );

function edibly_get_zip_from_location(){
	$lat1 = $_POST['lat'];
	$lon1 = $_POST['lon'];
	$d = 1;
	//earth's radius in miles
	$r = 3959;

	//compute max and min latitudes / longitudes for search square
	$latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
	$latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
	$lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
	$lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));

	global $wpdb;
	$sql = "SELECT ZIPCode FROM wp_edibly_zips WHERE (Latitude <= $latN AND Latitude >= $latS AND Longitude <= $lonE AND Longitude >= $lonW) AND (Latitude != $lat1 AND Longitude != $lon1) AND City != '' ORDER BY State, City, Latitude, Longitude";
	$zip = $wpdb->get_var($sql);

	echo $zip;
	wp_die();
}

add_action( 'wp_ajax_edibly_get_products_for_zipcode', 'edibly_get_products_for_zipcode' );
add_action( 'wp_ajax_nopriv_edibly_get_products_for_zipcode', 'edibly_get_products_for_zipcode' );

function edibly_get_products_for_zipcode(){
	$output = array();
	$zipcode = sanitize_text_field( $_POST['zipcode'] );
	$user_query = new WP_User_Query(
		array(
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => '_edibly_local_delivery_zipcodes',
					'value' => $zipcode,
					'compare' => 'LIKE'
				),
				array(
					'key' => '_edibly_local_pickup_zip',
					'value' => $zipcode,
					'compare' => 'LIKE'
				)
			),
			'orderby' => 'post_count',
			'order' => 'DESC'
		)
	);
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			//echo($user->ID);
			$sellerID = $user->ID;

			$store_info = dokan_get_store_info( $sellerID );

			$store_info['store_name'] = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'dokan' );
			$store_info['store_url']  = dokan_get_store_url( $sellerID );

			$banner_id  = isset( $store_info['banner'] ) ? $store_info['banner'] : 0;
			if($banner_id){
				$banner_url = wp_get_attachment_image_src( $banner_id, 'homepage-vendor' );
				$store_info['banner_image'] = esc_url( $banner_url[0] );
			}else{
				$banner_url = DOKAN_PLUGIN_ASSEST. '/images/no-seller-image.png';
				$store_info['banner_image'] = apply_filters( 'dokan_no_seller_image', $banner_url );
			}

			$output[] = $store_info;
		}
	}

	echo json_encode($output);
	wp_die();
}

add_action( 'wp_ajax_edibly_get_vendors_surrounding_location', 'edibly_get_vendors_surrounding_location' );
add_action( 'wp_ajax_nopriv_edibly_get_vendors_surrounding_location', 'edibly_get_vendors_surrounding_location' );

function edibly_get_vendors_surrounding_location(){
	echo json_encode(getVendorsSurroundingLocation($_POST['lat'], $_POST['lng'], (!empty($_POST['distance']) ? $_POST['distance'] : 50), (!empty($_POST['order']) ? $_POST['order'] : "RAND")));
	wp_die();
}

function getVendorsSurroundingLocation($lat, $long, $distance = 50, $order = "DESC"){
	$output = array();

	$user_query = new WP_User_Query(
		array(
			'meta_query' => array(
				array(
					'key' => 'dokan_enable_selling',
					'value' => 'yes'
				)
			),
			'orderby' => 'rand',
			'number' => '1000',
			'order' => $order
		)
	);

	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			//echo($user->ID);
			$sellerID = $user->ID;

			$store_info = dokan_get_store_info( $sellerID );

			if ( is_array($store_info['address']) ) {

				$seller_zip = $store_info['address']['zip'];

				if (strlen($seller_zip) === 5) {

					$sellerlocation = getLocationForZip($seller_zip);

					if(haversineGreatCircleDistance($lat, $long, $sellerlocation['lat'], $sellerlocation['lng']) <= 50){
						$store_info['store_id'] = $sellerID;
						$store_info['store_name'] = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'dokan' );
						$store_info['store_url']  = dokan_get_store_url( $sellerID );

						$banner_id  = isset( $store_info['banner'] ) ? $store_info['banner'] : 0;
						if($banner_id){
							$banner_url = wp_get_attachment_image_src( $banner_id, 'shop_catalog' );
							$store_info['banner_image'] = esc_url( $banner_url[0] );
						}else{
							$banner_url = DOKAN_PLUGIN_ASSEST. '/images/no-seller-image.png';
							$store_info['banner_image'] = apply_filters( 'dokan_no_seller_image', $banner_url );
						}

						$output[] = $store_info;
					}
				}
			}
		}
	}

	return $output;
}


// Get Staff Picks Function
function getStaffPicks(){
	$output = array();

	$user_query = new WP_User_Query(
		array(
			'meta_query' => array(
				array(
					'key' => 'staff_pick',
					'value' => '"yes"',
					'compare' => 'LIKE'
				)
			),
			'orderby' => 'rand',
			'number' => '49'
		)
	);

	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			//echo($user->ID);
			$sellerID = $user->ID;

			$store_info = dokan_get_store_info( $sellerID );

			$store_info['store_id'] = $sellerID;
			$store_info['store_name'] = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'dokan' );
			$store_info['store_url']  = dokan_get_store_url( $sellerID );

			$banner_id  = isset( $store_info['banner'] ) ? $store_info['banner'] : 0;

			if($banner_id){
				$banner_url = wp_get_attachment_image_src( $banner_id, 'shop_catalog' );
				$store_info['banner_image'] = esc_url( $banner_url[0] );
			}else{
				$banner_url = DOKAN_PLUGIN_ASSEST. '/images/no-seller-image.png';
				$store_info['banner_image'] = apply_filters( 'dokan_no_seller_image', $banner_url );
			}

			$output[] = $store_info;
		}
	}

	return $output;
}


/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [miles]
 * @return float Distance between points in [miles] (same as earthRadius)
 */
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3959){
	// convert from degrees to radians
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);
	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	return $angle * $earthRadius;
}

add_action( 'wp_ajax_edibly_get_location_for_zip', 'edibly_get_location_for_zip' );
add_action( 'wp_ajax_nopriv_edibly_get_location_for_zip', 'edibly_get_location_for_zip' );

function edibly_get_location_for_zip() {
	if ( ! empty( $_POST['zip'] ) ) {
		echo json_encode( getLocationForZip( sanitize_text_field( $_POST['zip'] ) ) );
	}

	wp_die();
}
function getLocationForZip($zip){
	if ( false === ( $location = get_transient( 'zip_location_'.$zip ) ) ) {
		$apikey = '19sKErh4cLDY0WnkC4hUJF5r4UK3XN3ZWvJAX1FaMi8Nfw7RBoMl4SM8MxcKkfKh';
		$response = wp_remote_get( 'https://www.zipcodeapi.com/rest/' . $apikey . '/info.json/' . $zip . '/degrees' );
		$location = json_decode($response['body'], true);

		set_transient( 'zip_location_'.$zip, $location, YEAR_IN_SECONDS );
	}

	return $location;
}

add_filter('ot_shipping_options_for_product', 'edibly_add_local_shipping_options', 11, 3);

function edibly_add_local_shipping_options($options, $product_id, $variation_id){
	
	$userzip = 	(!empty($_POST['s_postcode']) ? 				$_POST['s_postcode'] :
		(!empty($_POST['shipping_postcode']) ? 		$_POST['shipping_postcode'] :
			(!empty($_POST['billing_postcode']) ? 		$_POST['billing_postcode'] :
				(!empty($_POST['postcode']) ? 				$_POST['postcode'] : null))));
	error_log($userzip);

	$product_post = get_post($product_id);
	$seller_id = $product_post->post_author;
	
	//echo 'Hello - '.get_user_meta($seller_id, '_edibly_local_delivery', true);
	//die('Come here');
	
	if(get_user_meta($seller_id, '_edibly_local_delivery', true) == "active" &&
	   in_array($userzip, get_user_meta($seller_id, '_edibly_local_delivery_zipcodes', true))){
		$options[] = json_decode(json_encode(array(
			'label' => 'Local Delivery',
			'price' => floatval(get_user_meta($seller_id, '_edibly_local_delivery_price', true)),
			'additional_price' => floatval(get_user_meta($seller_id, '_edibly_local_delivery_additional_price', true)),
			'id' => 'local-delivery'
		)), FALSE);
	}
	if(get_user_meta($seller_id, '_edibly_local_pickup', true) == "active" &&
	   in_array($userzip, get_user_meta($seller_id, '_edibly_local_pickup_zip', true))){
		$options[] = json_decode(json_encode(array(
			'label' => 'Local Pickup',
			'price' => floatval(get_user_meta($seller_id, '_edibly_local_pickup_price', true)),
			'additional_price' => floatval(get_user_meta($seller_id, '_edibly_local_pickup_additional_price', true)),
			'id' => 'local-pickup'
		)), FALSE);
	}
	/* Code start by aslam */
	$shipping_options_array = json_decode(get_user_meta($seller_id, '_shipping_options', true));	
	if(!empty($shipping_options_array)){
		foreach($shipping_options_array as $key=>$val){
			$options[] = json_decode(json_encode(array(
				'label' => $val->label,
				'price' => floatval($val->price),
				'additional_price' => floatval($val->additional_price),
				'id' => $val->id
			)), FALSE);
		}
	}
	/* Code end by aslam */
	ob_start();
	var_dump($options);
	$testoptions = ob_get_clean();
	error_log($testoptions);
	return $options;
}

add_action('dokan_load_custom_template', 'edibly_load_local_shipping_template');

function edibly_load_local_shipping_template($query_vars){
	if (isset($_POST['update-local-shipping'])) {
		edibly_save_local_shipping_from_post();
	}

	if ( isset( $query_vars['local-shipping'] ) ) {
		load_template(dirname(__FILE__).'/templates/template-local-shipping.php');

		wp_enqueue_script( 'edibly-carat', plugin_dir_url( __FILE__ ) .  'assets/js/jquery.caret.min.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) .  'assets/js/jquery.caret.min.js' ) );
		wp_enqueue_script( 'edibly-tag-editor', plugin_dir_url( __FILE__ ) .  'assets/js/jquery.tag-editor.min.js', array( 'edibly-carat' ), filemtime( plugin_dir_path( __FILE__ ) .  'assets/js/jquery.tag-editor.min.js' ) );
		wp_enqueue_style( 'edibly-tag-editor-css', plugin_dir_url( __FILE__ ) .  'assets/css/jquery.tag-editor.css', array(), filemtime( plugin_dir_path( __FILE__ ) .  'assets/css/jquery.tag-editor.css' ) );
	}
}

add_action('dokan_get_dashboard_nav', 'edibly_add_dokan_local_shipping_tab', 1);

function edibly_add_dokan_local_shipping_tab ($urls) {
	$output = $urls;
	$output += array('local-shipping' =>
		                 array(
			                 'title' => __( 'Pickup/Delivery', 'edibly'),
			                 'icon'  => '<i class="fa fa-truck"></i>',
			                 'url'   => dokan_get_navigation_url('local-shipping')
		                 )
	);
	return $output;
}


add_action('dokan_get_dashboard_settings_nav', 'edibly_remove_old_shipping', 1);

function edibly_remove_old_shipping ($urls) {
	unset($urls['shipping']);
	return $urls;
}

add_filter('dokan_query_var_filter', 'edibly_add_query_vars', 1);

function edibly_add_query_vars ($query_vars) {
	$query_vars[] = 'local-shipping';
	return $query_vars;
}

add_filter('woocommerce_no_shipping_available_html', function($html){
	return '<p>' . __( "There are no shipping methods available for this shipping address. Please double check your address, or remove this item from your cart if you'd like to check out without it.", 'woocommerce' ) . '</p>';
});

add_filter('woocommerce_cart_no_shipping_available_html', function($html){
	return '<p>' . __( "Please proceed to checkout for shipping options.", 'woocommerce' ) . '</p>';
});

function edibly_shipping_activate() {
	add_rewrite_endpoint('local-shipping', EP_PAGES);
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'edibly_shipping_activate' );

function edibly_shipping_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'edibly_shipping_deactivate' );

function edibly_seller_product_tab( $tabs) {

	$tabs['seller'] = array(
		'title'    => __( 'Seller Info', 'dokan' ),
		'priority' => 90,
		'callback' => 'edibly_product_seller_tab'
	);

	return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'edibly_seller_product_tab', 11 );

function edibly_product_seller_tab( $val ) {
	global $product;

	$author     = get_user_by( 'id', $product->post->post_author );
	$store_info = dokan_get_store_info( $author->ID );

	$Dokan_Store_Support = new Dokan_Store_Support;  // correct
	$Dokan_Store_Support->generate_support_button($product->post->post_author);
	?>
	<h2><?php _e( 'Seller Information', 'dokan' ); ?></h2>

	<ul class="list-unstyled">

		<?php if ( !empty( $store_info['store_name'] ) ) { ?>
			<li class="store-name">
				<span><?php _e( 'Store Name:', 'dokan' ); ?></span>
	            <span class="details">
	                <?php printf( '<a href="%s">%s</a>', dokan_get_store_url( $author->ID ), esc_html( $store_info['store_name'] ) ); ?>
	            </span>
			</li>
		<?php } ?>

		<li class="seller-name">
	        <span>
	            <?php _e( 'Seller:', 'dokan' ); ?>
	        </span>
	
	        <span class="details">
	            <?php printf( '<a href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name ); ?>
	        </span>
		</li>
		<?php if ( !empty( $store_info['address'] ) ) { ?>
			<li class="store-address">
				<span><b><?php _e( 'Address:', 'dokan' ); ?></b></span>
	            <span class="details">
	                <?php echo dokan_get_seller_address( $author->ID ) ?>
	            </span>
			</li>
		<?php } ?>

		<li class="clearfix">
			<?php dokan_get_readable_seller_rating( $author->ID ); ?>
		</li>
	</ul>
	<?php
}

function edibly_tc_editor( $current_user, $profile_info ){
	$store_tnc = isset( $profile_info['store_tnc'] ) ? $profile_info['store_tnc'] : '' ;
	?>
	<script>
		jQuery(function($){ 
			$("#edibly_dokan_tnc_text").remove();
		});
	</script>
	<div class="dokan-form-group" id="edibly_dokan_tnc_text">
		<label class="dokan-w3 dokan-control-label" for="dokan_store_tnc"><?php _e( 'TOC Details', 'dokan' ); ?></label>
		<div class="dokan-w9 dokan-text-left">
			<?php
			$settings = array(
				'editor_height' => 200,
				'media_buttons' => false,
				'quicktags'     => false,
				'teeny' 		=> false
			);
			wp_editor( $store_tnc, 'dokan_store_tnc', $settings );
			?>
		</div>
	</div>
	<?php
}
add_action('dokan_settings_form_bottom', 'edibly_tc_editor', 1, 2);

function edibly_add_our_story( $current_user, $profile_info ){
	$our_story_text = isset( $profile_info['ourstory'] ) ? $profile_info['ourstory'] : '';
	?>
	<div class="dokan-form-group our-story-form">
		<label class="dokan-w3 dokan-control-label" for="dokan_support_btn_name"><?php _e( 'Your Story', 'ediby' ); ?></label>

		<div class="dokan-w9 dokan-text-left">
			<?php
			$settings = array(
				'editor_height' => 200,
				'media_buttons' => false,
				'quicktags'     => false,
				'teeny' 		=> false
			);
			wp_editor( $our_story_text, 'dokan_ourstory', $settings );
			?>
		</div>
	</div>
	<input type="hidden" name="support_checkbox" value="yes">
	<?php
}
add_action('dokan_settings_form_bottom', 'edibly_add_our_story', 10, 2);

function edibly_save_our_story( $user_id ){
	$profile_info = get_user_meta( $user_id, 'dokan_profile_settings', true );

	if ( isset( $_POST['dokan_ourstory'] ) ) {
		$profile_info['ourstory'] = $_POST['dokan_ourstory'];
		$profile_info['show_support_btn'] = "yes";
	}

	$profile_info['phone'] = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $profile_info['phone']);

	update_user_meta( $user_id, 'dokan_profile_settings', $profile_info );
}
add_action('dokan_store_profile_saved', 'edibly_save_our_story', 15);

function edibly_add_our_story_text($store_user, $store_info){
	$our_story_text = isset( $store_info['ourstory'] ) ? $store_info['ourstory'] : '';
	if(strlen($our_story_text) > 0){
		echo '<div style="margin-bottom: 30px; padding: 0 40px;"><div style="text-align: center"><h3>Our Story</h3></div>';
		echo $our_story_text;
		echo '</div>';
	}
	echo '<div style="text-align: center"><h3>Our Products</h3></div>';
}
add_action('dokan_store_profile_frame_after', 'edibly_add_our_story_text', 10, 2);

add_filter('dokan_get_dashboard_nav', 'edibly_reorder_seller_dash', 10000, 1);
function edibly_reorder_seller_dash($urls){
	$output = array();
	if(!empty($urls['dashboard'])){
		$output += array('dashboard' => $urls['dashboard']);
	}
	if(!empty($urls['products'])){
		$output += array('products' => $urls['products']);
	}
	if(!empty($urls['orders'])){
		$output += array('orders' => $urls['orders']);
	}
	if(!empty($urls['reports'])){
		$output += array('reports' => $urls['reports']);
	}
	if(!empty($urls['reviews'])){
		$output += array('reviews' => $urls['reviews']);
	}
	if(!empty($urls['coupons'])){
		$output += array('coupons' => $urls['coupons']);
	}
	/*if(!empty($urls['wholesale'])){
		$output += array('wholesale' => $urls['wholesale']);
	}*/
	if(!empty($urls['local-shipping'])){
		$output += array('local-shipping' => $urls['local-shipping']);
	}
	if(!empty($urls['support'])){
		$output += array('support' => $urls['support']);
	}
	if(!empty($urls['withdraw'])){
		$output += array('withdraw' => $urls['withdraw']);
	}
	if(!empty($urls['settings'])){
		$output += array('settings' => $urls['settings']);
	}

	$index = 0;
	foreach($output as $key => $url){
		$output[$key]['pos'] = $index;
		$index++;
	}

	return $output;
}


function edibly_take_shipping($net_amount, $order){
	return $net_amount - $order->get_total_shipping();
}
add_filter('dokan_order_net_amount', 'edibly_take_shipping', 10, 2);

function dokan_seller_terms_conditions() {
	echo '<p class="form-row form-group form-row-wide"><label><input name="terms" required="" type="checkbox" value="checked" />I agree to the <a href="https://edibly.co/terms/">terms and conditions</a><span class="required">*</span></label></p>';
}
add_action( 'register_form', 'dokan_seller_terms_conditions', 15 );


function edibly_home_featured_products_callback() {

	$columns  = 4;
	$authors  = edibly_get_authors_within_radius();
	$products = edibly_get_featured_products( $columns );

	$row_class = '';
	if ( 'featured-products' == $_POST['current_page'] ) {
		$row_class = ' row';
	}

	ob_start();

	echo '<div class="featured-products-row' . esc_attr( $row_class ) . '">';
	echo '<div class="woocommerce">';
	echo '<div class="products-wrapper">';

		$featured_products = 0;

		if ( $products->have_posts() ) : ?>

			<?php //6tdo_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

			<?php woocommerce_product_loop_start(); ?>

			<div id="featured-products-slider" class="owl-carousel">

				<?php while ( $products->have_posts() && $featured_products < 49 ) {
					$products->the_post();

					if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
						wc_get_template_part( 'content', 'product' );
						$featured_products ++;
					} else {
						global $product;
						if ( edibly_is_product_shippable( $product ) ) {
								wc_get_template_part( 'content', 'product' );
							$featured_products ++;
						}
					}
				} ?>

			</div>

			<?php woocommerce_product_loop_end(); ?>

			<div class="previous"></div>
			<div class="next"></div>
			<div class="mobile-swipe">&#8592; <span>Swipe For More Products</span> &#8594;</div>
		</div>

		<?php //do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

	<?php endif;

	if ($featured_products < 1) {
		echo "<h3 style='margin-left: 25px;'>No featured products found.</h3>";
	}

	woocommerce_reset_loop();
	wp_reset_postdata();

	echo '</div></div>';

	$response = ob_get_clean();
	echo $response;
	die();

}

add_action( 'wp_ajax_edibly_home_featured_products', 'edibly_home_featured_products_callback' );
add_action( 'wp_ajax_nopriv_edibly_home_featured_products', 'edibly_home_featured_products_callback' );

/*
 * Return true if product has the data required to be shipped, false if not
 *
 * @param obj $product
 *
 * @return bool
 */
function edibly_is_product_shippable( $product ) {

	$perishable = get_post_meta( $product->id, '_dokan_easypost_perishable', true );

	if ( $product->product_type == 'variable' ) {
		$variations = $product->get_available_variations();
		foreach ( $variations as $variable_array ) {
			$variation = new WC_Product( $variable_array['variation_id'] );

			if ( 'no' === $perishable ) {

				$shipping_option = get_post_meta( $variation->id, '_dokan_easypost_shipping_option', true );
				$box_size = '';

				if ( 'priority-flat-rate' == $shipping_option ) {
					$box_size = get_post_meta( $variation->id, '_dokan_easypost_flat_rate_box', true );
				} elseif( 'priority-regional-rate' == $shipping_option ) {
					$box_size =  get_post_meta( $variation->id, '_dokan_easypost_regional_rate_box', true );
				}

				// Nonperishable products with the weight, shipping option & box size defined for at least one variety are shippable
				if ( ! empty( $variation->weight ) && ! empty( $shipping_option ) && ! empty( $box_size ) ) {
					return true;
				}
			} else {

				// Perishable products with the dimensions and weight defined for at least one variety are shippable
				if ( ! empty( $variation->length ) && ! empty( $variation->width ) && ! empty( $variation->height ) && ! empty( $variation->weight ) ) {
					return true;
				}
			}
		}
	} else {
		$weight = get_post_meta( $product->id, '_weight', true );

		if ( 'no' === $perishable ) {
			$shipping_option = get_post_meta( $product->id, '_dokan_easypost_shipping_option', true );
			$box_size = '';

			if ( 'priority-flat-rate' == $shipping_option ) {
				$box_size = get_post_meta( $product->id, '_dokan_easypost_flat_rate_box', true );
			} elseif( 'priority-regional-rate' == $shipping_option ) {
				$box_size =  get_post_meta( $product->id, '_dokan_easypost_regional_rate_box', true );
			}

			// Simple, nonperishable products with their weight, shipping option & box size defined are shippable
			if ( ! empty( $weight ) && ! empty( $shipping_option ) && ! empty( $box_size ) ) {
				return true;
			}
		} else {
			$length = get_post_meta( $product->id, '_length', true );
			$width  = get_post_meta( $product->id, '_width', true );
			$height = get_post_meta( $product->id, '_height', true );

			// Simple, perishiable products with their dimensions and weight defined are shippable
			if ( ! empty( $length ) && ! empty( $width ) && ! empty( $height ) && ! empty( $weight ) ) {
				return true;
			}
		}
	}

	return false;
}

function edibly_get_authors_within_radius() {

	$authors = array();
	$lat     = sanitize_text_field( $_POST['lat'] );
	$lng     = sanitize_text_field( $_POST['lng'] );

	if ( ! empty( $lat ) && ! empty( $lng ) ) {
		$vendors = getVendorsSurroundingLocation( $lat, $lng );
		foreach ( $vendors as $vendor ) {
			$authors[] = $vendor['store_id'];
		}
	}

	return $authors;
}

/*
 * Get featured products
 */
function edibly_get_featured_products( $columns = 4 ) {

	$atts = array(
		'per_page' => '1000',
		'orderby'  => 'rand',
		'order'    => 'desc',
	);

	$meta_query   = WC()->query->get_meta_query();
	$meta_query[] = array(
		'key'   => '_featured',
		'value' => 'yes',
	);

	$query_args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $atts['per_page'],
		'orderby'             => $atts['orderby'],
		'order'               => $atts['order'],
		'meta_query'          => $meta_query,
	);

	global $woocommerce_loop;
	$columns                     = absint( $atts['columns'] );
	$woocommerce_loop['columns'] = 1000;

	return new WP_Query( $query_args, $atts );
}

/*
 * Get local products
 */
function edibly_get_local_products( $columns = 4 ) {

	$atts = array(
		'per_page' => '1000',
		'orderby'  => 'rand',
		'order'    => 'desc',
	);

	$query_args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => $atts['per_page'],
		'orderby'             => $atts['orderby'],
		'order'               => $atts['order']
	);

	global $woocommerce_loop;
	$columns                     = absint( $atts['columns'] );
	$woocommerce_loop['columns'] = 1000;

	return new WP_Query( $query_args, $atts );
}

function fix_phones() {
	if ( ! isset( $_REQUEST['token'] ) ) {
		return false;
	}
	if ( '14swNdafL1Ym' == $_REQUEST['token'] ) {
		$users = get_users( );
		foreach ($users as $user) {
			$user_id = $user->ID;
			$profile_info = get_user_meta( $user_id, 'dokan_profile_settings', true );

			$profile_info['phone'] = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $profile_info['phone']);

			update_user_meta( $user_id, 'dokan_profile_settings', $profile_info );
		}
	}
}

add_action('init', 'fix_phones');

function product_class( $classes ) {
	global $post;
	if (in_array('type-product', $classes)) {
		$classes[] = 'product';
	}
	return $classes;
}
add_filter( 'post_class', 'product_class' );

add_filter('pre_user_query', function(&$query) {
	if($query->query_vars["orderby"] == 'rand') {
		$query->query_orderby = 'ORDER by RAND()';
	}
});


function edibly_featured_products_callback() {

	$columns  = 4;
	$authors  = edibly_get_authors_within_radius();
	$products = edibly_get_featured_products( $columns );

	ob_start();
	echo '<div class="row featured-products-row">';
	echo '<div class="woocommerce">';

	$featured_products = false;

	if ( $products->have_posts() ) : ?>

		<?php //do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php while ( $products->have_posts() ) {
			$products->the_post();

			if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
				wc_get_template_part( 'content', 'product' );
				$featured_products = true;
			} else {
				global $product;
				if ( edibly_is_product_shippable( $product ) ) {
					wc_get_template_part( 'content', 'product' );
					$featured_products = true;
				}
			}
		} ?>

		<?php woocommerce_product_loop_end(); ?>

		<?php //do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

	<?php endif;

	if ( ! $featured_products) {
		echo "<h3>No featured products found.</h3>";
	}

	woocommerce_reset_loop();
	wp_reset_postdata();
	echo '</div>';
	echo '</div>';

	$response = ob_get_clean();
	echo $response;
	die();
}
add_action( 'wp_ajax_edibly_featured_products', 'edibly_featured_products_callback' );
add_action( 'wp_ajax_nopriv_edibly_featured_products', 'edibly_featured_products_callback' );






function edibly_home_local_products_callback() {

	$columns  = 4;
	$authors  = edibly_get_authors_within_radius();
	$products = edibly_get_local_products( $columns );

	$row_class = '';
	if ( 'local-products' == $_POST['current_page'] ) {
		$row_class = ' row';
	}

	ob_start();

	echo '<div class="local-products-row' . esc_attr( $row_class ) . '">';
	echo '<div class="woocommerce">';

	$local_products_test = 0;
	$local_products = 0;

	if ( $products->have_posts() ) : ?>

		<?php 

			while ( $products->have_posts() && $local_products_test < 1 ) {
				
				$products->the_post();

				if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
					$local_products_test ++;
				} 
			}

			if($local_products_test == 0) {
				echo '<p>There aren\'t any local products in your area yet, but check out these other great products.</p>';
			}

		?>

		<div class="products-wrapper">

			<?php //6tdo_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

			<?php woocommerce_product_loop_start(); ?>

			<div id="local-products-slider" class="owl-carousel">

				<?php 

					if($local_products_test > 0) {

						while ( $products->have_posts() && $local_products < 49 ) {
							$products->the_post();

							if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
								wc_get_template_part( 'content', 'product' );
								$local_products ++;
							} 
						}

					}
					else {

						$tmpCount = 0;

						while ( $products->have_posts() && $tmpCount < 49 ) {
							$products->the_post();
							wc_get_template_part( 'content', 'product' );
							$tmpCount ++;
						}

					}

				 ?>

			</div>

			<?php woocommerce_product_loop_end(); ?>

			<div class="previous"></div>
			<div class="next"></div>
			<div class="mobile-swipe">&#8592; <span>Swipe For More Products</span> &#8594;</div>
		</div>

		<?php //do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

	<?php endif;

	woocommerce_reset_loop();
	wp_reset_postdata();

	echo '</div></div>';

	$response = ob_get_clean();
	echo $response;
	die();

}

add_action( 'wp_ajax_edibly_home_local_products', 'edibly_home_local_products_callback' );
add_action( 'wp_ajax_nopriv_edibly_home_local_products', 'edibly_home_local_products_callback' );


function edibly_local_products_callback() {

	$columns  = 4;
	$authors  = edibly_get_authors_within_radius();
	$products = edibly_get_local_products( $columns );

	ob_start();
	echo '<div class="row local-products-row">';
	echo '<div class="woocommerce">';

	$local_products_test = 0;
	$local_products = 0;

	if ( $products->have_posts() ) : ?>

		<?php 

			while ( $products->have_posts() && $local_products_test < 1 ) {
				
				$products->the_post();

				if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
					$local_products_test ++;
				} 
			}

			if($local_products_test == 0) {
				echo '<p>There aren\'t any local products in your area yet, but check out these other great products.</p>';
			}

		?>

		<?php //do_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php 

			if($local_products_test > 0) {

				while ( $products->have_posts() && $local_products < 100 ) {
					$products->the_post();

					if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
						wc_get_template_part( 'content', 'product' );
						$local_products ++;
					} 
				}

			}
			else {

				$tmpCount = 0;

				while ( $products->have_posts() && $tmpCount < 100 ) {
					$products->the_post();
					wc_get_template_part( 'content', 'product' );
					$tmpCount ++;
				}

			}

		?>

		<?php woocommerce_product_loop_end(); ?>

		<?php //do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

	<?php endif;

	woocommerce_reset_loop();
	wp_reset_postdata();
	echo '</div>';
	echo '</div>';

	$response = ob_get_clean();
	echo $response;
	die();
}
add_action( 'wp_ajax_edibly_local_products', 'edibly_local_products_callback' );
add_action( 'wp_ajax_nopriv_edibly_local_products', 'edibly_local_products_callback' );


/*
 * Display Local Vendors content
 */
function edibly_local_vendors_callback() {

	$cols = 4;
	$rows = 49;

	if ( 'local-vendors' === $_POST['current_page'] ) {
		$rows = 100;
	}

	$vendors = getVendorsSurroundingLocation( $_POST['lat'], $_POST['lng'] );

	$staff_picks_display = '';
	if ( empty( $vendors ) ) { 
		// Get staff picks
		$staff_picks_display = 'yes';
		$vendors = getStaffPicks();
	}

	ob_start(); ?>

	<div class="local-vendors-row woocommerce">

		<?php if ( ! empty( $vendors ) ) : ?>
			<?php
				if($staff_picks_display) {
					echo '<p>There aren\'t any local vendors in your area yet, but check out these other great Vendors.</p>';
				}
			?>
			<div class="products-wrapper">
				<ul class="local-vendors-page products">
					<?php if ( 'front-page' === $_POST['current_page'] ) : ?>
						<div id="local-vendors-slider" class="owl-carousel">
					<?php endif; ?>
						<?php for ( $currRow = 0; $currRow < $rows; $currRow ++ ) : ?>
							<?php if ( count( $vendors ) >= ( ( $currRow * $cols ) + 1 ) ) : ?>
								<?php for ( $currCol = 0; $currCol < $cols; $currCol ++ ) : ?>
									<?php $currentIndex = ( ( $currRow * $cols ) + $currCol ); ?>
									<?php if ( count( $vendors ) >= ( $currentIndex + 1 ) ) : ?>
											<li class="product <?php if ( ($currentIndex+1) % 4 == 0 ) { echo 'last'; } ?>">
												<a href="<?php echo esc_url( $vendors[ $currentIndex ]['store_url'] ); ?>">
													<div class="image-wrapper">
														<img src="<?php echo esc_url( $vendors[ $currentIndex ]['banner_image'] ); ?>" class="img-responsive" style="width:100%;">
													</div>
													<h3><?php esc_html_e( $vendors[ $currentIndex ]['store_name'] ); ?></h3>
												</a>
											</li>
									<?php endif; ?>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endfor; ?>
					<?php if ( 'front-page' === $_POST['current_page'] ) : ?>
						</div>
					<?php endif; ?>
				</ul>
				<?php if ( 'front-page' === $_POST['current_page'] ) : ?>
					<div class="previous"></div>
					<div class="next"></div>
					<div class="mobile-swipe">&#8592; <span>Swipe For More Products</span> &#8594;</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p>There aren't any local vendors in your area yet.</p>
	    <?php endif; ?>
    </div>

	<?php
	$response = ob_get_clean();
	echo $response;
	die();
}
add_action( 'wp_ajax_edibly_local_vendors', 'edibly_local_vendors_callback' );
add_action( 'wp_ajax_nopriv_edibly_local_vendors', 'edibly_local_vendors_callback' );

function edibly_seller_comment_moderation() {
	$role = get_role( 'seller' );
	$role->remove_cap('moderate_comments');
}

add_action( 'init', 'edibly_seller_comment_moderation' );



/* ----code start -----*/

 /* display category content */

function edibly_home_special_diets_product_callback() {

	$columns  = 4;
	$authors  = edibly_get_authors_within_radius();
	$products = edibly_get_local_products( $columns );

	$row_class = '';
	if ( 'local-products' == $_POST['current_page'] ) {
		$row_class = ' row';
	}

	ob_start();

	echo '<div class="local-products-row' . esc_attr( $row_class ) . '">';
	echo '<div class="woocommerce">';

	$local_products_test = 0;
	$local_products = 0;

	if ( $products->have_posts() ) : ?>

		<?php 

			while ( $products->have_posts() && $local_products_test < 1 ) {
				
				$products->the_post();

				if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
					$local_products_test ++;
				} 
			}

			if($local_products_test == 0) {
				echo '<p>There aren\'t any local products in your area yet, but check out these other great products.</p>';
			}

		?>

		<div class="products-wrapper">

			<?php //6tdo_action( "woocommerce_shortcode_before_{$loop_name}_loop" ); ?>

			<?php woocommerce_product_loop_start(); ?>

			<div id="local-products-slider" class="owl-carousel">

				<?php 

					if($local_products_test > 0) {

						while ( $products->have_posts() && $local_products < 49 ) {
							$products->the_post();

							if ( in_array( get_the_author_meta( 'ID' ), $authors ) ) {
								wc_get_template_part( 'content', 'product' );
								$local_products ++;
							} 
						}

					}
					else {

						$tmpCount = 0;

						while ( $products->have_posts() && $tmpCount < 49 ) {
							$products->the_post();
							wc_get_template_part( 'content', 'product' );
							$tmpCount ++;
						}

					}

				 ?>

			</div>

			<?php woocommerce_product_loop_end(); ?>

			<div class="previous"></div>
			<div class="next"></div>
			<div class="mobile-swipe">&#8592; <span>Swipe For More Products</span> &#8594;</div>
		</div>

		<?php //do_action( "woocommerce_shortcode_after_{$loop_name}_loop" ); ?>

	<?php endif;

	woocommerce_reset_loop();
	wp_reset_postdata();

	echo '</div></div>';

	$response = ob_get_clean();
	echo $response;
	die();

}

add_action( 'wp_ajax_edibly_home_special_diets_product', 'edibly_home_special_diets_product_callback' );
add_action( 'wp_ajax_nopriv_edibly_home_special_diets_product', 'edibly_home_special_diets_product_callback' );

/*-----code end here ----*/
