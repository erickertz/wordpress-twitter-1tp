<?php
/**
 * Wordpress_Twitter_1TP.
 *
 * @package   Wordpress_Twitter_1TP_Admin
 * @author    Eric Kertz <erickertz@1trickpony.com>
 * @license   GPL-2.0+
 * @link      http://1trickpony.com
 * @copyright 2014 1 Trick Pony
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package Wordpress_Twitter_1TP_Admin
 * @author  Eric Kertz <erickertz@1trickpony.com>
 */
 
class Wordpress_Twitter_1TP_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	 	 
	private function __construct() {
		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = Wordpress_Twitter_1TP::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'admin_init', array($this, 'register_twitter_settings') );
		add_action( 'add_meta_boxes', array($this, 'add_meta_box') );
		add_action( 'save_post', array($this, 'save') );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Wordpress_Twitter_1TP::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Wordpress_Twitter_1TP::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Twitter Settings', $this->plugin_slug ),
			__( 'Twitter', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
	
	/**
	 * Register plugin settings.
	 *
	 * @since    1.0.0
	 */
	public function register_twitter_settings() {
		register_setting( 'twitter_options', 'twitter_options' );
		add_settings_section('twitter_section', 'Twitter Section', array($this,'twitter_section_callback'), 'twitter');
		add_settings_field('twitter_access_token', 'Twitter Access Token', array($this,'twitter_access_token_callback'), 'twitter', 'twitter_section');
		add_settings_field('twitter_access_token_secret', 'Twitter Access Token Secret', array($this,'twitter_access_token_secret_callback'), 'twitter', 'twitter_section');
	}
	
	/**
	 * Callback when adding plugin settings section. nothing to see here...
	 *
	 * @since    1.0.0
	 */
	public function twitter_section_callback() {
		
	}
	
	/**
	 * Callback when adding Twitter access token setting.
	 *
	 * @since    1.0.0
	 */
	public function twitter_access_token_callback(){
		$options = get_option('twitter_options');
		if(!isset($options['twitter_access_token'])){
			$options['twitter_access_token'] = "";
		}
		echo "<input id='twitter_options_twitter_access_token' name='twitter_options[twitter_access_token]' size='40' type='text' value='{$options['twitter_access_token']}' />";
	}
	
	/**
	 * Callback when adding Twitter access token secret setting.
	 *
	 * @since    1.0.0
	 */
	public function twitter_access_token_secret_callback(){
		$options = get_option('twitter_options');
		if(!isset($options['twitter_access_token_secret'])){
			$options['twitter_access_token_secret'] = "";
		}
		echo "<input id='twitter_options_twitter_access_token_secret' name='twitter_options[twitter_access_token_secret]' size='40' type='text' value='{$options['twitter_access_token_secret']}' />";
	}
	
	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = array('page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'twitter_settings'
					,'Twitter Settings'
					,array( $this, 'twitter_render_meta_box_content' )
					,$post_type
					,'normal'
					,'high'
				);
            }
	}
	
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function twitter_render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'twitter_inner_custom_box', 'twitter_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_twitter_hashtag', true );

		// Display the form, using the current value.
		echo '<label for="twitter_hashtag">';
		echo 'Twitter Hashtag';
		echo '</label> ';
		echo '<input type="text" id="twitter_hashtag" name="twitter_hashtag"';
        echo ' value="' . esc_attr( $value ) . '" size="25" />';
		
		// hidden field with the latest tweets twitter id
		$value = get_post_meta( $post->ID, '_twitter_since_id', true );
		echo '<input type="hidden" id="twitter_since_id" name="twitter_since_id"';
        echo ' value="' . esc_attr( $value ) . '" />';
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['twitter_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['twitter_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'twitter_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST['twitter_hashtag'] );

		// Update the meta field.
		update_post_meta( $post_id, '_twitter_hashtag', $mydata );	
		 
	}

}