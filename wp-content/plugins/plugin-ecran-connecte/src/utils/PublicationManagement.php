<?php

namespace Utils;

use WP_Query;

class PublicationManagement{

	function __construct(){

	}

	public function get_page_by_title($title) {
		// Define the arguments what to retrieve.
		$args = array(
			'post_type' => 'post',
			'title' => $title,
			'post_status' => 'publish',
			'posts_per_page' => 1
		);

		// Execute the WP Query.
		$my_query = new WP_Query($args);

		// Start a loop.
		if ( $my_query->have_posts() ) : while ( $my_query->have_posts() ) : $my_query->the_post();

			the_title();
			the_content();

		endwhile;
		endif;

		// Reset to the main query (IMPORTANT).
		wp_reset_postdata();
	}
}
