<?php
/*
WP_TRACK - TASKS TEMPLATE
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

			<?php endwhile; endif; ?>

    <!-- Comments -->
    <?php comments_template(); ?>
    <!-- Comments -->


<?php
    } else { ?> <!-- end single --></div><div class="page">

<?php
    if ($_GET['project']) { ?>
    </div>
<?php } ?> <!-- end not single -->
		</div><!-- .page -->

		</div><!-- .padder -->
	</div><!-- #content -->
<?php get_footer(); ?>