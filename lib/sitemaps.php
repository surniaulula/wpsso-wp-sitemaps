<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWpsmSitemaps' ) ) {

	class WpssoWpsmSitemaps {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWpsm class object.

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

			add_filter( 'wp_sitemaps_post_types', array( $this, 'wp_sitemaps_post_types' ), 10, 1 );
			add_filter( 'wp_sitemaps_posts_query_args', array( $this, 'wp_sitemaps_posts_query_args' ), 10, 2 );
			add_filter( 'wp_sitemaps_posts_entry', array( $this, 'wp_sitemaps_posts_entry' ), 10, 3 );
			add_filter( 'wp_sitemaps_taxonomies', array( $this, 'wp_sitemaps_taxonomies' ), 10, 1 );
			add_filter( 'wp_sitemaps_taxonomies_query_args', array( $this, 'wp_sitemaps_taxonomies_query_args' ), 10, 2 );
			add_filter( 'wp_sitemaps_users_query_args', array( $this, 'wp_sitemaps_users_query_args' ), 10, 1 );
			add_filter( 'wp_sitemaps_posts_pre_url_list', array( $this, 'wp_sitemaps_posts_pre_url_list' ), 10, 3 );
		}

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
		 * Exclude posts from the sitemap that have been defined as noindex.
		 */
		public function wp_sitemaps_posts_query_args( $args, $post_type ) {

			/**
			 * The published post status for attachments is 'inherit'.
			 */
			if ( 'attachment' === $post_type ) {

				$args[ 'post_status' ] = array( 'inherit' );
			}

			/**
			 * Create a post ID noindex array by post type.
			 */
			static $local_cache = array();

			if ( ! isset( $local_cache[ $post_type ] ) ) {

				$redir_enabled = $this->p->util->is_redirect_enabled();

				$local_cache[ $post_type ] = array();

				$query = new WP_Query( array_merge( $args, array(
					'fields'        => 'ids',
					'no_found_rows' => true,
					'post_type'     => $post_type,
				) ) );

				if ( ! empty( $query->posts ) ) {	// Just in case.

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
		 * Add a modification time for Open Graph type non-website posts (ie. article, book, product, etc.).
		 */
		public function wp_sitemaps_posts_entry( $sitemap_entry, $post, $post_type ) {

			if ( empty( $post->ID ) ) {	// Just in case.

				return $sitemap_entry;
			}

			$mod = $this->p->post->get_mod( $post->ID );

			$og_type = $this->p->og->get_mod_og_type_id( $mod );	// Since WPSSO Core v9.13.0.

			if ( 'website' !== $og_type ) {

				if ( $mod[ 'post_modified_time' ] ) {

					$sitemap_entry[ 'lastmod' ] = $mod[ 'post_modified_time' ];
				}
			}

			return $sitemap_entry;
		}

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
		 * Exclude terms from the sitemap that have been defined as noindex.
		 */
		public function wp_sitemaps_taxonomies_query_args( $args, $taxonomy ) {

			/**
			 * Create a term ID noindex array by taxonomy.
			 */
			static $local_cache = array();

			if ( ! isset( $local_cache[ $taxonomy ] ) ) {

				$redir_enabled = $this->p->util->is_redirect_enabled();

				$local_cache[ $taxonomy ] = array();

				$query = new WP_Term_Query( array_merge( $args, array(
					'fields'        => 'ids',
					'no_found_rows' => true,
				) ) );

				if ( ! empty( $query->terms ) ) {	// Just in case.

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
		 * Exclude users from the sitemap that have been defined as noindex.
		 */
		public function wp_sitemaps_users_query_args( $args ) {

			if ( empty( $this->p->options[ 'wpsm_sitemaps_for_user_page' ] ) ) {

				/**
				 * Exclude all users by including only user ID 0 (which does not exist).
				 */
				$args[ 'include' ] = array( 0 );

			} else {

				/**
				 * Create a user ID noindex array.
				 */
				static $local_cache = null;

				if ( null === $local_cache ) {

					$redir_enabled = $this->p->util->is_redirect_enabled();

					$local_cache = array();

					$query = new WP_User_Query( array_merge( $args, array(
						'fields'        => 'ids',
						'no_found_rows' => true,
					) ) );

					$users = $query->get_results();

					if ( ! empty( $users ) ) {	// Just in case.

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
		 * Since WPSSO WPSM v2.0.0.
		 *
		 * Extend the functionality of the WP_Sitemaps_Posts->get_url_list() public method from
		 * wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php to include post type archive pages without
		 * a post ID.
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
				 *
				 * @since 5.5.0
				 *
				 * @param array $sitemap_entry Sitemap entry for the home page.
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
				 *
				 * @since 5.5.0
				 *
				 * @param array   $sitemap_entry Sitemap entry for the post.
				 * @param WP_Post $post          Post object.
				 * @param string  $post_type     Name of the post_type.
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
		 * Recreates the functionality of the WP_Sitemaps_Posts->get_posts_query_args() protected method from
		 * wordpress/wp-includes/sitemaps/providers/class-wp-sitemaps-posts.php.
		 */
		private function get_posts_query_args( $post_type ) {

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
	}
}
