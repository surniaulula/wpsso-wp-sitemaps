<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! function_exists( 'wpssowpsm_get_server' ) ) {

	/**
	 * Since WPSSO WPSM v3.0.0.
	 *
	 * Run wpssowpsm_get_server() to set $wp_sitemaps before the WordPress wp_sitemaps_get_server() function does.
	 *
	 * See wordpress/wp-includes/default-filters.php.
	 * See wordpress/wp-includes/sitemaps.php.
	 */
	add_action( 'init', 'wpssowpsm_get_server', -10 );

	function wpssowpsm_get_server() {

		global $wp_sitemaps;

		if ( empty( $wp_sitemaps ) ) {

			require_once WPSSOWPSM_PLUGINDIR . 'lib/sitemaps/sitemaps.php';

			if ( class_exists( 'WpssoWpsmSitemaps' ) ) {

				$wp_sitemaps = new WpssoWpsmSitemaps();

				$wp_sitemaps->init();

				do_action( 'wp_sitemaps_init', $wp_sitemaps );
			}
		}

		return $wp_sitemaps;
	}
}
