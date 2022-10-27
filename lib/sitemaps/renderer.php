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
	 * WpssoWpsmSitemapsRenderer extends WP_Sitemaps_Renderer to provide better render_sitemap() and get_sitemap_xml() methods.
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
		 * Since WPSSO WPSM v3.0.0.
		 *
		 * Replace the render_sitemap() method to clear the output buffer and if debugging is enabled, re-format the XML
		 * and include debugging messages.
		 */
		public function render_sitemap( $url_list ) {

			$wpsso =& Wpsso::get_instance();

			global $wp_query;

			$wp_query->is_404 = false;

			ob_implicit_flush( true );
			ob_end_flush();

			$content = $this->get_sitemap_xml( $url_list );

			if ( $wpsso->debug->enabled ) {

				/**
				 * Reformat the XML to make it human readable.
				 */
				$dom = new DOMDocument();

				$dom->preserveWhiteSpace = true;
				$dom->formatOutput = true;
				$dom->loadXML( $content );

				$content = $dom->saveXML();

				$content .= $wpsso->debug->get_html( null, 'debug log' );
			}

			$length = strlen( $content );

			header( 'HTTP/1.1 200 OK' );
			header( 'Content-type: application/xml; charset=UTF-8' );
			header( 'Content-Length: ' . $length );

			echo $content;

			flush();

			sleep( 1 );

			exit;
		}

		/**
		 * Since WPSSO WPSM v3.0.0.
		 *
		 * Replace the get_sitemap_xml() method to include additional namespaces and add support for alternate post and
		 * term languages.
		 *
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
			$allowed_tags = array(
				'loc'        => '',
				'lastmod'    => '',
				'changefreq' => '',
				'priority'   => '',
				'alternates' => '',
				'href'       => '',
				'hreflang'   => '',
				'images'     => '',
				'image:loc'  => '',
			);

			$items = array_merge( $allowed_tags, $items );	// Re-order the array.

			foreach ( $items as $name => $val ) {

				if ( '' === $val ) {

					continue;

				} elseif ( 'alternates' === $name && is_array( $val ) ) {

					foreach ( $val as $num => $attrs ) {

						$link_data = $data->addChild( 'link', null, 'http://www.w3.org/1999/xhtml' );

						$link_data->addAttribute( 'rel', 'alternate' );

						$this->add_items( $link_data, $attrs );	// Recurse.
					}

				} elseif ( 'href' === $name ) {	// Matched from 'alternates' recursion.

					$data->addAttribute( 'href', esc_url( $val ) );

				} elseif ( 'hreflang' === $name ) {	// Matched from 'alternates' recursion.

					$data->addAttribute( 'hreflang', esc_xml( $val ) );

				/**
				 * See https://developers.google.com/search/docs/crawling-indexing/sitemaps/image-sitemaps.
				 */
				} elseif ( 'images' === $name && is_array( $val ) ) {

					foreach ( $val as $num => $attrs ) {
					
						$image_data = $data->addChild( 'image:image', null, 'http://www.google.com/schemas/sitemap-image/1.1' );
						
						$this->add_items( $image_data, $attrs );	// Recurse.
					}

				} elseif ( 'image:loc' === $name ) {	// Matched from 'images' recursion.

					$data->addChild( $name, esc_url( $val ), 'http://www.google.com/schemas/sitemap-image/1.1' );

				} elseif ( 'loc' === $name ) {

					$data->addChild( $name, esc_url( $val ) );

				} elseif ( isset( $allowed_tags[ $name ] ) && is_string( $val ) ) {

					$data->addChild( $name, esc_xml( $val ) );

				} else {

					$error_msg = sprintf( __( '"%s" is not a recognized field name.', 'wpsso-wp-sitemaps' ),
						$name ) . ' ';

					$error_msg .= sprintf( __( 'Fields other than %s are not currently supported for sitemaps.', 'wpsso-wp-sitemaps' ),
						implode( ',', array_keys( $allowed_tags ) ) ) . ' ';

					_doing_it_wrong( __METHOD__, $error_msg, '5.5.0' );
				}
			}
		}
	}
}
