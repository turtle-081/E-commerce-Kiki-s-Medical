<?php

	propharm_enovathemes_global_variables();

	$blog_single_sidebar   = (isset($GLOBALS['propharm_enovathemes']['blog-single-sidebar']) && $GLOBALS['propharm_enovathemes']['blog-single-sidebar']) ? $GLOBALS['propharm_enovathemes']['blog-single-sidebar'] : "none";

	$class = array();

	if (is_active_sidebar('blog-single-widgets') && $blog_single_sidebar == "none" && !defined('ENOVATHEMES_ADDONS')) {
		$blog_single_sidebar = 'right';
	}

	if ($blog_single_sidebar != "none") {
		$class[] = 'sidebar-active';
	}

	$class[] = 'post-layout';
	$class[] = 'blog-layout-single';
	$class[] = 'layout-sidebar-'.$blog_single_sidebar;
	$class[] = 'lazy lazy-load';

?>
<div id="et-content" class="content et-clearfix padding-false">
	<div class="<?php echo implode(' ', $class); ?>">
		<div class="container">
			<?php if ($blog_single_sidebar == "left"): ?>
				<div class="blog-sidebar layout-sidebar single et-clearfix">
					<?php get_sidebar('single'); ?>
				</div>
				<div class="blog-content layout-content et-clearfix">
					<?php get_template_part( '/includes/blog/content-blog-single-code' ); ?>
				</div>
			<?php elseif ($blog_single_sidebar == "right"): ?>
				<div class="blog-content layout-content et-clearfix">
					<?php get_template_part( '/includes/blog/content-blog-single-code' ); ?>
				</div>
				<div class="blog-sidebar layout-sidebar single et-clearfix">
					<?php get_sidebar('single'); ?>
				</div>
			<?php else: ?>
				<?php get_template_part( '/includes/blog/content-blog-single-code' ); ?>
			<?php endif ?>
		</div>
		<div class="nav-container">
			<div class="container et-clearfix">
				<?php propharm_enovathemes_post_nav('post',get_the_ID()); ?>
			</div>
		</div>
		<?php get_template_part( '/includes/blog/content-blog-related-posts' ); ?>
	</div>
</div>