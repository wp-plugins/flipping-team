<?php
/*
	Flipping Team
	Copyright 2011-2012 Abhishek Gupta (email : abhishekgupta.iitd@gmail.com)
	                    Cédric Houbart (email : cedric@scil.coop)

    This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Setup admin part, views and actions. Requires shortcode

// create custom plugin settings menu
add_action( 'admin_menu', 'flipping_team_create_menu' );

function flipping_team_admin_scripts() {
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'jquery' );
}
add_action( 'admin_print_scripts', 'flipping_team_admin_scripts' );

function flipping_team_admin_styles() {
	wp_enqueue_style( 'thickbox' ); // For media library
	wp_enqueue_style( 'flipping-team', plugins_url( '/flipping-team.css', __FILE__ ) );

}
add_action( 'admin_print_styles', 'flipping_team_admin_styles' );


/** Hook the menu to functions */
function flipping_team_create_menu() {

	//create new top-level menu
	add_menu_page( __( 'Flipping Team', "flpt" ),
	               __( 'Flipping Team', "flpt" ),
	               'edit_pages',
	               'flipping-team',
	               'flipping_team_view',
	               plugins_url( '/images/icon.png', __FILE__ ) );
	// Settings page
	add_submenu_page( 'options-general.php',
	                  __( 'Flipping Team', 'flpt' ),
	                  __( 'Flipping Team', 'flpt' ),
	                  'administrator',
	                  'flipping-team-settings',
	                  'flipping_team_settings' );
	// Add member page
	add_submenu_page( 'flipping-team',
	                  __( 'Add', 'flpt' ),
	                  __( 'Add', 'flpt' ),
	                  'edit_pages',
	                  'flipping-team-member',
	                  'flipping_team_member' );

}

/** Add/edit member screen */
function flipping_team_member() {
	/* Get requested action from 'action' parameter
	   insert: request a new member, no prefilled values
	   edit: edit an existing member, show current values
	   update: member edited, save new values in database
	*/
?>
		<div class="wrap">
		
			<?php
			$creating = $_REQUEST['action'] != "edit"
			            && $_REQUEST['action'] != "update"; // esle editing
			// Perform data collection and database updates if requested
			if( isset($_REQUEST['action']) ) {
				if ( $_REQUEST['action'] == "insert"
				     || $_REQUEST['action'] == "update" ) {
					// Read data
					$name  = $_REQUEST['name'];
					$website = $_REQUEST['website'];
					$info = $_REQUEST['info'];
					$info = str_replace( "\'", "'", $info );
					$imageloc = $_REQUEST['upload_image'];
				}
				switch ( $_REQUEST['action'] ) {
				case "insert":
					$added = flipping_team_add( $name, $info,
				                            array( "website" => $website,
				                                   "image"   => $imageloc ) );
					if ( $added ) {
						$msg = sprintf( __( "%s added to the team.", 'flpt' ), $name );
					} else {
						$error = __( 'Unable to create member.', 'flpt' );
					}
					break;
				case "update":
					$id = $_REQUEST['editid'];
					$updated = flipping_team_edit( $id, $name, $info,
					                               array( "website" => $website,
					                                      "image"   => $imageloc ) );
					if ( $updated ) {
						$msg = sprintf( __( "%s edited." ), $name );
					} else {
						$error = __( 'Unable to edit member.', 'flpt' );
					}
					break;
				case "edit":
					$id = $_REQUEST['editid'];
					$member = flipping_team_get( $id );
					$name  = $member['name'];
					$website = $member['website'];
					$info = $member['info'];
					$imageloc = $member['image'];
					break;
				}
			}
			?>
			<h2>
				<?php
				if ( $creating ) {
					_e( 'Add Member to Flipping Team', 'flpt' );
					$form_action = "admin.php?page=flipping-team-member&action=insert";
				} else {
					_e( 'Edit member', 'flpt');
					$form_action = "admin.php?page=flipping-team-member&action=update&editid=" . $id;
				}
				?>
			</h2>
			<?php if ( $error ) { ?>
			<div class="error"><?php echo $error; ?></div>
			<?php } ?>
			<?php if ( $msg ) { ?>
			<div class="updated"><?php echo $msg; ?></div>
			<?php } ?>

			<form method="post" action="<?php echo $form_action; ?>">
				<div id="poststuff">
				<table class="form-table">
					<tr>
						<th valign="top" scope="row">
							<label for="name"><?php _e( 'Name' ); ?></label>
						</th>
						<td>
							<input type="text" name="name" value="<?php echo $name; ?>" />
						</td>
					</tr>
					
					<tr>
						<th valign="top" scope="row">
							<label for="website"><?php _e( 'Website' ); ?></label>
						</th>
						<td>
							<input type="text" name="website" value="<?php echo $website; ?>" />
						</td>
					</tr>
				
					<tr>
						<th valign="top" scope="row">
							<label for="upload_image"><?php _e( 'Image', 'flpt' ); ?></label>
						</th>
						<td>
							<input id="upload_image" type="hidden" size="36" name="upload_image" value="<?php echo( $imageloc ); ?>" />
							<img id="member_image" src="<?php echo $imageloc; ?>" />
							<input id="upload_image_button" type="button" value="<?php _e( 'Choose image', 'flpt' ); ?>" />
						</td>
					</tr>
				
					<tr>
						<th valign="top" scope="row" colspan="2">
							<label for="info"><?php _e( 'Info', 'flpt' ); ?></label>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php
							the_editor( $info, 'info', "prev", false, 1 );
							?>
						</td>
					</tr>

					<div class="clear"> </div>

				</table>
				<p class="submit">
					<input type="submit" class="button-secondary" value="<?php _e('Save Changes') ?>" />
				</p>
				</div>
			</form>
		</div>
		<?php
			$media_lib_url = plugins_url( '/media_library.js', __FILE__ );
			echo( '<script type="text/javascript" src="' . $media_lib_url . '"></script>' );
}

/** Lis all members with edit buttons */
function flipping_team_view() {
	if( isset( $_REQUEST['deleteid'] ) ) {
		$id = $_REQUEST['deleteid'];
		$deleted = flipping_team_delete( $id );
		if ( $deleted ) {
			$msg = __('Member deleted.', 'flpt' );
		} else {
			$error = __( 'Unable to delete member.', 'flpt' );
		}
	}
?>
	<div class='wrap'>
		<h2><?php _e( 'View All Team Members', 'flpt' ); ?> <a class="add-new-h2" href="admin.php?page=flipping-team-member"><?php _e( 'Add', 'flpt'); ?></a></h2>
		<?php if ( $error ) { ?>
		<div class="error"><?php echo $error; ?></div>
		<?php } ?>
		<?php if ( $msg ) { ?>
		<div class="updated"><?php echo $msg; ?></div>
		<?php } ?>
<?php

	$members = flipping_team_get_all( "alpha" );
	if ( count( $members ) > 0 ) {
?>
		<ul class="team-members-list">
<?php
		foreach ($members as $member) {
			echo flipping_team_display_one( $member, flipping_team_opt_use_flipping_effect() );
?>
			<li class="flipping-team-tool-bar">
				<form action="admin.php">
					<input type='hidden' name='page' value="flipping-team-member"/>
					<input type='hidden' name='action' value="edit"/>
					<input type='hidden' name='editid' value="<?php echo $member['id'];?>"/>
					<input type='submit' class='button-secondary' value='<?php _e( 'Edit' ); ?>' />
				</form>
				<form action="admin.php">
					<input type='hidden' name='page' value="flipping-team"/>
					<input type='hidden' name='action' value="delete"/>
					<input type='hidden' name='deleteid' value="<?php echo $member['id'];?>"/>
					<input type='submit' class='button-secondary' value='<?php _e( 'Delete' ); ?>' />
				</form>
			</li>
<?
		}
	} else {
?>
		<p><?php _e( 'There is currently no member in your team', 'flpt' ); ?></p>
<?php
	}
?>
		</ul>
	</div>
<?php
}
?>
