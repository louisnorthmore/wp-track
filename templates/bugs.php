<?php
/*
WP_TRACK - BUGS TEMPLATE
*/
?>

<?php get_header() ?>
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
                <h1 class="pagetitle">Entries for <?php echo $_GET['project'] ?></h1>
                <?php }   else { ?>
                <h1 class="pagetitle">Bugs</h1>
                <?php }
            ?>
<div class="post" id="post-<?php the_ID(); ?>">
<div class="entry">
<table class="wp-list-table widefat fixed posts" cellspacing="0">
<th>Name</th><th>Project</th><th>Author</th><th>Created</th><th>Priority</th>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<tr class="<?php get_post_type( get_the_id() ); ?>">
<td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>

<td>
<?php echo strip_tags( get_the_term_list( get_the_id(), 'projects', '', ', ', '' ) ); ?>
</td>
<td><?php the_author(); ?></td>
<td><?php the_date('jS F Y g:ia (e)'); ?></td>
<td><?php echo strip_tags( get_the_term_list( get_the_id(), 'priority', '', ', ', '' ) ); ?></td>
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
