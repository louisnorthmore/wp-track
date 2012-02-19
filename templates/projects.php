<?php
/*
WP_TRACK - PROJECTS TEMPLATE
*/
?>

<?php get_header() ?>
<style>
.updated {
font-size: 14px;
font-weight: bold;
background-color:#FFFFE0;
border: 1px #E6DB55 solid;
padding: 15px;
-moz-border-radius: 8px;
border-radius: 8px;
}
</style>
<?
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] )) {

	// Do some minor form validation to make sure there is content
	if (isset ($_POST['name'])) {
		$name =  $_POST['name'];
	} else {
		echo 'Please enter a name';
	}
	if (isset ($_POST['description'])) {
		$description1 = $_POST['description'];
	} else {
		echo 'Please enter the content';
	}
    
	wp_get_current_user();
	$user = $current_user->user_login;
	
	$project_name = $_POST['project_name'];
	// Add the content of the form to $post as an array
	$post = array(
		'post_title'	=> $name,
		'post_content'	=> $description1,
		'post_status'	=> 'publish',			// Choose: publish, preview, future, etc.
		'comment_status' => 'open',
		'post_type'	=> $_POST['post_type']
	);
	$postid = wp_insert_post($post); 
	
	wp_set_object_terms($postid, 'Medium', 'priority');
	wp_set_object_terms($postid, 'Current', 'milestone');
	wp_set_object_terms( $postid, 'Pending', 'status');
	wp_set_object_terms( $postid, 'FCC Client', 'projects');
	echo "<div class='updated'>Thanks $user! <br/>Bug ($postid) created and is status pending. It will be reviewed shortly.</div>";
	mail('admin@funchatcam.com',"$user submitted a bug - $name","$user submitted a bug - $name");
	
} // end IF
?>

<script type='text/javascript'>
function addbug() {
document.getElementById('AddBug').style.display = 'block'; 
document.getElementById('tasks').style.display = 'none'; 
}
</script>

	<div id="content" class="fullwidth">
		<div class="padder">

		<div class="page" id="blog-page">

		<?php if (is_single()) { ?> 
		
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<h1 class="pagetitle"><a href='/projects/'>Projects</a> > <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				
				
				
				<div class="post" id="post-<?php the_ID(); ?>">

					<div class="entry">
					
<p>
Description: <?php echo get_the_content(); ?><br />
Created: <?php the_date('jS F Y g:ia (e)'); ?><br />
				Author: <?php the_author(); ?><br />
				<a target="_blank" title="Edit Project" alt="Edit Project" href="/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit">Edit Project</a>
				</p>
		<div id="tasks" name="tasks">
		<?php list_project_tasks(get_the_title()); ?>
		</div>
		
		<div id="Bugs" name="bugs">
		
		<div id="AddBug" style="display: none">
		<h3>Add New Bug</h3>
		<form id="new_post" name="new_post" method="post" action="">

<p><label for="name">Name</label><br />

<input type="text" id="name" value="" tabindex="1" size="20" name="name" />

</p>

<p><label for="description">Description</label><br />

<textarea id="description" tabindex="3" name="description" cols="50" rows="6"></textarea>

</p>

<p><input type="submit" value="Submit Bug" tabindex="6" id="submit" name="submit" /></p>

<input type="hidden" name="post_type" id="post_type" value="track-bugs" />
<input type="hidden" name="action" value="post" />
<input type="hidden" name="project_name" value="<?php echo get_the_title(); ?>" />

<?php wp_nonce_field( 'new-post' ); ?>

</form>
		</div>
		
		<?php list_project_bugs(get_the_title()); ?>
		</div>

<?php wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', 'buddypress' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>
		

					</div>

				</div>

			<?php endwhile; endif; ?>


<?php } else { ?> <!-- end single -->

<h1 class="pagetitle">Projects</h1>
<!-- List all projects, we're not on single yet -->
<div class="post" id="post-<?php the_ID(); ?>">
<div class="entry">
<table class="wp-list-table widefat fixed posts" cellspacing="0">
<th>Name</th><th>Author</th><th>Tasks</th><th>Bugs</th>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<tr>
<td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
<td><?php the_author(); ?></td>
<td><?php count_posts('track-tasks', get_the_title()) ?></td>
<td><?php count_posts('track-bugs', get_the_title()) ?></td>
</tr>
		</div>

			<?php endwhile; endif; ?>
</table>
</div>
</div>
<?php } ?> <!-- end not single -->
		</div><!-- .page -->

		</div><!-- .padder -->
	</div><!-- #content -->
<?php get_footer(); ?>
