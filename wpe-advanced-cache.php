<?php
/**
 * Plugin Name: WP Engine Advanced Cache Options
 * Plugin URI: https://github.com/EthanKennedy/wpe-advanced-cache
 * Description: This plugin works to increase cache time across the board, and gives a smarter way to purge the cache
 * Version: 1.1.0
 * Author: Ethan Kennedy, Steven Word
 * Author URI: http://ethankennedy.me
 * License: GPL2.
 */

define( 'WPE_ADVANCED_CACHE_PATH', plugin_dir_path( __FILE__ ) );
require_once WPE_ADVANCED_CACHE_PATH . 'inc/wpeac-admin.php';
require_once WPE_ADVANCED_CACHE_PATH . 'inc/wpeac-core.php';

if ( is_admin() ) {
	new WPEAC_Admin();
}

new WPEAC_Core();

/**
 * Adds the headers to each request when the come in.
 *
 * Displays the headers we build further on in the plugin to the request.
 *
 * @since 0.1.0
 * @action wp
 * @uses get_the_ID, get_post_type, get_the_modified_date
 * @see WPEAC_Core::send_header_cache_control_length, WPEAC_Core::send_header_last_modified
 */
function wpe_ac_add_cache_header() {
	if ( ! is_singular() ) {
		return;
	}
	$post_id = get_the_ID();
	$post_type = get_post_type( $post_id );
	$last_modified = get_the_modified_date( 'U' );
	WPEAC_Core::send_header_cache_control_length( $last_modified, $post_id, $post_type );
	WPEAC_Core::send_header_last_modified( $last_modified, $post_id, $post_type );
}
add_action( 'wp', 'wpe_ac_add_cache_header' );

	/**
	 * Add cache headers for API end-points
	 *
	 * Adds a cache header in the same way we add them for posts
	 *
	 * @since 1.1.0
	 * @action rest_api_init
	 * @see WPEAC_CORE::send_header_cache_control_api
	 */

function wpe_ac_add_api_cache_header() {
	WPEAC_CORE::send_header_cache_control_api();
}

add_action( 'rest_api_init', 'wpe_ac_add_api_cache_header' );
