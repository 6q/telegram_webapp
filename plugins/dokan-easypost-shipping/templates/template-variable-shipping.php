<?php
$shipping_option   = get_post_meta( $variation->ID, '_dokan_easypost_shipping_option', true );
$flat_rate_box     = get_post_meta( $variation->ID, '_dokan_easypost_flat_rate_box', true );
$regional_rate_box = get_post_meta( $variation->ID, '_dokan_easypost_regional_rate_box', true );
?>
<div class="variable-product-shipping">
	<div class="shipping-option okan-form-group">
		<label class="dokan-control-label"><?php esc_html_e( 'Shipping Option', 'easypost' ); ?></label>
		<div class="dokan-text-left">
			<select class="shipping-option-select" name="easypost_shipping_option[<?php esc_attr_e( $loop ); ?>]">
				<option value="">Select Shipping Option</option>
				<option value="priority-flat-rate" <?php selected( $shipping_option, 'priority-flat-rate' ); ?>>USPS Priority Flat Rate</option>
				<option value="priority-regional-rate" <?php selected( $shipping_option, 'priority-regional-rate' ); ?>>USPS Priority Regional Rate</option>
			</select>
		</div>
	</div>
	<div class="flat-box-size box-size dokan-form-group">
		<label class="dokan-control-label"><?php esc_html_e( 'Box Size', 'easypost' ); ?></label>
		<div class="dokan-text-left">
			<select name="easypost_flat_rate_box[<?php esc_attr_e( $loop ); ?>]">
				<option value="small" <?php selected( $flat_rate_box, 'small' ); ?>>Small (5-3/8" x 8-5/8" x 1-5/8")</option>
				<option value="medium-1" <?php selected( $flat_rate_box, 'medium-1' ); ?>>Medium 1 (11" x 8-1/2" x 5-1/2")</option>
				<option value="medium-2" <?php selected( $flat_rate_box, 'medium-2' ); ?>>Medium 2 (11-7/8" x 3-3/8" x 13-5/8")</option>
				<option value="large" <?php selected( $flat_rate_box, 'large' ); ?>>Large (12" x 12" x 5-1/2")</option>
			</select>
		</div>
	</div>
	<div class="regional-box-size box-size dokan-form-group">
		<label class="dokan-control-label"><?php esc_html_e( 'Box Size', 'easypost' ); ?></label>
		<div class="dokan-text-left">
			<select name="easypost_regional_rate_box[<?php esc_attr_e( $loop ); ?>]">
				<option value="a-side" <?php selected( $regional_rate_box, 'a-side' ); ?>>Box A Side Loading (11 1⁄16" x 2 1⁄2" x 13 1⁄16")</option>
				<option value="a-top" <?php selected( $regional_rate_box, 'a-top' ); ?>>Box A Top Loading (10 1⁄8" x 7 1⁄8" x 5")</option>
				<option value="b-side" <?php selected( $regional_rate_box, 'b-side' ); ?>>Box B Side Loading (14 1⁄2" x 3" x 16 1⁄4")</option>
				<option value="b-top" <?php selected( $regional_rate_box, 'b-top' ); ?>>Box B Top Loading (12 1⁄4" x 10 1⁄2" x 5 1⁄2")</option>
				<option value="c" <?php selected( $regional_rate_box, 'c' ); ?>>Box C (15" x 12" x 12")</option>
			</select>
		</div>
	</div>
</div>