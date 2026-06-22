<?php 
    propharm_enovathemes_global_variables();

    $product_post_layout    = (isset($GLOBALS['propharm_enovathemes']['product-post-layout']) && $GLOBALS['propharm_enovathemes']['product-post-layout']) ? $GLOBALS['propharm_enovathemes']['product-post-layout'] : "grid";
    $product_sidebar        = (isset($GLOBALS['propharm_enovathemes']['product-sidebar']) && $GLOBALS['propharm_enovathemes']['product-sidebar']) ? $GLOBALS['propharm_enovathemes']['product-sidebar'] : "none";
    $product_sidebar_toggle = (isset($GLOBALS['propharm_enovathemes']['product-sidebar-toggle']) && $GLOBALS['propharm_enovathemes']['product-sidebar-toggle'] == 1) ? 'true' : 'false';

    if (is_active_sidebar('shop-widgets') && $product_sidebar == "none" && !defined('ENOVATHEMES_ADDONS')) {
        $product_sidebar = 'left';
    }

    $class = array();

    if ($product_sidebar != "none") {
        $class[] = 'sidebar-active';
    }

    $class[] = 'post-layout';
    $class[] = 'product-layout';
    $class[] = 'product-sidebar-toggle-'.$product_sidebar_toggle;
    $class[] = 'medium';
    $class[] = $product_post_layout;
    if ($product_post_layout == "grid") {
        $class[] = 'gap-false';
    }
    $class[] = 'layout-sidebar-'.$product_sidebar;

?>
<?php get_header(); ?>
<?php do_action('propharm_enovathemes_title_section'); ?>
<?php if (is_singular('product')): ?>
    <?php get_template_part('/woocommerce/content-product-single'); ?>
<?php else: ?>
    <div class="<?php echo implode(' ', $class); ?>">
        <?php get_template_part('/woocommerce/content-product-loop'); ?>
    </div>
<?php endif ?>
<?php get_footer(); ?>