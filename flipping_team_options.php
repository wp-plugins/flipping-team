<?php
/*
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

// Plugin options
// Option page is added in flipping_team_admin.php

define ( "OPT_USE_FLIPPING_EFFECT", "team_use_flipping_effect" );

function flipping_team_opt_use_flipping_effect() {
	return get_option( OPT_USE_FLIPPING_EFFECT, 1 );
}

/** Flipping team config screen */
function flipping_team_settings()
{
	// Save parameters
	if ( isset( $_REQUEST['team_use_flipping_effect'] ) ) {
		update_option( "team_use_flipping_effect",
		            $_REQUEST['team_use_flipping_effect'] );
	}
	/*if ( isset( $_REQUEST['team_size'] ) ) {
		add_option( "team_size", $_REQUEST['team_size'], '', 'yes' );
	}
	if ( isset( $_REQUEST['team_title'] ) )	{
		add_option( "team_title", $_REQUEST['team_title'], '', 'yes' );
	}
	if ( isset( $_REQUEST['if_team_sidebar'] ) ) {
		add_option( "if_team_sidebar", $_REQUEST['if_team_sidebar'], '', 'yes' );
	}*/
?>
	<div class="wrap">
		<h2><?php _e( 'Flipping Team' , 'flpt' ); ?></h2>

		<form method="post" action="admin.php?page=flipping-team-settings">
			<table class="form-table">
				<!--<tr valign="top">
					<th scope="row">Size of Thumbnails</th>
					<td><input type="text" name="team_size" value="<?php echo get_option('team_size'); ?>" /></td>
				</tr>
			
				<tr valign="top">
					<th scope="row">Title</th>
					<td><input type="text" name="team_title" value="<?php echo get_option('team_title'); ?>" /></td>
				</tr>
			
				<tr valign="top">
					<th scope="row">Add Sidebar</th>
					<td>
						<select>
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</td>
				</tr>-->
				<tr valign="top">
					<th scope="row"><?php _e( 'Use flipping effect', 'flpt' ); ?></th>
					<td>
						<select name="<? echo esc_attr( OPT_USE_FLIPPING_EFFECT ); ?>">
							<option value="1" <?php if ( flipping_team_opt_use_flipping_effect() ) echo 'selected="true"'; ?>><?php _e( 'Yes' ); ?></option>
							<option value="0" <?php if ( ! flipping_team_opt_use_flipping_effect() ) echo 'selected="true"'; ?>><?php _e( 'No' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
		
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
</div>
<?php
}
?>