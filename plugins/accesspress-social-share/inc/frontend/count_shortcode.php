<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
global $post;
$options = get_option( APSS_SETTING_NAME );
if(isset($attr['custom_url_link']) && $attr['custom_url_link'] !=''){
	$url = esc_url($attr['custom_url_link']);
}else{
	$url = (get_permalink() != FALSE) ? get_permalink() : $this->curPageURL();
}
if ( isset( $attr['network'] ) ) {
	$raw_array = explode( ',', $attr['network'] );
	$network_array = array_map( 'trim', $raw_array );
	$new_array = array();
	foreach ( $network_array as $network ) {
		$new_array[$network] = '1';
	}
	$options['social_networks'] = $new_array;
}
$total_count = 0;
$count = 0;
foreach ( $options['social_networks'] as $key => $value ) {
	if ( intval( $value ) == '1' ) {
		$count = $this->get_count( $key, $url );
		$total_count += $count;
	}
}
echo $total_count;