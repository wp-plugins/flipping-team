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

function flipping_team_flip_scripts() {
	wp_enqueue_script( 'flipping_team_flip', plugins_url( '/jquery.flip.min.js', __FILE__ ) );
	wp_enqueue_script( 'flipping_team_flip2', plugins_url( '/flipping-script.js', __FILE__ ) );
}
if ( flipping_team_opt_use_flipping_effect() ) {
	add_action( 'wp_print_scripts', 'flipping_team_flip_scripts' );
}

function flipping_team_display_one( $member, $flipping_effect ) {
	if ( ! $flipping_effect ) {
		// No flip display
		$output = "<li class=\"team-member\">";
		// Show image if set
		if ( isset( $member['image'] ) && $member['image'] != "" ) {
			$output .= "<img class=\"team-member-photo\" src=\"";
			$output .= $member['image'].'" alt="'.$member['name'].'" />';
		}
		// Show name and if set website
		$output .= "<h2 class=\"team-member-name\">".$member['name']."</h2>";
		if ( isset( $member['website'] ) && $member['website'] != "" ) {
			$url = $member['website'];
			if ( substr( $url, 0, 7 ) != "http://" ) {
				$url = "http://" . $url;
			}
			$output .= '<div class="team-member-website">';
			$output .= '<a href="' . $url . '">' . $member['website'] . '</a>';
			$output .= '</div>';
		}
		// Show description
		$output .= '<div class="team-member-info">';
		$output .= wpautop($member['info']);
		$output .= '</div>';
		$output .= "<div style=\"clear:both;\"></div>";
		$output .= '</li>';
		return $output;
	} else {
		// Flip display
		$output = '<li class="team-member team-member-flip" data-id="' . $member['id'] . '" title="' . __( 'Click to flip', 'flpt' ) . '">';
		if ( isset( $member['image'] ) && $member['image'] != "" ) {
			$output .= "<img class=\"team-member-photo\" src=\"";
			$output .= $member['image'].'" alt="'.$member['name'].'" />';
		}
		// Show name and if set website
		$output .= "<h2 class=\"team-member-name\">".$member['name']."</h2>";
		if ( isset( $member['website'] ) && $member['website'] != "" ) {
			$url = $member['website'];
			if ( substr( $url, 0, 7 ) != "http://" ) {
				$url = "http://" . $url;
			}
			$output .= '<div class="team-member-website">';
			$output .= '<a href="' . $url . '">' . $member['website'] . '</a>';
			$output .= '</div>';
		}
		$output .= "<div style=\"clear:both;\"></div>";
		$output .= "</li>";
		// Set back display (flip data)
		$output .= '<div class="team-member-data" data-id="' . $member['id'] . '" style="display:none;">';
		$output .= '<div class="team-member-info">' . wpautop( $member['info'] ) .'</div>';
		$output .= "<div style=\"clear:both;\"></div>";
		$output .= '</div>';
		return $output;
	}
}

/** Shortcode function
 * @param $atts not used
 */
function flipping_team_shortcode( $atts ) {

	$members = flipping_team_get_all( "alpha" );

	$output .= '<ul class="team-members-list">';
	// Looping through the array:
	foreach( $members as $member ) {
		$output .= flipping_team_display_one( $member, flipping_team_opt_use_flipping_effect() );
	}
	$output .= '</ul>';
	return $output;
}

// Register shortcode
add_shortcode( 'flipping_team', 'flipping_team_shortcode' ); // Generic
add_shortcode( 'team', 'flipping_team_shortcode' );          // EN
add_shortcode( 'équipe', 'flipping_team_shortcode' );        // FR
add_shortcode( 'मंडली', 'flipping_team_shortcode' );        // Hindi
?>
