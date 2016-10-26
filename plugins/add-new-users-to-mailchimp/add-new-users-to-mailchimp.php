<?php
/**
 * Plugin Name: Add New Users to MailChimp
 * Description: Add new buyers and sellers to the corresponding MailChimp mailing lists
 * Version: 1.0
 * Author: Luminary
 * Author URI: http://luminary.ws/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
 * Add new buyers and sellers to the corresponding MailChimp lists
 *
 * @param $user_id The ID of the newly registered user
 */
function edibly_add_user_to_email_list( $user_id = 0 ) {

	$mc4wp_options        = mc4wp_get_options();
	$api_key              = sanitize_text_field( $mc4wp_options['api_key'] );
	$seller_newsletter_id = '99f28b17c8';
	$buyer_newsletter_id  = 'acab65c55b';

	$user_data = get_userdata( $user_id );
	$email     = $user_data->user_email;
	$role      = $user_data->roles[0];

	if ( 'seller' === $role ) {
		edibly_subscribe_user( $api_key, $seller_newsletter_id, $email );
	}

	if ( 'customer' === $role ) {
		edibly_subscribe_user( $api_key, $buyer_newsletter_id, $email );
	}
}
add_action( 'user_register', 'edibly_add_user_to_email_list', 10, 1 );

/*
 * Subscribe user to a mailing list
 *
 * @param string $api_key The MailChimp API key
 * @param int $newsletter_id The id of the MailChimp mailing list
 * @param string $email The user's email address
 */
function edibly_subscribe_user( $api_key, $newsletter_id, $email ) {

	if ( empty( $api_key ) || empty( $newsletter_id ) || empty( $email ) ) {
		return;
	}

	$mailchimp_api = new MC4WP_API( $api_key );
	$mailchimp_api->subscribe( $newsletter_id, $email );
}