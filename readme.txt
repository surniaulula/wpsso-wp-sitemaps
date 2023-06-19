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
Requires PHP: 7.2.5
Requires At Least: 5.5
Tested Up To: 6.2.2
WC Tested Up To: 7.8.0
Stable Tag: 5.4.0

Include Schema images, alternate language URLs, post type archive pages, select post types and taxonomies, exclude "No Index" and redirected pages.

== Description ==

<!-- about -->

**Extends the Built-in WordPress Sitemaps XML:**

Improves the WordPress sitemaps XML with article modification times, alternate language URLs, and Schema images for Google rich results.

<!-- /about -->

**Includes Post Type Archive Pages:**

Includes missing post type archive pages in the WordPress sitemaps (like the WooCommerce shop page and The Events Calendar events page).

**Includes Alternate Languages:**

Includes [localized pages for Google](https://developers.google.com/search/docs/advanced/crawling/localized-versions#sitemap) (ie. alternate language URLs) from PolyLang and WMPL.

**Select Post Types and Taxonomies:**

Optionally include or exclude post types and taxonomies from the WordPress sitemaps XML.

**Excludes Noindex and Redirected URLs:**

Excludes noindex and redirected posts, pages, custom post types, taxonomies (categories, tags, etc.), and user profiles pages from the WordPress sitemaps XML.

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
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-wp-sitemaps/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium edition customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<p><strong>WPSSO Core Standard edition users (ie. the plugin hosted on WordPress.org) have access to <a href="https://wordpress.org/plugins/wpsso-wp-sitemaps/advanced/">the latest development version under the Advanced Options section</a>.</strong></p>

<h3>Changelog / Release Notes</h3>

**Version 5.4.0 (2023/04/13)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Replaced the `WpssoWpsmSitemapsFilters->get_exclude_meta_query()` private method by the new `WpssoAbstractWpMeta::get_column_meta_query_exclude()` public method in WPSSO Core v15.8.0.
* **Requires At Least**
	* PHP v7.2.5.
	* WordPress v5.5.
	* WPSSO Core v15.8.0.

**Version 5.3.0 (2023/02/11)**

Maintenance release.

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v15.2.0.

**Version 5.2.1 (2023/01/26)**

* **New Features**
	* None.
* **Improvements**
	* Added compatibility declaration for WooCommerce HPOS.
	* Added an XML sitemaps conflict notice for Yoast SEO v20.0.
	* Updated the minimum WordPress version from v5.2 to v5.5.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new `WpssoWpsmConflict` library class.
	* Added a new `WpssoWpsmConflictSeo` library class.
	* Updated the `WpssoAbstractAddOn` library class.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v14.7.0.

**Version 5.2.0 (2023/01/20)**

* **New Features**
	* None.
* **Improvements**
	* Removed the "Change to View" button from the WP Sitemaps settings page.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the `SucomAbstractAddOn` common library class.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.5.
	* WPSSO Core v14.5.0.

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

== Upgrade Notice ==

= 5.4.0 =

(2023/04/13) Replaced the `WpssoWpsmSitemapsFilters->get_exclude_meta_query()` private method by a new public method in WPSSO Core v15.8.0.

= 5.3.0 =

(2023/02/11) Maintenance release.

= 5.2.1 =

(2023/01/26) Added compatibility declaration for WooCommerce HPOS. Updated the minimum WordPress version from v5.2 to v5.5.

= 5.2.0 =

(2023/01/20) Removed the "Change to View" button from the WP Sitemaps settings page.

= 5.1.0 =

(2022/12/29) Refactored and changed the public `WpssoWpsmSitemapsFilters->get_exclude_meta_query()` method to a private static method.

= 5.0.0 =

(2022/11/04) Improved performance when creating the sitemaps XML. Added a new "Maximum URLs per Sitemap" option (default is 2000).

