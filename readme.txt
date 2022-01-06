=== WPSSO WP Sitemaps XML ===
Plugin Name: WPSSO WP Sitemaps XML
Plugin Slug: wpsso-wp-sitemaps
Text Domain: wpsso-wp-sitemaps
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-wp-sitemaps/assets/
Tags: xml sitemaps, xml sitemap, sitemaps, noindex, seo, google
Contributors: jsmoriss
Requires PHP: 7.2
Requires At Least: 5.5
Tested Up To: 5.8.2
Stable Tag: 1.1.0

Manage post and taxonomy types included in the WordPress sitemaps XML and exclude content marked as "No Index".

== Description ==

<!-- about -->

Manage which post and taxonomy types are included in the WordPress sitemaps XML.

Exclude posts, pages, custom post types, taxonomy terms (categories, tags, etc.), and user profiles marked as "No Index".

Automatically enhance the built-in WordPress sitemaps XML with article modification times.

<!-- /about -->

<h3>The WP Sitemaps Settings Page</h3>

The WP Sitemaps settings page allows you to choose which post and taxonomy types are included in the WordPress sitemaps XML.

<h3>The Document SSO Metabox</h3>

When editing a post, page, custom post type, taxonomy term (category, tag, etc.), or user profile page, you can enable the "No Index" option under the Document SSO &gt; Robots Meta metabox tab to exclude that webpage from the WordPress sitemaps XML.

<h3>WPSSO Core Required</h3>

WPSSO WP Sitemaps XML (WPSSO WPSM) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO WP Sitemaps XML add-on](https://wpsso.com/docs/plugins/wpsso-wp-sitemaps/installation/install-the-plugin/).
* [Uninstall the WPSSO WP Sitemaps XML add-on](https://wpsso.com/docs/plugins/wpsso-wp-sitemaps/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. The WPSSO WPSM settings page offers options to customize the post and taxonomy types included in the WordPress sitemaps XML.
02. The No Index option in the Document SSO &gt; Robots Meta metabox tab can be used to exclude individual posts, pages, custom post types, taxonomy terms, or user profile pages from the WordPress sitemaps XML.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev &lt; a (alpha) &lt; b (beta) &lt; rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-wp-sitemaps/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. Save the plugin settings and click the "Check for Plugin Updates" button to fetch the latest version information. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can always reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

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

= 1.1.0 =

(2022/01/06) Added support for the new `WpssoOpenGraph->get_mod_og_type_id()` method in WPSSO Core v9.13.0.

= 1.0.1 =

(2021/11/17) Removed a 'WPSSO_PLUGINDIR' dependency check before loading the `WpssoWpsmSitemaps` class.

= 1.0.0 =

(2021/11/16) Initial release.

