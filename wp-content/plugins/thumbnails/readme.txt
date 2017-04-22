=== Thumbnails ===
Tags: thumbnails
Requires at least: 3.4.0
Tested up to: 4.7.2
Stable tag: 1.0.7
Contributors: satollo

Enhances the WordPress thumbnail functions generating and caching perfect thumbnails of any size.

== Description ==

WordPress themes needs thumbnails in different sizes and they even let the user 
to change those dimensions. But WordPress has a problem: it does not regenerates 
the thumbnails when new sizes are registered.

Thumbnails intercepts the request by themes or plugins to WordPress to get a 
specific thumbnail, generating it and caching it on disk. Efficiently, 
it produces a perfectly cropped image, with the right dimension avoiding 
unpleasant stretching.

Thumbnails does not modify your blog or your media library. When deactivated
the blog returns to its old behavior.

Read the [Thumbnails](http://www.satollo.net/plugins/thumbnails) official page
for detailed information.

Theme developers can find instructions to use it (no code tied to Thumbnails
needs to be written!).

Other plugins by Stefano Lissa:

* [Hyper Cache](http://www.satollo.net/plugins/hyper-cache)
* [Newsletter](http://www.thenewsletterplugin.com)
* [Header and Footer](http://www.satollo.net/plugins/header-footer)
* [Include Me](http://www.satollo.net/plugins/include-me)
* [Ads for bbPress](http://www.satollo.net/plugins/ads-bbpress)
* [Comment Plus](http://www.satollo.net/plugins/comment-plus)

== Installation ==

1. Put the plug-in folder into [wordpress_dir]/wp-content/plugins/
2. Go into the WordPress admin interface and activate the plugin
3. Optional: go to the options page and configure the plugin

== Frequently Asked Questions ==

See the [Thumbnails](http://www.satollo.net/plugins/thumbnails) official page.

== Screen shots ==

No screenshots are available at this time.

== Changelog ==

= 1.0.7 =

* Added core size processing
* Improved admin CSS

= 1.0.6 =

* Added featured image persistence option

= 1.0.5 =

* Compatibility checks with WP 4.7
* Tagged to be translatable on translate.wordpress.org (even you can help!)


= 1.0.4 =

* Fixes to the options panel

= 1.0.3 =

* Compatibility with WP 4.4.2

= 1.0.2 =

* Fixes

= 1.0.0 =

* First public release (but it is working on many blogs since months)

