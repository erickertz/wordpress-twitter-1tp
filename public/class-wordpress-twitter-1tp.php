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
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @package Wordpress_Twitter_1TP_Admin
 * @author  Eric Kertz <erickertz@1trickpony.com>
 */
 
class Wordpress_Twitter_1TP {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wordpress-twitter-1tp';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_shortcode( 'get_tweets', array($this, 'get_tweets') );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

					restore_current_blog();

				}

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * Main function to get all the tweets and save them to their corresponding page.
	 *
	 * @since    1.0.0
	 */
	public function get_tweets(){
		$hashtagPages = $this->get_hashtags();
		foreach($hashtagPages as $hashtagPage){
			$postId = $hashtagPage['id'];
			$hashtag = $hashtagPage['hashtag'];
			$sinceId = $hashtagPage['since_id'];
			$statuses = array_reverse($this->retrieve_tweets($hashtag,$sinceId));
			$this->save_tweets($postId,$statuses);
		}
		return "tweets saved";
	}
	
	/**
	 * Retrieve the tweets from Twitter.
	 *
	 * @since    1.0.0
	 */
	private function retrieve_tweets($hashtag, $sinceId = null, $query = null){
		$replies = array();
		$sinceIdStr = "";
		if(!class_exists('\Codebird\Codebird')){
			$this->load_codebird();	
		}
		if(is_null($query)){
			if((!empty($sinceId)) && (!is_null($sinceId))){
				$sinceIdStr = "&since_id=".$sinceId;
			}
			$replies[] = $this->cb->search_tweets('q='.$hashtag.'&count=100'.$sinceIdStr, true);
		} else {
			if(substr($query,0,1) == "?"){
				$query = substr($query,1,(strlen($query) - 1));
			}
			$reply = $this->cb->search_tweets($query, true);
			return $reply;
		}
		if(isset($replies[0]->search_metadata->next_results)){
			$paginatedTweets = $replies[0];
			while(isset($paginatedTweets->search_metadata->next_results)){
				$replies[] = $paginatedTweets = $this->retrieve_tweets($hashtag, null, $paginatedTweets->search_metadata->next_results);
			}
		}
		return $this->parse_statuses($replies);
	}
	
	/**
	 * Reformat the twitter replies and return an array of the statuses.
	 *
	 * @since    1.0.0
	 */
	private function parse_statuses(Array $replies){
		$statuses = array();
		if(!empty($replies)){
			foreach($replies as $reply){
				if(isset($reply->statuses)){
					if(!empty($reply->statuses)){
						foreach($reply->statuses as $status){
							$statuses[] = $status;
						}		
					}
				}
			}	
		}
		return $statuses;
	}
	
	/**
	 * Save tweets to their corresponding pages.
	 *
	 * @since    1.0.0
	 */
	private function save_tweets($pageId, array $statuses){
		$saveMethod = null;
		if(class_exists('Wordpress_Twitter_1TP_Acf')){
			if(Wordpress_Twitter_1TP_Acf::check_acf_repeater()){
				$saveMethod = "acf";
			}
		}
		if(!empty($statuses)){
			foreach($statuses as $status){
				switch ($saveMethod) {
				    case "acf":
				        $this->save_tweet_acf( $pageId, $status);
				        break;
				    default:
				       $this->save_tweet_meta( $pageId, $status);
				}
			}
			update_post_meta( $pageId, '_twitter_since_id',  $statuses[count($statuses)-1]->id);
		}
		return true;
	}
	
	/**
	 * Save tweets as WP native meta fields
	 *
	 * @since    1.0.0
	 */
	private function save_tweet_meta($pageId,$status){
		$statusJson = json_encode($status,JSON_HEX_QUOT | JSON_HEX_APOS);
		update_post_meta( $pageId, 'tweet: '.$status->text, $statusJson, "" );
	}
	
	/**
	 * Save tweets as Advanced Custom Field Repeater field
	 *
	 * @since    1.0.0
	 */
	private function save_tweet_acf($pageId,$status){
		$statusJson = json_encode($status,JSON_HEX_QUOT | JSON_HEX_APOS);
		$acfRepeaterStructure = Wordpress_Twitter_1TP_Acf::$acf_field_repeater_structure;
		$newTweet = array();
		foreach($acfRepeaterStructure['fields'][0]['sub_fields'] as $acfRepeaterStructureSubField){
			switch ($acfRepeaterStructureSubField['name']) {
			    case "user_name":
			        $newTweet[$acfRepeaterStructureSubField['key']] = $status->user->name;
			        break;
			    case "tweet":
			        $newTweet[$acfRepeaterStructureSubField['key']] = $status->text;
			        break;
			    case "json":
			        $newTweet[$acfRepeaterStructureSubField['key']] = $statusJson;
			        break;
			    case "approved":
			        $newTweet[$acfRepeaterStructureSubField['key']] = array(1);
			        break;
			}
		}
		$field_key = $acfRepeaterStructure['fields'][0]['key'];
		$value = get_field($field_key, $pageId);
		$valueNew = array();
		$valueNew[] = $newTweet;
		$valueMerged = array_merge($valueNew,$value);
		update_field( $field_key, $valueMerged, $pageId );
	}

	/**
	 * Get a list of all of the pages along with their hashtags and since_id.
	 *
	 * @since    1.0.0
	 */
	private function get_hashtags(){
		$hashtagPages = array();
		$args = array(
			'post_type' => 'any',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_key' => '_twitter_hashtag'
		);
		$myposts = get_posts( $args );
		foreach ( $myposts as $post ) {
			$sinceId = 1;
			$custom_fields = get_post_custom($post->ID);
			if(isset($custom_fields['_twitter_since_id'][0])){
				$sinceId = $custom_fields['_twitter_since_id'][0];
			}
			$hashtagPages[]  = array('id' => $post->ID, 'hashtag' => $custom_fields['_twitter_hashtag'][0], 'since_id' => $sinceId);
		}
		wp_reset_postdata();
		return $hashtagPages;
	}
	
	/**
	 * Load the codebird library.
	 *
	 * @since    1.0.0
	 */
	private function load_codebird(){
		require( plugin_dir_path( __FILE__ ) . '../includes/codebird-php-master/src/codebird.php' );
		$this->set_codebird();
	}
	
	/**
	 * Initiate codebird.
	 *
	 * @since    1.0.0
	 */
	private function set_codebird(){
		$this->cb = \Codebird\Codebird::getInstance();
		$options = get_option('twitter_options');
		$twitter_access_token = $options['twitter_access_token'];
		$twitter_access_token_secret = $options['twitter_access_token_secret'];
		\Codebird\Codebird::setConsumerKey($twitter_access_token, $twitter_access_token_secret);
		$twitter_bearer_token = get_option('twitter_bearer_token');
		if(!$twitter_bearer_token){
			$twitter_bearer_token = $this->set_twitter_bearer_token($twitter_access_token, $twitter_access_token_secret);
		}
		\Codebird\Codebird::setBearerToken($twitter_bearer_token);
	}
	
	/**
	 * Request and set Twitter bearer token.
	 *
	 * @since    1.0.0
	 */
	private function set_twitter_bearer_token($twitter_access_token, $twitter_access_token_secret){
		$reply = $this->cb->oauth2_token();
		$bearer_token = $reply->access_token;
		update_option('twitter_bearer_token',$bearer_token);
		return $bearer_token;
	}

}
