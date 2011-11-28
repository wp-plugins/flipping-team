<?php
/*
	Plugin Name: Flipping Team
	Plugin URI: http://abhishekgupta92.info
	Description: Team page for your blog who made it possible.
	Version: 1.4
	Author: abhishekgupta92
	Author URI: http://abhishekgupta92.info

	Copyright 2011 Abhishek Gupta (email : abhishekgupta.iitd@gmail.com)
	               Cédric Houbart (email : cedric@scil.coop)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// check for WP context
if ( ! defined( 'ABSPATH' ) ) { die(); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////// Lets create a table prefix_team, and add some fields to it. Add the version of the database to it. ////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

global $flipping_team_db_version;
$flipping_team_db_version = "1.0";

/** Setup database and sample data */
function flipping_team_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	if($wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

		$sql = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time bigint(11) DEFAULT '0' NOT NULL,
			name tinytext NOT NULL,
			url VARCHAR(200) NOT NULL,
			imageloc VARCHAR(300) NOT NULL,
			info text NOT NULL,
			UNIQUE KEY id (id)
			);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		add_option( "flipping_team_db_version", $flipping_team_db_version );

		$table_name = $wpdb->prefix . "team";
		$name  = "Abhishek Gupta";
		$website = "http://abhishekgupta92.info";
		$info = "Abhishek is a sophomore Undergraduate student at IIT Delhi.";
		$imageloc = get_site_url()."/wp-content/plugins/flipping-team/images/images.jpeg";

		$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $name, 'url' => $website, 'imageloc' => $imageloc, 'info' => $info ) );

		$table_name = $wpdb->prefix . "team";
		$name  = "Abhishek Gupta";
		$website = "http://abhishekgupta92.info";
		$info = "Abhishek is a sophomore Undergraduate student at IIT Delhi.";
		$imageloc = get_site_url() . "/wp-content/plugins/flipping-team/images/images.jpeg";

		$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $name, 'url' => $website, 'imageloc' => $imageloc, 'info' => $info ) );
		
	}
	
	$installed_ver = get_option( "flipping_team_db_version" );
	if( $installed_ver != $jal_db_version ) {
		$sql = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time bigint(11) DEFAULT '0' NOT NULL,
			name tinytext NOT NULL,
			url VARCHAR(200) NOT NULL,
			imageloc VARCHAR(300) NOT NULL,
			info text NOT NULL,
			UNIQUE KEY id (id)
			);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		update_option( "flipping_team_db_version", $flipping_team_db_version );
	}
}
register_activation_hook( __FILE__, 'flipping_team_install' );



function flipping_team_init() {
	$lang_dir = basename( dirname( __FILE__ ) ) . '/languages';
	load_plugin_textdomain( 'flpt', false, $lang_dir );
}
add_action( 'init', 'flipping_team_init' );

// Add custom style
function flipping_team_styles() {
	wp_enqueue_style( 'flipping_team', plugins_url( '/flipping-team.css', __FILE__ ) );
}
add_action( 'wp_print_styles', 'flipping_team_styles' );



/** Add a new member
 * @param string $name Member name
 * @param string $info Member info (as html)
 * @param array $attrs Other attributes as associative array (website, image)
 */
function flipping_team_add( $name, $info, $attrs ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	$website = ( isset( $attrs['website'] ) ? $attrs['website'] : "" );
	$imageloc = ( isset( $attrs['image'] ) ? $attrs['image'] : "" );
	$rows_affected = $wpdb->insert( $table_name,
	                                array( 'time'     => current_time( 'mysql' ),
	                                       'name'     => $name,
	                                       'url'      => $website,
	                                       'imageloc' => $imageloc,
	                                       'info'     => $info ) );
	return $rows_affected > 0;
}

function flipping_team_edit( $id, $name, $info, $attrs ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	$website = ( isset($attrs['website'] ) ? $attrs['website'] : "" );
	$imageloc = ( isset($attrs['image'] ) ? $attrs['image'] : "" );
	$rows_affected = $wpdb->update( $table_name,
	                                array( 'time'     => current_time( 'mysql' ),
	                                       'name'     => $name,
	                                       'url'      => $website,
	                                       'imageloc' => $imageloc,
	                                       'info'     => $info ),
	                                array( 'id' => $id ) );
	return $rows_affected > 0;
}

function flipping_team_delete( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	$request = $wpdb->prepare( "DELETE FROM $table_name WHERE id = '%d'",
	                           $id );
	$rows_affected = $wpdb->query( $request );
	return $rows_affected > 0;
}

/** Get all member
 * @param {string} $sort Sort type, may be "id" (default) or "alpha"
 */
function flipping_team_get_all( $sort = "id" ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	switch ( $sort ) {
	case "alpha":
		$rows = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY name",
		                            ARRAY_A );
		break;
	case "id":
	default:
		$rows = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id",
		                            ARRAY_A );
		break;
	}
	$results = array();
	foreach ( $rows as $row ) {
		$row['image'] = $row['imageloc'];
		$row['website'] = $row['url'];
		unset( $row['imageloc'] );
		array_push( $results, $row );
	}
	return $results;
}

function flipping_team_get( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
	$request = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d",
	                           $id );
	$row = $wpdb->get_row( $request, ARRAY_A, 0 );
	$row['image'] = $row['imageloc'];
	$row['website'] = $row['url'];
	unset( $row['imageloc'] );
	return $row;
}

// Register shortcode
require_once( dirname( __FILE__ )."/flipping_team_shortcode.php");
// Register admin options
require_once( dirname( __FILE__ )."/flipping_team_admin.php");
