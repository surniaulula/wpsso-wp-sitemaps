<?php
/**
 * Plugin Name: WPSSO WP Sitemaps XML
 * Plugin Slug: wpsso-wp-sitemaps
 * Text Domain: wpsso-wp-sitemaps
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-wp-sitemaps/
 * Assets URI: https://surniaulula.github.io/wpsso-wp-sitemaps/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Select post types and taxonomies added to the WordPress sitemaps XML, includes localized pages for Google, excludes "No Index" and redirected pages.
 * Requires Plugins: wpsso
 * Requires PHP: 7.2
 * Requires At Least: 5.5
 * Tested Up To: 6.1.0
 * WC Tested Up To: 7.0.1
 * Version: 4.1.0-dev.9
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAbstractAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstract/add-on.php';
}

if ( ! class_exists( 'WpssoWpsm' ) ) {

	class WpssoWpsm extends WpssoAbstractAddOn {

		protected $p;		// Wpsso class object.

		private static $instance = null;	// WpssoWpsm class object.

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'wpsso-wp-sitemaps', false, 'wpsso-wp-sitemaps/languages/' );
		}

		public function init_objects() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			new WpssoWpsmFilters( $this->p, $this );
			new WpssoWpsmSitemapsFilters( $this->p, $this );
		}
	}

	WpssoWpsm::get_instance();
}
