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

			$urlset = array(
				'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
				'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
				'xmlns:xhtml="http://www.w3.org/1999/xhtml"',
				'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.w3.org/1999/xhtml http://www.w3.org/2002/08/xhtml/xhtml1-strict.xsd"',
			);

			// @todo pvb: I STRONLY suggest you change the name of this filter by prefixing it with this plugin's name
			//       so that it does not seem like it is a core filter.
			// @todo pvb: I also STRONGLY suggest you change the same to something like 'xxxx_declarations' so that
			//       it's clear to users of the filter that it's not filtering the $urlset, it's filtering the
			//       namespace declarations (and schemaLocations associated with those declarations).
			// @todo pvb: I also STRONGLY suggest you NOT allow other plugins to filter the default namespace (i.e.,
			//       only allow them to add extension namespaces).  See https://github.com/GoogleChromeLabs/wp-sitemaps/issues/151#issuecomment-612252959
			//       for an alternate (pun intended :-) way of having this filter work.  Note: that comment on that
			//       sitemaps feature plugin does NOT address (i.e., via a filter) allowing plugins to specify @author pbiron
			//       URL for the schema document to use for a given namespace URI (e.g., for use in @xsi:schemaLocation).
			//       I've got code somehwere that provides another filter to do that but I've switched computers
			//       since I developed that and I'll have to dig out the hard drive for my old machine to find it.
			$urlset = (array) apply_filters( 'wp_sitemap_xml_urlset', $urlset );

			/**
			 * See https://www.php.net/manual/en/class.simplexmlelement.php.
			 */
			$urlset = new SimpleXMLElement(
				sprintf(
					'%1$s%2$s%3$s',
					'<?xml version="1.0" encoding="UTF-8" ?' . '>',
					$this->stylesheet,
					'<urlset ' . implode( ' ', $urlset ) . '/>'
				)
			);

			foreach ( $url_list as $url_item ) {
				$url = $urlset->addChild( 'url' );

				// Add each element as a child node to the <url> entry.
				foreach ( $url_item as $name => $value ) {
					if ( 'loc' === $name ) {
						$url->addChild( $name, esc_url( $value ) );
					} elseif ( in_array( $name, array( 'lastmod', 'changefreq', 'priority' ), true ) ) {
						$url->addChild( $name, esc_xml( $value ) );
					} elseif ( 'alternates' === $name && ! empty( $value ) ) {
						$xhtml_link = $url->addChild( 'link', null, 'http://www.w3.org/1999/xhtml' );
						$xhtml_link->addAttribute( 'rel', 'alternate' );

						foreach ( $value as $attributes ) {
							foreach ( $attributes as $attr_name => $attr_value ) {
								if ( 'href' === $attr_name ) {
									$xhtml_link->addAttribute( $attr_name, esc_url( $attr_value ) );
								} elseif ( 'hreflang' === $attr_name ) {
									$xhtml_link->addAttribute( $attr_name, esc_attr( $attr_value ) );
								}
								// @todo pvb: allow other attributes on xhtml:link (e.g., @charset)?  I don't know if
								//       Google et. al accept any attributes other than @rel, @href, and @hreflang
								//       that are legal in XHTML...or only those 3.
							}
						}
					} else {
						_doing_it_wrong(
							__METHOD__,
							sprintf(
								/* translators: %s: List of element names. */
								__( 'Fields other than %s are not currently supported for sitemaps.' ),
								implode( ',', array( 'loc', 'lastmod', 'changefreq', 'priority', 'xhtml:link' ) )
							),
							'5.5.0'
						);
					}
				}
			}

			echo $urlset->asXML();
		}

	}
}
