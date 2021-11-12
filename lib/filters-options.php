<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmFiltersOptions' ) ) {

	class WpssoWpsmFiltersOptions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWpsm class object.

		/**
		 * Instantiated by WpssoWpsmFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults'           => 1,
			) );
		}

		public function filter_get_defaults( $defs ) {

			$this->p->util->add_post_type_names( $defs, array(
				'plugin_sitemaps_for' => 1,
			) );

			$this->p->util->add_taxonomy_names( $defs, array(
				'plugin_sitemaps_for_tax' => 1,
			) );

			return $defs;
		}
	}
}
