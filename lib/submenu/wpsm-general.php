<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmSubmenuWpsmGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoWpsmSubmenuWpsmGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;
		}

		/**
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$metabox_id      = 'wpsm';
			$metabox_title   = _x( 'WordPress Sitemaps', 'metabox title', 'wpsso-google-merchant-feed' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_' . $metabox_id ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );
		}

		public function show_metabox_wpsm() {

			$metabox_id = 'wpsm';

			$tab_key = 'general';

			$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );

			$table_rows = $this->get_table_rows( $metabox_id, $tab_key );

			$table_rows = apply_filters( $filter_name, $table_rows, $this->form, $network = false );

			$this->p->util->metabox->do_table( $table_rows, 'metabox-' . $metabox_id . '-' . $tab_key );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'wpsm-general':

					$table_rows[] = '<td colspan="2">' . $this->p->msgs->get( 'info-' . $metabox_id . '-' . $tab_key ) . '</td>';

					if ( SucomUtilWP::sitemaps_disabled() ) {	// Nothing to do.

						return $this->p->msgs->get_wp_sitemaps_disabled_rows( $table_rows );
					}

					$sitemaps_url = get_site_url( $blog_id = null, $path = '/wp-sitemap.xml' );

					$table_rows[ 'wpsm_sitemaps_url' ] = '' . 
						$this->form->get_th_html( _x( 'WordPress Sitemaps URL', 'option label', 'wpsso' ) ) .
						'<td>' . $this->form->get_no_input_clipboard( $sitemaps_url ) . '</td>';

					$table_rows[ 'wpsm_sitemaps_for' ] = '' .
						$this->form->get_th_html( _x( 'Include in Sitemaps', 'option label', 'wpsso' ),
							$css_class = '', $css_id = 'wpsm_sitemaps_for' ) .
						'<td>' . $this->form->get_checklist_post_tax_user( $name_prefix = 'wpsm_sitemaps_for' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
