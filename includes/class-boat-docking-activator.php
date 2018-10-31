<?php

/**
 * Fired during plugin activation
 *
 * @link       http://joshdaleriley.com
 * @since      1.0.0
 *
 * @package    Boat_Docking
 * @subpackage Boat_Docking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Boat_Docking
 * @subpackage Boat_Docking/includes
 * @author     Joshua Riley <jdaleriley@gmail.com>
 */
class Boat_Docking_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::create_submissions_table();
	}

	public static function create_submissions_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'bd_submissions';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL default '0',
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name varchar(60) NOT NULL,
			email varchar(100) NOT NULL,
			boat_length smallint(5),
			notes varchar(400),
			admin_notes varchar(400),
			UNIQUE KEY id (id),
			KEY user_id (user_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
