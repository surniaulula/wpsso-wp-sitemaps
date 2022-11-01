<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

/**
 * Since WPSSO WPSM v3.0.0.
 *
 * Run wpssowpsm_get_server() to set $wp_sitemaps before the WordPress wp_sitemaps_get_server() function does.
 *
 * Note that the 'init' action hook runs before the 'parse_request' action hooks.
 *
 * See wordpress/wp-includes/default-filters.php.
 * See wordpress/wp-includes/sitemaps.php.
 */
if ( ! function_exists( 'wpssowpsm_get_server' ) ) {

	add_action( 'init', 'wpssowpsm_get_server', -100 );

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

/**
 * Since WPSSO WPSM v4.1.0.
 *
 * Fix for sitemaps pagination bug.
 *
 * Executes before send_headers(), query_posts(), handle_404(), and register_globals().
 *
 * See https://wp-kama.com/note/wp-sitemap-bug-404-pagination.
 * See https://core.trac.wordpress.org/ticket/51912
 */
if ( ! function_exists( 'wpssowpsm_wp_query_handle_sitemap' ) ) {

	add_action( 'parse_request', 'wpssowpsm_wp_query_handle_sitemap', -100, 1 );

	function wpssowpsm_wp_query_handle_sitemap( $wp ) {

		global $wp_query;

		$wp_query->is_sitemap            = empty( $wp->query_vars[ 'sitemap' ] ) ? false : true;
		$wp_query->is_sitemap_stylesheet = empty( $wp->query_vars[ 'sitemap-stylesheet' ] ) ? false : true;

		if ( ! $wp_query->is_sitemap && ! $wp_query->is_sitemap_stylesheet ) {

			return;
		}

		$saved_query_vars = $wp_query->query_vars;

		$wp_query->query_vars = $wp->query_vars;

		wp_sitemaps_get_server()->render_sitemaps();

		/**
		 * If the sitemap request was invalid, then undo the query changes. 
		 */
		$wp_query->is_sitemap            = false;
		$wp_query->is_sitemap_stylesheet = false;
		$wp_query->query_vars            = $saved_query_vars;
	}
}

if ( ! function_exists( 'is_sitemap' ) ) {

	function is_sitemap() {

		global $wp_query;

		if ( ! isset( $wp_query ) ) {

			_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );

			return false;
		}

		return property_exists( $wp_query, 'is_sitemap' ) ? $wp_query->is_sitemap : false;
	}
}

if ( ! function_exists( 'is_sitemap_stylesheet' ) ) {

	function is_sitemap_stylesheet() {

		global $wp_query;

		if ( ! isset( $wp_query ) ) {

			_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );

			return false;
		}

		return property_exists( $wp_query, 'is_sitemap_stylesheet' ) ? $wp_query->is_sitemap_stylesheet : false;
	}
}
