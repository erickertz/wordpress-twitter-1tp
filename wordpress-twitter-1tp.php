<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Wordpress_Twitter_1TP
 * @author    Eric Kertz <eric@1trickpony.com>
 * @license   GPL-2.0+
 * @link      http://1trickpony.com
 * @copyright 2014 1 Trick Pony
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress Twitter 1TP
 * Plugin URI:        http://1trickpony.com
 * Description:       A Twitter plugin for Wordpress Developed by 1 Trick Pony
 * Version:           1.0.0
 * Author:            Eric Tr1ck
 * Author URI:        http://1trickpony.com
 * Text Domain:       wordpress-twitter-1tp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/erickertz/wordpress-twitter-1tp
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Global Functionality
 *----------------------------------------------------------------------------*/
 /*
 * Use this section to include shared functionality
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-twitter-1tp-acf.php' );
add_action( 'plugins_loaded', array( 'Wordpress_Twitter_1TP_Acf', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wordpress-twitter-1tp.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Wordpress_Twitter_1TP', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Wordpress_Twitter_1TP', 'deactivate' ) );
add_action( 'plugins_loaded', array( 'Wordpress_Twitter_1TP', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wordpress-twitter-1tp-admin.php' );
	add_action( 'plugins_loaded', array( 'Wordpress_Twitter_1TP_Admin', 'get_instance' ) );

}