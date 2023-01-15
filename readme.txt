=== WPSSO Better WordPress Sitemaps XML ===
Plugin Name: WPSSO Better WordPress Sitemaps XML
Plugin Slug: wpsso-wp-sitemaps
Text Domain: wpsso-wp-sitemaps
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-wp-sitemaps/assets/
Tags: xml sitemaps, sitemap, schema, noindex, woocommerce, seo, google
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.2
Requires At Least: 5.5
Tested Up To: 6.1.1
Stable Tag: 5.1.0

Include Schema images, alternate language URLs, post type archive pages, select post types and taxonomies, exclude "No Index" and redirected pages.

== Description ==

<!-- about -->

<h3>Extends the WordPress Sitemaps XML</h3>

Improves the WordPress sitemaps XML with article modification times, alternate language URLs, and Schema images for Google rich results.

<h3>Includes Post Type Archive Pages</h3>

Includes missing post type archive pages in the WordPress sitemaps (like the WooCommerce shop page and The Events Calendar events page).

<h3>INcludes Alternate Languages</h3>

Includes [localized pages for Google](https://developers.google.com/search/docs/advanced/crawling/localized-versions#sitemap) (ie. alternate language URLs) from PolyLang and WMPL.

<h3>Select Post Types and Taxonomies</h3>

Optionally include or exclude post types and taxonomies from the WordPress sitemaps XML.

<h3>Excludes Noindex and Redirected URLs</h3>

Excludes noindex and redirected posts, pages, custom post types, taxonomies (categories, tags, etc.), and user profiles pages from the WordPress sitemaps XML.

<!-- /about -->

<h3>WPSSO Core Required</h3>

WPSSO Better WordPress Sitemaps XML (WPSSO WPSM) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which provides complete structured data for WordPress to present your content at its best on social sites and in search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Better WordPress Sitemaps XML add-on](https://wpsso.com/docs/plugins/wpsso-wp-sitemaps/installation/install-the-plugin/).
* [Uninstall the WPSSO Better WordPress Sitemaps XML add-on](https://wpsso.com/docs/plugins/wpsso-wp-sitemaps/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. The WPSSO WPSM settings page provides options to customize the post and taxonomy types added to the WordPress sitemaps XML.
02. The "No Index" option under the Document SSO &gt; Edit Visibility tab can be used to exclude individual posts, pages, custom post types, taxonomy terms, or user profile pages from the WordPress sitemaps XML.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes and/or incompatible API changes (ie. breaking changes).
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev &lt; a (alpha) &lt; b (beta) &lt; rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-wp-sitemaps/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

**Version 5.2.0-b.5 (2023/01/13)**

* **New Features**
	* None.
* **Improvements**
	* Removed the "Change to View" button from the WP Sitemaps settings page.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v14.5.0-b.5.

**Version 5.1.0 (2022/12/29)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored and changed the public `WpssoWpsmSitemapsFilters->get_exclude_meta_query()` method to a private static method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v14.1.0.

**Version 5.0.0 (2022/11/04)**

* **New Features**
	* None.
* **Improvements**
	* Improved performance when creating the sitemaps XML.
	* Added a new "Maximum URLs per Sitemap" option (default is 2000).
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added `is_sitemap()` and `is_sitemap_stylesheet()` functions.
	* Refactored the following methods to improve performance when excluding noindex and/or redirected posts:
		* WpssoWpsmSitemapsFilters->wp_sitemaps_posts_query_args()
		* WpssoWpsmSitemapsFilters->wp_sitemaps_taxonomies_query_args()
		* WpssoWpsmSitemapsFilters->wp_sitemaps_users_query_args()
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v13.8.0.

**Version 4.0.0 (2022/10/28)**

* **New Features**
	* None.
* **Improvements**
	* Added a new "Add Schema Images" option in the SSO &gt; WP Sitemaps settings page.
	* Added a notice in case WP sitemaps are disabled on a production site.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v13.7.0.

**Version 3.0.0 (2022/04/04)**

* **New Features**
	* None.
* **Improvements**
	* Added 'xhtml:link' markup for alternate language URLs to the WordPress sitemaps and its stylesheet.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Moved lib/sitemaps.php to lib/sitemaps/filters.php.
	* Added lib/sitemaps.php to define the `$wp_sitemaps` global variable before `wp_sitemaps_get_server()` runs.
	* Added lib/sitemaps/sitemaps.php to extend WP_Sitemaps and set a different renderer.
	* Added lib/sitemaps/renderer.php to extend WP_Sitemaps_Renderer.
	* Added a new 'wp_sitemaps_stylesheet_content' filter in lib/sitemaps/filters.php to show 'xhtml:link' values.
	* Replaced the WordPress `WP_Sitemaps_Renderer->get_sitemap_xml()` method to provide alternate languages in the sitemap.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v12.1.0.

**Version 2.1.2 (2022/03/26)**

* **New Features**
	* None.
* **Improvements**
	* Added support for `WpssoUtil->is_redirect_enabled()` in WPSSO Core v12.0.0.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v12.0.0.

**Version 2.1.1 (2022/03/07)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated `SucomUtilWP` method calls to `SucomUtil` for WPSSO Core v11.5.0.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v11.5.0.

**Version 2.1.0 (2022/02/19)**

* **New Features**
	* None.
* **Improvements**
	* Added a test for `WpssoUtil->get_redirect_url()` to exclude the post, term, or user if it is being redirected.
	* Removed test for `WpssoUtilRobots->is_enabled()` when checking for noindex, which allows noindex checks even if the robots meta tag is disabled.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v10.1.0.

**Version 2.0.0 (2022/02/05)**

* **New Features**
	* Adds post type archive pages to the WordPress sitemap XML, like the WooCommerce shop page and The Events Calendar events page.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new `WpssoWpsmSitemaps->wp_sitemaps_posts_pre_url_list()` filter to include the post type archive page.
	* Added a new `WpssoWpsmSitemaps->get_posts_query_args()` method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v10.1.0.

**Version 1.2.0 (2022/01/19)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Renamed the lib/abstracts/ folder to lib/abstract/.
	* Renamed the `SucomAddOn` class to `SucomAbstractAddOn`.
	* Renamed the `WpssoAddOn` class to `WpssoAbstractAddOn`.
	* Renamed the `WpssoWpMeta` class to `WpssoAbstractWpMeta`.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v9.14.0.

**Version 1.1.0 (2022/01/06)**

* **New Features**
	* None.
* **Improvements**
	* Added support for the new `WpssoOpenGraph->get_mod_og_type_id()` method in WPSSO Core v9.13.0.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v9.13.0.

**Version 1.0.1 (2021/11/17)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Removed a 'WPSSO_PLUGINDIR' dependency check before loading the `WpssoWpsmSitemaps` class.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v9.8.0.

**Version 1.0.0 (2021/11/16)**

* **New Features**
	* Initial release.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v9.8.0.

== Upgrade Notice ==

= 5.2.0-b.5 =

(2023/01/13) Removed the "Change to View" button from the WP Sitemaps settings page.

= 5.1.0 =

(2022/12/29) Refactored and changed the public `WpssoWpsmSitemapsFilters->get_exclude_meta_query()` method to a private static method.

= 5.0.0 =

(2022/11/04) Improved performance when creating the sitemaps XML. Added a new "Maximum URLs per Sitemap" option (default is 2000).

= 4.0.0 =

(2022/10/28) Added a new "Add Schema Images" option in the SSO &gt; WP Sitemaps settings page. Added a notice in case WP sitemaps are disabled on a production site.

= 3.0.0 =

(2022/04/04) Added 'xhtml:link' markup for alternate language URLs to the WordPress sitemaps and its stylesheet.

= 2.1.2 =

(2022/03/26) Added support for `WpssoUtil->is_redirect_enabled()` in WPSSO Core v12.0.0.

= 2.1.1 =

(2022/03/07) Updated `SucomUtilWP` method calls to `SucomUtil` for WPSSO Core v11.5.0.

= 2.1.0 =

(2022/02/19) Exclude posts, terms, or users that are being redirected. Allow noindex checks even if the robots meta tag is disabled.

= 2.0.0 =

(2022/02/05) Adds post type archive pages to the WordPress sitemap XML, like the WooCommerce shop page and The Events Calendar events page.

= 1.2.0 =

(2022/01/19) Renamed the lib/abstracts/ folder and its classes.

= 1.1.0 =

(2022/01/06) Added support for the new `WpssoOpenGraph->get_mod_og_type_id()` method in WPSSO Core v9.13.0.

= 1.0.1 =

(2021/11/17) Removed a 'WPSSO_PLUGINDIR' dependency check before loading the `WpssoWpsmSitemaps` class.

= 1.0.0 =

(2021/11/16) Initial release.

