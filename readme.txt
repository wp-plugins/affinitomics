=== Plugin Name ===
Contributors: ignitriun, joewils, hansthered
Donate link: http://prefrent.com/
Tags: match, sort, rank, related, relational, relate, tags, posts, post-types, types, ai, a.i., filter, filtering, micro format, context, contextual, contextually, search, data, freeform, construct, descriptors, draws, distance, support-vector,
Requires at least: 3.6
Tested up to: 4.0
Stable tag: 1.0
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
1. Either use the “add new plugin” functionality internal to Wordpress.org in your “Plugins” menu or;
1. Upload the plugin directory “affinitomics” to your /wp-content/plugins/ directory.
1. Activate the plugin through the Plugins menu in WordPress.
1. Configure Affintomics™ for your site. If you intend to use JumpSearch

= Configure Affinitomics =

1. Install the plugin
1. In the admin panel, find the “Affinitomics” menu (usually below Posts) and select “settings”
1. Next, under “To which Post-types would you like to apply your Affinitomics™?” check the boxes for the post-types you want to use with Affinitomics.
1. Now scroll to the bottom and save changes. Unless you want to configure Google Custom Search (CSE) to work with Affinitomics, you’re done.

= Configure Google CSE integration =

1. In the admin panel, find the “Affinitomics” menu and select “settings”
1. Ensure that the settings for the API URL, API Key, and API Account Domain are configured
1. Ensure that the post types are selected
1. Under “Jump Search select “yes” to apply the Google CSE “JumpSearch” to posts or pages
1. Follow the links to obtain free API / Search credentials from Google.
1. For your Google API Key (separate from your Affinitomics API key above) follow the instructions here [https://cloud.google.com/console](https://cloud.google.com/console)
1. For your Google Custom Search Engine (CSE) ID follow the instructions here [https://developers.google.com/custom-search/](https://developers.google.com/custom-search/)

= If you would like to convert your tags and categories to Affinitomic draws and descriptors you can use our conversion tool. =
1. In the admin panel, find the “Affinitomics” menu and select "cloud export"
1. You will see a message "Hey, did you know we have a handy importing tool? Check out the Affinitomics Taxonomy Converter"
1. Follow the link to activate the plug-in in the same way as this plug-in was activated.
1. Back on the "cloud export" page you will now see "Convert Taxonomy"
1. Click this link and select categories to convert into descriptors.
1. Next click on the "Tags" tab at the top.
1. Now repeat this process to convert tags into draws.
1. Back on the "cloud export" page you should now check the "make it so" box and sync your Affinitomics with the cloud.

= Configure individual Posts, Pages, or Archetypes™ =

1. For each post you will want to add Affinitomic data such as descriptors, draws and distances.
1. A descriptor is similar to a category, you can add as many as you want separated by a comma. If you are posting pictures of your cat you might have a descriptors like "cats, pets, lolcats".
1. A draw is something that is attracted to this post. Draws are also separated by commas and you can add as many as you like. If your post or page is about cats, you might have a draw like "laser pointer" or "catnip". Extra emphasis can be placed on draws by adding a magnitude from 1 to 5 to the end of the term. eg. "catnip3, laser pointer2" which will mean that catnip is more desired  than a laser pointer.
1. A distance is something that is repelled by your page. For example, if your post or page is about cats, you might have a distance of "dogs" or "fleas". Extra emphasis can be placed on a distance by adding a magnitude from 1 to 5 at the end. eg. "dogs3, fleas5". This will mean that fleas are more hated than dogs.
1. If none of the Affinitomic™ Element fields are filled in, the page, post, or Archetype will not be effected by Affinitomics™ - JumpSearch will have no effect, and only shortcodes with overriding Affinitomics™ will function.

= Connect your similar pages with Affinitomics™! =

1. On a page that you would like to add a list of similar posts or pages, simply add the shortcode [afview]
1. an [afview] can be modified with the following options:

limit:           how many results to return

category_filter: only display results from one category (a post can be in multiple categories, this restricts similar results to a single category)

display_title:   just like it sounds, you can hide the title by setting this to "false"

Examples: [afview], [afview limit="4"], [afview category_filter="50"]

To use more than one option at a time, just separate options with a space.

[afview limit=1 display_title="false"]

=Use the following class' to style [afview] display=
* afview
* aftitle
* afsubtitle
* afelement
* afelementurl
* afelementscore

== Frequently Asked Questions ==

= What versions of Wordpress and php are required? =

Affinitomics™ requires Wordpress 3.5 or better, and php 5.3 or better.

= How much storage do I get in the Affinitomics™ Cloud =

Users are granted space for 1000 Affinitomic™ constructs and transactions of 50,000 pageviews per month.
Larger accounts are available at [Prefrent.com](http://prefrent.com).

= How many “Archetypes” will I need? =
An Affinitomic Archetype can be applied to a post, page, custom post-type, or archetype (Affinitomics’™ custom post type)
So 1000 archetypes could be 50 pages, 900 posts, and 50 ads if you didn’t assign individual Archetypes to members.


== Screenshots ==

1. This shows the settings area under Archetype > settings. Note that the plugin requires and API key from Prefrent.
for Jump-search functionality, credentials are required form Google as well.

2. This shows the descriptor, draw, and distance fields and how they are utilized.

3. This is a list of Investment firms sorted by Prefrent’s Archetype - All of them have a history of investing in companies very like ours.

4. This is a JumpSearch from a “Cheese Shop” Archetype, searching for “hat.”

== Changelog ==

=1.0=
* Removed "archetypes"
* cloud sync now done via ajax
* completely re-built back-end (IQ cloud)
* related views now done via ajax
* Removed complicated features
* Simplifies the sign-up process
* User dashboards and subscriptions offered via IQ-cloud
* Various bug fixes and improvements

=0.9.0=
* Cleaned up admin settings
* Improved uploading to cloud
* Sped up oporations within Wordpress
* Integration with Affinitomics Taxonomy Converter
* Various bugfixes and improvements

=0.7.0=
* CSS Styling hooks!
* Support for WooCommerce!

=0.6.06=
* Changes to internal documentation
* Other little bug fixes/improvements

=0.6.05=
* Corrections in readme.txt and documentation

= 0.6.04 =
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
* Deleted base scoring from plugin - no longer relies on the Wordpress host machine for calculation or indexing
* other little bug fixes/improvements

= 0.5.04 =
* Limited public beta
* Commercial-to-GPL License
* Fixed Multisite retrieval issue
* Increased element limitation
* other little bug fixes/improvements

== Upgrade Notice ==

= 0.9.0 =
Upgrade - GPLv2 license, Affinitomics™ Cloud storage improvements, speed enhancements, taxonomy converter integration.