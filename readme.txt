=== WPSSO Better WordPress Sitemaps XML ===
Plugin Name: WPSSO Better WordPress Sitemaps XML
Plugin Slug: wpsso-wp-sitemaps
Text Domain: wpsso-wp-sitemaps
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-wp-sitemaps/assets/
Tags: xml sitemap, image sitemap, news sitemap, woocommerce, google news
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.2.34
Requires At Least: 5.5
Tested Up To: 6.3.2
WC Tested Up To: 8.2.1
Stable Tag: 6.0.0

Improves the WordPress sitemaps XML with article modification times, alternate language URLs, images sitemaps, news sitemaps and more.

== Description ==

<!-- about -->

**Extends the WordPress Sitemaps XML:**

Improves the WordPress sitemaps XML with article modification times, alternate language URLs, images sitemaps, news sitemaps and more.

<!-- /about -->

**Includes Post Type Archives:**

Includes post type archive pages in the WordPress sitemaps (like the WooCommerce shop page and The Events Calendar events page).

**Includes Alternate Languages:**

Includes [localized pages for Google](https://developers.google.com/search/docs/advanced/crawling/localized-versions#sitemap) (ie. alternate language URLs) from PolyLang and WMPL.

**Select Post Types and Taxonomies:**

Optionally include or exclude post types and taxonomies from the WordPress sitemaps XML.

**Excludes Noindex and Redirected URLs:**

Exclude noindex and redirected posts, pages, custom post types, taxonomies (categories, tags, etc.), and user profiles pages from the WordPress sitemaps XML.

<h3>WPSSO Core Required</h3>

WPSSO Better WordPress Sitemaps XML (WPSSO WPSM) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which provides complete structured data for WordPress to present your content at its best on social sites and in search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Better WordPress Sitemaps XML add-on](https://wpsso.com/docs/plugins/wpsso-wp-sitemaps/installation/install-the-plugin/).
* [Uninstall the WPSSO Better WordPress Sitemaps XML add-on](https://wpsso.com/docs/plugins/wpsso-wp-sitemaps/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

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

**Version 6.0.0 (2023/10/17)**

* **New Features**
	* None.
* **Improvements**
	* Added new options in the SSO &gt; WP Sitemaps settings page for news sitemaps:
		* Post Type for News Sitemaps
		* News Publication Cut-Off
		* News Publication Name
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a `WpssoWpsmSitemaps::get_news_pub_name()` method.
	* Added a `WpssoWpsmSitemaps::get_default_news_pub_name()` method.
	* Refactored `WpssoWpsmSitemapsFilters->wp_sitemaps_posts_entry` to add news sitemap XML tags.
	* Refactored `WpssoWpsmSitemapsRenderer->add_items` to add support for the 'news:language', 'news:name', 'news:news', 'news:publication', 'news:publication_date', and 'news:title' tags.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.5.
	* WPSSO Core v16.3.0.

== Upgrade Notice ==

= 6.0.0 =

(2023/10/17) Added new options in the SSO &gt; WP Sitemaps settings page for news sitemaps.

