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
	 * WpssoWpsmSitemapsRenderer extends WP_Sitemaps_Renderer to provide a different get_sitemap_xml() method.
	 *
	 * See wordpress/wp-includes/sitemaps/class-wp-sitemaps-renderer.php.
	 */
	class WpssoWpsmSitemapsRenderer extends WP_Sitemaps_Renderer {

		public function get_sitemap_xml( $url_list ) {

			$urlset = array( 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' );

			$urlset = (array) apply_filters( 'wp_sitemap_xml_urlset', $urlset );

			/**
			 * See https://www.php.net/manual/en/class.simplexmlelement.php.
			 */
			$data = new SimpleXMLElement( sprintf( '%1$s%2$s%3$s',
				'<?xml version="1.0" encoding="UTF-8" ?' . '>',
				$this->stylesheet,
				'<urlset ' . implode( ' ', $urlset ) . ' />'
			) );

			/**
			 * See https://www.sitemaps.org/protocol.html.
			 */
			$standard_tags = array( 'loc' => '', 'lastmod' => '', 'changefreq' => '', 'priority' => '' );

			foreach ( $url_list as $url_item ) {

				$url = $data->addChild( 'url' );

				/**
				 * Order tags with 'loc' first.
				 */
				$url_item = array_merge( $standard_tags, $url_item );
	
				foreach ( $url_item as $name => $value ) {

					if ( '' === $value ) {

						continue;
					}

					if ( 'loc' === $name ) {

						$loc = $url->addChild( $name, esc_url( $value ) );

						if ( ! empty( $url_item[ 'language' ] ) ) {

							$loc->addAttribute( 'language', $url_item[ 'language' ] );

							unset( $url_item[ 'language' ] );
						}

					} elseif ( isset( $standard_tags[ $name ] ) ) {

						$url->addChild( $name, esc_xml( $value ) );
					}
				}
			}
	
			return $data->asXML();
		}
	}
}
