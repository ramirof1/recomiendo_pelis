=== WordPress Post Tabs ===
Contributors: internet techies, tejaswini
Tags:post, page,tabs,menu,ui-tabs,shortcode,navigation,jquery,slider,tabber,best tabs,
Donate link: http://www.clickonf5.org/go/donate-wp-plugins/
Requires at least: 3.5
Tested up to: 4.5.3
Stable tag: 1.6.2
License: GPLv2

Add clean, responsive and structured tabbed content to WordPress Posts and Pages easily through the edit post/page panel.

== Description ==

WordPress Post Tabs will help you add tabs to your WordPress post or Page on current theme. For the best utilization of the webpage real estate, tabs are one of the most effective way to show more content in less area. <a href="http://tabbervilla.com/wordpress-post-tabs-pro/" rel="friend" title="Best Responsive Tab Plugin">Best Responsive Tab Plugin</a> developer at TabberVilla built this WordPress Post Tabs Lite version which comes with essential features for a tab plugin. For premium features and designs, you can take a look at the PRO version of WordPress Post Tabs plugin at <a href="http://tabbervilla.com/wordpress-post-tabs-pro/" rel="friend" title="Best Responsive Tab Plugin">TabberVilla</a>.

= Features =

* 3 predfined skins/styles, you can easily make your own as well
* Quicktag to add tabs in post or page content
* Next Previous Navigation links can be enabled
* Unlimited number of tab sets can be added to the post/page
* Load JS only on pages having Tabs
* Enable - Disable Page Reload on Tab Click
* Custom Style Box to Apply CSS Changes from Admin Panel
* Embed YouTube, Vimeo videos, Tables inside Tab Content Area

= <a href="http://tabbervilla.com/wordpress-post-tabs-pro/" rel="friend">Premium</a> Features =

* 12+ Skins and Style
* Style Editor to Change Tab Title, Content Colors from Admin Panel
* Advance QuickTag Box with Skin, Width, Location Selector
* Widget Placeholder to have Popular Posts, Latest Post, Related Posts or any widget inside a tab
* External Link to Tab Title
* Nested Tabs i.e. Tabs inside a Tab
* Linkable Tabs
* External Link Icon
* Image beside Tab Title
* Smooth Scroll to Tab Title
* Export/Import Setting Values (Helpful in Implementation on Live Site)
* Prompt Support with Response Time less than 6 Hours

As you can see the format of gadgets review on PCWorld and CNET where they provide different sections like specifications, user review, full review, shop etc in different tabs. Similar format is visible on different automobile review sites. In fact the tabbing format is useful for movies review and recipes sites. You can use one tab for picture gallery and other tab for detailed review. To make your post more interesting, you can add videos under one tab as well.  

**For more advanced features, upgrade to [WordPress Post Tabs PRO](http://tabbervilla.com/wordpress-post-tabs-pro/)**

[Plugin Information and FAQs](http://tabbervilla.com/wordpress-post-tabs/) | 

== Installation ==

There's 3 ways to install this plugin:

= 1. The super easy way =
1. In your Admin, go to menu Plugins > Add
1. Search for WordPress Post Tabs
1. Click to install
1. Activate the plugin
1. A new sub-menu under Settings menu named `WordPress Post Tabs` will appear in your Admin

= 2. The easy way =
1. Download the plugin (.zip file) on the right column of this page
1. In your Admin, go to menu Plugins > Add
1. Select the tab "Upload"
1. Upload the .zip file you just downloaded
1. Activate the plugin
1. A new sub-menu under Settings menu named `WordPress Post Tabs` will appear in your Admin

= 3. The old way (FTP) =
1. Upload `wordPress-post-tabs` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. A new sub-menu under Settings menu named `WordPress Post Tabs` will appear in your Admin

== Usage ==

**Option 1 - Quick Tag**

* Use Quick Tags to get the plugin shortcode in the post/page content area. Fill the Quick Tag box with name of tabs (commna separated) and click on 'Insert' button. It will insert shortcode with default tab content, you can edit the content in the same edit panel of post/page.

**Option 2 - Manual Insertion of Shortcode**

* To insert the tabs, you would need to use two shortcodes on your Edit Post/Page admin panel. The first shortcode is for tab names and the second shortcode represents the end of the set of the tabs. You can insert multiple number of tabset on a single page.
* Example how to put tabs:


`[wptab name='Overview']

This is the overview section.

[/wptab]

[wptab name='Specifications']

Various product Specifications can be entered here.

[/wptab]

[wptab name='Screenshots']

You can insert images or screenshots of the product. 

[/wptab]

[end_wptabset]`

**Do not forget to insert the 'name' attribute for each tab. It is important** 

** Just remember to put the 'end_tabset' shortcode at the end of all tab contents as shown above.**

* On the 'Post/Page Tabs' Settings on your WordPress admin, you can select your style. Currently there are three styles - default, gray and red. You can add your own styles easily. Please refer the plugin page how to add the custom styles, its just 2 minute work.

* In case you wish to reload the page every time another tab is clicked, you can select that option from the settings page. This would be helpful if you wish to count these pageviews as well.

* If you check the 'Disable loading on all Posts' option, this will disable the plugin on the posts all together and you will get a custom box 'Enable Post/Page Tabs Feature' on your Edit Post panel. Now you can check this checkbox for those posts only where you wish to enable the tabs. This will prevent the loading of the script and style specific to this plugin on all other pages.

* Same as above is the case with 'Disable loading on all Pages', its for pages.

* You can replace the default shortcodes with your own, that are comfortable for you. 

== Screenshots ==

1. Tab 1 on post
2. Another tab on post (gallery tab)
3. Usage on Edit Post Panel

Visit the plugin page (http://tabbervilla.com/wordpress-post-tabs/) to read more about it.

== Frequently Asked Questions ==

Visit the plugin page (http://tabbervilla.com/wordpress-post-tabs/) for recent FAQs.

== Upgrade Notice ==

Please use the contact form in case of any issues while upgrading.

== Changelog ==

Version 1.6.2 (08/10/2016)

1. Fixed - Plugin was not automatically deactivated on tabs pro activation

Version 1.6.1 (07/08/2016)

1. Fixed - Issue with https (SSL) sites

Version 1.6 (08/20/2014)

1. New - Set cookie for last active tab.
2. Fix - Reload on click will scroll page to top of tabs not to content.
3. Fix - Debug errors.

Version 1.5.1 (06/04/2014)

1. New - Option to choose between prettytitle or default title

Version 1.5 (05/28/2014)

1. New - Added new skin 'Simple Gray'
2. New - Tab title instead of #tab_number in URL
3. Fix - Better admin panel
4. Fix - No 'wp post tabs' link by default
5. Fix - Debug notifications


Version 1.4.1 (04/24/2014)

1. Fix: Quicktag to insert tab shortcode not working in WordPress 3.9 Post Editor 

Version 1.4 (01/08/2013)

1. New - Quick Tag to insert tabs shortcode in content area
2. New - Assign external links to the tabs
3. New - Option to open links in new window or the same window of browser
4. New - A new shortcode attribute active='1' can be used for the wptab (individual tab) shortcode. So you can specify which tab is active by default.
5. Fix - FOUC function
6. Fix - Alignment and CSS fix for multiple rows tabs

Version 1.3.1 (08/02/2011)

1. Fix - For some installations, tabs were not working in case the Post/Page ID was changed in footer due to custom WP Query. Fixed this issue, so that the tabs JS will pick correct tab ID in all cases.
2. Fix - Tabs were trimmed off in RSS feed in case full post text is chosen to be displayed in the RSS feeds. Corrected the issue. Thanks Michael who brought it forward.
3. Fix - An extra space used to appear before the content in the tab in some cases due to empty p tags (WordPress autop formatter). Fixed this issue.


Version 1.3 (02/25/2011)

1. Fix - Settings page link was not working on Plugins admin page on dashboard.
2. New - You can now add 'Previous' and 'Next' links or buttons to the tabset
3. New - Now tabs in the post can be displayed on the home, index or the category or date archive pages
4. New - Option to disable tab cookies in the browser of the user that remembers which tab was last opened by the visitor
5. New - Option to disable FOUC (Flash Of Unstyled Content) before the page/document loads completely


Version 1.2 (01/28/2011)

1. Major Fix - The tabs were breaking the post/page in preview or on multiple comments page (post/page permalink change).

Version 1.1 (11/02/2010)

1. New - Facility to select 'Fade Effect' for the tabs
2. New - Facility to disable the load of jquery on the page in case the active theme or some plugin already has it hardcoded in the header or footer. This would avoid the JS conflict.

Visit the plugin page (http://tabbervilla.com/wordpress-post-tabs/) to see the changelog and release notes.