<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmSitemapsRenderer' ) && class_exists( 'WP_Sitemaps_Renderer' ) ) {

	/**
	 * Since WPSSO WPSM v3.0.0.
	 *
	 * WpssoWpsmSitemapsRenderer extends WP_Sitemaps_Renderer to provide a better get_sitemap_xml() method.
	 *
	 * See wordpress/wp-includes/sitemaps/class-wp-sitemaps-renderer.php.
	 *
	 * You can use a sitemap to tell Google all of the language and region variants for each URL. To do so, add a <loc> element
	 * specifying a single URL, with child <xhtml:link> entries listing every language/locale variant of the page including
	 * itself. Therefore if you have 3 versions of a page, your sitemap will have 3 entries, each with 3 identical child
	 * entries.
	 *
	 * See https://developers.google.com/search/docs/advanced/crawling/localized-versions#sitemap.
	 */
	class WpssoWpsmSitemapsRenderer extends WP_Sitemaps_Renderer {

		/**
		 * Example:
		 *
		 * $url_list = array(
		 *	array(
		 *		'loc' => 'https://example.com/page-1/',
		 *		'lastmod' => '2021-12-13T03:56:29+00:00',
		 *		'alternates' => array(
		 * 			array(
		 *				'href' => 'https://example.com/en/page-1/',
		 * 				'hreflang' => 'en_US',
		 * 			),
		 * 			array(
		 *				'href' => 'https://example.com/fr/page-1/',
		 * 				'hreflang' => 'fr_FR',
		 * 			),
		 * 			array(
		 *				'href' => 'https://example.com/es/page-1/',
		 * 				'hreflang' => 'es_ES',
		 * 			),
		 * 		),
		 *	),
		 *	array(
		 *		'loc' => 'https://example.com/page-2/',
		 *		'lastmod' => '2021-12-13T03:56:29+00:00',
		 *	),
		 * );
		 */
		public function get_sitemap_xml( $url_list ) {

			$namespaces = array(
				'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
				'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
				'xmlns:xhtml="http://www.w3.org/1999/xhtml"',
				'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.w3.org/1999/xhtml http://www.w3.org/2002/08/xhtml/xhtml1-strict.xsd"',
			);

			$urlset = '<urlset ' . implode( ' ', $namespaces ) . ' />';

			$data = new SimpleXMLElement( '<?xml version="1.0" encoding="UTF-8" ?' . '>' . $this->stylesheet . $urlset );

			foreach ( $url_list as $num => $url_items ) {

				$url_data = $data->addChild( 'url' );

				$this->add_items( $url_data, $url_items );
			}
	
			return $data->asXML();
		}
		
		protected function add_items( &$data, $items ) {

			if ( ! is_array( $items ) ) {

				return;
			}

			/**
			 * Standard sitemap tags array used for re-ordering the $item array with 'loc' as the first element.
			 *
			 * See https://www.sitemaps.org/protocol.html.
			 */
			$standard_tags = array( 'loc' => '', 'lastmod' => '', 'changefreq' => '', 'priority' => '' );

			$items = array_merge( $standard_tags, $items );	// Re-order the array.

			foreach ( $items as $name => $val ) {

				if ( '' === $val ) {

					continue;

				} elseif ( 'alternates' === $name && is_array( $val ) ) {

					foreach ( $val as $num => $attrs ) {
					
						$link_data = $data->addChild( 'link', null, 'http://www.w3.org/1999/xhtml' );

						$link_data->addAttribute( 'rel', 'alternate' );

						$this->add_items( $link_data, $attrs );	// Recurse.
					}

				} elseif ( 'href' === $name ) {

					$data->addAttribute( 'href', esc_url( $val ) );

				} elseif ( 'hreflang' === $name ) {

					$data->addAttribute( 'hreflang', esc_xml( $val ) );
						
				} elseif ( 'loc' === $name ) {

					$data->addChild( $name, esc_url( $val ) );

				} elseif ( isset( $standard_tags[ $name ] ) && is_string( $val ) ) {

					$data->addChild( $name, esc_xml( $val ) );
				}
			}
		}
	}
}
