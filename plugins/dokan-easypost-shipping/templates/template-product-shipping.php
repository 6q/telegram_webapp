<?php
$product_id        = $post->ID;
$perishable        = get_post_meta( $product_id, '_dokan_easypost_perishable', true );
$shipping_option   = get_post_meta( $product_id, '_dokan_easypost_shipping_option', true );
$flat_rate_box     = get_post_meta( $product_id, '_dokan_easypost_flat_rate_box', true );
$regional_rate_box = get_post_meta( $product_id, '_dokan_easypost_regional_rate_box', true );

/*
	$length = get_post_meta($product_id, '_dokan_easypost_length', true);
	$width = get_post_meta($product_id, '_dokan_easypost_width', true);
	$height = get_post_meta($product_id, '_dokan_easypost_height', true);
	$weight = get_post_meta($product_id, '_dokan_easypost_weight', true);
*/
?>
<style>
.dokan-product-shipping .dokan-form-group {
	display: none !important;
}
.parcel .dokan-form-group{
	display: block !important;
}
.parcel .checkbox{
	margin: 0;
}
</style>
<div id="simple-product-shipping" class="parcel dokan-form-horizontal text-left">
	<ul>
		<li style="list-style: initial;"><strong>Turn On Shipping</strong> - To offer shipping, start by selecting <i>Perishable</i> or <i>Nonperishable</i> below.</li>
		<li style="list-style: initial;"><strong>For Perishable Products -</strong></li>
		<ul>
			<li style="list-style: initial;">For <span style="text-decoration: underline;"><i>Simple Products</i></span> (i.e. single size or quantity), you MUST fill in all the shipping fields below (dimensions and weight) to correctly calculate shipping.</li>
			<li style="list-style: initial;">For <span style="text-decoration: underline;"><i>Variable Products</i></span> (i.e. multiple sizes or quantities), enter the shipping details for each variation within the "Variations" tab.  For the <i>weight</i>, include the box, packing materials and the product.</li>
		</ul>
		<li style="list-style: initial;"><strong>For Nonperishable Products -</strong></li>
		<ul>
			<li style="list-style: initial;">For <span style="text-decoration: underline"><i>Simple Products</i></span> (i.e. single size or quantity), select your desired USPS shipping method (i.e. Priority Flat Rate or Priority Regional Rate) from the first dropdown menu. Then, select your box size from the second dropdown menu and enter the shipping weight.</li>
			<li style="list-style: initial;">For <span style="text-decoration: underline"><i>Variable Products</i></span> (i.e. multiple sizes or quantities), select the box size and enter the weight for each variation within the "Variations" tab.</li>
		</ul>
		<li style="list-style: initial;"><strong>Turn Off Shipping</strong> - To disable shipping choose 'Select Shipping Option' in '<i>Shipping Option</i>' dropdown menu AND leave the Weight section blank.</li>
	</ul>
	<p>(<strong>Note:</strong> If the "Variations" tab is not visible to the right of the "Attributes"  tab, select <i>Edit</i> next to <i>Product Type</i> on the right side of the Dashboard and choose <i>Variable Product</i>, then select <i>OK</i>.)</p>
	<div class="dokan-form-group">
		<label class="dokan-w4 dokan-control-label"><?php _e( 'Perishable', 'easypost' ); ?></label>
		<div class="dokan-w5 dokan-text-left easypost-perishable">
			<input type="radio" id="easypost-perishable" value="yes" <?php checked( $perishable, 'yes' ); ?> name="easypost_perishable" ><?php _e( 'Product is perishable.', 'easypost' ); ?><br>
			<input type="radio" id="easypost-non-perishable" value="no" <?php checked( $perishable, 'no' ); ?> name="easypost_perishable" ><?php _e( 'Product is nonperishable.', 'easypost' ); ?>
		</div>
	</div>
	<div class="shipping-option dokan-form-group">
		<label class="dokan-w4 dokan-control-label"><?php _e( 'Shipping Option', 'easypost' ); ?></label>
		<div class="dokan-w5 dokan-text-left">
			<select class="shipping-option-select" name="easypost_shipping_option_simple">
				<option value="">Select Shipping Option</option>
				<option value="priority-flat-rate" <?php selected( $shipping_option, 'priority-flat-rate' ); ?>>USPS Priority Flat Rate</option>
				<option value="priority-regional-rate" <?php selected( $shipping_option, 'priority-regional-rate' ); ?>>USPS Priority Regional Rate</option>
			</select>
		</div>
	</div>
	<div class="flat-box-size box-size dokan-form-group">
		<label class="dokan-w4 dokan-control-label"><?php _e( 'Box Size', 'easypost' ); ?></label>
		<div class="dokan-w5 dokan-text-left">
			<select name="easypost_flat_rate_box_simple">
				<option value="small" <?php selected( $flat_rate_box, 'small' ); ?>>Small (5-3/8" x 8-5/8" x 1-5/8")</option>
				<option value="medium-1" <?php selected( $flat_rate_box, 'medium-1' ); ?>>Medium 1 (11" x 8-1/2" x 5-1/2")</option>
				<option value="medium-2" <?php selected( $flat_rate_box, 'medium-2' ); ?>>Medium 2 (11-7/8" x 3-3/8" x 13-5/8")</option>
				<option value="large" <?php selected( $flat_rate_box, 'large' ); ?>>Large (12" x 12" x 5-1/2")</option>
			</select>
		</div>
	</div>
	<div class="regional-box-size box-size dokan-form-group">
		<label class="dokan-w4 dokan-control-label"><?php _e( 'Box Size', 'easypost' ); ?></label>
		<div class="dokan-w5 dokan-text-left">
			<select name="easypost_regional_rate_box_simple">
				<option value="a-side" <?php selected( $regional_rate_box, 'a-side' ); ?>>Box A Side Loading (11 1⁄16" x 2 1⁄2" x 13 1⁄16")</option>
				<option value="a-top" <?php selected( $regional_rate_box, 'a-top' ); ?>>Box A Top Loading (10 1⁄8" x 7 1⁄8" x 5")</option>
				<option value="b-side" <?php selected( $regional_rate_box, 'b-side' ); ?>>Box B Side Loading (14 1⁄2" x 3" x 16 1⁄4")</option>
				<option value="b-top" <?php selected( $regional_rate_box, 'b-top' ); ?>>Box B Top Loading (12 1⁄4" x 10 1⁄2" x 5 1⁄2")</option>
				<option value="c" <?php selected( $regional_rate_box, 'c' ); ?>>Box C (15" x 12" x 12")</option>
			</select>
		</div>
	</div>
	<div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php echo __( 'Weight', 'dokan' ) . ' (' . get_option( 'woocommerce_weight_unit' ) . ')'; ?></label>
        <div class="dokan-w4 dokan-text-left">
            <?php dokan_post_input_box( $post->ID, '_weight' ); ?>
        </div>
    </div>
    <div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php echo __( 'Dimensions', 'dokan' ) . ' (' . get_option( 'woocommerce_dimension_unit' ) . ')'; ?></label>
        <div class="dokan-text-left product-dimension">
            <?php dokan_post_input_box( $post->ID, '_length', array( 'class' => 'form-control col-sm-1', 'placeholder' => __( 'length', 'dokan' ) ), 'number' ); ?>
            <?php dokan_post_input_box( $post->ID, '_width', array( 'class' => 'form-control col-sm-1', 'placeholder' => __( 'width', 'dokan' ) ), 'number' ); ?>
            <?php dokan_post_input_box( $post->ID, '_height', array( 'class' => 'form-control col-sm-1', 'placeholder' => __( 'height', 'dokan' ) ), 'number' ); ?>
        </div>
    </div>
</div>