<?php
    propharm_enovathemes_global_variables();
    $error_id  = (isset($GLOBALS['propharm_enovathemes']['error-id']) && $GLOBALS['propharm_enovathemes']['error-id']) ? $GLOBALS['propharm_enovathemes']['error-id'] : "";
?>

<?php get_header(); ?>
<?php do_action('propharm_enovathemes_title_section'); ?>

    <?php

        $default_message = '<div id="et-content" class="content default et-clearfix padding-true"><div class="container et-clearfix">
            <div class="message404 et-clearfix">
                <h1 class="error404-default-title">40<span class="transform-error">4</span></h1>
                <p class="error404-default-subtitle">'.esc_html__('Page not found','propharm').'</p>
                <p class="error404-default-description">'.esc_html__('The page you are looking for could not be found.','propharm').'</p>
                <br>
                <a href="'.esc_url(home_url('/')).'" class="error404-button et-button medium" title="'.esc_attr__('Go to home','propharm').'">'.esc_html__('Homepage','propharm').'</a>
            </div> 
        </div></div>';

        if (!empty($error_id)) {
            
            $post_info = get_post($error_id);

            if (!is_wp_error($post_info) && is_object($post_info)) {

                $content   = $post_info->post_content;
                $content = do_shortcode($content);
                $content = str_replace( ']]>', ']]&gt;', $content );
                echo '<div id="et-content" class="content"><div class="page-content et-clearfix">'.propharm_enovathemes_output_html($content).'</div></div>';

            } else {
                echo propharm_enovathemes_output_html($default_message);
            }
        } else {
            echo propharm_enovathemes_output_html($default_message);
        }

    ?>

<?php get_footer(); ?>
