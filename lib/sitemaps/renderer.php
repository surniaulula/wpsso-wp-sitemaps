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

			$urlset = new SimpleXMLElement(
				sprintf(
					'%1$s%2$s%3$s',
					'<?xml version="1.0" encoding="UTF-8" ?' . '>',
					$this->stylesheet,
					'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />'
				)
			);

			foreach ( $url_list as $url_item ) {

				$url = $urlset->addChild( 'url' );
	
				foreach ( $url_item as $name => $value ) {

					if ( 'loc' === $name ) {

						$url->addChild( $name, esc_url( $value ) );

					} elseif ( in_array( $name, array( 'lastmod', 'changefreq', 'priority' ), true ) ) {

						$url->addChild( $name, esc_xml( $value ) );

					} else {

						_doing_it_wrong( __METHOD__, sprintf( __( 'Fields other than %s are not currently supported for sitemaps.' ),
							implode( ',', array( 'loc', 'lastmod', 'changefreq', 'priority' ) ) ), '5.5.0' );
					}
				}
			}
	
			return $urlset->asXML();
		}
	}
}
