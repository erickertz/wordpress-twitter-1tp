=== Wordpress_Twitter_1TP ===

Contributors: erictr1ck
Tags: twitter
Requires at least: 3.9.1
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Uses the Twitter REST API 1.1 search/tweets functionality to add tweets to posts

== Description ==

This plugin uses the Twitter REST API 1.1 search/tweets functionality to add tweets to posts. After Installation a new Twitter Settins section will appear in the admin where you can input your Twitter API credentials. There will also be a new Twitter Hashtag field available for the posts that will use the plugin (specified in admin/class-wordpress-twitter-1tp-admin.php). The plugin will use the text specified in these fields to search twitter and attach the returned tweets to the corresponding posts. 

To retrieve the tweets, simply add the shortcode [get_tweets] to a post and visit that post in a browser. You can also set a cron task to call the post peridically in order to keep your posts up to date with the latest tweets.

notes: During its first run the plugin will go back as far as possible to retrieve already existing tweets. I'm not 100% sure how long Twitter keeps tweets avaiable though the API but it seems to be about a month or so. After the first run the pugin utilizes Twitter's since_id to cut down on API calls.

== Installation ==

1. Download `wordpress-twitter-1tp`
2. Extract the `wordpress-twitter-1tp` directory to your computer
3. Upload the `wordpress-twitter-1tp` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard
5: Go to Settings->Twitter and enter your API credentials
6: Go to a page or post and enter the search phrase you would like to associate with the post.
7: Add the shortcode [get_tweets] to a post and visit that post in a browser

You should now see the retrieved tweets available for that post in the Wordpress admin as Custom Fields. Make sure you have Custom fields checked off in your Screen )ptions.

== TO-Do's ==

1: Add option in setting page to define which post types the Twitter Hashtag field should be applied to. Right now it must be manually set in admin/class-wordpress-twitter-1tp-admin.php.
2: Use Composer to manage and load Codebird dependency.
3: Check if Advanced Custom Fields plugin is installed. If so, perhaps use ACF's repeater field for easier managemant of tweets. If ACF is not available the plugin should ALWAYS fall back to using Wordpress's out of the box meta fields as it does now.

== Updates ==

The basic structure of this plugin was cloned from the [WordPress-Plugin-Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) project.
This plugin supports the [GitHub Updater](https://github.com/afragen/github-updater) plugin, so if you install that, this plugin becomes automatically updateable direct from GitHub. Any submission to WP.org repo will make this redundant.
