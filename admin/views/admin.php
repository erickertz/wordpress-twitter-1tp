<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Wordpress_Twitter_1TP
 * @author    Eric Kertz <erickertz@1trickpony.com>
 * @license   GPL-2.0+
 * @link      http://1trickpony.com
 * @copyright 2014 1 Trick Pony
 */
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form method="post" action="options.php">
	<?php settings_fields('twitter_options'); ?>
	<?php do_settings_sections('twitter'); ?>
	<?php submit_button(); ?>
	</form>
</div>
