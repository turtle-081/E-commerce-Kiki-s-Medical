<?php propharm_enovathemes_global_variables();?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<!-- META TAGS -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=8">
	<!-- LINK TAGS -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <?php if (is_super_admin()): ?>

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <section id="title-section-<?php the_ID(); ?>" <?php post_class("et-clearfix title-section"); ?>>
                    <?php the_content(); ?>
                </section>
                
            <?php endwhile; ?>
        <?php endif; ?>

    <?php else: ?>
        <br><br>
        <div class="container">
            <div class="alert note"><div class="alert-message"><?php echo esc_html__("You don't have sufficient privileges to view this page.","enovathemes-addons") ?></div></div>
        </div>
    <?php endif ?>
<?php do_action('propharm_enovathemes_custom_cursor'); ?>
<?php wp_footer(); ?>
</body>
</html>
