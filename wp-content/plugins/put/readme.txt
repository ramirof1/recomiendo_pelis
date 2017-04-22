=== Plugin Name ===
Contributors: t31os_
Tags: editor, shortcode, tabs, jquery, jquery-ui, post, page, custom-post-types, jquery-tabs, themeroller
Requires at least: 3.1.0
Tested up to: 3.6
Stable tag: 1.1.0

Create jQuery tabs inside your posts, pages or post types using simple shortcodes inside the post editor. 

== Description ==

Show off your post content inside stylish jQuery powered tabs using one of 24 different jQuery UI themes or include your own custom stylesheet.

== Installation ==

1. Upload the `put`(post-ui-tabs) folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress
1. Visit the plugin configuration screen under **WordPress Admin > Settings > Post UI Tabs** and set your preferences
1. Create posts using the easy to use shortcode and watch the magic happen

== Frequently Asked Questions ==

= So how do i create tabs? =

Click one of the simple to use tinymce buttons provided by PUT in the post editor, quick tags are also available in the text editor.

or

Use the following format to write them directly into the post editor.
`
[tab name="Your tab name 1"]Your tab content[/tab] 
[tab name="Your tab name 2"]Your tab content[/tab]
[tab name="Your tab name 3"]Your tab content[/tab]
[end_tabset]
`
**NOTE:** 
It is necessary for all tab sets end with the `[end_tabset]` shortcode, the tabs will not appear without this shortcode.  
Please be aware that tab shortcodes can not be placed on the same line, this is a limitation of shortcode functionality in WordPress(and i do not wish to bloat this plugin with extra code to work around it).

= Can i use HTML inside the tabs? =

You may use any HTML the WordPress content editor usually allows inside the tab content, but not inside the tab names(which are sanitized seperately).

= Can i use tabs in pages? =

The plugin is not restricted to a particular kind of content, so yes pages, posts or custom post types(or at least any type that supports using the content editor).

= Can i use other shortcodes inside the tab content? =

Yes you should be able to, it has not been tested, but if you have any problems feel free to start a support topic right here in the WordPress.org forums.

= Why do the tabs not look the same when i view them on the front facing side of my site? =

It is possible your theme's stylesheet is applying CSS to some of the tabs elements, please feel free to start a support topic if you need help making adjustments.

= How can i remove the UI classes from the next and previous links? =

Add the following to your theme's **functions.php** file.
`
add_filter( 'put_nav_class', '__return_empty_array' );
`
= When using text nav links how i can change the link text? =

You can modify the previous and next link text using the following in your theme's **functions.php** file.
`
// Remove the UI classes(used by default to display nav icons)
add_filter( 'put_nav_class', '__return_empty_array' );

// Hook callback functions to the filters
add_filter( 'put_next_text', 'put_nav_next_text' );
add_filter( 'put_prev_text', 'put_nav_prev_text' );

// Callback to change the 'Next' text
function put_nav_next_text() { return 'Your next text'; }

// Callback to change the 'Previous' text
function put_nav_prev_text() { return 'Your previous text'; }
`
= How do i include my own CSS in place of one of the jQuery UI themes? =

**Method one:**  
*Using a stylesheet in your theme's directory(will work for child themes to)*
`
add_filter( 'put_stylesheet_uri', 'my_custom_put_stylesheet' );

function my_custom_put_stylesheet( $uri ) {
	return get_stylesheet_directory_uri() . '/mycustom.css';
}
`
**Method two:**  
*Adding a filter from inside your own plugin file*
`
add_filter( 'put_stylesheet_uri', 'my_plugin_put_stylesheet' );

function my_plugin_put_stylesheet( $uri ) {
	
	// If the stylesheet is in the plugin's main directory
	
	return plugins_url( '', __FILE__ ) . '/mycustom.css';
	// Eg. http://www.example.com/wp-content/plugins/your-plugin/mycustom.css
	
	
	// If the stylesheet is in a subdirectory of the plugin
	
	return plugins_url( 'dir', __FILE__ ) . '/mycustom.css';
	// Eg. http://www.example.com/wp-content/plugins/your-plugin/dir/mycustom.css
}
`
**Method three:**  
Check the **Disable skins** option on the Post UI Tabs settings page and do either of the following.

* Add your own CSS to your theme's stylesheet(typically **style.css**).  
* Call `wp_enqueue_style` on the `put_enqueue_styles` action, ie. the regular WP enqueuing method.

= Can i remove the linebreak that Post UI Tabs after each tab set? =

You can, simply place the following code into your theme's `functions.php` file or a plugin.
`
add_filter( 'put_trailing_linebreak', '__return_false' );
`

== Filters ==

The plugin provides various filters to aid users, they are as follows

* `put_decide_has_tabs`: (bool) true/false value that determines whether to run the tabs script(runs inside a filter on `the_content`)
* `put_theme_dir`: (string) passes the theme directory uri
* `put_stylesheet`: (string) name of the stylesheet to use for tabs CSS
* `put_skins`: (array) passes the array of skins available to the plugin
* `put_stylesheet_uri`: (string) the full stylesheet uri used (easier hook for custom stylesheets)
* `put_prev_text`: (string) the text used for previous tab navigation(text not shown by default)
* `put_next_text`: (string) the text used for next tab navigation(text not shown by default)
* `put_nav_class`: (string) the classes applied to the prev and next navigation(second arg indicates prev or next text)
* `put_trailing_linebreak`: (bool) true/false value to determine whether or not to add a trailing `<br />` (linebreak) after each tab set

A couple of actions are also available for when you'd rather just turn off skins and enqueue your own stylesheet

* `put_enqueue_css`: runs on front facing pages with tab sets when the *Disable skins* is enabled
* `put_admin_enqueue_css`: runs on the plugin settings page when the *Disable skins* is enabled

== Screenshots ==

1. Two different sets of tabs in a single post.
2. The Post UI Tabs settings page.
3. One set of tabs in a post.
4. TinyMCE buttons in the editor to make adding tabs easier.

== Changelog ==

= 1.0.10 =
* Fix for undefined error in `post-tabs-ui.js` + some additional small adjustments
* Fix end tabs quicktag in text editor
* Added toggle option to determine whether to automatically select the active tab(disabling allows anchored links to select the active tab)

= 1.0.9 =
* Added WordPress version check
* Added GPL License to top of plugin main file
* First pass adding screen help to the plugin's configuration page(wp 3.3+ only)
* First pass adding TinyMCE buttons for tab shortcodes
* Added `<br />` after each tabset(can be removed using the new `put_trailing_linebreak` filter)
* Rewrote some of the Javscript/jQuery code

= 1.0.8 =
* Fixed bug with jQuery code that caused the tabs not to render in IE7/8 (my fault)
* Added backwards support for jQuery UI tabs prior to jQuery UI 1.9(WordPress 3.1 - 3.4)
* Update more of the readme, to reflect changes to plugin filter/action names

= 1.0.7 =
* Added two new filters `put_decide_has_tabs` and `put_stylesheet_uri`. 
* Switched the filter names(sorry) to use a more consistent and descriptive naming scheme - all hooks are now use the `put_` prefix.
* Change example code in readme and added a list of all the available plugin hooks

= 1.0.6 =
* Update jQuery functions inline with jQuery UI updates, ie. replace calls to deprecated functions
* Removed cookie support(removed from jQuery UI) - alternative to be provided later on
* Moved some filters that were not working correctly
* Added some new action hooks for users to disable jQuery UI css and enqueue their own
* Improved some jQuery to better handle previous and next navigation when there are disabled tabs

= 1.0.5 =
* Changed the `has_tabs` variable to public

= 1.0.4 =
* Update to jQuery UI 1.8.15 (for skins)
* Add option to display tab titles and content in feeds
* Rearrange markup for feeds so titles and content does not bunch together
* Move enqueues to more appropriate action

= 1.0.3 =
* Rename cookie javascript, possible fix for unknown problem with 404s to cookie script
* Remove shortcode content from feeds(was never intended to be output in feeds)

= 1.0.2 =
* Fix style/script versions
* Add proper plugin version to plugin(whoops)
* Add more to readme.txt
