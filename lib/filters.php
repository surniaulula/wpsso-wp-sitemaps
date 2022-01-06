<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmFilters' ) ) {

	class WpssoWpsmFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWpsm class object.
		private $msgs;	// WpssoWpsmFiltersMessages class object.
		private $opts;	// WpssoWpsmFiltersOptions class object.

		/**
		 * Instantiated by WpssoWpsm->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			require_once WPSSOWPSM_PLUGINDIR . 'lib/filters-options.php';

			$this->opts = new WpssoWpsmFiltersOptions( $plugin, $addon );

			if ( is_admin() ) {

				require_once WPSSOWPSM_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoWpsmFiltersMessages( $plugin, $addon );
			}
		}
	}
}
