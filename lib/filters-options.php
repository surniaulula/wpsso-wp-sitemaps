<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
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
				'add_custom_post_type_options' => 1,
				'add_custom_taxonomy_options'  => 1,
			) );
		}

		public function filter_add_custom_post_type_options( $opt_prefixes ) {

			$opt_prefixes[ 'wpsm_sitemaps_for' ] = 1;

			return $opt_prefixes;
		}

		public function filter_add_custom_taxonomy_options( $opt_prefixes ) {

			$opt_prefixes[ 'wpsm_sitemaps_for_tax' ] = 1;

			return $opt_prefixes;
		}
	}
}
