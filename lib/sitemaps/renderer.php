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
	 */
	class WpssoWpsmSitemapsRenderer extends WP_Sitemaps_Renderer {

		/**
		 * Example:
		 *
		 * $url_list = array(
		 *	array(
		 *		'loc' => 'https://example.com/page-1/',
		 *		'language' => 'en_US',
		 *		'lastmod' => '2021-12-13T03:56:29+00:00',
		 *		'alternates' => array(
		 * 			array(
		 *				'href' => 'https://example.com/fr/page-1/',
		 * 				'language' => 'fr_FR',
		 *				'lastmod' => '2021-12-13T03:56:29+00:00',
		 * 			),
		 * 			array(
		 *				'href' => 'https://example.com/es/page-1/',
		 * 				'language' => 'es_ES',
		 *				'lastmod' => '2021-12-13T03:56:29+00:00',
		 * 			),
		 * 		),
		 *	),
		 *	array(
		 *		'loc' => 'https://example.com/page-2/',
		 *		'language' => 'en_US',
		 *		'lastmod' => '2021-12-13T03:56:29+00:00',
		 *	),
		 * );
		 */
		public function get_sitemap_xml( $url_list ) {

			/**
			 * Include 'xmlns:image="https://www.google.com/schemas/sitemap-image/1.1"' if adding images.
			 */
			$urlset = array(
				'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
				'xmlns:xhtml="http://www.w3.org/1999/xhtml"',
			);

			$urlset = (array) apply_filters( 'wp_sitemap_xml_urlset', $urlset );

			/**
			 * See https://www.php.net/manual/en/class.simplexmlelement.php.
			 */
			$data = new SimpleXMLElement( sprintf( '%1$s%2$s%3$s',
				'<?xml version="1.0" encoding="UTF-8" ?' . '>',
				$this->stylesheet,
				'<urlset ' . implode( ' ', $urlset ) . ' />'
			) );

			$this->add_sitemap_xml_children( $data, 'url', $url_list );
	
			return $data->asXML();
		}
		
		protected function add_sitemap_xml_children( &$data, $element_name, $items ) {

			if ( ! is_array( $items ) ) {

				return;
			}

			/**
			 * See https://www.sitemaps.org/protocol.html.
			 */
			$standard_tags = array( 'loc' => '', 'lastmod' => '', 'changefreq' => '', 'priority' => '' );

			foreach ( $items as $item ) {

				$container = $data->addChild( $element_name );

				$item = array_merge( $standard_tags, $item );	// Make sure 'loc' is first.
	
				foreach ( $item as $name => $value ) {

					if ( '' === $value ) {

						continue;
					}

					if ( 'loc' === $name ) {

						$loc = $container->addChild( $name, esc_url( $value ) );

						if ( ! empty( $item[ 'language' ] ) ) {

							$loc->addAttribute( 'language', $item[ 'language' ] );
						}

					} elseif ( 'alternates' === $name ) {

						$this->add_sitemap_xml_children( $container, 'xhtml:link', $value );	// Recurse.

					} elseif ( 'xhtml:link' === $element_name ) {

						$container->addAttribute( 'rel', 'alternate' );

						if ( ! empty( $item[ 'language' ] ) ) {

							$container->addAttribute( 'hreflang', esc_xml( $item[ 'language' ] ) );
						}
							
						if ( ! empty( $item[ 'href' ] ) ) {

							$container->addAttribute( 'href', esc_url( $value ) );
						}

					} elseif ( isset( $standard_tags[ $name ] ) ) {

						$container->addChild( $name, esc_xml( $value ) );
					}
				}
			}
		}
	}
}
