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

/* Definition of shortcode to include team template in a post or page */

function flipping_team_display_one( $member ) {
	$output = "<li class=\"team-member\">";
	if ( isset( $member['image'] ) && $member['image'] != "" ) {
		$output .= "<img class=\"team-member-photo\" src=\"";
		$output .= $member['image'].'" alt="'.$member['name'].'" />';
	}
	$output .= "<h2 class=\"team-member-name\">".$member['name']."</h2>";
	$output .= '<div class="team-member-info">';
	$output .= wpautop($member['info']);
	$output .= '</div>';
	$output .= "<div style=\"clear:both;\"></div>";
	$output .= '</li>';
	return $output;
}

/** Shortcode function
 * @param $atts not used
 */
function flipping_team_shortcode( $atts ) {

	$members = flipping_team_get_all( "alpha" );

	$output .= '<ul class="team-members-list">';
	// Looping through the array:
	foreach( $members as $member ) {
		$output .= flipping_team_display_one( $member );
	}
	$output .= '</ul>';
	return $output;
}

// Register shortcode
add_shortcode( 'flipping_team', 'flipping_team_shortcode' ); // Generic
add_shortcode( 'team', 'flipping_team_shortcode' );          // EN
add_shortcode( 'équipe', 'flipping_team_shortcode' );        // FR
?>
