jQuery(document).ready(function($) {

	var simpleProductShipping = $('#simple-product-shipping');
	var perishable = $('#easypost-perishable');
	var nonPerishable = $('#easypost-non-perishable');
	var shippingOption = $('#simple-product-shipping .shipping-option');
	var flatBoxSize = $('#simple-product-shipping .flat-box-size');
	var regionalBoxSize = $('#simple-product-shipping .regional-box-size');
	var weight = $('#simple-product-shipping div:nth-of-type(5)');
	var dimensions = $('#simple-product-shipping div:nth-of-type(6)');
	var variableProductShipping = $('.variable-product-shipping');
	var variableDimensions = $('#variable_product_options .dimensions_field');
	var productType = $('.product-type .dokan-toggle-select');

	toggleShippingOptions();

	// When product is switched from simple/variable, toggle shipping options
	productType.change( function() {
		toggleShippingOptions();
	});

	// Toggle shipping option fields
	function toggleShippingOptions() {
		if ( 'simple' == productType.val() ) {
			showElement( weight );
			if ( perishable.is(':checked') ) {
				showElement( dimensions );
			} else if ( nonPerishable.is(':checked') ) {
				showElement( shippingOption );
				toggleBoxDropdowns( $('#simple-product-shipping .shipping-option-select option[selected]'), 'simple' );
			}
		} else if ( 'variable' == productType.val() ) {
			hideElement( weight );
			hideElement( dimensions );
			hideElement( shippingOption );
			hideElement( flatBoxSize );
			hideElement( regionalBoxSize )
			if ( perishable.is(':checked') ) {
				hideElement( variableProductShipping );
				showElement( variableDimensions );
			} else if ( nonPerishable.is(':checked') ) {
				showElement( variableProductShipping );
				hideElement( variableDimensions );
				toggleBoxDropdowns( $('.variable-product-shipping .shipping-option-select option[selected]'), 'variable' );
			}
		}
	}

	// If perishable product is selected, show dimensions, else show shipping option fields
	$('#easypost-perishable, #easypost-non-perishable').change( function() {
		if ( 'simple' == productType.val() ) {
			if ( 'yes' === $(this).val() ) {
				showElement( dimensions );
				hideElement( shippingOption );
				hideElement( flatBoxSize );
				hideElement( regionalBoxSize );
				hideElement( variableProductShipping );
			} else if ( 'no' === $(this).val() ) {
				showElement( shippingOption );
				hideElement( dimensions );
				toggleBoxDropdowns( $('.variable-product-shipping .shipping-option-select option[selected]'), 'simple' );
			}
		} else if ( 'variable' == productType.val() ) {
			if ( 'yes' === $(this).val() ) {
				showElement( variableDimensions );
				hideElement( shippingOption );
				hideElement( flatBoxSize );
				hideElement( regionalBoxSize );
				hideElement( variableProductShipping );
			} else if ( 'no' === $(this).val() ) {
				hideElement( dimensions );
				hideElement( variableDimensions );
				showElement( variableProductShipping );
				toggleBoxDropdowns( $('.variable-product-shipping .shipping-option-select option[selected]'), 'variable' );
			}
		}
	});

	// Toggle box dropdowns after user selects a shipping option
	$('#simple-product-shipping .shipping-option-select').change( function() {
		toggleBoxDropdowns( $(this), 'simple' );
	});
	$('.variable-product-shipping .shipping-option-select').change( function() {
		toggleBoxDropdowns( $(this), 'variable' );
	});

	// Toggle flat rate and regional rate dropdowns, depending on what shipping option was chosen
	function toggleBoxDropdowns( shippingOptionSelected, productType ) {
		if ( 'priority-flat-rate' === shippingOptionSelected.val() ) {
			if ( 'simple' == productType ) {
				showElement( simpleProductShipping.find('.flat-box-size') );
				hideElement( simpleProductShipping.find('.regional-box-size') );
			} else if ( 'variable' == productType ) {
				showElement( shippingOptionSelected.closest('.variable-product-shipping').find('.flat-box-size') );
				hideElement( shippingOptionSelected.closest('.variable-product-shipping').find('.regional-box-size') );
			}
		} else if ( 'priority-regional-rate' === shippingOptionSelected.val() ) {
			if ( 'simple' == productType ) {
				hideElement( simpleProductShipping.find('.flat-box-size') );
				showElement( simpleProductShipping.find('.regional-box-size') );
			} else if ( 'variable' == productType ) {
				hideElement( shippingOptionSelected.closest('.variable-product-shipping').find('.flat-box-size') );
				showElement( shippingOptionSelected.closest('.variable-product-shipping').find('.regional-box-size') );
			}
		} else {
			if ( 'simple' == productType ) {
				hideElement( simpleProductShipping.find('.flat-box-size') );
				hideElement( simpleProductShipping.find('.regional-box-size') );
			} else if ( 'variable' == productType ) {
				hideElement( shippingOptionSelected.closest('.variable-product-shipping').find('.flat-box-size') );
				hideElement( shippingOptionSelected.closest('.variable-product-shipping').find('.regional-box-size') );
			}
		}
	}

	function hideElement( element ) {
		element.attr("style", "display: none !important");
	}

	function showElement( element ) {
		element.attr("style", "display: block !important");
	}

	// Require marking the product as perishable/nonperishable
	$( '.dokan-dashboard-content .dokan-form-container' ).on( 'submit', function() {

		if ( $(this).find('input[name="update_product"]').length > 0 ) {

			if (perishable.is(':checked') || nonPerishable.is(':checked')) {
				return true;
			}

			perishable.focus();
			window.alert('Please specify whether the product is perishable or nonperishable under the Shipping tab.');
			return false;
		}
	});

});