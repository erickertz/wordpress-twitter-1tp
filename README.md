# Wordpress_Twitter_1TP

Uses the Twitter REST API 1.1 search/tweets functionality to add tweets to posts

-----------------------

## Description

This plugin uses the Twitter REST API 1.1 search/tweets functionality to add tweets to posts. After Installation a new Twitter Settings section will appear in the admin where you can input your Twitter API credentials. There will also be a new Twitter Hashtag field available for the posts that will use the plugin (specified in admin/class-wordpress-twitter-1tp-admin.php). The plugin will use the text specified in these fields to search twitter and attach the returned tweets to the corresponding posts.

If the Advanced Custom Field Repeater field is installed, the tweets will go into a grouped repeater field. If not, they will be saved as regular post meta.
If the Advanced Custom Fields Hidden field (https://github.com/erickertz/acf-hidden) is installed, the full json string will not be visible in the admin.

To retrieve the tweets, simply add the shortcode [get_tweets] to a post and visit that post in a browser. You can also set a cron task to call the post peridically in order to keep your posts up to date with the latest tweets.

notes: During its first run the plugin will go back as far as possible to retrieve already existing tweets. I'm not 100% sure how long Twitter keeps tweets avaiable through the API but it seems to be about a month or so. After the first run the plugin utilizes Twitter's since_id to cut down on API calls.

## Installation

1. Download `wordpress-twitter-1tp`
2. Extract the `wordpress-twitter-1tp` directory to your computer
3. Upload the `wordpress-twitter-1tp` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard
5. Go to Settings->Twitter and enter your API credentials
6. Go to a page or post and enter the search phrase you would like to associate with the post.
7. Add the shortcode [get_tweets] to a post and visit that post in a browser

You should now see the retrieved tweets available for that post in the Wordpress admin as Custom Fields. Make sure you have Custom fields checked off in your Screen Options.

## To-Do's

1. Add an option in setting page to define which post types the Twitter Hashtag field should be applied to. Right now it must be manually set in admin/class-wordpress-twitter-1tp-admin.php.
2. Use Composer to manage and load Codebird dependency.

## Updates

The basic structure of this plugin was cloned from the [WordPress-Plugin-Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate) project.
This plugin supports the [GitHub Updater](https://github.com/afragen/github-updater) plugin, so if you install that, this plugin becomes automatically updateable direct from GitHub. Any submission to WP.org repo will make this redundant.
