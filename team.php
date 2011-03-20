<?php
/**
 * Template Name: Team Page
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
	<link rel="stylesheet" type="text/css" href="wp-content/plugins/flipping_team/styles.css" />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="wp-content/plugins/flipping_team/jquery.flip.min.js"></script>

	<script type="text/javascript" src="wp-content/plugins/flipping_team/script.js"></script>

	<div id="container">
		<div id="content" role="main">

	<h1><?php echo get_option('team_title'); ?></h1>
<?php
	/* Queue the first post, that way we know who
	 * the author is when we try to get their name,
	 * URL, description, avatar, etc.
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	if ( have_posts() )
		the_post();
	 */
?>

<?php

global $wpdb;
				$table_name = $wpdb->prefix . "team";

				$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );

// Each sponsor is an element of the $sponsors array:
$sponsors = array();

foreach($myrows as $row)
{
	array_push($sponsors, array($row->name, $row->info, $row->url, $row->imageloc));
}
// Randomizing the order of sponsors:

shuffle($sponsors);

?>



<div id="main">

	<div class="sponsorListHolder">

		
        <?php
			
			// Looping through the array:
			
			foreach($sponsors as $company)
			{
				echo'
				<div class="sponsor" title="Click to flip">
					<div class="sponsorFlip">
						<img src="'.$company[3].'" alt="More about '.$company[0].'" />
					</div>
					
					<div class="sponsorData">
						<div class="sponsorDescription">
							'.$company[1].'
						</div>
						<div class="sponsorURL
							<a href="'.$company[2].'">'.$company[2].'</a>
						</div>
					</div>
				</div>
				
				';
			}
		
		?>
    </div>

</div>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
