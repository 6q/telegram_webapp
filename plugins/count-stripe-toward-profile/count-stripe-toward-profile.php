<?php
/**
 * Plugin Name: Count Stripe Setup Toward Seller Profile Completion
 * Description: Dokan doesn't recognize Stripe as a payment gateway and count it toward sellers' profile completion. This plugin adds that.
 * Version: 1.0
 * Author: Luminary
 * Author URI: https://luminary.ws/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
 * Count Stripe setup toward seller profile completion
 */
function edibly_count_stripe_toward_profile_progress( $store_id ) {

	if ( ! empty( $dokan_settings = get_user_meta( $store_id, 'dokan_profile_settings', true ) ) ) {
		$profile_completion = $dokan_settings['profile_completion'];
		$next_todo          = $profile_completion['next_todo'];
		$progress           = $profile_completion['progress'];
	}

	if ( strpos( $next_todo, 'Add a Payment method to gain') !== false ) {

		$stripe_key = get_user_meta( $store_id, '_stripe_connect_access_key', true );

		// If stripe is set up, count that toward profile progress and proceed to evaluating completeness of location data
		if ( ! empty( $stripe_key ) ) {
			$next_todo = '';
			$progress += 15;
			edibly_calculate_location_profile_completeness_value( $store_id, $dokan_settings, $next_todo, $progress );
		}
	}
}
add_action( 'dokan_store_profile_saved', 'edibly_count_stripe_toward_profile_progress' );

/*
 * Calculate location profile completeness value
 */
function edibly_calculate_location_profile_completeness_value( $store_id, $dokan_settings, $next_todo, $progress ) {

	$track_val = array();
	$map_val   = 15;

	if ( isset( $dokan_settings['location'] ) && strlen( trim( $dokan_settings['location'] ) ) != 0 ) {
		$progress += $map_val;
		$track_val['location'] = $map_val;
	} elseif ( strlen( $next_todo ) == 0 ) {
		$next_todo = sprintf( __( 'Add Map location to gain %s%% progress', 'dokan' ), $map_val );
	}

	$dokan_settings['profile_completion']['next_todo'] = $next_todo;
	$dokan_settings['profile_completion']['progress']  = $progress;

	$prev_dokan_settings = get_user_meta( $store_id, 'dokan_profile_settings', true );
	$updated_settings = array_merge( $prev_dokan_settings, $dokan_settings );

	update_user_meta( $store_id, 'dokan_profile_settings', $updated_settings );
}