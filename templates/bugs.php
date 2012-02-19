<?php
/*
WP_TRACK - BUGS TEMPLATE
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
if( $_POST['action'] == 'post') {

    // Do some minor form validation to make sure there is content
    if (empty($_POST['name'])) {
        echo "Your bug needs a name!";
        die();
    }
    if (empty($_POST['description'])) {
        echo "Your bug needs a description!";
        die();
    }

    wp_get_current_user();
    $user = $current_user->user_login;

    //$project_name = $_POST['project_name'];
    // Add the content of the form to $post as an array
    $post = array(
        'post_title'	=> wp_strip_all_tags($name),
        'post_content'	=> wp_strip_all_tags($description1),
        'post_status'	=> 'pending',			// Choose: publish, preview, future, etc.
        'comment_status' => 'open',
        'post_type'	=> $_POST['post_type']
    );

    echo "Adding bug from $user: <br>Title: $name<br>Description: $description1";

    //$postid = wp_insert_post($post, true);

    //wp_set_object_terms($postid, 'Medium', 'priority');
    //wp_set_object_terms($postid, 'Current', 'milestone');
    //wp_set_object_terms( $postid, 'Pending', 'status');

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

				<h1 class="pagetitle"><a href='/bugs/'>Bugs</a> > <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				
				
				
				<div class="post" id="post-<?php the_ID(); ?>">
<?php $status = strip_tags( get_the_term_list( get_the_id(), 'priority', '', ', ', '' ) ); ?>
					<div class="entry">
					
<p>
Created: <?php the_date('jS F Y g:ia (e)'); ?><br />
				Submitter: <?php the_author(); ?><br />
				Priority: <?php echo strip_tags( get_the_term_list( get_the_id(), 'priority', '', ', ', '' ) ); ?><br />
                Status: <?php echo strip_tags( get_the_term_list( get_the_id(), 'status', '', ', ', '' ) ); ?> <?php //bug_status_actions($status) ?><br />
				<a target="_blank" title="Edit Bug" alt="Edit Bug" href="/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit">Edit Bug</a>
				</p>
		
<p><?php the_content(); ?></p>
                        <!-- Comments -->
                        <?php comments_template(); ?>
                        <!-- Comments -->
					</div>

				</div>

			<?php endwhile; endif; ?>


<?php } else { ?> <!-- end single -->

            <?php
            if ($_GET['project']) { ?>
                <?php $project = $_GET['project']; ?>
                <h1 class="pagetitle">Entries for <?php echo $project; ?></h1>
                <p><?php the_content(); ?></p>
                <?php }   else { ?>
                <h1 class="pagetitle">Bugs</h1>
                <?php }
            ?> <a href="<?php $_SERVER['php_self'] ?>">Refresh</a>
            <?php add_bug_button(); ?>
            <div id="Bugs" name="bugs">

                <div id="AddBug" style="display: none">
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

                        <?php wp_nonce_field( 'new-post' ); ?>

                    </form>
                </div>

<div class="post" id="post-<?php the_ID(); ?>">
<div class="entry">
<table class="wp-list-table widefat fixed posts" cellspacing="0">
<th>Name</th><th>Project</th><th>Author</th><th>Created</th><th>Priority</th><th>Status</th><th>Actions</th>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <tr class="<?php echo get_post_type(get_the_id()); ?>">
        <td>
            <?php if (get_post_type(get_the_id()) == 'track-tasks') {
            $img = '/wp-content/plugins/wp-track/icons/tick.png';
            $title = 'Task';
        } else {
            $img = '/wp-content/plugins/wp-track/icons/bug.png';
            $title = 'Bug';
        }?>
            <?php echo "<img title=\"$title\" src=\"$img\" />"; ?>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>

<td>
<?php echo strip_tags( get_the_term_list( get_the_id(), 'projects', '', ', ', '' ) ); ?>
</td>
<td><?php the_author(); ?></td>
        <td><?php the_date('jS M Y g:ia'); ?></td>
<td><?php echo strip_tags( get_the_term_list( get_the_id(), 'priority', '', ', ', '' ) ); ?></td>
<td><?php echo strip_tags( get_the_term_list( get_the_id(), 'status', '', ', ', '' ) ); ?></td>
<td>
    <a target="_blank" title="Edit Bug" alt="Edit Bug" href="/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit">Edit</a>
    <!-- <a target="_blank" title="Delete Bug" alt="Delete Bug" href="/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=trash">Delete</a> -->
</td>
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
<?php get_footer() ?>
