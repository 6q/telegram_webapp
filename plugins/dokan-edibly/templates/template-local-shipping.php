<?php
	$store_id = get_current_user_id();
	$shipping_options = OneThirteenShippingBase::get_shipping_options_for_store($store_id);
?>

<script>
	var mode = null;
	function getZipCodesAroundZip(zip, radius){
		jQuery('#zipcodeSearch .modal-footer .btn-primary').html('Loading...');
		jQuery('#zipcodeSearch .modal-footer .btn-primary').attr("disabled", true);
		getZipCodesSurroundingZip(zip, radius, function(zip_codes){
			zip_codes.forEach(function(zipcode){
				if(mode == "delivery"){
					jQuery('#local-delivery-container textarea').tagEditor('addTag', zipcode.zip_code);
				}else if(mode == "pickup"){
					jQuery('#local-pickup-container textarea').tagEditor('addTag', zipcode.zip_code);
				}
			});
			mode = null;
			jQuery('#zipcodeSearch').modal('hide');
			jQuery('#zipcodeSearch .modal-footer .btn-primary').html('Add Zip Codes');
			jQuery('#zipcodeSearch .modal-footer .btn-primary').attr("disabled", false);
			jQuery('#zipcodeSearch input').val('');
		});
	}
	
	/* Code update by aslam */
	function getZipCodesSurroundingZip(zip, radius, callback){
		var api_key = "js-qr8MHX7Jxj0Z6sPtjk5yOaBWIp9yQb0hD93l6b4Ev4EBxt4QNaoCk0y3pICgUCyx";
		jQuery.get("https://www.zipcodeapi.com/rest/" + api_key + "/radius.json/" + zip + "/" + radius + "/mile", function(data){
			callback(data.zip_codes);
		}).fail(function() {
			alert( "Invalid zip code." );
			jQuery('#zipcodeSearch .modal-footer .btn-primary').html('Add Zip Codes');
			jQuery('#zipcodeSearch .modal-footer .btn-primary').attr("disabled", false);			
		});
	}

	function removeAllTags(selector) {
		jQuery('textarea', selector).next('.tag-editor').find('.tag-editor-delete').click();
	}
</script>

<div class="modal fade" id="zipcodeSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Zip Codes Around You</h4>
      </div>
      <div class="modal-body">
	    <p>Enter your zip code and a search radius and we will automatically find all the zip codes in that area for you.</p>
        <div class="form-group">
	      <label for="zipcode">Zip Code</label>
	      <input type="text" class="form-control" id="zipcode" placeholder="Zip Code">
	    </div>
	    <div class="form-group">
	      <label for="radius">Radius (in miles)</label>
	      <input type="text" class="form-control" id="radius" placeholder="Radius">
	    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="getZipCodesAroundZip(jQuery('#zipcodeSearch #zipcode').val(), jQuery('#zipcodeSearch #radius').val());">Add Zip Codes</button>
      </div>
    </div>
  </div>
</div>


<div class="dokan-dashboard-wrap">

    <?php

        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
		
		/* Code start by aslam */
		if($post->ID == '2860'){
			$template_directory = get_template_directory_uri();
			wp_enqueue_script( 'bootstrap-min', $template_directory . '/assets/js/bootstrap.min.js', false, null, true );
		}
		/* Code end by aslam */
    ?>
	
	<div class="dokan-dashboard-content">
		<p>
			These Local Pickup / Delivery preferences will be the default Pickup / Delivery costs for EVERY PRODUCT added to your Edibly Shop.
		</p>
		
		<form method="post" id="onethirteen-shipping-form" action="">
			<div id="local-delivery-container">
				<label>
					<input type="checkbox" name="local-delivery" id="delivery-checkbox" <?php if(get_user_meta($store_id, '_edibly_local_delivery', true) == "active") echo "checked"; ?>>
					Local Delivery
				</label>
				<div class="local-options" id="delivery-options" style="<?php if(get_user_meta($store_id, '_edibly_local_delivery', true) != "active") echo "display: none; "; ?>margin-bottom: 10px;">
					Enter your supported zip codes below:
					<div class="zipcode-box">
						<textarea name="delivery-zipcodes"><?php
							$meta = get_user_meta($store_id, '_edibly_local_delivery_zipcodes', true);
							if($meta)
								echo implode(',', $meta);
						?></textarea>
						<div style="float: right; display: inline-block;">
							<a href="#" data-toggle="modal" data-target="#zipcodeSearch" onclick="mode = 'delivery';">Add Zip Codes Around You</a>
							<a href="javascript:removeAllTags('#local-delivery-container')" id="clearBtn">Clear All</a></div>
						<div style="clear: both;"></div> 
					</div>
					Delivery Fee:
					<div class="dokan-input-group">
                        <span class="dokan-input-group-addon">$</span>
                        <input class="dokan-form-control" name="delivery-price" id="delivery-price" type="text" placeholder="0.00" value="<?php echo get_user_meta($store_id, '_edibly_local_delivery_price', true); ?>">
                    </div>
                    <div data-toggle="tooltip" data-placement="bottom" title="Enter the amount you charge to deliver additional items in the same order. (e.g. $2 per add'l item) If you charge a flat delivery fee, enter 0.00 in the box.">
		                Add'l Delivery Fee (per item):
						<div class="dokan-input-group">
	                        <span class="dokan-input-group-addon">$</span>
	                        <input class="dokan-form-control" name="delivery-additional-price" id="delivery-additional-price" type="text" placeholder="0.00" value="<?php echo get_user_meta($store_id, '_edibly_local_delivery_additional_price', true); ?>">
	                    </div>
                    </div>
				</div>
			</div>
			<div id="local-pickup-container" style="margin-bottom: 15px;">
				<label>
					<input type="checkbox" name="local-pickup" id="pickup-checkbox" <?php if(get_user_meta($store_id, '_edibly_local_pickup', true) == "active") echo "checked"; ?>>
					Local Pickup
				</label>
				<div class="local-options" id="pickup-options" style="<?php if(get_user_meta($store_id, '_edibly_local_pickup', true) != "active") echo "display: none;"; ?>">
					Enter your supported zip codes below:
					<div class="zipcode-box">
						<textarea name="pickup-zip"><?php
							$meta = get_user_meta($store_id, '_edibly_local_pickup_zip', true);
							if($meta)
								echo implode(',', $meta);
						?></textarea>
						<div style="float: right; display: inline-block;"><a href="#" data-toggle="modal" data-target="#zipcodeSearch" onclick="mode = 'pickup';">Add Zip Codes Around You</a> | <a href="javascript:removeAllTags('#local-pickup-container')" id="clearBtn">Clear All</a></div>
						<div style="clear: both;"></div> 
					</div>
					<div data-toggle="tooltip" data-placement="bottom" title="Enter the amount you will charge customers to pickup items. If you don't charge for pickup, enter 0.00 in the box.">
						Local Pickup Fee:
						<div class="dokan-input-group">
	                        <span class="dokan-input-group-addon">$</span>
	                        <input class="dokan-form-control" name="pickup-price" id="pickup-price" type="text" placeholder="0.00" value="<?php echo get_user_meta($store_id, '_edibly_local_pickup_price', true); ?>">
	                    </div>
					</div>
                    <div style="display: none;">
	                    Additional Qty Price:
						<div class="dokan-input-group">
	                        <span class="dokan-input-group-addon">$</span>
	                        <input class="dokan-form-control" name="pickup-additional-price" id="pickup-additional-price" type="text" placeholder="0.00" value="<?php echo get_user_meta($store_id, '_edibly_local_pickup_additional_price', true); ?>">
	                    </div>
                    </div>
				</div>
			</div>
			<input type="submit" name="update-local-shipping" class="btn btn-primary" value="Update Pickup / Delivery">
		</form>
	</div>
</div>

<script>
	jQuery(function(){
		jQuery('.local-options textarea').tagEditor({maxLength:5});
	});
	jQuery('#delivery-checkbox').click(function(){
		jQuery('#delivery-options').toggle(this.checked);
	});
	jQuery('#pickup-checkbox').click(function(){
		jQuery('#pickup-options').toggle(this.checked);
	});
</script>