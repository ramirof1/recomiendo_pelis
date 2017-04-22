=== IMDb Connector ===
Contributors: thaikolja
Tags: imdb, imdb connector, imdb database, movie, movies, movie details, movie database
Tested up to: 4.5.2
Stable tag: 1.0.0
Requires at least: 3.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin that allows you to easily display and use movie details from IMDb.com.

== Description ==

**IMDb Connector** is a simple plugin that allows you to easily access the [IMDb.com](http://ww.imdb.com) database through the [API provided by omdbapi.com](http://www.omdbapi.com) and get details for a specific movie.

So far, the plugin comes with the following features:

* [**Widgets**](http://codex.wordpress.org/Widgets_API) that lets you display the movie details within your sidebar,
* **PHP functions** that allows theme/plugin developers to easily parse information for a specific movie,
* [**Shortcodes**](http://codex.wordpress.org/Shortcode_API) which you can use to display one or more details about a movie inside your post or page,
* and a **settings page** that lets you (de)activate features and customize the way IMDb Connector works.

**For instructions on how to use, examples and additional information, please see [the official documentation](http://www.koljanolte.com/wordpress/plugins/imdb-connector/)**.

In case you like this plugin, please consider [making a donation to Brian Fritz](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X7Z6YBM23ECYJ), the author of omdbapi.com IMDb Connector is based on.

If you have any other ideas for features, please don't hesitate to submit them by [sending me an e-mail](mailto:kolja.nolte@gmail.com) and I'll try my best to implement it in the next version. Your WP.org username will be added to the plugin's contributor list, of course (if you provide one).

*Feel free to make IMDb Connector easier to use for users from a non English speaking country by [help translating the plugin on Transifex](https://www.transifex.com/projects/p/imdb-connector/)*.

== Installation ==

= How to install =

1. Install IMDb Connector either through WordPress' native plugin installer found under *Plugins > Install* or copy the *imdb-connector* folder into the */wp-content/plugins/* directory of your WordPress installation.
2. Make sure the folder */cache/* in the plugin's directory is writable (CHMOD 755).
3. Activate the plugin in the plugin section of your admin interface.
4. Go to *Settings > IMDb Connector* to customize the plugin as desired.

**Please see the [official documentary](http://www.koljanolte.com/wordpress/plugins/imdb-connector/) for information and examples related to IMDb Connector.**

== Frequently Asked Questions ==

**The full FAQ can be found on the [official website](http://www.koljanolte.com/wordpress/plugins/imdb-connector/#FAQ).**

== Screenshots ==

1. The plugin's settings page.
2. The standard widget displayed in a sidebar.
3. The widget configuration on the admin interface.

== Changelog ==

= 1.5.0 =
* Added administration option to choose between short and full movie plot.
* Compatibility with WordPress 4.5.2
* Updated Font Awesome to 4.6.3.

= 1.4.2 =
* Compatibility with WordPress 4.4.1.

= 1.4.1 =
* Changed table format for "released" movie detail from integer to string so it no longer returns just the year number but the actual date (YYY-MM-DD). **Note:** To apply the change, you must drop the whole *imdb_connector* table in your MySQL database. Thanks to [selse](https://wordpress.org/support/profile/wwwp) for pointing this out.
* Updated translations.

= 1.4.0 =
* Added shortcode detail "poster_url" to display movie's poster URL. Please see the ["Shortcodes" area in the official documentation](http://www.koljanolte.com/wordpress/plugins/imdb-connector/#Shortcodes) for more information.
* Moved functions to classes ´IMDb_Connector_Movies´ and ´IMDb_Connector_Cache´.
* Updated translations.
* PHP 7 support.
* Cleaned up code.
* Minor cosmetic changes.

= 1.3.4 =
* Removed use of deprecated function in movie widget (thanks to [MajorFusion](https://wordpress.org/support/profile/majorfusion)).

= 1.3.3 =
* [Extended shortcodes](https://wordpress.org/support/topic/movies-tagline-runtime-format-poster-embed-shortcode) which now accepts several more attributes to let users customize the output even more individually. Please see the ["Shortcodes" area in the official documentation](http://www.koljanolte.com/wordpress/plugins/imdb-connector/#Shortcodes) for an overview of all available attributes.
* Added compatibility for WordPress 4.3 that has just been released.
* Added "Reset Settings" button to settings page.
* Fixed bug resulting in an error when activating IMDb Connector.

= 1.3.2 =
* Changed several function names to be deprecated in order to make it more organized. All functions now start with ´imdb_connector_*´.
* Cleaned up and optimized main.js.
* Small face lifting on the settings page.
* Removed debug mode.

= 1.3.1 =
* Fixed bug resulting in corrupt JSON file provided by omdbapi.com, making it unable for the plugin to work (thanks to [jcandsv](https://wordpress.org/support/profile/jcandsv)).
* Added Font Awesome icons to plugin's settings page.
* Small code improvements.

= 1.3.0 =
* Added more shortcode parameters and details. From now on you can display the runtime either as "runtime-minutes", "runtime-hours" or as "runtime-timestamp".
* Re-programmed some sections.
* Updated translations.

= 1.2.1 =
* WordPress 4.2.3 compatibility.
* Updated translations.

= 1.2.0 =
* Fixed bug.

= 1.1.0 =
* WordPress 4.2.2 compatibility.

= 1.1 =
* Added compatibility with WordPress 4.2.1.
* Updated translations.

= 1.0 =
* Stable release.
* Code cleanup and other small optimizations.
* Updated [documentation](www.koljanolte.com/wordpress/plugins/imdb-connector/).
* Updated translations.
* Updated screenshots.

= 0.6.2 =
* Fixed bug with newly added movies that do not contain all values.

= 0.6.1 =
* [Fixed small bug](https://wordpress.org/support/topic/php-connector-not-working).
* Updated/added translations.

= 0.6 =
* Fixed bug with PHP version below 5.2.
* Cleaned up code.

= 0.5 =
* Added plugin installer icon.
* Code rearrangements.
* Updated translations.

= 0.4.3 =
* Added "imdbrating" field.
* Updated translations.

= 0.4.2 =
* Added function PHP function `imdb_connector_get_cached_movies()`.
* Added [nonce protection for "delete cache" script](http://codex.wordpress.org/WordPress_Nonces) to prevent misuse.
* Added nonce protection for settings page.
* Updated translations.

= 0.4.1 =
* Fixed shortcode movie details with multiple values in it.

= 0.4 =
* MySQL cache is now stored in a separate table.
* Added feature to select the table name the cache data is being stored.
* Added feature to automatically delete the cache after a certain time.
* Added feature allowing admins to chose what cached files and settings IMDb Connector should keep after disabling the plugin.
* Added "type" movie detail that returns the type (documentary, series, movie, ...) of the movie.
* Renamed movie details "genre", "country", "language", "writer" and "director" to plural names.
* Updated translations.

= 0.3 =
* Added option to chose if the movie detail cache should be stored locally on in MySQL.
* Added an option to the settings page that defines whether the movie poster should be cached or not.
* Added "format" option array to imdb_get_connector_movie() function that defines whether the output should be an "array" or "object".
* Added translations and updated existing ones.
* The movie details "genre", "director", "writer", "actors", "country" and "language" are split up in arrays.
* The movie detail "runtime" is now an array containing "timestamp", "minutes" and "hours".
* Removed "Use default widgets style" from settings page.

= 0.2 =
* Added "Delete cache" function on settings page.
* Added several PHP functions, e.g. search_imdb_connector_movies().
* Added debug mode to display errors and warnings.
* Added several translations and updated existing ones.
* Fixed "headers already sent" bug on plugin activation.
* Fixed bug that prevented translations from being loaded.
* Fixed [bug](https://wordpress.org/support/topic/imdb-connector-dont-import-some-movies-informations) when a string run through `wptexturize()` is used for the IMDb title ([thanks to 7movies](https://wordpress.org/support/profile/7movies)).
* Changed `get_imdb_*` functions to `imdb_get_connector_*` to avoid conflicts with other plugins.
* Updated documentation.
* Rebuild movie widget.
* Restructured plugin files.

= 0.1.2 =
* Hotfix.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 1.5.0 =
Added administration option to choose between short and full movie plot.

= 1.4.2 =
Compatibility with WordPress 4.4.1.

= 1.4.1 =
Changed table format for "released" movie detail from integer to string so it no longer returns just the year number but the actual date (YYY-MM-DD). **Note:** To apply the change, you must drop the whole *imdb_connector* table in your MySQL database.

= 1.4.0 =
Moved functions to classes ´IMDb_Connector_Movies´ and ´IMDb_Connector_Cache´ and added shortcode "poster_url".

= 1.3.4 =
Fixed movie widget.

= 1.3.3 =
The shortcodes have been extended and accept now several more attributes to let users customize the output individually. For a full list of available attributes and examples, please see the ["Shortcodes" section in the official documentation](http://www.koljanolte.com/wordpress/plugins/imdb-connector/#Shortcodes).

= 1.3.2 =
**IMPORTANT**: In this version most functions are now deprecated, meaning that they still work under their old name but you should change them to the new one if you use them independently in your blog. Every IMDb Connector function starts with ´imdb_connector_*´. If you experience any problems, please report them either in the [support forum](https://wordpress.org/support/plugin/imdb-connector) or directly via [e-mail](mailto:kolja.nolte@gmail.com) so I can fix it as soon as possible.

= 1.3.1 =
Important hotfix.

= 1.3.0 =
New shortcode detail parameters: "runtime-minutes", "runtime-hours" and "runtime-timestamp".

= 1.2.1 =
Translations update.

= 1.2.0 =
Bug fixes.

= 1.1.0 =
WordPress 4.2.2 compatibility.

= 1.1 =
Added compatibility with WordPress 4.2.1.

= 1.0 =
Stable release with small optimizations.

= 0.6.2 =
Fix for details of newly added movies.

= 0.6.1 =
Minor bug fixes and updated/new translations.

= 0.6 =
Bug fix for PHP version < 5.3.

= 0.5 =
Cosmetic fixes; plugin installer icon.

= 0.4.3 =
Added "imdbrating" field.

= 0.4.2 =
Security fix, translations updates.

= 0.4.1 =
Shortcode fix.

= 0.4 =
Major update with many new functions (auto delete, MySQL caching, deactivation actions), bug fixes and corrections.

= 0.3 =
**IMPORTANT:** The array key names have been renamed and partly reformatted. Please see "PHP functions" section in the [official documentation](http://www.koljanolte.com/wordpress/plugins/imdb-connector/) for the new structure.

= 0.2 =
Major update with many bug fixes and new features and functions. See changelog for more information.

= 0.1.2 =
Implemented hotfix.

= 0.1 =
This is the first release of IMDb Connector.