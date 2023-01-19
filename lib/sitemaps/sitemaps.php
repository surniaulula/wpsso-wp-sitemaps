<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmSitemaps' ) && class_exists( 'WP_Sitemaps' ) ) {

	/*
	 * Since WPSSO WPSM v3.0.0.
	 *
	 * WpssoWpsmSitemaps extends WP_Sitemaps to set a different rederer class.
	 *
	 * See wordpress/wp-includes/sitemaps/class-wp-sitemaps.php.
	 */
	class WpssoWpsmSitemaps extends WP_Sitemaps {

		public function __construct() {

			parent::__construct();

			require_once WPSSOWPSM_PLUGINDIR . 'lib/sitemaps/renderer.php';

			if ( class_exists( 'WpssoWpsmSitemapsRenderer' ) ) {

				$this->renderer = new WpssoWpsmSitemapsRenderer();
			}
		}
	}
}
