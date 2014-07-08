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
 * This class should be used when the Advanced custom fields plugin is installed to work with the
 * administrative and public side of the WordPress site.
 *
 * @package Wordpress_Twitter_1TP_Admin
 * @author  Eric Kertz <erickertz@1trickpony.com>
 */
class Wordpress_Twitter_1TP_Acf {
	
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Define Advanced Custom Fields repeater field structure
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $acf_field_repeater_structure = array (
		'id' => 'acf_tweets',
		'title' => 'tweets',
		'fields' => array (
			array (
				'key' => 'field_53bafa11cf1de',
				'label' => 'Tweets',
				'name' => 'tweets',
				'type' => 'repeater',
				'sub_fields' => array (
					array (
						'key' => 'field_53bafa19cf1df',
						'label' => 'tweet',
						'name' => 'tweet',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_53bafa41cf1e0',
						'label' => 'json',
						'name' => 'json',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_53bb06882fce4',
						'label' => 'approved',
						'name' => 'approved',
						'type' => 'checkbox',
						'column_width' => '',
						'choices' => array (
							1 => 'approved'
						),
						'default_value' => 1,
						'layout' => 'vertical',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	);
	
	private function __construct(){
		add_action( 'init', array($this, 'create_acf_tweet_fields') );
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
	 * Checks to see if Advanced Custom Fields is installed
	 *
	 * @since    1.0.0
	 */
	public static function check_acf(){
		if(class_exists('acf_field')){
			return true;
		}
		return false;
	}
	
	
	/**
	 * Checks to see if Advanced Custom Fields Repeater Field is installed
	 *
	 * @since    1.0.0
	 */
	public static function check_acf_repeater(){
		if(class_exists('acf_field_repeater')){
			return true;
		}
		return false;
	}

	/**
	 * Adds an ACF repeater field 
	 *
	 * @since    1.0.0
	 */
	public static function create_acf_tweet_fields(){
		if(self::check_acf_repeater()){
			if(function_exists("register_field_group"))
			{
				register_field_group(self::$acf_field_repeater_structure);
			}
		}
	}
	
}
	