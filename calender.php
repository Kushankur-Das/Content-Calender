<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://kushankur.wisdmlabs.net
 * @since             1.0.0
 * @package           Calender
 *
 * @wordpress-plugin
 * Plugin Name:       Content Calendar
 * Plugin URI:        https://https://content-calendar.com
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Kushankur Das
 * Author URI:        https://https://kushankur.wisdmlabs.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       calender
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CALENDER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-calender-activator.php
 */
function activate_calender() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calender-activator.php';
	Calender_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-calender-deactivator.php
 */
function deactivate_calender() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calender-deactivator.php';
	Calender_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_calender' );
register_deactivation_hook( __FILE__, 'deactivate_calender' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-calender.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */


 function cc_create_table()
 {
	 global $wpdb;
	 $table_name = $wpdb->prefix . 'cc_data';
	 $charset_collate = $wpdb->get_charset_collate();
 
	 $sql = "CREATE TABLE IF NOT EXISTS $table_name (
	   id mediumint(9) AUTO_INCREMENT,
	   date date NOT NULL,
	   occasion varchar(255) NOT NULL,
	   post_title varchar(255) NOT NULL,
	   author int(11) NOT NULL,
	   reviewer varchar(255) NOT NULL,
	   PRIMARY KEY  (id)
	 ) $charset_collate;";
 
	 require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	 dbDelta($sql);
 }
 
 register_activation_hook(__FILE__, 'cc_create_table');
 
 // Handle the form submission
 function cc_handle_form()
 {
	 global $wpdb;
 
	 if (isset($_POST['date']) && isset($_POST['occasion']) && isset($_POST['post_title']) && isset($_POST['author']) && isset($_POST['reviewer'])) {
		 $table_name = $wpdb->prefix . 'cc_data';
		 $date = sanitize_text_field($_POST['date']);
		 $occasion = sanitize_text_field($_POST['occasion']);
		 $post_title = sanitize_text_field($_POST['post_title']);
		 $author = sanitize_text_field($_POST['author']);
		 $reviewer = sanitize_text_field($_POST['reviewer']);
		 $wpdb->insert(
			 $table_name,
			 array(
				 'date' => $date,
				 'occasion' => $occasion,
				 'post_title' => $post_title,
				 'author' => $author,
				 'reviewer' => $reviewer
			 )
		 );
	 }
 }
 
 add_action('init', 'my_form_submission_handler');
 
 function my_form_submission_handler()
 {
	 if (isset($_POST['submit'])) {
		 cc_handle_form();
	 }
 }
 
 //Add Custom Menu Page
 function cc_add_menu_pages()
 {
	 add_menu_page(
		 __('Content Calendar', 'content-calendar'),
		 'Content Calendar',
		 'manage_options',
		 'content-calendar',
		 'content_calendar_callback',
		 'dashicons-calendar-alt',
		 6
	 );
	 add_submenu_page(
		 'content-calendar',
		 __('Schedule Content', 'content-calendar'),
		 __('Schedule Content', 'content-calendar'),
		 'manage_options',
		 'schedule-content',
		 'schedule_content_callback'
	 );
	 add_submenu_page(
		 'content-calendar',
		 __('View Schedule', 'content-calendar'),
		 __('View Schedule', 'content-calendar'),
		 'manage_options',
		 'view-schedule',
		 'view_schedule_callback'
	 );
 }
 add_action('admin_menu', 'cc_add_menu_pages');
 
 function content_calendar_callback()
 {
	 ?>
	 <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
	 <?php
	 schedule_content_callback();
	 view_schedule_callback();
 }
 
 
 
 function schedule_content_callback()
 {
 ?>
 
	 <!--Add Input fields on Schedule Content Page-->
	 <div class="wrap">
		 <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
 
 
		 <form method="post">
			 <input type="hidden" name="action" value="cc_form">
 
			 <label for="date">Date:</label>
			 <input type="date" name="date" id="date" value="<?php echo esc_attr(get_option('date')); ?>" /><br />
 
			 <label for="occasion">Occasion:</label>
			 <input type="text" name="occasion" id="occasion" value="<?php echo esc_attr(get_option('occasion')); ?>" /><br />
 
			 <label for="post_title">Post Title:</label>
			 <input type="text" name="post_title" id="post_title" value="<?php echo esc_attr(get_option('post_title')); ?>" /><br />
 
			 <label for="author">Author:</label>
			 <?php wp_dropdown_users(array('name' => 'author', 'selected' => get_option('author'))); ?><br />
 
			 <label for="reviewer">Reviewer:</label>
			 <input type="text" name="reviewer" id="reviewer" value="<?php echo esc_attr(get_option('reviewer')); ?>" /><br />
 
			 <?php submit_button('Schedule Post'); ?>
 
		 </form>
	 </div>
 
 <?php
 }
 
 
 
 function view_schedule_callback()
 {
	 ?>
	 <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
	 <?php
	 
	 global $wpdb;
	 $table_name = $wpdb->prefix . 'cc_data';
   
	 $data = $wpdb->get_results( "SELECT * FROM $table_name" );
   
	 echo '<table>';
	 echo '<tr><th>ID</th><th>Date</th><th>Occasion</th><th>Post Title</th><th>Author</th><th>Reviewer</th></tr>';
	 foreach ( $data as $row ) {
	   echo '<tr>';
	   echo '<td>' . $row->id . '</td>';
	   echo '<td>' . $row->date . '</td>';
	   echo '<td>' . $row->occasion . '</td>';
	   echo '<td>' . $row->post_title . '</td>';
	   echo '<td>' . get_userdata( $row->author )->user_login . '</td>';
	   echo '<td>' . $row->reviewer . '</td>';
	   echo '</tr>';
	 }
	 echo '</table>';
	 
 }


function run_calender() {

	$plugin = new Calender();
	$plugin->run();

}
run_calender();
