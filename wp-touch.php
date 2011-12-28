<?php
/*
Plugin Name: wp-track
Plugin URI: http://geekfix.net/wp-track
Description: Manage Tasks & Projects
Version: 1.0
Author: louisnorthmore
Author URI: http://www.northmore.net
License: GPL2
*/

define( 'MY_PLUGIN_TEMPLATES', dirname( __FILE__ ) . '/templates' );

//capabilities
$role = get_role( 'administrator' );
$role->add_cap( 'add_tasks' );
$role->add_cap( 'manage_bugs' );
$role->add_cap( 'manage_tasks' );
$role->add_cap( 'manage_projects' );

//create post type for projects
add_action( 'init', 'track_create_post_type' );
function track_create_post_type() {
	register_post_type( 'track-projects',
		array(
			'labels' => array(
				'name' => __( 'Projects' ),
				'singular_name' => __( 'Project' ),
				'add_new_item' => 'Add Project',
				'edit_item' => 'Edit Project',
				'new_item' => 'New Project',
				'view_item' => 'View Project',
				'search_items' => 'Search Projects'
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'projects'),
		'supports' => array('title','editor','author' )
		)
	);
register_post_type( 'track-tasks',
		array(
			'labels' => array(
				'name' => __( 'Tasks' ),
				'singular_name' => __( 'Task' )
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'tasks'),
		'supports' => array('title','editor','comments')
		)
	);
register_post_type( 'track-bugs',
		array(
			'labels' => array(
				'name' => __( 'Bugs' ),
				'singular_name' => __( 'Bug' )
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'bugs'),
		'supports' => array('title','editor','comments')
		)
	);
}
	
	//taxonomies
    add_action('init', 'build_taxonomies', 0);
	function build_taxonomies() {
	
	//projects for tasks & bugs
	register_taxonomy( 'projects', array('track-tasks','track-bugs'), array( 'hierarchical' => true, 'label' => 'Projects', 'query_var' => true, 'rewrite' => true ) );
	register_taxonomy( 'status', array('track-tasks','track-bugs'), array( 'hierarchical' => true, 'label' => 'Status', 'query_var' => true, 'rewrite' => true ) );
	//register_taxonomy( 'milestone', array('track-tasks','track-bugs'), array( 'hierarchical' => true, 'label' => 'Milestone', 'query_var' => true, 'rewrite' => true ) );
	register_taxonomy( 'priority', array('track-tasks','track-bugs'), array( 'hierarchical' => true, 'label' => 'Priority', 'query_var' => true, 'rewrite' => true ) );
	}


add_action('save_post', 'build_taxonomy');
function build_taxonomy( $post_id ){

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

    if ( 'track-projects' == $_POST['post_type'] ){
        if (!wp_is_post_revision($post_id)){
            if (!term_exists( $_POST["post_title"], 'projects' )){
                $termid = wp_insert_term( $_POST["post_title"], 'projects' );
            }
        }
    }
}	
	
add_filter('template_include', 'track_templates');
function track_templates($template) {
global $post;

if($post->post_type == 'track-projects') {
$template = MY_PLUGIN_TEMPLATES . '/projects.php';
}
if($post->post_type == 'track-bugs') {
$template = MY_PLUGIN_TEMPLATES . '/bugs.php';
}
if($post->post_type == 'track-tasks') {
$template = MY_PLUGIN_TEMPLATES . '/tasks.php';
}
return $template;
}

function list_project_tasks($project_title) {

echo "<h4>Tasks</h4>";
add_task_button(); ?>
<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<th>Name</th><th>Author</th><th>Priority</th><th>Status</th><th colspan="2">Milestone</th>
			<?php

  query_posts( array( 'post_type' => 'track-tasks', 'projects' => $project_title ) );
  if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
			


	<tr>
	<td><?php the_title(); ?></td>
	<td><?php the_author(); ?></td>
	<td><?php echo strip_tags( get_the_term_list( get_the_id(), 'priority', '', ', ', '' ) ); ?>
	</td>
	<td><?php echo strip_tags( get_the_term_list( get_the_id(), 'status', '', ', ', '' ) ); ?>
	</td>
	<!-- <td><?php echo strip_tags( get_the_term_list( get_the_id(), 'milestone', '', ', ', '' ) ); ?> -->
	</td>
	<td><?php task_actions(get_the_id(), get_permalink()); ?></td>
	</tr>

			
<?php endwhile; endif; wp_reset_query(); ?>
</table>
<?php
}

function list_project_bugs($project_title) {

echo "<h4>Bugs</h4>";
add_bug_button(); ?>
<table class="wp-list-table widefat fixed posts" cellspacing="0">
			<th>Name</th><th>Author</th><th>Priority</th><th>Status</th><th colspan="2">Milestone</th> 
			<?php

  query_posts( array( 'post_type' => 'track-bugs', 'projects' => $project_title ) );
  if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<tr>
	<td><?php the_title(); ?></td>
	<td><?php the_author(); ?></td>
	<td><?php echo strip_tags( get_the_term_list( get_the_id(), 'priority', '', ', ', '' ) ); ?>
	</td>
	<td><?php echo $terms_as_text = strip_tags( get_the_term_list( get_the_id(), 'status', '', ', ', '' ) ); ?>
	</td>
	<!-- <td><?php echo $terms_as_text = strip_tags( get_the_term_list( get_the_id(), 'milestone', '', ', ', '' ) ); ?> -->
	</td>
	<td><?php bug_actions(get_the_id(), get_permalink()); ?></td>
	</tr>

			
<?php endwhile; endif; wp_reset_query(); ?>
</table>
<?php
}

function add_task_button() {
if( current_user_can('add_tasks') ) {
echo "<a href='/wp-admin/post-new.php?post_type=track-tasks'>Add Task</a>";
}
}

function add_bug_button() {
if( current_user_can('add_tasks') ) {
echo "<a href='javascript:addbug()'>Add Bug</a>";
}
}

function task_actions($taskid, $link) {
if( current_user_can('manage_tasks') ) {
echo "
<a target='_blank' href='/wp-admin/post.php?post=$taskid&action=edit'>Edit</a>
<a href='$link'>View</a>
";
}
}
function bug_actions($bugid, $link) {
if( current_user_can('manage_bugs') ) {
echo "
<a target='_blank' href='/wp-admin/post.php?post=$bugid&action=edit'>Edit</a>
<a href='$link'>View</a>
";
}
}

function count_posts($type, $project_title) {
$args = array(
    'post_type' => $type,
    'post_status' => 'published',
	'projects' => $project_title
);
echo count( get_posts( $args ) );
}

function bug_status_options($status) {
    if ($status == 'closed') { 
        $actions = array(Re-open);
        echo "<a href='#'>$actions</a>";
    }
    if ($status == 'open') $actions = array(Resolve, Close);
    
    
}

?>