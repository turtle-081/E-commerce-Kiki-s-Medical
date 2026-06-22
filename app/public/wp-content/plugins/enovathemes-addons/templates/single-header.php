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

    <div id="gen-wrap">

        <div id="wrap">

            <?php if (is_super_admin()): ?>

                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>

                        <?php

                        $transparent  = get_post_meta(get_the_ID(), 'enovathemes_addons_transparent', true);
                        $sticky       = get_post_meta(get_the_ID(), 'enovathemes_addons_sticky', true);
                        $shadow       = get_post_meta(get_the_ID(), 'enovathemes_addons_shadow', true);
                        $type         = get_post_meta(get_the_ID(), 'enovathemes_addons_header_type', true);
                        $transparent      = (empty($transparent)) ? "false" : "true";
                        $sticky           = (empty($sticky)) ? "false" : "true";
                        $shadow           = (empty($shadow)) ? "false" : "true";

                        $class = array();

                        ?>

                        <?php if ($type == "mobile"): ?>

                            <?php

                                $class[] = 'header et-mobile et-clearfix';
                                $class[] = 'transparent-false';
                                $class[] = 'sticky-false';
                                $class[] = 'shadow-'.$shadow;
                                $class[] = 'mobile-true';
                                $class[] = 'desktop-true';

                            ?>
                            <header id="et-mobile-<?php the_ID(); ?>" <?php post_class(implode(" ", $class)); ?>>
                                <?php the_content(); ?>
                            </header>
                        <?php elseif($type == "desktop"): ?>

                            <?php

                                $class[] = 'header';
                                $class[] = 'et-desktop';
                                $class[] = 'et-clearfix';

                                if ($type == "sidebar") {
                                    $class[] = 'side-true';
                                }
                                
                                $class[] = 'transparent-'.$transparent;
                                $class[] = 'sticky-false';
                                $class[] = 'shadow-'.$shadow;
                                $class[] = 'mobile-true';
                                $class[] = 'desktop-true';

                            ?>
                            <header id="et-desktop-<?php the_ID(); ?>" <?php post_class(implode(" ", $class)); ?>>
                                <?php the_content(); ?>
                            </header>
                        <?php elseif($type == "sidebar"): ?>

                            <?php

                                $class[] = 'header';
                                $class[] = 'et-desktop';
                                $class[] = 'et-clearfix';
                                $class[] = 'side-true';
                                $class[] = 'transparent-false';
                                $class[] = 'sticky-false';
                                $class[] = 'shadow-false';
                                $class[] = 'mobile-true';
                                $class[] = 'desktop-true';

                            ?>
                            <header id="et-desktop-<?php the_ID(); ?>" <?php post_class(implode(" ", $class)); ?>>
                                <?php the_content(); ?>
                            </header>
                        <?php endif; ?>

                    <?php endwhile; ?>
                <?php endif; ?>

                <?php $homepage_id = get_option('page_on_front'); ?>

                <?php if ($homepage_id): ?>

                    <div class="page-content-wrap">

                        <?php

                            global $propharm_enovathemes;

                            $title_section_id = (isset($GLOBALS['propharm_enovathemes']['title-section-id']) && !empty($GLOBALS['propharm_enovathemes']['title-section-id'])) ? $GLOBALS['propharm_enovathemes']['title-section-id'] : "default";
                            $slider           = "none";
                            $etp_title        = "";
                            $etp_subtitle     = "";
                            $etp_breadcrumbs  = (function_exists('enovathemes_addons_breadcrumbs')) ? enovathemes_addons_breadcrumbs() : "";



                            $slider                 = get_post_meta( $homepage_id, 'enovathemes_addons_slider', true );
                            $page_title_section_id  = get_post_meta( $homepage_id, 'enovathemes_addons_title_section', true );
                            $title_section_subtitle = get_post_meta( $homepage_id, 'enovathemes_addons_subtitle', true );

                            if ($page_title_section_id !="none" && $page_title_section_id != "inherit") {
                                $title_section_id = $page_title_section_id;
                            }

                            $etp_title     = get_the_title( $homepage_id );
                            $etp_subtitle  = $title_section_subtitle;

                        ?>

                        <?php if(shortcode_exists("rev_slider") && $slider != "none" && !empty($slider)): ?>
                            <?php echo(do_shortcode('[rev_slider '.$slider.']')); ?>
                        <?php else: ?>

                            <?php
                                if ($title_section_id != "none" && $title_section_id != "default" && function_exists('enovathemes_addons_title_section_html')) {
                                    enovathemes_addons_title_section_html($title_section_id, $etp_title, $etp_subtitle, $etp_breadcrumbs);
                                } elseif ($title_section_id == "default") {
                                    propharm_enovathemes_default_title_section($etp_title, $etp_subtitle, $etp_breadcrumbs);
                                }

                            ?>

                        <?php endif ?>

                    </div>

                <?php endif ?>

            <?php else: ?>
                <br><br>
                <div class="container">
                    <div class="alert note"><div class="alert-message"><?php echo esc_html__("You don't have sufficient privileges to view this page.","enovathemes-addons") ?></div></div>
                </div>
            <?php endif ?>

        </div>

    </div>
<?php do_action('propharm_enovathemes_custom_cursor'); ?>
<?php wp_footer(); ?>
</body>
</html>
