<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmSitemapsFilters' ) ) {

	class WpssoWpsmSitemapsFilters {

		private $p;		// Wpsso class object.
		private $a;		// WpssoWpsm class object.
		private $stylesheet;	// WP_Sitemaps_Stylesheet class object.

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

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( SucomUtilWP::sitemaps_disabled() ) {	// Nothing to do.

				return;
			}

			$this->stylesheet = new WP_Sitemaps_Stylesheet();

			add_filter( 'wp_sitemaps_post_types', array( $this, 'wp_sitemaps_post_types' ), 1000, 1 );
			add_filter( 'wp_sitemaps_posts_query_args', array( $this, 'wp_sitemaps_posts_query_args' ), 1000, 2 );
			add_filter( 'wp_sitemaps_posts_pre_url_list', array( $this, 'wp_sitemaps_posts_pre_url_list' ), 1000, 3 );
			add_filter( 'wp_sitemaps_posts_entry', array( $this, 'wp_sitemaps_posts_entry' ), 1000, 3 );

			add_filter( 'wp_sitemaps_taxonomies', array( $this, 'wp_sitemaps_taxonomies' ), 1000, 1 );
			add_filter( 'wp_sitemaps_taxonomies_query_args', array( $this, 'wp_sitemaps_taxonomies_query_args' ), 1000, 2 );
			add_filter( 'wp_sitemaps_taxonomies_entry', array( $this, 'wp_sitemaps_taxonomies_entry' ), 1000, 3 );

			add_filter( 'wp_sitemaps_users_query_args', array( $this, 'wp_sitemaps_users_query_args' ), 1000, 1 );

			add_filter( 'wp_sitemaps_stylesheet_content', array( $this, 'wp_sitemaps_stylesheet_content'), 1000, 1 );
		}

		/**
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php.
		 */
		public function wp_sitemaps_post_types( $post_types ) {

			$post_types = SucomUtil::get_post_types( $output = 'objects' );

			foreach ( $post_types as $name => $obj ) {

				if ( empty( $this->p->options[ 'wpsm_sitemaps_for_' . $name ] ) ) {

					unset( $post_types[ $name ] );
				}
			}

			return $post_types;
		}

		/**
		 * Exclude posts from the sitemap that are noindex or redirected.
		 *
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php.
		 */
		public function wp_sitemaps_posts_query_args( $args, $post_type ) {

			/**
			 * The published post status for attachments is 'inherit'.
			 */
			if ( 'attachment' === $post_type ) {

				$args[ 'post_status' ] = array( 'inherit' );
			}

			static $local_cache = array();	// Create the exclusion list only once.

			if ( ! isset( $local_cache[ $post_type ] ) ) {

				$local_cache[ $post_type ] = array();

				$query = new WP_Query( array_merge( $args, array(
					'fields'        => 'ids',
					'no_found_rows' => true,
					'post_type'     => $post_type,
				) ) );

				if ( ! empty( $query->posts ) ) {	// Just in case.

					$redir_enabled = $this->p->util->is_redirect_enabled();

					foreach ( $query->posts as $post_id ) {

						if ( $this->p->util->robots->is_noindex( 'post', $post_id ) ) {

							$local_cache[ $post_type ][] = $post_id;

						/**
						 * If WPSSO is handling redirects, then exclude this post if it is being redirected.
						 */
						} elseif ( $redir_enabled && $this->p->util->get_redirect_url( 'post', $post_id ) ) {

							$local_cache[ $post_type ][] = $post_id;
						}
					}
				}
			}

			if ( ! empty( $local_cache[ $post_type ] ) ) {

				$args[ 'post__not_in' ] = empty( $args[ 'post__not_in' ] ) ? $local_cache[ $post_type ] :
					array_merge( $args[ 'post__not_in' ], $local_cache[ $post_type ] );
			}

			return $args;
		}

		/**
		 * Since WPSSO WPSM v2.0.0.
		 *
		 * Include post type archive pages without a post ID.
		 *
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php.
		 */
		public function wp_sitemaps_posts_pre_url_list( $url_list, $post_type, $page_num ) {

			$args = $this->get_posts_query_args( $post_type );

			$args[ 'paged' ] = $page_num;

			$query = new WP_Query( $args );

			$url_list = array();

			if ( $post_type_archive_url = get_post_type_archive_link( $post_type ) ) {

				$sitemap_entry = array( 'loc' => $post_type_archive_url );

				$sitemap_entry = apply_filters( 'wp_sitemaps_posts_post_type_archive_entry', $sitemap_entry, $post_type );

				if ( $sitemap_entry ) {	// Just in case.

					$url_list[] = $sitemap_entry;
				}
			}

			/**
			 * Add a URL for the homepage in the pages sitemap.
			 *
			 * Shows only on the first page if the reading settings are set to display latest posts.
			 */
			if ( 'page' === $post_type && 1 === $page_num && 'posts' === get_option( 'show_on_front' ) ) {

				/**
				 * Extract the data needed for home URL to add to the array.
				 */
				$sitemap_entry = array( 'loc' => home_url( '/' ) );

				/**
				 * Filters the sitemap entry for the home page when the 'show_on_front' option equals 'posts'.
				 */
				$sitemap_entry = apply_filters( 'wp_sitemaps_posts_show_on_front_entry', $sitemap_entry );

				if ( $sitemap_entry ) {	// Just in case.

					$url_list[] = $sitemap_entry;
				}
			}

			foreach ( $query->posts as $post ) {

				$sitemap_entry = array( 'loc' => get_permalink( $post ) );

				/**
				 * Filters the sitemap entry for an individual post.
				 */
				$sitemap_entry = apply_filters( 'wp_sitemaps_posts_entry', $sitemap_entry, $post, $post_type );

				if ( $sitemap_entry ) {	// Just in case.

					$url_list[] = $sitemap_entry;
				}
			}

			return $url_list;
		}

		/**
		 * Since WPSSO WPSM v2.0.0.
		 *
		 * Recreates the functionality of the WP_Sitemaps_Posts->get_posts_query_args() protected method.
		 * 
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php.
		 */
		public function get_posts_query_args( $post_type ) {

			$args = apply_filters(
				'wp_sitemaps_posts_query_args',
				array(
					'orderby'                => 'ID',
					'order'                  => 'ASC',
					'post_type'              => $post_type,
					'posts_per_page'         => wp_sitemaps_get_max_urls( 'post' ),
					'post_status'            => array( 'publish' ),
					'no_found_rows'          => true,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				),
				$post_type
			);

			return $args;
		}

		/**
		 * Add the modification time for Open Graph type non-website posts (ie. article, book, product, etc.), post
		 * language, and alternate post languages.
		 *
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php.
		 */
		public function wp_sitemaps_posts_entry( $sitemap_entry, $post, $post_type ) {

			if ( empty( $post->ID ) ) {	// Just in case.

				return $sitemap_entry;
			}

			$mod     = $this->p->post->get_mod( $post->ID );
			$og_type = $this->p->og->get_mod_og_type_id( $mod );	// Since WPSSO Core v9.13.0.

			if ( 'website' !== $og_type ) {

				if ( $mod[ 'post_modified_time' ] ) {

					$sitemap_entry[ 'lastmod' ] = $mod[ 'post_modified_time' ];
				}
			}

			$sitemap_entry[ 'alternates' ] = $this->p->util->get_link_rel_alternates( $mod );

			return $sitemap_entry;
		}

		/**
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-taxonomies.php.
		 */
		public function wp_sitemaps_taxonomies( $taxonomies ) {

			$taxonomies = SucomUtil::get_taxonomies( $output = 'objects' );

			foreach ( $taxonomies as $name => $obj ) {

				if ( empty( $this->p->options[ 'wpsm_sitemaps_for_tax_' . $name ] ) ) {

					unset( $taxonomies[ $name ] );
				}
			}

			return $taxonomies;
		}

		/**
		 * Exclude terms from the sitemap that are noindex or redirected.
		 * 
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-taxonomies.php.
		 */
		public function wp_sitemaps_taxonomies_query_args( $args, $taxonomy ) {

			static $local_cache = array();	// Create the exclusion list only once.

			if ( ! isset( $local_cache[ $taxonomy ] ) ) {

				$local_cache[ $taxonomy ] = array();

				$query = new WP_Term_Query( array_merge( $args, array(
					'fields'        => 'ids',
					'no_found_rows' => true,
				) ) );

				if ( ! empty( $query->terms ) ) {	// Just in case.

					$redir_enabled = $this->p->util->is_redirect_enabled();

					foreach ( $query->terms as $term_id ) {

						if ( $this->p->util->robots->is_noindex( 'term', $term_id ) ) {

							$local_cache[ $taxonomy ][] = $term_id;

						/**
						 * If WPSSO is handling redirects, then exclude this term if it is being redirected.
						 */
						} elseif ( $redir_enabled && $this->p->util->get_redirect_url( 'term', $term_id ) ) {

							$local_cache[ $taxonomy ][] = $term_id;
						}
					}
				}
			}

			if ( ! empty( $local_cache[ $taxonomy ] ) ) {

				$args[ 'exclude' ] = empty( $args[ 'exclude' ] ) ? $local_cache[ $taxonomy ] :
					array_merge( $args[ 'exclude' ], $local_cache[ $taxonomy ] );
			}

			return $args;
		}

		/**
		 * Add the term language and alternate term languages.
		 *
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-taxonomies.php.
		 */
		public function wp_sitemaps_taxonomies_entry( $sitemap_entry, $term, $taxonomy ) {

			if ( empty( $term->term_id ) ) {	// Just in case.

				return $sitemap_entry;
			}

			$mod = $this->p->term->get_mod( $term->term_id );

			$sitemap_entry[ 'alternates' ] = $this->p->util->get_link_rel_alternates( $mod );

			return $sitemap_entry;
		}

		/**
		 * Exclude users from the sitemap that are noindex or redirected.
		 *
		 * See wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-users.php
		 */
		public function wp_sitemaps_users_query_args( $args ) {

			if ( empty( $this->p->options[ 'wpsm_sitemaps_for_user_page' ] ) ) {

				/**
				 * Exclude all users by including only user ID 0 (which does not exist).
				 */
				$args[ 'include' ] = array( 0 );

			} else {

				static $local_cache = null;	// Create the exclusion list only once.

				if ( null === $local_cache ) {

					$local_cache = array();

					$query = new WP_User_Query( array_merge( $args, array(
						'fields'        => 'ids',
						'no_found_rows' => true,
					) ) );

					$users = $query->get_results();

					if ( ! empty( $users ) ) {	// Just in case.

						$redir_enabled = $this->p->util->is_redirect_enabled();

						foreach ( $users as $user_id ) {

							if ( $this->p->util->robots->is_noindex( 'user', $user_id ) ) {

								$local_cache[] = $user_id;

							/**
							 * If WPSSO is handling redirects, then exclude this user if it is being redirected.
							 */
							} elseif ( $redir_enabled && $this->p->util->get_redirect_url( 'user', $user_id ) ) {

								$local_cache[] = $user_id;
							}
						}
					}
				}

				if ( ! empty( $local_cache ) ) {

					$args[ 'exclude' ] = empty( $args[ 'exclude' ] ) ? $local_cache :
						array_merge( $args[ 'exclude' ], $local_cache );
				}
			}

			return $args;
		}

		/**
		 * See wordpress/wp-includes/sitemaps/class-wp-sitemaps-stylesheet.php.
		 */
		public function wp_sitemaps_stylesheet_content( $xsl_content ) {

			$css         = $this->stylesheet->get_stylesheet_css();
			$title       = esc_xml( __( 'XML Sitemap' ) );
			$desc        = esc_xml( __( 'This XML Sitemap is generated by WordPress to make your content more visible for search engines.' ) );
			$plugin_name = $this->p->cf[ 'plugin' ][ 'wpssowpsm' ][ 'name' ];
			$plugin_desc = esc_xml( sprintf( __( 'The default XML Sitemap generated by WordPress has been extended and customized by the %s plugin.', 'wpsso-wp-sitemaps' ), $plugin_name ) );
			$learn_more  = sprintf(
				'<a href="%s">%s</a>',
				esc_url( __( 'https://www.sitemaps.org/' ) ),
				esc_xml( __( 'Learn more about XML sitemaps.' ) )
			);

			$text = sprintf(
				/* translators: %s: Number of URLs. */
				esc_xml( __( 'Number of URLs in this XML Sitemap: %s.' ) ),
				'<xsl:value-of select="count( sitemap:urlset/sitemap:url )" />'
			);

			$lang       = get_language_attributes( 'html' );
			$url        = esc_xml( __( 'URL' ) );
			$lastmod    = esc_xml( __( 'Last Modified' ) );
			$changefreq = esc_xml( __( 'Change Frequency' ) );
			$priority   = esc_xml( __( 'Priority' ) );
			$xhtml_link = esc_xml( __( 'xhtml:link' ) );

			$xsl_content = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
		version="1.0"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
        	xmlns:xhtml="http://www.w3.org/1999/xhtml"
		exclude-result-prefixes="sitemap"
		>

	<xsl:output method="html" encoding="UTF-8" indent="yes" />

	<!--
	  Set variables for whether lastmod, changefreq or priority occur for any url in the sitemap.
	  We do this up front because it can be expensive in a large sitemap.
	  -->
	<xsl:variable name="has-lastmod"    select="count( /sitemap:urlset/sitemap:url/sitemap:lastmod )" />
	<xsl:variable name="has-changefreq" select="count( /sitemap:urlset/sitemap:url/sitemap:changefreq )" />
	<xsl:variable name="has-priority"   select="count( /sitemap:urlset/sitemap:url/sitemap:priority )" />
	<xsl:variable name="has-xhtml-link" select="count( /sitemap:urlset/sitemap:url/xhtml:link )" />

	<xsl:template match="/">
		<html {$lang}>
			<head>
				<title>{$title}</title>
				<style>
					{$css}
					#sitemap__table tr td { vertical-align:top; }
					#sitemap__table tr td ul.xhtml-links { list-style:none; font-size:0.85em; }
				</style>
			</head>
			<body>
				<div id="sitemap">
					<div id="sitemap__header">
						<h1>{$title}</h1>
						<p>{$desc}</p>
						<p>{$plugin_desc}</p>
						<p>{$learn_more}</p>
					</div>
					<div id="sitemap__content">
						<p class="text">{$text}</p>
                                		<xsl:apply-templates select="sitemap:urlset"/>
					</div>
				</div>
			</body>
		</html>
	</xsl:template>

    	<xsl:template match="sitemap:urlset">
		<table id="sitemap__table">
			<thead>
				<tr>
					<th class="loc">{$url}</th>
					<xsl:if test="\$has-lastmod">
						<th class="lastmod">{$lastmod}</th>
					</xsl:if>
					<xsl:if test="\$has-changefreq">
						<th class="changefreq">{$changefreq}</th>
					</xsl:if>
					<xsl:if test="\$has-priority">
						<th class="priority">{$priority}</th>
					</xsl:if>
				</tr>
			</thead>
			<tbody>
                             	<xsl:apply-templates select="sitemap:url"/>
			</tbody>
		</table>
	</xsl:template>

    	<xsl:template match="sitemap:url">
		<tr>
			<td class="loc">
				<a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc" /></a>
				<xsl:if test="\$has-xhtml-link">
					<ul class="xhtml-links">
                             			<xsl:apply-templates select="xhtml:link"/>
					</ul>
				</xsl:if>
			</td>
			<xsl:if test="\$has-lastmod">
				<td class="lastmod">
					<xsl:value-of select="sitemap:lastmod" />
				</td>
			</xsl:if>
			<xsl:if test="\$has-changefreq">
				<td class="changefreq">
					<xsl:value-of select="sitemap:changefreq" />
				</td>
			</xsl:if>
			<xsl:if test="\$has-priority">
				<td class="priority">
					<xsl:value-of select="sitemap:priority" />
				</td>
			</xsl:if>
		</tr>
	</xsl:template>

	<xsl:template match="xhtml:link">
		<li>
			<xsl:variable name="altloc">
				<xsl:value-of select="@href"/>
			</xsl:variable>
			<a href="{\$altloc}"><xsl:value-of select="@href"/></a>
			<xsl:if test="@hreflang">
				[<xsl:value-of select="@hreflang"/>]
			</xsl:if>
		</li>
	</xsl:template>
</xsl:stylesheet>
EOF;

			return $xsl_content;
		}
	}
}
