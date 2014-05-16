=== Plugin Name ===
Contributors: ignitriun, joewils
Donate link: http://prefrent.com/
Tags: tags, related posts, ai, a.i., filter, micro format, context, contextual, search, knowledge, knowledge-base, data, freeform, construct, descriptors, draws, distance, svm
Requires at least: 3.6
Tested up to: 3.9.1
Stable tag: 0.6.05
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Affinitomics™ transforms Wordpress into a hyper-relevant, context aware and intelligent powerhouse.

== Description ==

Affinitomics™ transforms Wordpress into a hyper-relevant, context aware and intelligent powerhouse.

Using patent-pending feature/tag dimensionalization methods, the plugin creates AI constructs from pages, posts, and custom post types. These constructs are then used to allow information to self-organize based on contextual value. This makes link lists and menus contextual and dynamic - making sites sticky and visitors more likely to convert. Applied to searches (Google CSE), Affinitomics improves results by as much as 9x, imparting context and reducing noise.

Categories and traditional tags create flat index structures that little actual relational value. Some plugins try to impart contextual value by either requiring hard-coded relationships or forcing Wordpress to calculate tag counts and concordances in an effort to find contextually valuable matches. Plugins that do the latter cause Wordpress to perform tens of thousands more calculations than normal, bogging servers and slowing performance. Some hosts have banned the use of these plugins.

Affinitomics™ for Wordpress uses a RESTful API to communicate with the Affinitomics™ Cloud, storing AI constructs, and calculating contextual relationships and values. Free of the the computational load, Wordpress benefits, becoming a hyper-contextual information system that dynamically molds itself to the users needs.

== Installation ==

1. Download the plugin.
1. Either use the upload link provided within WordPress’ “add new” plugin page (the link reads “upload a plugin in .zip format via this page. or;
1. Upload the plugin directory “affinitomics” to your /wp-content/plugins/ directory
1. Activate the plugin through the Plugins menu in WordPress
1. Go to “Affinitomics™” in the left hand control menu
1. Select “settings” from the bottom of the list
1. Generate and input an Affinitomics™ API key. Save this key someplace secure – it can’t be retrieved. You can use the link in the plugin, or you can get it here
1. Configure Affintomics™ for your site. If you intend to use Jumpsearch, follow the links to obtain free API / Search credentials from Google.

== Frequently Asked Questions ==

= What versions of Wordpress and php are required? =

Affinitomics™ requires Wordpress 3.5 or better, and php 5.3 or better.

= How much storage do I get in the Affinitomics™ Cloud =

Users are granted space for 1000 Affinitomic™ constructs and transactions of 50,000 pageviews per month.
Larger accounts are available at [Prefrent.com](http://prefrent.com).

== Screenshots ==

1. This shows the settings area under Archetype > settings. Note that the plugin requires and API key from Prefrent.
for Jump-search functionality, credentials are required form Google as well.

2. This shows the descriptor, draw, and distance fields and how they are utilized.

== Changelog ==

=0.6.05=
* Corrections in readme.txt and documentation

=0.6.04=
* Changed Archetype to Affinitomics in admin menu per user suggestions
* Cleaned up legacy and simplified UI
* Submitted title screen

= 0.6.03 =
* Added link in settings to generate API key
* Added changelog.txt to the plugin
* other little bug fixes/improvements

= 0.6.02 =
* GPLv2 License
* Enabled shortcodes for limit, category, override
* fixed bug displaying deleted post-types
* other little bug fixes/improvements

= 0.6.01 =
* Fixed google CSE implementation
* Added API key and API key generation
* other little bug fixes/improvements

= 0.6.00 =
* Interface via API with Affinitomics™ Proxy Server - storing Affinitomics in the Cloud.
* Added cloud export feature
* Deleted base scoring from pluggin - no longer relies on the Wordpress host machne for calculation or indexing
* other little bug fixes/improvements

= 0.5.04 =
* Limited public beta
* Commercial-to-GPL License
* Fixed Multisite retrieval issue
* Increased element limitation
* other little bug fixes/improvements

== Upgrade Notice ==

= 0.6.05 =
Changes to instructions, tags

= 0.5.04 =
This version is Commercial-to-GPL License
