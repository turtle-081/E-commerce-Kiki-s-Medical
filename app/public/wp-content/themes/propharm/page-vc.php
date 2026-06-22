<?php /* Template Name: Visual Composer */ ?>
<?php get_header(); ?>
<?php do_action('propharm_enovathemes_title_section'); ?>
<!-- content start -->
<div id="et-content" class='content et-clearfix'>
	<?php while ( have_posts() ) : the_post(); ?>
		<!-- post start -->
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<main class="page-content et-clearfix">
				<?php the_content(); ?>
			</main>
		</div>
		<!-- post end -->
	<?php endwhile; ?>
</div>
<!-- content end -->
<?php get_footer(); ?>