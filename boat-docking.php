<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.joshriley.tk
 * @since             1.0.0
 * @package           Boat_Docking
 *
 * @wordpress-plugin
 * Plugin Name:       Boat Docking Reservations
 * Plugin URI:        http://www.joshriley.tk/
 * Description:       Plugin to allow resorts, hotels and other hospitality with boat docking accept reservations by tenants traveling by boat.
 * Version:           1.0.0
 * Author:            Josh Riley
 * Author URI:        http://www.joshriley.tk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       boat-docking
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-boat-docking-activator.php
 */
function activate_boat_docking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-boat-docking-activator.php';
	Boat_Docking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-boat-docking-deactivator.php
 */
function deactivate_boat_docking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-boat-docking-deactivator.php';
	Boat_Docking_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_boat_docking' );
register_deactivation_hook( __FILE__, 'deactivate_boat_docking' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-boat-docking.php';

// for custom list tables
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );//added
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    require_once( ABSPATH . 'wp-admin/includes/template.php' );
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_boat_docking() {

	$plugin = new Boat_Docking();
	$plugin->run();

}
run_boat_docking();
