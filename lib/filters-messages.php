<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmFiltersMessages' ) ) {

	class WpssoWpsmFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;     // WpssoWpsm class object.

		/*
		 * Instantiated by WpssoWpsmFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'messages_info'    => 2,
				'messages_tooltip' => 2,
			) );
		}

		public function filter_messages_info( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'info-wpsm-' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'info-wpsm-general':

					$addon_name = $this->p->cf[ 'plugin' ][ 'wpssowpsm' ][ 'name' ];

					$sitemap_url = get_site_url( $blog_id = null, $path = '/wp-sitemap.xml' );

					// translators: Please ignore - translation uses a different text domain.
					$no_index_label = _x( 'No Index', 'option label', 'wpsso' );

					// translators: Please ignore - translation uses a different text domain.
					$metabox_title = _x( $this->p->cf[ 'meta' ][ 'title' ], 'metabox title', 'wpsso' );

					// translators: Please ignore - translation uses a different text domain.
					$visibility = _x( 'Edit Visibility', 'metabox tab', 'wpsso' );

					$about_wp55 = __( 'https://make.wordpress.org/core/2020/07/22/new-xml-sitemaps-functionality-in-wordpress-5-5/', 'wpsso-wp-sitemaps' );

					$text = '<blockquote class="top-info">';

					$text .= '<p>';

					$text .= sprintf( __( 'The %1$s add-on extends the built-in WordPress sitemaps XML <a href="%2$s">available since WordPress version 5.5</a>.', 'wpsso-wp-sitemaps' ), $addon_name, $about_wp55 ) . ' ';

					$text .= __( 'These options allow you to customize the post and taxonomy types included in the WordPress sitemaps XML.', 'wpsso-wp-sitemaps' ) . ' ';

					$text .= sprintf( __( 'To <strong>exclude</strong> individual posts, pages, custom post types, taxonomy terms, or user profile pages from the WordPress sitemaps XML, enable the <strong>%1$s</strong> option under the %2$s &gt; %3$s metabox tab.', 'wpsso-wp-sitemaps' ), $no_index_label, $metabox_title, $visibility ) . ' ';

					$text .= '</p>';

					$text .= '</blockquote>';

					break;

			}

			return $text;
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-wpsm_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-wpsm_sitemaps_url':

					$text = __( 'The WordPress sitemaps URL can be submitted in Google\'s search console and viewed in a web browser.', 'wpsso-wp-sitemaps' );

					break;

				case 'tooltip-wpsm_sitemaps_for':

					$text = __( 'Select the post types and taxonomies to include in the WordPress sitemaps.', 'wpsso-wp-sitemaps' );

					break;

				case 'tooltip-wpsm_schema_images':

					$text = __( 'Add the images from Schema markup to the WordPress sitemaps.', 'wpsso-wp-sitemaps' ) . ' ';

					$text .= __( 'Google already reads image URLs and image information from the Schema markup, so this option is not required.', 'wpsso-wp-sitemaps' );

					break;

				case 'tooltip-wpsm_max_urls':

					$def_val = $this->p->opt->get_defaults( 'wpsm_max_urls' );

					$text .= sprintf( __( 'The maximum number of URLs to include in each sitemap (default is %s).', 'wpsso-wp-sitemaps' ), $def_val ) . ' ';

					break;
			}

			return $text;
		}
	}
}
