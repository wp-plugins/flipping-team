<?php
/*
	Plugin Name: Flipping Team
	Plugin URI: http://abhishekgupta92.info
	Description: Team page for your blog who made it possible.
	Version: 1.1
	Author: abhishekgupta92
	Author URI: http://abhishekgupta92.info

	Copyright 2011 Abhishek Gupta (email : abhishekgupta.iitd@gmail.com)

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
if ( !defined('ABSPATH') ){ die(); }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////// Lets create a table prefix_team, and add some fields to it. Add the version of the database to it. ////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

global $flipping_team_db_version;
$flipping_team_db_version = "1.0";

function flipping_team_install () {
	global $wpdb;
	$table_name = $wpdb->prefix . "team";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time bigint(11) DEFAULT '0' NOT NULL,
			name tinytext NOT NULL,
			url VARCHAR(200) NOT NULL,
			imageloc VARCHAR(300) NOT NULL,
			info text NOT NULL,
			UNIQUE KEY id (id)
			);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option("flipping_team_db_version", $flipping_team_db_version);

		$table_name = $wpdb->prefix . "team";	
		$name  = "Abhishek Gupta";
		$website = "http://abhishekgupta92.info";
		$info = "Abhishek is a sophomore Undergraduate student at IIT Delhi.";
		$imageloc = "wp-content/plugins/flipping_team/images/images.jpeg";

		$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $name, 'url' => $website, 'imageloc' => $imageloc, 'info' => $info ) );

		$table_name = $wpdb->prefix . "team";	
		$name  = "Abhishek Gupta";
		$website = "http://abhishekgupta92.info";
		$info = "Abhishek is a sophomore Undergraduate student at IIT Delhi.";
		$imageloc = "wp-content/plugins/flipping_team/images/images.jpeg";

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

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      update_option( "flipping_team_db_version", $flipping_team_db_version );
	}
}
register_activation_hook(__FILE__, 'flipping_team_install');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////// Lets add the menu for the options. DEPRECATED /////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	add_action('admin_menu', 'flipping_team_menu');
//	function flipping_team_menu() {
//		add_options_page('Flipping Team Options', 'Flipping Team', 'manage_options', 'flipping_team', 'my_plugin_options');
//	}
//
//	function my_plugin_options() {
//		if (!current_user_can('manage_options'))  {
//			wp_die( __('You do not have sufficient permissions to access this page.') );
//		}
//		echo '<div class="wrap">';
		?>
		<!--TEST -->
		<?
//		echo '</div>';
//	}
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////// Lets add the menu for the options. ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// create custom plugin settings menu
add_action('admin_menu', 'flipping_team_create_menu');

function flipping_team_create_menu() {

	//create new top-level menu
	add_menu_page('Flipping Team Plugin Settings', 'Flipping Team', 'administrator', 'flipping-menu', 'flipping_team', plugins_url('/images/icon.png', __FILE__));
	//call register settings function
	//add_action( 'admin_init', 'register_mysettings' );

	add_submenu_page( 'flipping-menu', 'View All Team Members', 'View All', 'administrator', 'team_view', 'team_view');
	add_submenu_page( 'flipping-menu', 'Add Team Member', 'Add', 'administrator', 'team_add', 'team_add');

}

function flipping_team()
{
	if(isset($_REQUEST['team_size']))
	{
		add_option("team_size", $_REQUEST['team_size'], '', 'yes');
	}
	if(isset($_REQUEST['team_title']))
	{
		add_option("team_title", $_REQUEST['team_title'], '', 'yes');
	}
?>
	<div class="wrap">
	<h2>Flipping Team</h2>
	<h3 align="center">The plugin was created by <a href='http://abhishekgupta92.info'/>Abhishek Gupta</a> for his blog <a href='http://thelazy.info'>thelazy.info</a>.</h3>
	</div>

	<form method="post" action="admin.php?page=flipping-menu">
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Size of Thumbnails</th>
        <td><input type="text" name="team_size" value="<?php echo get_option('team_size'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Title</th>
        <td><input type="text" name="team_title" value="<?php echo get_option('team_title'); ?>" /></td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
	</form>
</div>
<?php }

function team_add()
{
?>
    <script src="../wp-content/plugins/flipping_team/thickbox/thickbox.js"></script>
    <script src="../wp-content/plugins/flipping_team/my-script.js"></script>
    <script src="js/media-upload.js"></script>
	<link rel="stylesheet" type="text/css" href="../wp-content/plugins/flipping_team/thickbox/thickbox.css" /> 

		<div class="wrap">
	
<?php
	if(isset($_REQUEST['name']) && $_REQUEST['action']=="insert")
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "team";	
		$name  = $_REQUEST['name'];
		$website = $_REQUEST['website'];
		$info = $_REQUEST['info'];
		$imageloc = $_REQUEST['upload_image'];

		$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $name, 'url' => $website, 'imageloc' => $imageloc, 'info' => $info ) );
		echo "<br/><br/>".$name. " added to the team.<br/>";
	}
	?>
			<br/><br/>
			<h2 align='center'>Add Member to Flipping Team</h2>

			<form method="post" action="admin.php?page=flipping-menu&action=insert">
				<table class="form-table">
					<tr valign="top">
					<th scope="row">Name</th>
					<td><input type="text" name="name"/></td>
					</tr>
					 
					<tr valign="top">
					<th scope="row">Website</th>
					<td><input type="text" name="website"/></td>
					</tr>
				
					<tr valign="top">
					<th scope="row">Info</th>
					<td><input type="textbox" name="info"/></td>
					</tr>

	<?php
	/*
	// Add code for upload field
	function my_admin_scripts() { // function to load scripts
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
	}

	function my_admin_styles() {
	wp_enqueue_style('thickbox');
	}
	if (isset($_GET['page']) && $_GET['page'] == 'flipping-menu') {
	add_action('admin_print_scripts', 'my_admin_scripts'); // load own javascripts
	add_action('admin_print_styles', 'my_admin_styles'); // load own css
	}
	*/
	?>
				<tr valign="top">
				<th scope="row">Upload Image</th>
				<td><label for="upload_image">
				<input id="upload_image" type="text" size="36" name="upload_image" value="" />
				<input id="upload_image_button" type="button" value="Upload Image" />
				<br />Enter an URL or upload an image for the banner.
				</label></td>
				</tr>

				<div class="clear"> </div>

				</table>	
				<p class="submit">
				<input type="submit" class="button-secondary" value="<?php _e('Save Changes') ?>" />
				</p>
	<!--			<a onclick="return false;" title="Upload image" class="thickbox" id="add_image" href="media-upload.php?type=image&amp;TB_iframe=true&amp;width=640&amp;height=105">Upload Image</a> -->

			</form>
			</div>
<?php }

function team_view()
{
////////////////////////////////////////////////////////////////////////////
////// Check if the user have asked to edit something. /////////////////////
////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['editid']))
{
			$id=$_REQUEST['editid'];
?>
			<script src="../wp-content/plugins/flipping_team/thickbox/thickbox.js"></script>
			<script src="../wp-content/plugins/flipping_team/my-script.js"></script>
			<script src="js/media-upload.js"></script>
			<link rel="stylesheet" type="text/css" href="../wp-content/plugins/flipping_team/thickbox/thickbox.css" /> 

				<div class="wrap">
			<?php
			if(isset($_REQUEST['name']) &&  $_REQUEST['action']=="update")
			{
				global $wpdb;
				$table_name = $wpdb->prefix . "team";
				$name  = $_REQUEST['name'];
				$website = $_REQUEST['website'];
				$info = $_REQUEST['info'];
				$imageloc = $_REQUEST['upload_image'];

				$where = "id=$id";
				$wpdb->show_errors();
				$rows_affected = $wpdb->query("UPDATE $table_name SET name='$name', url='$website', info='$info', imageloc='$imageloc' WHERE id=$id");
				echo "<br/><br/>".$rows_affected. " row updated.<br/>";
			}
				global $wpdb;
				$table_name = $wpdb->prefix . "team";

				$myrows = $wpdb->get_results( "SELECT * FROM $table_name WHERE id='$id'" );
			?>
					<h2>Flipping Team</h2>

					<form method="post" action='<?php echo "admin.php?page=flipping-menu&action=update&editid=".$id ; ?> '>
						<table class="form-table">
							<tr valign="top">
							<th scope="row">Name</th>
							<td><input type="text" name="name" value="<?php echo $myrows[0]->name;?>"/></td>
							</tr>
							 
							<tr valign="top">
							<th scope="row">Website</th>
							<td><input type="text" name="website" value="<?php echo $myrows[0]->url;?>"/></td>
							</tr>
				
							<tr valign="top">
							<th scope="row">Info</th>
							<td><input type="textbox" name="info" value="<?php echo $myrows[0]->info;?>"/></td>
							</tr>

			<?php
			// Add code for upload field

			function my_admin_scripts() { // function to load scripts
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_register_script('my-upload', WP_PLUGIN_URL.'/datafeedr-ads/my-script.js', array('jquery','media-upload','thickbox'));
			wp_enqueue_script('my-upload');
			}

			function my_admin_styles() {
			wp_enqueue_style('thickbox');
			}

			if (isset($_GET['page']) && $_GET['page'] == 'flipping_team') {
			add_action('admin_print_scripts', 'my_admin_scripts'); // load own javascripts
			add_action('admin_print_styles', 'my_admin_styles'); // load own css
			}
			?>
						<tr valign="top">
						<th scope="row">Upload Image</th>
						<td><label for="upload_image">
						<input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo $myrows[0]->imageloc;?>"/>
						<input id="upload_image_button" type="button" value="Upload Image" />
						<br />Enter an URL or upload an image for the banner.
						</label></td>
						</tr>

						<div class="clear"> </div>

						</table>	
						<p class="submit">
						<input type="submit" class="button-secondary" value="<?php _e('Save Changes') ?>" />
						</p>
			<!--			<a onclick="return false;" title="Upload image" class="thickbox" id="add_image" href="media-upload.php?type=image&amp;TB_iframe=true&amp;width=640&amp;height=105">Upload Image</a> -->

					</form>
					</div>
<?		
}
else if(isset($_REQUEST['deleteid']))
{
			$id=$_REQUEST['deleteid'];
?>
			<div class="wrap">
			<?php
			if($_REQUEST['action']=="delete")
			{
				global $wpdb;
				$table_name = $wpdb->prefix . "team";
				$wpdb->show_errors();
				$rows_affected = $wpdb->query("DELETE FROM $table_name WHERE id=$id");
				echo "<br/><br/>".$rows_affected. " row deleted.<br/><br/><br/>";
			}
?>
			<br/>
			<h2 align='center'>View All Team Members</h2><br/>
			<?php
			global $wpdb;
		 	$wpdb->show_errors();
			$table_name =  $wpdb->prefix. "team";
			$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );
			//print_r($myrows[0]->id);
		?>
			<style>
			tr {
				font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
				sans-serif;
				color: #6D929B;
				border-right: 1px solid #C1DAD7;
				border-bottom: 1px solid #C1DAD7;
				border-top: 1px solid #C1DAD7;
				letter-spacing: 2px;
				text-align: left;
				padding: 6px 6px 6px 12px;
				background: #CAE8EA url(images/bg_header.jpg) no-repeat;
			}

		td {
			border-right: 1px solid #C1DAD7;
			border-bottom: 1px solid #C1DAD7;
			background: #fff;
			padding: 6px 6px 6px 12px;
			color: #6D929B;
		}

			</style>
			<table>
			<tr height="50px">
			<td align="center">Id</td>
			<td align="center">Name</td>
			<td align="center">Website URL</td>
			<td align="center">Image</td>
			<td align="center">Info</td>
			<td align="center">Edit</td>
			<td align="center">Delete</td>
			</tr>
			<?php foreach ($myrows as $row)
			{
				echo "<tr>";
				echo "<td>".$row->id."</td>";
				echo "<td>".$row->name."</td>";
				echo "<td>".$row->url."</td>";
				echo "<td><img src='../".$row->imageloc."' width='40px' height='40px'></img></td>";
				echo "<td>".$row->info."</td>";
				//$action="admin.php?page=flipping-menu&action=edit&editid=".$row->id;
				?>
				<?php echo $action; ?>
				<td>
					<form action="admin.php">
						<input type='hidden' name='page' value="team_view"/>
						<input type='hidden' name='action' value="edit"/>
						<input type='hidden' name='editid' value="<?php echo $row->id;?>"/>
						<input type='submit' class='button-secondary' value='Edit' />
					</form>
				</td>
				<td>
					<form action="admin.php">
						<input type='hidden' name='page' value="team_view"/>
						<input type='hidden' name='action' value="delete"/>
						<input type='hidden' name='deleteid' value="<?php echo $row->id;?>"/>
						<input type='submit' class='button-secondary' value='Delete' />
					</form>
				</td>
				</tr>
		<?}?>	
			</table>
<?php }	

else
{
?>
	<div class='wrap'>
	<br/>
	<h2 align='center'>View All Team Members</h2><br/>
	<?php
	global $wpdb;
 	$wpdb->show_errors();
	$table_name =  $wpdb->prefix. "team";
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );
	//print_r($myrows[0]->id);
?>
	<style>
	tr {
		font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
		sans-serif;
		color: #6D929B;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-align: left;
		padding: 6px 6px 6px 12px;
		background: #CAE8EA url(images/bg_header.jpg) no-repeat;
	}

td {
	border-right: 1px solid #C1DAD7;
	border-bottom: 1px solid #C1DAD7;
	background: #fff;
	padding: 6px 6px 6px 12px;
	color: #6D929B;
}

	</style>
	<table>
	<tr height="50px">
	<td align="center">Id</td>
	<td align="center">Name</td>
	<td align="center">Website URL</td>
	<td align="center">Image</td>
	<td align="center">Info</td>
	<td align="center">Edit</td>
	<td align="center">Delete</td>
	</tr>
	<?php foreach ($myrows as $row)
	{
		echo "<tr>";
		echo "<td>".$row->id."</td>";
		echo "<td>".$row->name."</td>";
		echo "<td>".$row->url."</td>";
		echo "<td><img src='../".$row->imageloc."' width='40px' height='40px'></img></td>";
		echo "<td>".$row->info."</td>";
		//$action="admin.php?page=flipping-menu&action=edit&editid=".$row->id;
		?>
		<?php echo $action; ?>
		<td>
			<form action="admin.php">
				<input type='hidden' name='page' value="team_view"/>
				<input type='hidden' name='action' value="edit"/>
				<input type='hidden' name='editid' value="<?php echo $row->id;?>"/>
				<input type='submit' class='button-secondary' value='Edit' />
			</form>
		</td>
		<td>
			<form action="admin.php">
				<input type='hidden' name='page' value="team_view"/>
				<input type='hidden' name='action' value="delete"/>
				<input type='hidden' name='deleteid' value="<?php echo $row->id;?>"/>
				<input type='submit' class='button-secondary' value='Delete' />
			</form>
		</td>
		</tr>
<?} } ?>	
	</table>
<?php
}
