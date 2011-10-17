<?php
/*
Plugin Name: wp-track
Plugin URI: http://geekfix.net/wp-track
Description: Manage Tasks, Projects & Milestones
Version: 1.0
Author: louisnorthmore
Author URI: http://www.northmore.net
License: GPL2
*/


//create post type for projects
add_action( 'init', 'track_create_post_type' );
function wp_track_create_post_type() {
	register_post_type( 'track-projects',
		array(
			'labels' => array(
				'name' => __( 'Projects' ),
				'singular_name' => __( 'Project' )
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'projects')
		)
	);
register_post_type( 'track-tasks',
		array(
			'labels' => array(
				'name' => __( 'Tasks' ),
				'singular_name' => __( 'Tasks' )
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'tasks')
		)
	);
register_post_type( 'track-bugs',
		array(
			'labels' => array(
				'name' => __( 'Bugs' ),
				'singular_name' => __( 'Bugs' )
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'bugs')
		)
	);
}
?>
