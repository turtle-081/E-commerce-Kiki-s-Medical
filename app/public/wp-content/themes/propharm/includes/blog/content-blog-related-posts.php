<?php
propharm_enovathemes_global_variables();
$blog_related_posts         = (isset($GLOBALS['propharm_enovathemes']['blog-related-posts']) && $GLOBALS['propharm_enovathemes']['blog-related-posts'] == 1) ? "true" : "false";
$blog_related_posts_by      = (isset($GLOBALS['propharm_enovathemes']['blog-related-posts-by']) && $GLOBALS['propharm_enovathemes']['blog-related-posts-by']) ? $GLOBALS['propharm_enovathemes']['blog-related-posts-by'] : "categories";
$blog_image_full            = (isset($GLOBALS['propharm_enovathemes']['blog-image-full']) && $GLOBALS['propharm_enovathemes']['blog-image-full'] == 1) ? "true" : "false";

?>

<?php if ($blog_related_posts == "true"): ?>
	<?php $categories = wp_get_post_categories(get_the_ID());?>
	<?php $tags = wp_get_post_tags(get_the_ID());?>
	<?php if ($categories || $tags): ?>

		<?php

			if ($blog_related_posts_by == "categories") {
				$args = array(
					'post_type'           => 'post',
					'category__in'        => $categories,
					'posts_per_page'      => 6,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'date',
					'post__not_in'        => array($post->ID)
				);
			} else {

				$terms = array();
				foreach ($tags as $tag) {
					array_push($terms, $tag->term_id);
				}
				
				$args = array(
					'post_type'           => 'post',
					'tag__in'             => $terms,
					'posts_per_page'      => 6,
					'ignore_sticky_posts' => 1,
					'orderby'             => 'date',
					'post__not_in'        => array($post->ID)
				);
			}

		    $related_posts = new WP_Query($args);

		    $thumb_size = ($blog_image_full == "true") ? 'full' : 'propharm_600X400';

		?>

		<?php if ($related_posts->have_posts()): ?>


			<div class="related-posts-wrapper medium blog-layout-grid grid et-clearfix">
				<div class="container">
					<h4 class="related-posts-title"><?php echo esc_html__("Related posts", 'propharm'); ?></h4>
					<div id="related-posts" class="related-posts loop-posts only-posts et-carousel et-clearfix" data-columns="3">
						<div class="slides">
							<?php while($related_posts->have_posts()) : $related_posts->the_post(); ?>
								<?php echo propharm_enovathemes_post('grid',112,50,$thumb_size,false,false,false); ?>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif ?>
		
	<?php endif ?>
<?php endif ?>