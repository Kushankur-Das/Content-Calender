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

require plugin_dir_path(__FILE__) . 'scripts.php';

//Create Database
// Create a new database table
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


function run_calender() {

	$plugin = new Calender();
	$plugin->run();

}
run_calender();
