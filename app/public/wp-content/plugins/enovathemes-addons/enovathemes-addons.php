<?php
/*
    Plugin Name: Enovathemes add-ons
    Plugin URI: http://www.enovathemes.com
    Text Domain: enovathemes-addons
    Domain Path: /languages/
    Description: Plugin comes with Enovathemes to extend theme functionality
    Author: Enovathemes
    Version: 3.1
    Author URI: http://enovathemes.com
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'ENOVATHEMES_ADDONS', plugin_dir_path( __FILE__ ));
define( 'THEME_IMG', get_template_directory_uri().'/images/');
define( 'THEME_SVG', THEME_IMG.'icons/');

if ( !class_exists( 'ReduxFramework' ) && file_exists( ENOVATHEMES_ADDONS . '/optionpanel/framework.php' ) ) {
    require_once('optionpanel/framework.php' );
}

function enovathemes_addons_load_plugin_textdomain() {
    load_plugin_textdomain( 'enovathemes-addons', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );

    if (!isset( $redux_demo ) && file_exists( ENOVATHEMES_ADDONS . '/optionpanel/config.php' ) ) {
        require_once('optionpanel/config.php' );
    }
}
add_action( 'init', 'enovathemes_addons_load_plugin_textdomain' );


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (is_plugin_active( 'js_composer/js_composer.php' )) {
    require_once('includes/megamenu.php' );
    require_once('includes/footer.php' );
    require_once('includes/header.php' );
    require_once('includes/title.php' );
    require_once('includes/banner.php' );
    require_once('shortcodes/shortcodes.php' );
    require_once('includes/admin-footer-scripts.php' );
}

require_once('includes/MobileDetect/vendor/autoload.php');
require_once('includes/cmb2.php' );
require_once('widgets/widget-banner.php' );
require_once('widgets/widget-login.php' );
require_once('widgets/widget-posts.php' );
require_once('widgets/widget-mailchimp.php' );
require_once('widgets/widget-flickr.php' );
require_once('widgets/widget-facebook.php' );
require_once('widgets/widget-contact-form.php' );
require_once('widgets/widget-product-search.php' );
require_once('widgets/widget-product-filter.php' );
require_once('includes/dynamic-styles.php' );

/*  Scripts
/*-------------------*/

    function enovathemes_addons_script(){
        if(!is_admin()){

            global $wp_query,$propharm_enovathemes;

            wp_register_script( 'widget-product-search', plugins_url('/js/widget-product-search.js', __FILE__ ), array('jquery'), '', true);
            wp_localize_script(
                'widget-product-search',
                'psearch_opt',
                array(
                    'ajaxUrl'   => admin_url('admin-ajax.php'),
                    'noResults' => esc_html__( 'No products found', 'enovathemes-addons' ),
                )
            );


            $shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';
            if ('' === get_option( 'permalink_structure' )) {
                $shop_link = get_home_url().'?post_type=product';
            }

            wp_register_script( 'widget-product-filter', plugins_url('/js/widget-product-filter.js', __FILE__ ), array('jquery'), '', true);
            wp_localize_script(
                'widget-product-filter',
                'pfilter_opt',
                array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'error'   => esc_html__("Something was wrong, please try later or contact site administrator","enovathemes-addons"),
                    'total'   => esc_html__("products found","enovathemes-addons"),
                    'shopURL' => $shop_link,
                    'shopName'=> sanitize_title_with_dashes(sanitize_title_with_dashes(get_bloginfo('name'))),
                    'already' => esc_html__("Product already added", 'enovathemes'),
                    'noMore'  => esc_html__("No more", 'enovathemes-addons'),
                )
            );

            wp_register_script( 'widget-product-filter-select', plugins_url('/js/widget-product-filter-select.js', __FILE__ ), array('jquery'), '', true);
            wp_localize_script(
                'widget-product-filter-select',
                'pfilter_select_opt',
                array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'error'   => esc_html__("Something was wrong, please try later or contact site administrator","enovathemes-addons"),
                )
            );

            $wishlist  = (isset($GLOBALS['propharm_enovathemes']['wishlist']) && $GLOBALS['propharm_enovathemes']['wishlist'] == 1) ? "true" : "false";
            $compare   = (isset($GLOBALS['propharm_enovathemes']['compare']) && $GLOBALS['propharm_enovathemes']['compare'] == 1) ? "true" : "false";
            $quickview = (isset($GLOBALS['propharm_enovathemes']['quickview']) && $GLOBALS['propharm_enovathemes']['quickview'] == 1) ? "true" : "false";
            
            if ($quickview == "true") {
                if (class_exists('Woocommerce')) {
                    wp_enqueue_script( 'wc-add-to-cart-variation', plugins_url() . '/woocommerce/assets/js/frontend/add-to-cart-variation.min.js', array('jquery', 'wp-util', 'jquery-blockui'), '', true );
                    wp_enqueue_script( 'flexslider', plugins_url() . '/woocommerce/assets/js/flexslider/jquery.flexslider.min.js', array('jquery'), '', true );
                    wp_enqueue_script( 'prettyPhoto', plugins_url() . '/woocommerce/assets/js/prettyPhoto/jquery.prettyPhoto.min.js', array('jquery'), '', true );
                    wp_enqueue_script( 'zoom', plugins_url() . '/woocommerce/assets/js/zoom/jquery.zoom.min.js', array('jquery'), '', true );
                    wp_enqueue_style( 'woocommerce_prettyPhoto_css', plugins_url() . '/woocommerce/assets/css/prettyPhoto.css', '', '', true );
                }
                if (defined('WCVS_PLUGIN_VERSION')) {
                    wp_enqueue_script( 'tawcvs-frontend', plugins_url( '/variation-swatches-for-woocommerce/assets/js/frontend.js'), array( 'jquery' ), '', true );
                }
            }

            if ($wishlist == "true") {

                wp_enqueue_script( 'widget-product-wishlist', plugins_url('/js/widget-product-wishlist.js', __FILE__ ), array('jquery','plugins-combined'), '', true);
                wp_localize_script(
                    'widget-product-wishlist',
                    'wishlist_opt',
                    array(
                        'ajaxUrl'        => admin_url('admin-ajax.php'),
                        'ajaxPost'       => admin_url('admin-post.php'),
                        'shopName'       => sanitize_title_with_dashes(sanitize_title_with_dashes(get_bloginfo('name'))),
                        'inWishlist'     => esc_html__("Added to wishlist","enovathemes-addons"),
                        'addedWishlist'  => esc_html__("In wishlist","enovathemes-addons"),
                        'error'          => esc_html__("Something went wrong, could not add to wishlist","enovathemes-addons"),
                        'noWishlist'     => esc_html__("No products found","enovathemes-addons"),
                        'confirm'        => esc_html__("Remove the item from wishlist?","enovathemes-addons"),
                    )
                );
            }

            if ($compare == "true") {

                wp_enqueue_script( 'widget-product-compare', plugins_url('/js/widget-product-compare.js', __FILE__ ), array('jquery','plugins-combined'), '', true);
                wp_localize_script(
                    'widget-product-compare',
                    'compare_opt',
                    array(
                        'ajaxUrl'   => admin_url('admin-ajax.php'),
                        'shopName'  => sanitize_title_with_dashes(sanitize_title_with_dashes(get_bloginfo('name'))),
                        'inCompare' => esc_html__("Added to compare","enovathemes-addons"),
                        'addedCompare' => esc_html__("In compare","enovathemes-addons"),
                        'error'     => esc_html__("Something went wrong, could not add to compare","enovathemes-addons"),
                        'noCompare' => esc_html__("No products found","enovathemes-addons"),
                        'confirm'   => esc_html__("Remove the item","enovathemes-addons"),
                    )
                );
            }

            if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

                wp_enqueue_script( 'wpb_composer_front_js' );
                wp_enqueue_style( 'js_composer_front' );
                wp_enqueue_style( 'js_composer_custom_css' );

            }

            /* < Dynamic google fonts
            ------------------------------------*/

                $global_dynamic_font = array();

                $title_section_id  = "none";
                $blog_title_id     = (isset($GLOBALS['propharm_enovathemes']['blog-title']) && !empty($GLOBALS['propharm_enovathemes']['blog-title'])) ? $GLOBALS['propharm_enovathemes']['blog-title'] : "none";
                $product_title_id  = (isset($GLOBALS['propharm_enovathemes']['product-title']) && !empty($GLOBALS['propharm_enovathemes']['product-title'])) ? $GLOBALS['propharm_enovathemes']['product-title'] : "none";

                $header_desktop_id = (isset($GLOBALS['propharm_enovathemes']['header-desktop-id']) && !empty($GLOBALS['propharm_enovathemes']['header-desktop-id'])) ? $GLOBALS['propharm_enovathemes']['header-desktop-id'] : "default";
                $header_mobile_id  = (isset($GLOBALS['propharm_enovathemes']['header-mobile-id']) && !empty($GLOBALS['propharm_enovathemes']['header-mobile-id'])) ? $GLOBALS['propharm_enovathemes']['header-mobile-id'] : "default";
                $footer_id         = (isset($GLOBALS['propharm_enovathemes']['footer-id']) && !empty($GLOBALS['propharm_enovathemes']['footer-id'])) ? $GLOBALS['propharm_enovathemes']['footer-id'] : "default";

                /* Page
                ---------------*/

                    if (is_page()) {

                        $page_header_desktop_id = get_post_meta( get_the_ID(), 'enovathemes_addons_desktop_header', true );
                        $page_header_mobile_id  = get_post_meta( get_the_ID(), 'enovathemes_addons_mobile_header', true );
                        $page_footer_id         = get_post_meta( get_the_ID(), 'enovathemes_addons_footer', true );
                        $title_section_id       = get_post_meta( get_the_ID(), 'enovathemes_addons_title_section', true );

                        if ($page_header_desktop_id != "inherit") {
                            $header_desktop_id = $page_header_desktop_id;
                        }

                        if ($page_header_mobile_id != "inherit") {
                            $header_mobile_id = $page_header_mobile_id;
                        }

                        if ($page_footer_id != "inherit") {
                            $footer_id = $page_footer_id;
                        }

                        $element_font = get_post_meta(get_the_ID(), 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }

                    }

                /* Blog
                ---------------*/

                    elseif (is_home() || is_category() || is_tag() || is_day() || is_month() || is_year() || is_author() || is_search() || is_singular('post')) {
                        $title_section_id = $blog_title_id;
                    }

                /*  CPT
                ---------------*/

                    else {

                        $post_info = get_post(get_the_ID());

                        if (!is_wp_error($post_info) && is_object($post_info)) {

                            $post_type   = $post_info->post_type;

                            if ($post_type != 'post' && $post_type != 'page') {
                                switch ($post_type) {
                                    case 'product':
                                        $title_section_id = $product_title_id;
                                        break;
                                    default :
                                        $title_section_id = $blog_title_id;
                                        break;
                                }
                            }

                        }

                    }

                if ($header_desktop_id == $header_mobile_id && $header_desktop_id != "default") {
                    $header_mobile_id = "none";
                }

                /*  Singular header
                ---------------*/

                    if (is_singular('header')) {
                        $header_mobile_id = get_the_ID();
                        $header_desktop_id = get_the_ID();
                    }

                /*  Singular footer
                ---------------*/

                    elseif (is_singular('footer')) {
                        $footer_id = get_the_ID();
                    }

                /*  Singular title section
                ---------------*/

                    elseif (is_singular('title_section')) {
                        $title_section_id = get_the_ID();
                    }

                /*  Singular post
                ---------------*/

                    elseif (is_singular('post')) {
                        $element_font = get_post_meta(get_the_ID(), 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }
                    }

                /*  Singular product
                ---------------*/

                    elseif (is_singular('product')) {
                        $element_font = get_post_meta(get_the_ID(), 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }
                    }

                /*  Mobile header
                ---------------*/

                    if ($header_mobile_id != "none" && $header_mobile_id != "default") {
                        $element_font = get_post_meta($header_mobile_id, 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }
                    }

                /*  Desktop header
                ---------------*/

                    if ($header_desktop_id != "none" && $header_desktop_id != "default") {
                        $element_font = get_post_meta($header_desktop_id, 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }
                    }

                /* Megamenu
                --------------*/

                    $megamenu = enovathemes_addons_megamenus();
                    if (!is_wp_error($megamenu)) {
                        foreach ($megamenu as $megam => $atts) {
                            $element_font = get_post_meta($megam, 'element_font', true);
                            if (!empty($element_font)) {
                                $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                            }
                        }
                    }

                /*  Title section
                ---------------*/

                    if ($title_section_id != "none" && $title_section_id != "default") {
                        $element_font = get_post_meta($title_section_id, 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }
                    }

                /*  Banners
                ---------------*/

                    $banners = enovathemes_addons_banners();

                    if (!is_wp_error($banners)) {
                        foreach ($banners as $banner => $atts) {
                            $element_font = get_post_meta($banner, 'element_font', true);
                            if (!empty($element_font)) {
                                $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                            }
                        }
                    }

                /*  Footer
                ---------------*/

                    if ($footer_id != "none" && $footer_id != "default") {
                        $element_font = get_post_meta($footer_id , 'element_font', true);
                        if (!empty($element_font)) {
                            $global_dynamic_font = array_merge($global_dynamic_font,enovathemes_addons_create_dynamic_scripts($element_font));
                        }
                    }

                /*  Dynamic font enqueue
                ---------------*/

                    if (!empty($global_dynamic_font)) {

                        $global_dynamic_font = array_unique($global_dynamic_font,SORT_REGULAR);

                        $global_dynamic_font_result = array();
                        foreach ($global_dynamic_font as $dynamic_font) {

                            if (!isset($global_dynamic_font_result[$dynamic_font['font-name']])){
                                $global_dynamic_font_result[$dynamic_font['font-name']] = $dynamic_font;
                            }else{

                                if (!strpos($global_dynamic_font_result[$dynamic_font['font-name']]['font-style'], $dynamic_font['font-style'])) {
                                    $global_dynamic_font_result[$dynamic_font['font-name']]['font-style'] = $global_dynamic_font_result[$dynamic_font['font-name']]['font-style'].','.$dynamic_font['font-style'];
                                }

                                if (!strpos($global_dynamic_font_result[$dynamic_font['font-name']]['subset'], $dynamic_font['subset'])) {
                                    $global_dynamic_font_result[$dynamic_font['font-name']]['subset'] = $global_dynamic_font_result[$dynamic_font['font-name']]['subset'].','.$dynamic_font['subset'];
                                    $global_dynamic_font_result[$dynamic_font['font-name']]['subset'] = implode(',', array_unique(explode(',', $global_dynamic_font_result[$dynamic_font['font-name']]['subset'])));
                                }

                            }
                        }

                        $global_dynamic_font_string   = '';
                        $global_dynamic_subset_string = '';

                        foreach ($global_dynamic_font_result as $global_dynamic_font_output) {
                            $global_dynamic_font_string .= str_replace(' ', '+', $global_dynamic_font_output['font-name']).':'.$global_dynamic_font_output['font-style'].'|';
                            $global_dynamic_subset_string .= $global_dynamic_font_output['subset'].',';
                        }

                        wp_enqueue_style( 'dynamic-google-fonts', 'https://fonts.googleapis.com/css?family='.rtrim($global_dynamic_font_string,'|').'&amp;subset='.rtrim($global_dynamic_subset_string,','),array(), false );

                    }

            /* Dynamic google fonts >
            ------------------------------------*/

        }
    }
    add_action( 'wp_enqueue_scripts', 'enovathemes_addons_script' );

/*  Header html
/*-------------------*/

    function enovathemes_addons_header_html($header_id, $header_type){

        $headers = enovathemes_addons_headers();

        if (!is_wp_error($headers) && array_key_exists($header_id,$headers)) {

            $class   = array();
            if ($header_type == "mobile") {
                $class[] = 'et-mobile';
                $class[] = 'mobile-true';
                $class[] = 'desktop-false';
            } elseif($header_type == "desktop"){
                $class[] = 'et-desktop';
                $class[] = 'mobile-false';
                $class[] = 'desktop-true';
            }

            $class = array_merge($class,$headers[$header_id][1]);

            echo '<header id="et-'.$header_type.'-'.esc_attr($header_id).'" class="'.esc_attr(implode(" ", $class)).'">'.do_shortcode(gzuncompress($headers[$header_id][0])).'<div class="megamenu-overlay"></div></header>';
        } else {
            echo '<div class="container"><div class="alert error"><div class="alert-message">'.esc_html__("No custom header is found, make sure you create a one", "enovathemes-addons").'</div></div></div>';
        }
    }

    function enovathemes_addons_headers() {

        if ( false === ( $headers = get_transient( 'enovathemes-headers' ) ) ) {

            $query_options = array(
                'post_type'           => 'header',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => -1,
                'suppress_filters'    => 'true',
                'lang'                => ''
            );

            $headers = array();
            $headers_query = new WP_Query($query_options);
            if ($headers_query->have_posts()){
                while($headers_query->have_posts()) { $headers_query->the_post();

                    $header_id = get_the_ID();
                    $content   = get_the_content();

                    if ($content) {

                        $transparent   = get_post_meta($header_id, 'enovathemes_addons_transparent', true);
                        $sticky        = get_post_meta($header_id, 'enovathemes_addons_sticky', true);
                        $shadow        = get_post_meta($header_id, 'enovathemes_addons_shadow', true);
                        $shadow_sticky = get_post_meta($header_id, 'enovathemes_addons_shadow_sticky', true);
                        $type          = get_post_meta($header_id, 'enovathemes_addons_header_type', true);

                        $transparent      = (empty($transparent)) ? "false" : "true";
                        $sticky           = (empty($sticky)) ? "false" : "true";
                        $shadow           = (empty($shadow)) ? "false" : "true";
                        $shadow_sticky    = (empty($shadow_sticky)) ? "false" : "true";

                        $class   = array();
                        $class[] = 'header';
                        $class[] = 'et-clearfix';
                        $class[] = 'transparent-'.$transparent;
                        $class[] = 'sticky-'.$sticky;
                        $class[] = 'shadow-'.$shadow;
                        $class[] = 'shadow-sticky-'.$shadow_sticky;
                        if ($type == "sidebar") {$class[] = 'side-true';}

                        $headers[$header_id] = array(gzcompress($content),$class);

                    }
                }
                wp_reset_postdata();
            }

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $headers ) ) {
                $headers = base64_encode(serialize($headers ));
                set_transient( 'enovathemes-headers', $headers, apply_filters( 'null_headers_cache_time', 0 ) );
            }
        }

        if ( ! empty( $headers ) ) {

            return unserialize(base64_decode($headers));

        } else {

            return new WP_Error( 'no_headers', esc_html__( 'No headers.', 'enovathemes-addons' ) );

        }
    }

    function enovathemes_addons_mobile_load($args) {

        if (isset($_POST["args"]) && !empty($_POST["args"])){

            $decoded = $_POST["args"];

            if (isset($decoded["content"]) && !empty($decoded["content"])){
                $data = array();
                WPBMap::addAllMappedShortcodes();
                $data[$decoded["mobile"]] = apply_filters('the_content', gzuncompress(base64_decode($decoded["content"])));
                if (!empty($data)) {
                    wp_send_json($data);
                }
            }

        }
  
        die();
    }
    add_action( 'wp_ajax_mobile_load', 'enovathemes_addons_mobile_load');
    add_action( 'wp_ajax_nopriv_mobile_load', 'enovathemes_addons_mobile_load');

/*  Title section html
/*-------------------*/

    add_filter("the_content", "enovathemes_addons_title_section_filter");
    function enovathemes_addons_title_section_filter($content) {

        if (is_singular('title_section')) {

            $home_link   = esc_url(home_url('/'));
            $home_text   = esc_html__('Home','enovathemes-addons');

            if(!empty(get_option('page_on_front'))){$home_text = get_the_title( get_option('page_on_front') );}

            $text_before = '<span>';
            $text_after  = '</span>';

            $etp_title       = esc_html__("Page title here","propharm-enovathemes");
            $etp_subtitle    = esc_html__("Page subtitle here","propharm-enovathemes");
            $etp_breadcrumbs = $home_text.' / '.get_the_title();

            $content = str_replace("etp-title-replace-this", $etp_title, $content);
            $content = str_replace("etp-subtitle-replace-this", $etp_subtitle, $content);
            $content = preg_replace("/etp-breadcrumbs-replace-this/", $etp_breadcrumbs, $content);
        }

        return $content;

    }

    function enovathemes_addons_title_section_html($title_section_id, $etp_title, $etp_subtitle, $etp_breadcrumbs){
        $title_sections = enovathemes_addons_title_sections();

        if (!is_wp_error($title_sections)) {
            $content = do_shortcode(gzuncompress($title_sections[$title_section_id]));
            $content = str_replace( ']]>', ']]&gt;', $content );
            $content = str_replace("etp-title-replace-this", $etp_title, $content);
            $content = str_replace("etp-subtitle-replace-this", $etp_subtitle, $content);
            $content = preg_replace("/etp-breadcrumbs-replace-this/", $etp_breadcrumbs, $content);
            echo propharm_enovathemes_output_html($content);
        } else {
            echo '<div class="container"><div class="alert error"><div class="alert-message">'.esc_html__("No custom title section is found, make sure you create a one", "enovathemes-addons").'</div></div></div>';
        }
    }

    function enovathemes_addons_title_sections() {

        if ( false === ( $title_sections = get_transient( 'enovathemes-title_sections' ) ) ) {

            $query_options = array(
                'post_type'           => 'title_section',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => -1,
                'suppress_filters'    => 'true',
                'lang'                => ''
            );

            $title_sections = array();
            $title_sections_query = new WP_Query($query_options);
            if ($title_sections_query->have_posts()){
                while($title_sections_query->have_posts()) { $title_sections_query->the_post();

                    $title_section_id = get_the_ID();
                    $content          = get_the_content();

                    if ($content) {

                        $content ='<section id="title-section-'.esc_attr($title_section_id).'" class="title-section et-clearfix">'.$content.'</section>';

                        $title_sections[$title_section_id] = gzcompress($content);

                    }
                }
                wp_reset_postdata();
            }

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $title_sections ) ) {
                $title_sections = base64_encode(serialize( $title_sections));
                set_transient( 'enovathemes-title-sections', $title_sections, apply_filters( 'null_title_sections_cache_time', 0 ) );
            }
        }

        if ( ! empty( $title_sections ) ) {

            return unserialize(base64_decode($title_sections));

        } else {

            return new WP_Error( 'no_title_sections', esc_html__( 'No title_sections.', 'enovathemes-addons' ) );

        }
    }

/*  Footer html
/*-------------------*/

    function enovathemes_addons_footer_html($footer_id){

        $footer_async   = get_post_meta($footer_id, 'enovathemes_addons_footer_async', true);
        $dis_async_blog = get_post_meta($footer_id, 'enovathemes_addons_dis_async_blog', true);
        $dis_async_shop = get_post_meta($footer_id, 'enovathemes_addons_dis_async_shop', true);
        $dis_async_page = get_post_meta($footer_id, 'enovathemes_addons_dis_async_page', true);

        $disable_async = 'false';

        $pages   = array();
        $is_page = false;

        if (!empty($dis_async_page)) {
           $dis_async_page = explode(',', $dis_async_page);
           if (is_array($dis_async_page)) {
                $pages = $dis_async_page;
                foreach($pages as $page){
                    if (is_page($page)) {
                        $is_page = true;
                    }
                }
           }
        }

        if (
            ($dis_async_blog == "on" && (is_home() || is_tax() || is_category() || is_tag() || is_single('post') || is_search() || is_404())) ||
            ($dis_async_shop == "on" && (is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_singular( 'product' ))) ||
            (!empty($pages) && $is_page)
        ) {
            $disable_async = 'true';
        }

        $iframe = 'false';

        if( isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe' ) {$iframe = 'true';}

        if ($iframe == 'true' || $footer_async == "false" || $disable_async == "true") {
            $footers = enovathemes_addons_footers();
            if (!is_wp_error($footers)) {
                echo do_shortcode(gzuncompress($footers[$footer_id]));
            } else {
                echo '<div class="alert error"><div class="alert-message">'.esc_html__("No custom footer is found, make sure you create a one", "enovathemes-addons").'</div></div>';
            }
        } else {

            $footer_placeholder        = get_post_meta($footer_id, 'enovathemes_addons_footer_placeholder', true);
            $footer_placeholder_color  = get_post_meta($footer_id, 'enovathemes_addons_footer_placeholder_color', true);
            $style = '';
            if (!empty($footer_placeholder)) {
                $style .= 'height:'.$footer_placeholder.'px;';
            }
            if (!empty($footer_placeholder_color)) {
                $style .= 'background:'.$footer_placeholder_color;
            }
            echo '<footer id="footer-placeholder-'.$footer_id.'" data-footer="'.$footer_id.'" style="'.$style.'" class="footer et-footer et-clearfix sticky-false footer-placeholder"></footer>';
            
        }

    }

    function enovathemes_addons_footer_load($footer) {

        if (isset($_POST["footer"]) && !empty($_POST["footer"])){
           
            $footers = enovathemes_addons_footers();
            if (!is_wp_error($footers)) {
                $data = array();
                WPBMap::addAllMappedShortcodes();
                $data[$_POST["footer"]] = apply_filters('the_content', gzuncompress($footers[$_POST["footer"]]));
                wp_send_json($data);
            }
            
        }
      
        die();
    }
    add_action( 'wp_ajax_footer_load', 'enovathemes_addons_footer_load');
    add_action( 'wp_ajax_nopriv_footer_load', 'enovathemes_addons_footer_load');

    function enovathemes_addons_footers() {

        if ( false === ( $footers = get_transient( 'enovathemes-footers' ) ) ) {

            $query_options = array(
                'post_type'           => 'footer',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => -1,
                'suppress_filters'    => 'true',
                'lang'                => ''
            );

            $footers = array();
            $footers_query = new WP_Query($query_options);
            if ($footers_query->have_posts()){
                while($footers_query->have_posts()) { $footers_query->the_post();

                    $footer_id = get_the_ID();
                    $content   = get_the_content();

                    if ($content) {

                        $sticky = get_post_meta($footer_id, 'enovathemes_addons_sticky', true);
                        $sticky = (empty($sticky)) ? "false" : "true";

                        $class   = array();
                        $class[] = 'footer';
                        $class[] = 'et-footer';
                        $class[] = 'et-clearfix';
                        $class[] = 'sticky-'.$sticky;

                        $content ='<footer id="et-footer-'.esc_attr($footer_id).'" class="'.implode(" ", $class).'">'.$content.'</footer>';

                        $footers[$footer_id] = gzcompress($content);

                    }
                }
                wp_reset_postdata();
            }

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $footers ) ) {
                $footers = base64_encode(serialize( $footers ));
                set_transient( 'enovathemes-footers', $footers, apply_filters( 'null_footers_cache_time', 0 ) );
            }
        }

        if ( ! empty( $footers ) ) {

            return unserialize(base64_decode($footers));

        } else {

            return new WP_Error( 'no_footers', esc_html__( 'No footers.', 'enovathemes-addons' ) );

        }
    }

/*  After import
/*-------------------*/

    function enovathemes_addons_ocdi_before_content_import( $selected_import ) {
        update_option('uploads_use_yearmonth_folders', false);

        if (class_exists('Woocommerce')) {
            $shop_page_id = get_option('woocommerce_shop_page_id');
            if ($shop_page_id) {
                wp_delete_post($shop_page_id);
            }

        }

        $hello_world_posts = get_posts(array('category' => 1, 'post_type' => 'post', 'numberposts' => 1));
        if ($hello_world_posts) {
            $hello_world_post_id = $hello_world_posts[0]->ID;
            wp_delete_post($hello_world_post_id, true);
        }

    }
    add_action( 'ocdi/before_content_import', 'enovathemes_addons_ocdi_before_content_import' );

    function enovathemes_addons_ocdi_after_import( $selected_import ) {

        global $wpdb;

        $old_url = 'https://enovathemes.com/propharm/';
        $new_url = esc_url(home_url('/'));

        $posts_table = $wpdb->prefix . "posts";
        $meta_table  = $wpdb->prefix . "postmeta";

        $sql_1 = $wpdb->prepare( "UPDATE {$posts_table} SET post_content  = REPLACE (post_content, %s, '{$new_url}') ",$old_url);
        $sql_2 = $wpdb->prepare( "UPDATE {$meta_table} SET meta_value  = REPLACE (meta_value, %s, '{$new_url}') ",$old_url);
        $sql_3 = $wpdb->prepare( "UPDATE {$posts_table} SET guid  = REPLACE (guid, %s, '{$new_url}') ",$old_url);


        if (isset($old_url) && !empty($old_url) && $old_url != $new_url) {
            $wpdb->query($sql_1);
            $wpdb->query($sql_2);
            $wpdb->query($sql_3);
        }

        // Set the homepage and blog page

        $home_query = new WP_Query(
            array(
                'post_type'              => 'page',
                'title'                  => 'Home',
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'ignore_sticky_posts'    => true,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
            )
        );
         
        if ( ! empty( $home_query->post ) ) {
            update_option( 'page_on_front', $home_query->posts[0]->ID );
        }

        $post_query = new WP_Query(
            array(
                'post_type'              => 'page',
                'title'                  => 'Blog',
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'ignore_sticky_posts'    => true,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
            )
        );
         
        if ( ! empty( $post_query->post ) ) {
            update_option( 'page_for_posts', $post_query->posts[0]->ID );
        }

        update_option( 'show_on_front', 'page' );

        if (class_exists('Alg_WC_Currency_Switcher')) {
            update_option( 'alg_currency_switcher_format', '%currency_symbol% %currency_code%' );
            update_option( 'alg_wc_currency_switcher_link_list_separator', '' );
        }

        if (class_exists('Woocommerce')) {
            $shop = get_page_by_path( 'shop' );
            if ($shop) {
                update_option( 'woocommerce_shop_page_id', $shop->ID );
            }
        }

        if ( function_exists( 'wp_update_custom_css_post' ) ) {

            $wp_custom_css_styles = Redux::get_option('propharm_enovathemes','custom-css');

            if (!empty($wp_custom_css_styles)) {
                $core_css = wp_get_custom_css();
                $return   =  wp_update_custom_css_post( $core_css . $wp_custom_css_styles );
                if ( ! is_wp_error( $return ) ) {
                    Redux::set_option('propharm_enovathemes','custom-css','');
                }
            }
        }

        Redux::set_option('propharm_enovathemes','disable-defaults',1);

        // Delete transients
        delete_transient( 'enovathemes-product-categories' );
        delete_transient( 'enovathemes-attributes-filter' );
        delete_transient( 'dynamic-styles-cached' );
        delete_transient( 'enovathemes-banners' );
        delete_transient( 'enovathemes-megamenu' );
        delete_transient( 'enovathemes-headers' );
        delete_transient( 'enovathemes-footers' );
        delete_transient( 'enovathemes-title-sections' );
        delete_transient( 'product-categories-hierarchy' );
        delete_transient( 'product-categories-raw' );

        update_option('permalink_structure', '/%category%/%postname%/');
        flush_rewrite_rules();

        if ( class_exists( 'WooCommerce' ) ) {
            if ( function_exists( 'wc_regenerate_product_lookup_tables' ) ) {
                wc_regenerate_product_lookup_tables();
            }
            if ( function_exists( 'wc_delete_expired_transients' ) ) {
                wc_delete_expired_transients();
            }

            if ( function_exists( 'wc_delete_product_transients' ) && function_exists( 'wc_delete_shop_order_transients' ) ) {
                wc_delete_product_transients();
                wc_delete_shop_order_transients();
            }

            if ( function_exists( 'wc_update_term_count' ) ) {
                $taxonomy = 'product_cat';
                $terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
                foreach ( $terms as $term ) {
                    wc_update_term_count( $term->term_id, $taxonomy );
                }

                $woo_attributes = wc_get_attribute_taxonomies();
                if (!empty($woo_attributes) && !is_wp_error($woo_attributes)) {
                    foreach( $woo_attributes as $attribute) {

                        $attribute_terms = get_terms( array(
                            'taxonomy' => 'pa_'.$attribute->attribute_name,
                            'hide_empty' => false,
                        ));

                        foreach ( $attribute_terms as $term ) {
                            wc_update_term_count( $term->term_id, 'pa_'.$attribute->attribute_name );
                        }

                    }
                }

            }
        }
      
    }
    add_action( 'pt-ocdi/after_import', 'enovathemes_addons_ocdi_after_import' );

/*  Actions/Filters
/*-------------------*/

    function enovathemes_addons_svg_mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }
    add_filter('upload_mimes', 'enovathemes_addons_svg_mime_types');

    add_filter('body_class', 'enovathemes_addons_general_body_classes');
    function enovathemes_addons_general_body_classes($classes) {

            $custom_class = array();
            $custom_class[] = "addon-active";

            $classes[] = implode(" ", $custom_class);
            return $classes;
    }


    function enovathemes_addons_recursively_parse_nested_shortcodes( $regex, $content, $existing = array() ) {

        if ( is_array( $content ) ) {
            $content = implode( ' ', $content );
        }

        $count = preg_match_all( "/$regex/", $content, $matches );

        if ( $count ) {

            foreach ( $matches[3] as $index => $attributes ) {

                if ( empty( $existing[ $matches[2][ $index ] ] ) ) {
                    $existing[ $matches[2][ $index ] ] = array();
                }

                $shortcode_data = shortcode_parse_atts( $attributes );

                $existing[ $matches[2][ $index ] ][] = $shortcode_data;

            }

            return enovathemes_addons_recursively_parse_nested_shortcodes( $regex, $matches[5], $existing );

        } else {

            return $existing;
        }

    }

    function enovathemes_addons_extract_shortcode_attrs($post_id,$content,$element_css,$element_font){

        global $shortcode_tags;

        $extended_shortcode_tags = $shortcode_tags;
        $extended_shortcode_tags['vc_row'] = 'vc_row';
        $extended_shortcode_tags['vc_row_inner'] = 'vc_row_inner';
        $extended_shortcode_tags['vc_column'] = 'vc_column';
        $extended_shortcode_tags['vc_column_text'] = 'vc_column_text';

        preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
        $tagnames = array_intersect( array_keys( $extended_shortcode_tags ), $matches[1] );

        $shortcode_regex = get_shortcode_regex($tagnames);
        $shortcode_data  = enovathemes_addons_recursively_parse_nested_shortcodes( $shortcode_regex, $content );

        if ($element_css == true) {

            $element_styling = array();

            foreach ($shortcode_data as $shortcode => $attributes) {
                foreach ($attributes as $attribute => $group) {
                    if (is_array($group)) {
                        if (array_key_exists('element_css',$group)) {

                            $element_styling_group = str_replace('dir-child*', '>', $group['element_css']);
                            $element_styling_group = str_replace('|typebutton|', '[type="button"]', $element_styling_group);
                            $element_styling_group = str_replace('|typesubmit|', '[type="submit"]', $element_styling_group);
                            $element_styling[]     = $element_styling_group;
                        }
                    }
                }
            }

            if (!empty($element_styling)) {
                $element_styling = array_unique($element_styling);
                $element_styling = implode('', $element_styling);
                $element_styling = propharm_enovathemes_minify_css($element_styling);
            } else {
                $element_styling = '';
            }

            update_post_meta($post_id, "element_css",$element_styling);

        }

        if ($element_font == true) {

            $element_font = array();

            foreach ($shortcode_data as $shortcode => $attributes) {
                foreach ($attributes as $attribute => $group) {
                    if (is_array($group)) {
                        if (array_key_exists('element_font',$group)) {
                            array_push($element_font, $group['element_font']);
                        }
                        if (array_key_exists('subelement_font',$group)) {
                            array_push($element_font, $group['subelement_font']);
                        }
                    }
                }
            }

            if (!empty($element_font)) {
                $element_font = implode(",", $element_font);
            } else {
                $element_font = '';
            }

            update_post_meta($post_id, "element_font",$element_font);

        }

    }

    add_action( 'init', 'enovathemes_addons_init' );
    function enovathemes_addons_init(){

        global $propharm_enovathemes;

        add_action( 'save_post', 'enovathemes_addons_save_elements_styles', 99, 3);
        function enovathemes_addons_save_elements_styles( $post_id )
        {

            if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if (!current_user_can( 'edit_page', $post_id ) ) return;

            $post_info = get_post($post_id);

            if (!is_wp_error($post_info) && is_object($post_info)) {

                $content   = $post_info->post_content;
                $post_type = $post_info->post_type;

                $element_css      = (isset($_POST['element_css'])) ? true : false;
                $element_font     = (isset($_POST['element_font'])) ? true : false;

                enovathemes_addons_extract_shortcode_attrs($post_id,$content,$element_css,$element_font);

                if ($post_type == "megamenu" && isset($_POST['enovathemes_addons_megamenus_width'])) {
                    if ($_POST['enovathemes_addons_megamenus_width'] == 100) {
                        update_post_meta($post_id, "enovathemes_addons_megamenus_position","left");
                        update_post_meta($post_id, "enovathemes_addons_megamenus_offset","");
                    }
                }

                if ($post_type == "header" && isset($_POST['enovathemes_addons_header_type'])) {

                    if ($_POST['enovathemes_addons_header_type'] == 'sidebar') {
                        update_post_meta($post_id, "enovathemes_addons_transparent", "");
                        update_post_meta($post_id, "enovathemes_addons_sticky", "");
                        update_post_meta($post_id, "enovathemes_addons_shadow", "");
                    }

                }

                if ($post_type == "product"){
                    delete_transient( 'enovathemes-product-categories' );
                    delete_transient( 'enovathemes-attributes-filter' );
                }

                delete_transient( 'dynamic-styles-cached' );
                delete_transient( 'enovathemes-banners' );
                delete_transient( 'enovathemes-megamenu' );
                delete_transient( 'enovathemes-headers' );
                delete_transient( 'enovathemes-footers' );
                delete_transient( 'enovathemes-title-sections' );
                delete_transients_with_prefix( 'et_products_' );
                delete_transients_with_prefix( 'et_posts_' );
                delete_transients_with_prefix( 'et_icon_' );

            }

        }

        if (class_exists("Woocommerce")) {
            add_action('woocommerce_after_cart_totals',function(){ ?>
                <a class="button medium checkout-button wc-forward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                    <?php esc_html_e( 'Continue Shopping', 'enovathemes-addons' ); ?>
                </a>
            <?php });
        }

        if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

            // Disable Gutenberg

            if (isset($GLOBALS['propharm_enovathemes']['disable-gutenberg']) && $GLOBALS['propharm_enovathemes']['disable-gutenberg'] == 1) {


                $disable_gutenberg_post = (isset($GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['post']) && $GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['post'] == 1) ? 'true' : 'false';
                $disable_gutenberg_page = (isset($GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['page']) && $GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['page'] == 1) ? 'true' : 'false';
                $disable_gutenberg_product = (isset($GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['product']) && $GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['product'] == 1) ? 'true' : 'false';
                $disable_gutenberg_widgets = (isset($GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['widgets']) && $GLOBALS['propharm_enovathemes']['disable-gutenberg-type']['widgets'] == 1) ? 'true' : 'false';


                function enovathemes_addons_disable_gutenberg_post($is_enabled, $post_type) {
                    if ($post_type === 'post') return false;

                    return $is_enabled;
                }

                if ($disable_gutenberg_post == "true") {
                    add_filter('use_block_editor_for_post_type', 'enovathemes_addons_disable_gutenberg_post', 10, 2);
                }

                function enovathemes_addons_disable_gutenberg_page($is_enabled, $post_type) {
                    if ($post_type === 'page') return false;

                    return $is_enabled;
                }

                if ($disable_gutenberg_page == "true") {
                    add_filter('use_block_editor_for_post_type', 'enovathemes_addons_disable_gutenberg_page', 10, 2);
                }

                function enovathemes_addons_disable_gutenberg_product($is_enabled, $post_type) {
                    if ($post_type === 'product') return false;

                    return $is_enabled;
                }

                if ($disable_gutenberg_product == "true") {
                    add_filter('use_block_editor_for_post_type', 'enovathemes_addons_disable_gutenberg_product', 10, 2);
                }

                function enovathemes_addons_disable_gutenberg_banner($is_enabled, $post_type) {
                    if ($post_type === 'banner') return false;

                    return $is_enabled;
                }
                add_filter('use_block_editor_for_post_type', 'enovathemes_addons_disable_gutenberg_banner', 10, 2);

                if ($disable_gutenberg_widgets == "true") {
                    add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
                    add_filter( 'use_widgets_block_editor', '__return_false' );
                }

            }


            $list = array(
                'page',
                'footer',
                'header',
                'megamenu',
                'title_section',
                'banner',
            );

            if(function_exists('vc_set_default_editor_post_types')){
                vc_set_default_editor_post_types( $list );
            }

            vc_add_shortcode_param( 'rv', 'enovathemes_addons_param_settings_rv' );
            function enovathemes_addons_param_settings_rv( $settings, $value ) {

                $output = '';

                $output .= '<span class="vc_description vc_clearfix">'.esc_html__('Responsive visibility options','enovathemes-addons').'</span><br>';
                $output .= '<table class="responsive-visibility">';
                    $output .= '<tr>';
                        $output .= '<th>'.esc_html__('Screen width','enovathemes-addons').'</th>';
                        $output .= '<th>'.esc_html__('Hide?','enovathemes-addons').'</th>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="374">';
                        $output .= '<td class="title">'.esc_html__('max-width 374px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="374" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="375">';
                        $output .= '<td class="title">'.esc_html__('min-width 375px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="375" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="767">';
                        $output .= '<td class="title">'.esc_html__('max-width 767px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="767" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="768">';
                        $output .= '<td class="title">'.esc_html__('min-width 768px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="768" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="768-1023">';
                        $output .= '<td class="title">'.esc_html__('min-width 768px and max-width 1023px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="768-1023" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1024">';
                        $output .= '<td class="title">'.esc_html__('min-width 1024px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="1024" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1024-1279">';
                        $output .= '<td class="title">'.esc_html__('min-width 1024px and max-width 1279px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="1024-1279" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1280">';
                        $output .= '<td class="title">'.esc_html__('min-width 1280px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="1280" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1280-1599">';
                        $output .= '<td class="title">'.esc_html__('min-width 1280px and max-width 1599px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="1280-1599" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1600">';
                        $output .= '<td class="title">'.esc_html__('min-width 1600px','enovathemes-addons').'</th>';
                        $output .= '<td class="checkbox"><input type="checkbox" name="1600" class="rvc" value="true"></td>';
                    $output .= '</tr>';
                $output .= '</table>';

                $output.= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .esc_attr( $settings['param_name'] ) . ' ' .esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

                return $output;
            }

            vc_add_shortcode_param( 'crp', 'enovathemes_addons_param_settings_crp' );
            function enovathemes_addons_param_settings_crp( $settings, $value ) {

                $output = '';

                $padding_array = array('i','0');

                for ($i=0; $i <= 50; $i++) {
                    array_push($padding_array, $i);
                }

                $select = '<select class="column-responsive-padding-opt">';
                    $select .= '<option value="i" selected="selected">'.esc_html__('inherit','enovathemes-addons').'</option>';
                    $select .= '<option value="0">0</option>';
                    foreach ($padding_array as $option) {
                        if ($option != "i" && $option != "0") {
                            $select .= '<option value="'.$option.'">'.$option.'%</option>';
                        }
                    }
                $select .= '</select>';

                $output .= '<span class="vc_description vc_clearfix">'.esc_html__('Responsive padding is advanced option designed to take controll over left/right padding','enovathemes-addons').'</span><br>';
                $output .= '<table class="column-responsive-padding">';
                    $output .= '<tr>';
                        $output .= '<th>'.esc_html__('Screen width','enovathemes-addons').'</th>';
                        $output .= '<th>'.esc_html__('Padding left','enovathemes-addons').'</th>';
                        $output .= '<th>'.esc_html__('Padding right','enovathemes-addons').'</th>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="374">';
                        $output .= '<td class="title">'.esc_html__('Max 374 width','enovathemes-addons').'</th>';
                        $output .= '<td class="left">'.$select.'</td>';
                        $output .= '<td class="right">'.$select.'</td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="375-767">';
                        $output .= '<td class="title">'.esc_html__('Min 375 Max 767 width','enovathemes-addons').'</th>';
                        $output .= '<td class="left">'.$select.'</td>';
                        $output .= '<td class="right">'.$select.'</td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="768-1023">';
                        $output .= '<td class="title">'.esc_html__('Min 768 Max 1023 width','enovathemes-addons').'</th>';
                        $output .= '<td class="left">'.$select.'</td>';
                        $output .= '<td class="right">'.$select.'</td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1024-1279">';
                        $output .= '<td class="title">'.esc_html__('Min 1024 Max 1279 width','enovathemes-addons').'</th>';
                        $output .= '<td class="left">'.$select.'</td>';
                        $output .= '<td class="right">'.$select.'</td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1280-1599">';
                        $output .= '<td class="title">'.esc_html__('Min 1280 Max 1599 width','enovathemes-addons').'</th>';
                        $output .= '<td class="left">'.$select.'</td>';
                        $output .= '<td class="right">'.$select.'</td>';
                    $output .= '</tr>';
                    $output .= '<tr class="media-query" data-query="1600-1919">';
                        $output .= '<td class="title">'.esc_html__('Min 1600 Max 1919 width','enovathemes-addons').'</th>';
                        $output .= '<td class="left">'.$select.'</td>';
                        $output .= '<td class="right">'.$select.'</td>';
                    $output .= '</tr>';
                $output .= '</table>';

                $output.= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .esc_attr( $settings['param_name'] ) . ' ' .esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

                return $output;
            }

            vc_add_shortcode_param( 'margin', 'enovathemes_addons_param_settings_margin' );
            function enovathemes_addons_param_settings_margin( $settings, $value ) {

                $output = '';
                $output .= '<span class="vc_description vc_clearfix">'.esc_html__('Use only numbers without any string. Default unit is px','enovathemes-addons').'</span><br>';
                $output .= '<div class="margin-box">';
                     $output .= '<input class="margin-input" type="text" value="0" name="margin-left" />';
                     $output .= '<input class="margin-input" type="text" value="0" name="margin-top" />';
                     $output .= '<input class="margin-input" type="text" value="0" name="margin-right" />';
                     $output .= '<input class="margin-input" type="text" value="0" name="margin-bottom" />';
                     $output .= '<div class="element">'.esc_html__("Element", "enovathemes-addons").'</div>';
                $output .= '</div>';
                $output.= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .esc_attr( $settings['param_name'] ) . ' ' .esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';
                return $output;
            }

            vc_add_shortcode_param( 'padding', 'enovathemes_addons_param_settings_padding' );
            function enovathemes_addons_param_settings_padding( $settings, $value ) {

                $output = '';
                $output .= '<span class="vc_description vc_clearfix">'.esc_html__('Use only numbers without any string. Default unit is px','enovathemes-addons').'</span><br>';
                $output .= '<div class="padding-box">';
                     $output .= '<input class="padding-input" type="text" value="0" name="padding-left" />';
                     $output .= '<input class="padding-input" type="text" value="0" name="padding-top" />';
                     $output .= '<input class="padding-input" type="text" value="0" name="padding-right" />';
                     $output .= '<input class="padding-input" type="text" value="0" name="padding-bottom" />';
                     $output .= '<div class="element">'.esc_html__("Element", "enovathemes-addons").'</div>';
                $output .= '</div>';
                $output.= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .esc_attr( $settings['param_name'] ) . ' ' .esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';
                return $output;
            }

            function enovathemes_addons_vc_param_animation_style_list( $styles ) {
                $styles = array(
                    array(
                        'values' => array(
                            esc_html__( 'None', 'enovathemes-addons' ) => 'none',
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Attention Seekers', 'enovathemes-addons' ),
                        'values' => array(
                            // text to display => value
                            esc_html__( 'bounce', 'enovathemes-addons' ) => array(
                                'value' => 'bounce',
                                'type' => 'other',
                            ),
                            esc_html__( 'flash', 'enovathemes-addons' ) => array(
                                'value' => 'flash',
                                'type' => 'other',
                            ),
                            esc_html__( 'pulse', 'enovathemes-addons' ) => array(
                                'value' => 'pulse',
                                'type' => 'other',
                            ),
                            esc_html__( 'rubberBand', 'enovathemes-addons' ) => array(
                                'value' => 'rubberBand',
                                'type' => 'other',
                            ),
                            esc_html__( 'shake', 'enovathemes-addons' ) => array(
                                'value' => 'shake',
                                'type' => 'other',
                            ),
                            esc_html__( 'swing', 'enovathemes-addons' ) => array(
                                'value' => 'swing',
                                'type' => 'other',
                            ),
                            esc_html__( 'tada', 'enovathemes-addons' ) => array(
                                'value' => 'tada',
                                'type' => 'other',
                            ),
                            esc_html__( 'wobble', 'enovathemes-addons' ) => array(
                                'value' => 'wobble',
                                'type' => 'other',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Bouncing Entrances', 'enovathemes-addons' ),
                        'values' => array(
                            // text to display => value
                            esc_html__( 'bounceIn', 'enovathemes-addons' ) => array(
                                'value' => 'bounceIn',
                                'type' => 'in',
                            ),
                            esc_html__( 'bounceInDown', 'enovathemes-addons' ) => array(
                                'value' => 'bounceInDown',
                                'type' => 'in',
                            ),
                            esc_html__( 'bounceInLeft', 'enovathemes-addons' ) => array(
                                'value' => 'bounceInLeft',
                                'type' => 'in',
                            ),
                            esc_html__( 'bounceInRight', 'enovathemes-addons' ) => array(
                                'value' => 'bounceInRight',
                                'type' => 'in',
                            ),
                            esc_html__( 'bounceInUp', 'enovathemes-addons' ) => array(
                                'value' => 'bounceInUp',
                                'type' => 'in',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Bouncing Exits', 'enovathemes-addons' ),
                        'values' => array(
                            // text to display => value
                            esc_html__( 'bounceOut', 'enovathemes-addons' ) => array(
                                'value' => 'bounceOut',
                                'type' => 'out',
                            ),
                            esc_html__( 'bounceOutDown', 'enovathemes-addons' ) => array(
                                'value' => 'bounceOutDown',
                                'type' => 'out',
                            ),
                            esc_html__( 'bounceOutLeft', 'enovathemes-addons' ) => array(
                                'value' => 'bounceOutLeft',
                                'type' => 'out',
                            ),
                            esc_html__( 'bounceOutRight', 'enovathemes-addons' ) => array(
                                'value' => 'bounceOutRight',
                                'type' => 'out',
                            ),
                            esc_html__( 'bounceOutUp', 'enovathemes-addons' ) => array(
                                'value' => 'bounceOutUp',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Fading Entrances', 'enovathemes-addons' ),
                        'values' => array(
                            // text to display => value
                            esc_html__( 'fadeIn', 'enovathemes-addons' ) => array(
                                'value' => 'fadeIn',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInDown', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInDown',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInDownBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInDownBig',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInLeft', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInLeft',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInLeftBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInLeftBig',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInRight', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInRight',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInRightBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInRightBig',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInUp', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInUp',
                                'type' => 'in',
                            ),
                            esc_html__( 'fadeInUpBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeInUpBig',
                                'type' => 'in',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Fading Exits', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'fadeOut', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOut',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutDown', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutDown',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutDownBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutDownBig',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutLeft', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutLeft',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutLeftBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutLeftBig',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutRight', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutRight',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutRightBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutRightBig',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutUp', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutUp',
                                'type' => 'out',
                            ),
                            esc_html__( 'fadeOutUpBig', 'enovathemes-addons' ) => array(
                                'value' => 'fadeOutUpBig',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Flippers', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'flip', 'enovathemes-addons' ) => array(
                                'value' => 'flip',
                                'type' => 'other',
                            ),
                            esc_html__( 'flipInX', 'enovathemes-addons' ) => array(
                                'value' => 'flipInX',
                                'type' => 'in',
                            ),
                            esc_html__( 'flipInY', 'enovathemes-addons' ) => array(
                                'value' => 'flipInY',
                                'type' => 'in',
                            ),
                            esc_html__( 'flipOutX', 'enovathemes-addons' ) => array(
                                'value' => 'flipOutX',
                                'type' => 'out',
                            ),
                            esc_html__( 'flipOutY', 'enovathemes-addons' ) => array(
                                'value' => 'flipOutY',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Lightspeed', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'lightSpeedIn', 'enovathemes-addons' ) => array(
                                'value' => 'lightSpeedIn',
                                'type' => 'in',
                            ),
                            esc_html__( 'lightSpeedOut', 'enovathemes-addons' ) => array(
                                'value' => 'lightSpeedOut',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Rotating Entrances', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'rotateIn', 'enovathemes-addons' ) => array(
                                'value' => 'rotateIn',
                                'type' => 'in',
                            ),
                            esc_html__( 'rotateInDownLeft', 'enovathemes-addons' ) => array(
                                'value' => 'rotateInDownLeft',
                                'type' => 'in',
                            ),
                            esc_html__( 'rotateInDownRight', 'enovathemes-addons' ) => array(
                                'value' => 'rotateInDownRight',
                                'type' => 'in',
                            ),
                            esc_html__( 'rotateInUpLeft', 'enovathemes-addons' ) => array(
                                'value' => 'rotateInUpLeft',
                                'type' => 'in',
                            ),
                            esc_html__( 'rotateInUpRight', 'enovathemes-addons' ) => array(
                                'value' => 'rotateInUpRight',
                                'type' => 'in',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Rotating Exits', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'rotateOut', 'enovathemes-addons' ) => array(
                                'value' => 'rotateOut',
                                'type' => 'out',
                            ),
                            esc_html__( 'rotateOutDownLeft', 'enovathemes-addons' ) => array(
                                'value' => 'rotateOutDownLeft',
                                'type' => 'out',
                            ),
                            esc_html__( 'rotateOutDownRight', 'enovathemes-addons' ) => array(
                                'value' => 'rotateOutDownRight',
                                'type' => 'out',
                            ),
                            esc_html__( 'rotateOutUpLeft', 'enovathemes-addons' ) => array(
                                'value' => 'rotateOutUpLeft',
                                'type' => 'out',
                            ),
                            esc_html__( 'rotateOutUpRight', 'enovathemes-addons' ) => array(
                                'value' => 'rotateOutUpRight',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Specials', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'hinge', 'enovathemes-addons' ) => array(
                                'value' => 'hinge',
                                'type' => 'out',
                            ),
                            esc_html__( 'rollIn', 'enovathemes-addons' ) => array(
                                'value' => 'rollIn',
                                'type' => 'in',
                            ),
                            esc_html__( 'rollOut', 'enovathemes-addons' ) => array(
                                'value' => 'rollOut',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Zoom Entrances', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'zoomIn', 'enovathemes-addons' ) => array(
                                'value' => 'zoomIn',
                                'type' => 'in',
                            ),
                            esc_html__( 'zoomInDown', 'enovathemes-addons' ) => array(
                                'value' => 'zoomInDown',
                                'type' => 'in',
                            ),
                            esc_html__( 'zoomInLeft', 'enovathemes-addons' ) => array(
                                'value' => 'zoomInLeft',
                                'type' => 'in',
                            ),
                            esc_html__( 'zoomInRight', 'enovathemes-addons' ) => array(
                                'value' => 'zoomInRight',
                                'type' => 'in',
                            ),
                            esc_html__( 'zoomInUp', 'enovathemes-addons' ) => array(
                                'value' => 'zoomInUp',
                                'type' => 'in',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Zoom Exits', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'zoomOut', 'enovathemes-addons' ) => array(
                                'value' => 'zoomOut',
                                'type' => 'out',
                            ),
                            esc_html__( 'zoomOutDown', 'enovathemes-addons' ) => array(
                                'value' => 'zoomOutDown',
                                'type' => 'out',
                            ),
                            esc_html__( 'zoomOutLeft', 'enovathemes-addons' ) => array(
                                'value' => 'zoomOutLeft',
                                'type' => 'out',
                            ),
                            esc_html__( 'zoomOutRight', 'enovathemes-addons' ) => array(
                                'value' => 'zoomOutRight',
                                'type' => 'out',
                            ),
                            esc_html__( 'zoomOutUp', 'enovathemes-addons' ) => array(
                                'value' => 'zoomOutUp',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Slide Entrances', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'slideInDown', 'enovathemes-addons' ) => array(
                                'value' => 'slideInDown',
                                'type' => 'in',
                            ),
                            esc_html__( 'slideInLeft', 'enovathemes-addons' ) => array(
                                'value' => 'slideInLeft',
                                'type' => 'in',
                            ),
                            esc_html__( 'slideInRight', 'enovathemes-addons' ) => array(
                                'value' => 'slideInRight',
                                'type' => 'in',
                            ),
                            esc_html__( 'slideInUp', 'enovathemes-addons' ) => array(
                                'value' => 'slideInUp',
                                'type' => 'in',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Slide Exits', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'slideOutDown', 'enovathemes-addons' ) => array(
                                'value' => 'slideOutDown',
                                'type' => 'out',
                            ),
                            esc_html__( 'slideOutLeft', 'enovathemes-addons' ) => array(
                                'value' => 'slideOutLeft',
                                'type' => 'out',
                            ),
                            esc_html__( 'slideOutRight', 'enovathemes-addons' ) => array(
                                'value' => 'slideOutRight',
                                'type' => 'out',
                            ),
                            esc_html__( 'slideOutUp', 'enovathemes-addons' ) => array(
                                'value' => 'slideOutUp',
                                'type' => 'out',
                            ),
                        ),
                    ),
                    array(
                        'label' => esc_html__( 'Defaults', 'enovathemes-addons' ),
                        'values' => array(
                            esc_html__( 'Top to bottom', 'enovathemes-addons' ) => array(
                                'value' => 'top-to-bottom',
                                'type' => 'in',
                            ),
                            esc_html__( 'Bottom to top', 'enovathemes-addons' ) => array(
                                'value' => 'bottom-to-top',
                                'type' => 'in',
                            ),
                            esc_html__( 'Left to right', 'enovathemes-addons' ) => array(
                                'value' => 'left-to-right',
                                'type' => 'in',
                            ),
                            esc_html__( 'Right to left', 'enovathemes-addons' ) => array(
                                'value' => 'right-to-left',
                                'type' => 'in',
                            ),
                            esc_html__( 'Appear from center', 'enovathemes-addons' ) => array(
                                'value' => 'appear',
                                'type' => 'in',
                            ),
                        ),
                    ),
                );
                return $styles;
            }
            add_filter( 'vc_param_animation_style_list', 'enovathemes_addons_vc_param_animation_style_list' );


            vc_add_shortcode_param( 'atts', 'enovathemes_addons_param_settings_woo_atts' );
            function enovathemes_addons_param_settings_woo_atts( $settings, $value ) {

                $output = '';

                $output .='<div class="sortable-droppable-attributes">';

                    $attributes = enovathemes_addons_build_filter_attributes();

                    if ($attributes){

                        $cat_data = 'attr:cat,label:'.esc_html__( 'Category', 'enovathemes-addons' );

                        $output .='<h4>'.esc_html__( 'Available filter options', 'enovathemes-addons' ).'</h4>';
                        $output .='<ul class="draggable">';
                            $output .='<li data-attribute="'.$cat_data.'" data-title="'.esc_html__( 'Category', 'enovathemes-addons' ).'" class="draggable-item">';
                                $output .=esc_html__( 'Category', 'enovathemes-addons' );
                                $output .='<span class="remove"></span>';
                            $output .='</li>';
                            foreach ($attributes as $attribute => $data){
                                $attr_data = 'attr:'.esc_attr($attribute).',label:'.esc_attr($data['label']);
                                $output .='<li data-attribute="'.$attr_data.'" data-title="'.esc_attr($data['label']).'" class="draggable-item">';
                                    $output .= esc_html($data['label']);
                                    $output .='<span class="remove"></span>';
                                $output .='</li>';
                            }
                        $output .='</ul>';
                        $output .='<h4>'.esc_html__( 'Drop here filter attributes', 'enovathemes-addons' ).'</h4>';
                        $output .='<ul class="sortable"></ul>';
                    }

                    $output .='<input name="'.esc_attr( $settings['param_name'] ).'" class="atts wpb_vc_param_value wpb-textinput '.esc_attr( $settings['param_name'] ) . ' ' .esc_attr( $settings['type'] ) . '_field" type="hidden" value="'. esc_attr( $value ).'" />';

                $output .='</div>';

                return $output;

            }
        }
        
    }


    function enovathemes_addons_search_join( $join ){
       global $wpdb;
       $join .= " LEFT JOIN $wpdb->postmeta gm ON (" . 
       $wpdb->posts . ".ID = gm.post_id AND gm.meta_key='_sku')"; // change to your meta key if not woo

       return $join;
    }

    function enovathemes_addons_search_where( $where ){
       global $wpdb;
       $where = preg_replace(
         "/\(\s*{$wpdb->posts}.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
         "({$wpdb->posts}.post_title LIKE $1) OR (gm.meta_value LIKE $1)", $where );
       return $where;
    }
    /* grouping by id to make sure no dupes */
    function enovathemes_addons_search_groupby( $groupby ){
       global $wpdb;
       $mygroupby = "{$wpdb->posts}.ID";
       if( preg_match( "/$mygroupby/", $groupby )) {
         // grouping we need is already there
         return $groupby;
       }
       if( !strlen(trim($groupby))) {
          // groupby was empty, use ours
          return $mygroupby;
       }
       // wasn't empty, append ours
       return $groupby . ", " . $mygroupby;
    }

    add_action( 'pre_get_posts', 'enovathemes_addons_pre_get_post' );
    function enovathemes_addons_pre_get_post( $query ) {

        global $propharm_enovathemes;

        if( (is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' )) && !is_admin() && $query->is_main_query() ) {

            $product_per_page  = (isset($GLOBALS['propharm_enovathemes']['product-per-page']) && !empty($GLOBALS['propharm_enovathemes']['product-per-page'])) ? $GLOBALS['propharm_enovathemes']['product-per-page'] : get_option( 'posts_per_page' );

            $query->set( 'posts_per_page', $product_per_page );

            if (isset($_GET["s"]) && !empty($_GET["s"]) && $query->is_search) {

                add_filter('posts_join', 'enovathemes_addons_search_join' );
                add_filter('posts_where', 'enovathemes_addons_search_where' );
                add_filter('posts_groupby', 'enovathemes_addons_search_groupby' );
                
            }

        }

    }

    remove_filter( 'the_content', 'wp_make_content_images_responsive' );
    add_action('init', 'enovathemes_addons_disable_responsive_images');
    function enovathemes_addons_disable_responsive_images() {

        add_filter( 'wp_get_attachment_image_attributes', function( $attr ){
            if( isset( $attr['sizes'] ) ){unset( $attr['sizes'] );}
            if( isset( $attr['srcset'] ) ){unset( $attr['srcset'] );}
            $attr['data-responsive-images'] = 'false';
            return $attr;

        }, PHP_INT_MAX );

        add_filter( 'wp_calculate_image_sizes', '__return_empty_array',  PHP_INT_MAX );
        add_filter( 'wp_calculate_image_srcset', '__return_empty_array', PHP_INT_MAX );
        remove_filter( 'the_content', 'wp_make_content_images_responsive' );
    }

    add_action( 'redux/loaded', 'propharm_enovathemes_remove_demo' );
    if ( ! function_exists( 'propharm_enovathemes_remove_demo' ) ) {
        function propharm_enovathemes_remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }
    }

/*  Visual composer front-end save
/*-------------------*/

    function enovathemes_addons_et_vc_save(){

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            if (isset($_POST["post_id"]) && !empty($_POST["post_id"])) {

                $post_id   = $_POST["post_id"];
                $post_info = get_post($post_id);

                if (!is_wp_error($post_info) && is_object($post_info)) {

                    $post_type = $post_info->post_type;

                    if (
                        $post_type == "post" ||
                        $post_type == "page" ||
                        $post_type == "product" ||
                        $post_type == "header" ||
                        $post_type == "footer" ||
                        $post_type == "megamenu" ||
                        $post_type == "title_section" ||
                        $post_type == "banner"
                    ) {
                        $element_css  = true;
                        $element_font = true;
                        $content      = urldecode($_POST["content"]);

                        if (!empty($content)) {
                            enovathemes_addons_extract_shortcode_attrs($post_id,$content,$element_css,$element_font);
                        }
                    }

                }

            }
            die;

        }

        die();

    }

    add_action('wp_ajax_nopriv_et_vc_save', 'enovathemes_addons_et_vc_save');
    add_action('wp_ajax_et_vc_save', 'enovathemes_addons_et_vc_save');

/*  Google fonts
/*-------------------*/

    function enovathemes_addons_google_fonts() {

        $api_key = 'AIzaSyD4_siUiwNbGDKcVNPQjCl-6eyzhctrPsM';
        $url     = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $api_key;

        $transient_prefix = $api_key;

        if ( false === ( $google_fonts = get_transient( 'gfonts-' . $transient_prefix . '-enovathemes' ) ) ) {

            $remote = wp_remote_get( $url );

            if ( is_wp_error( $remote ) ) {
                return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Google fonts.', 'enovathemes-addons' ) );
            }

            if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
                return new WP_Error( 'invalid_response', esc_html__( 'Google fonts did not return a 200.', 'enovathemes-addons' ) );
            }

            $gfonts_array = json_decode( $remote['body'], true );

            if ( ! $gfonts_array ) {
                return new WP_Error( 'bad_json', esc_html__( 'Google fonts has returned invalid data.', 'enovathemes-addons' ) );
            }

            if ( isset( $gfonts_array['items'] ) ) {
                $fonts = $gfonts_array['items'];
            } else {
                return new WP_Error( 'bad_json_2', esc_html__( 'Google fonts has returned invalid data.', 'enovathemes-addons' ) );
            }

            if ( ! is_array( $fonts ) ) {
                return new WP_Error( 'bad_array', esc_html__( 'Google fonts has returned invalid data.', 'enovathemes-addons' ) );
            }

            $google_fonts = array();

            foreach ( $fonts as $font ) {
                $google_fonts[] = array(
                    'family'   => $font['family'],
                    'variants' => $font['variants'],
                    'subsets'  => $font['subsets']
                );
            } // End foreach().

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $google_fonts ) ) {
                $google_fonts = base64_encode(serialize( $google_fonts ));
                set_transient( 'gfonts-' . $transient_prefix . '-enovathemes', $google_fonts, apply_filters( 'null_gfonts_cache_time', MONTH_IN_SECONDS * 2 ) );
            }
        }

        if ( ! empty( $google_fonts ) ) {

            return unserialize(base64_decode( $google_fonts ));

        } else {

            return new WP_Error( 'no_fonts', esc_html__( 'Google fonts did not return any fonts.', 'enovathemes-addons' ) );

        }
    }

    function enovathemes_addons_create_dynamic_scripts($element_font){
        if (!empty($element_font)) {

            $element_font_builder = array();

            $element_font = explode(",", $element_font);

            foreach ($element_font as $font) {
                $font = explode(":", $font);
                array_push($element_font_builder, $font);
            }

            $element_font_result = array();
            foreach ($element_font_builder as $font_style) {

                if (!isset($element_font_result[$font_style[0]])){
                    $element_font_result[$font_style[0]] = $font_style;
                }else{

                    if (array_key_exists(2,$font_style)) {

                        if (strpos($element_font_result[$font_style[0]][1], $font_style['1'])) {
                            $element_font_result[$font_style[0]][2] = $element_font_result[$font_style[0]][2];
                        } else {
                            $element_font_result[$font_style[0]][1] = $element_font_result[$font_style[0]][1].','.$font_style['1'];
                            $element_font_result[$font_style[0]][2] = $element_font_result[$font_style[0]][2];
                        }

                    } else {
                        if (strpos($element_font_result[$font_style[0]][1], $font_style['1']) !== false) {
                            $element_font_result[$font_style[0]][2] = $element_font_result[$font_style[0]][2];
                        }
                    }

                }
            }

            $element_font_result = array_values($element_font_result);

            $element_font_output = array();

            foreach ($element_font_result as $font_output) {

                if ($font_output[0] != "Theme default") {
                    $font_output = array(str_replace(' ', '+', $font_output[0]),$font_output[1],$font_output[2]);
                    array_push($element_font_output, array(
                        'font-name' => $font_output[0],
                        'font-style'=> str_replace('italic','i',$font_output[1]),
                        'subset'    => $font_output[2]
                    ));
                }

            }

            return $element_font_output;
        }
    }

/*  Flickr
/*-------------------*/

    function enovathemes_addons_flickr($user_id,$per_page) {

        global $propharm_enovathemes;
        $api_key = (isset($propharm_enovathemes['flickr-api']) && !empty($propharm_enovathemes['flickr-api'])) ? esc_attr($propharm_enovathemes['flickr-api']): "";

        $transient_prefix = $api_key.esc_attr($user_id);

        if ( false === ( $flickr_images = get_transient( 'flickr-' . $transient_prefix . '-enovathemes' ) ) ) {

            $params = array(
                'api_key'   => $api_key,
                'method'    => 'flickr.people.getPublicPhotos',
                'user_id'   => $user_id,
                'per_page'  => $per_page,
                'format'    => 'php_serial',
                'content_type' => 1,
                'privacy_filter' => 1,
                'extras'    => 'url_q'
            );

            $encoded_params = array();
            foreach ($params as $k => $v){
                $encoded_params[] = urlencode($k).'='.urlencode($v);
            }

            $url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);

            $rsp = file_get_contents($url);

            if ($rsp === FALSE) {
                return new WP_Error( 'flickr_no_images', esc_html__( 'Server Error. Not data is found', 'enovathemes-addons' ) );
            }

            $rsp_obj = unserialize($rsp);

            if ($rsp_obj['stat'] == 'ok'){

                if ($rsp_obj['photos']['photo']) {
                    foreach ( $rsp_obj['photos']['photo'] as $photo ) {
                        $flickr_images[] = array(
                            'url_q'   => $photo['url_q'],
                            'url_o'   => '//flickr.com/photos/'.$user_id,
                        );
                    }
                } else {
                    return new WP_Error( 'flickr_no_images', esc_html__( 'Flickr did not find any images.', 'enovathemes-addons' ) );
                }

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $flickr_images ) ) {

                    $flickr_images = base64_encode(serialize($flickr_images));

                    set_transient( 'flickr-' . $transient_prefix . '-enovathemes', $flickr_images, apply_filters( 'null_flickr_cache_time', HOUR_IN_SECONDS * 2 ) );
                }
            } else {
                return $rsp_obj['message'];
            }

        }

        if ( ! empty( $flickr_images ) ) {
            return unserialize(base64_decode($flickr_images));
        } else {
            return new WP_Error( 'flickr_no_images', esc_html__( 'Flickr did not find any images.', 'enovathemes-addons' ) );
        }
    }

/*  Mailchimp
/*-------------------*/

    function enovathemes_addons_mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() ) {
        if( $request_type == 'GET' )
            $url .= '?' . http_build_query($data);

        $mch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode( 'user:'. $api_key )
        );
        curl_setopt($mch, CURLOPT_URL, $url );
        curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
        curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
        curl_setopt($mch, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

        if( $request_type != 'GET' ) {
            curl_setopt($mch, CURLOPT_POST, true);
            curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
        }

        return curl_exec($mch);
    }

    function enovathemes_addons_mailchimp_subscriber_status( $email, $status, $list_id, $api_key, $merge_fields = array('FNAME' => '','LNAME' => '','ADDRESS' => '','PHONE' => '') ){

        $data = array(
            'apikey'        => $api_key,
            'email_address' => $email,
            'status'        => $status,
            'merge_fields'  => $merge_fields
        );
        $mch_api = curl_init(); // initialize cURL connection

        curl_setopt($mch_api, CURLOPT_URL, 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address'])));
        curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.base64_encode( 'user:'.$api_key )));
        curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
        curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
        curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch_api, CURLOPT_POST, true);
        curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json

        $result = curl_exec($mch_api);
        return $result;
    }

    function enovathemes_addons_mailchimp_list() {

        global $propharm_enovathemes;
        $mailchimp_api_key = (isset($propharm_enovathemes['mailchimp-api-key']) && !empty($propharm_enovathemes['mailchimp-api-key'])) ? esc_attr($propharm_enovathemes['mailchimp-api-key']): "";

        $api_key = $mailchimp_api_key;
        $transient_prefix = $api_key;

        if (empty($api_key)) {
            return new WP_Error( 'no_api_key', esc_html__( 'No Mailchimp API key is found. Go to theme options >> general >> Mailchimp API key', 'enovathemes-addons' ) );
        }

        $url = 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/';

        if ( false === ( $mailchimp_list = get_transient( 'mailchimp-' . $transient_prefix . '-enovathemes' ) ) ) {

            $data = array(
                'fields' => 'lists',
                'count' => 'all',
            );

            $result = json_decode( enovathemes_addons_mailchimp_curl_connect( $url, 'GET', $api_key, $data) );

            if (! $result ) {
                return new WP_Error( 'bad_json', esc_html__( 'Mailchimp has returned invalid data.', 'enovathemes-addons' ) );
            }

            if ( !empty( $result->lists ) ) {
                foreach( $result->lists as $list ){
                    $mailchimp_list[] = array(
                        'id'      => $list->id,
                        'name'    => $list->name,
                    );
                }
            } elseif(is_int( $result->status)) {
                return '<strong>' . $result->title . ':</strong> ' . $result->detail;
            } else {
                return new WP_Error( 'bad_json_2', esc_html__( 'Mailchimp has returned invalid data.', 'enovathemes-addons' ) );
            }

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $mailchimp_list ) ) {
                $mailchimp_list = base64_encode(serialize($mailchimp_list));
                set_transient( 'mailchimp-' . $transient_prefix . '-enovathemes', $mailchimp_list, apply_filters( 'null_mailchimp_cache_time', WEEK_IN_SECONDS * 2 ) );
            }
        }

        if ( ! empty( $mailchimp_list ) ) {

            return unserialize(base64_decode($mailchimp_list));

        } else {

            return new WP_Error( 'no_list', esc_html__( 'Mailchimp did not return any list.', 'enovathemes-addons' ) );

        }
    }

    function enovathemes_addons_mailchimp_subscribe(){

        global $post, $propharm_enovathemes;
        if (! isset( $_POST['id'] )) {
            exit;
        } else {

            $nonce = 'et_mailchimp_nonce_'.$_POST['id'];

            if ( ! isset( $_POST[$nonce] ) || !wp_verify_nonce( $_POST[$nonce], 'et_mailchimp_action' )) {
               echo esc_html__("Sorry, your nonce did not verify.", "enovathemes-addons");
               exit;
            } else {

                $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
                $fname   = strip_tags(trim($_POST["fname"]));
                $lname   = strip_tags(trim($_POST["lname"]));
                $phone   = strip_tags(trim($_POST["phone"]));
                $list    = strip_tags(trim($_POST["list"]));

                $list_id = $list;
                $api_key = (isset($propharm_enovathemes['mailchimp-api-key']) && !empty($propharm_enovathemes['mailchimp-api-key'])) ? esc_attr($propharm_enovathemes['mailchimp-api-key']): "";
                $result  = json_decode( enovathemes_addons_mailchimp_subscriber_status($email, 'subscribed', $list_id, $api_key, array('FNAME' => $fname,'LNAME' => $lname,'PHONE' => $phone) ) );

                if( $result->status == 400 ){
                    foreach( $result->errors as $error ) {
                        echo '<p>Error: ' . $error->message . '</p>';
                    }
                } elseif( $result->status == 'subscribed' ){
                    echo 'Thank you, ' . $result->merge_fields->FNAME . '. You have subscribed successfully';
                }

                die;
            }
        }
    }

    add_action('admin_post_nopriv_et_mailchimp', 'enovathemes_addons_mailchimp_subscribe');
    add_action('admin_post_et_mailchimp', 'enovathemes_addons_mailchimp_subscribe');

/*  Post social share
/*-------------------*/

    function enovathemes_addons_post_social_share($class){
        $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
        $output = '<div id="post-social-share" class="post-social-share '.esc_attr($class).' et-social-links">';
            $output .= '<div class="social-links et-social-links styling-original-true">';

                if (et_get_social_icon() && isset(et_get_social_icon()['facebook'])) {
                    $output .= '<a title="'.esc_html__("Share on Facebook", 'enovathemes-addons').'" class="social-share post-facebook-share facebook" target="_blank" href="//facebook.com/sharer.php?u='.urlencode(get_the_permalink(get_the_ID())).'">'.et_get_social_icon()['facebook'].'</a>';
                }

                if (et_get_social_icon() && isset(et_get_social_icon()['tweet'])) {
                    $output .= '<a title="'.esc_html__("Tweet this!", 'enovathemes-addons').'" class="social-share post-twitter-share twitter" target="_blank" href="//twitter.com/intent/tweet?text='.urlencode(get_the_title(get_the_ID()).' - '.get_the_permalink(get_the_ID())).'">'.et_get_social_icon()['twitter'].'</a>';
                }

                if (et_get_social_icon() && isset(et_get_social_icon()['pinterest'])) {
                    $output .= '<a title="'.esc_html__("Share on Pinterest", 'enovathemes-addons').'" class="social-share post-pinterest-share pinterest" target="_blank" href="//pinterest.com/pin/create/button/?url='.urlencode(get_the_permalink(get_the_ID())).'&media='.urlencode(esc_url($url)).'&description='.rawurlencode(get_the_title(get_the_ID())).'">'.et_get_social_icon()['pinterest'].'</a>';
                }

                if (et_get_social_icon() && isset(et_get_social_icon()['linkedin'])) {
                    $output .= '<a title="'.esc_html__("Share on LinkedIn", 'enovathemes-addons').'" class="social-share post-linkedin-share linkedin" target="_blank" href="//www.linkedin.com/shareArticle?mini=true&url='.urlencode(get_the_permalink(get_the_ID())).'&title='.rawurlencode(get_the_title(get_the_ID())).'">'.et_get_social_icon()['linkedin'].'</a>';
                }

                if (et_get_social_icon() && isset(et_get_social_icon()['whatsapp'])) {
                    $output .= '<a title="'.esc_html__("Share on Whatsapp", 'enovathemes-addons').'" class="whatsapp social-share post-whatsapp-share" target="_blank" href="whatsapp://send?text='.urlencode(get_the_permalink(get_the_ID())).'">'.et_get_social_icon()['whatsapp'].'</a>';
                }

                if (et_get_social_icon() && isset(et_get_social_icon()['viber'])) {
                    $output .= '<a title="'.esc_html__("Share on Viber", 'enovathemes-addons').'" class="viber social-share post-viber-share" target="_blank" href="viber://forward?text='.urlencode(get_the_permalink(get_the_ID())).'">'.et_get_social_icon()['viber'].'</a>';
                }

                if (et_get_social_icon() && isset(et_get_social_icon()['telegram'])) {
                    $output .= '<a title="'.esc_html__("Share on Telegram", 'enovathemes-addons').'" class="telegram social-share post-telegram-share" target="_blank" href="tg://msg_url?url='.urlencode(get_the_permalink(get_the_ID())).'&text='.rawurlencode(get_the_title(get_the_ID())).'">'.et_get_social_icon()['telegram'].'</a>';
                }

            $output .= '</div>';
        $output .= '</div>';
        return $output;
    }

    add_action('wp_head', 'enovathemes_addons_open_graph_tags');
    function enovathemes_addons_open_graph_tags(){ ?>
        <?php

        if (defined( 'WPSEO_PATH' )) {
            return;
        }

        global $post;

        $sitename    = get_bloginfo('name');
        $image       = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()),"full");
        $url         = get_the_permalink(get_the_ID());
        $title       = get_the_title(get_the_ID());
        $description = (has_excerpt(get_the_ID())) ? get_the_excerpt(get_the_ID()) : '';

        ?>
        <?php if ($title): ?>
            <meta property="og:site_name" content="<?php echo esc_attr($sitename); ?>" />
            <meta name="twitter:title" content="<?php echo esc_attr($sitename); ?>">
        <?php endif ?>
        <?php if ($url): ?>
            <meta property="og:url" content="<?php echo esc_url($url); ?>" />
            <meta property="og:type" content="article" />
        <?php endif ?>
        <?php if ($title): ?>
            <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
        <?php endif ?>
        <?php if ($description): ?>
            <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
            <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
        <?php endif ?>
        <?php if ($image): ?>
            <meta property="og:image" content="<?php echo esc_url($image[0]); ?>" />
            <meta property="og:image:width" content="<?php echo esc_attr($image[1]); ?>" />
            <meta property="og:image:height" content="<?php echo esc_attr($image[2]); ?>" />
            <meta name="twitter:image" content="<?php echo esc_url($image[0]); ?>">
            <meta name="twitter:card" content="summary_large_image">
        <?php endif ?>

    <?php }

/* SVGs
/*-------------------*/

    function et_get_theme_icon() {

        $dir = get_template_directory().'/images/icons/';

        if ( false === ( $icons = get_transient( 'enovathemes-icons' ) ) ) {

            if (is_dir($dir)) {

                $icons = array_diff(scandir($dir), array('..', '.','dashboard','social','php'));

                $icons_array = array();

                foreach ($icons as $icon) {

                    $name = basename($icon,'.svg');

                    $icons_array[$name] = file_get_contents(THEME_SVG.$name.'.svg');
                }

                $icons = $icons_array;

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $icons ) ) {
                    $icons = base64_encode(serialize( $icons ));
                    set_transient( 'enovathemes-icons', $icons, apply_filters( 'null_icons_cache_time', 0 ) );
                }

            }
        }

        if ( ! empty( $icons ) ) {

            return unserialize(base64_decode($icons));

        } else {
            return false;
        }
    }

    function et_get_dashboard_icon() {

        $dir = get_template_directory().'/images/icons/dashboard/';

        if ( false === ( $icons = get_transient( 'enovathemes-dashboard-icons' ) ) ) {

            if (is_dir($dir)) {

                $icons = array_diff(scandir($dir), array('..', '.'));

                $icons_array = array();

                foreach ($icons as $icon) {

                    $name = basename($icon,'.svg');

                    $icons_array[$name] = file_get_contents(THEME_SVG.'dashboard/'.$name.'.svg');
                }

                $icons = $icons_array;

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $icons ) ) {
                    $icons = base64_encode(serialize( $icons ));
                    set_transient( 'enovathemes-dashboard-icons', $icons, apply_filters( 'null_icons_cache_time', 0 ) );
                }
            }
        }

        if ( ! empty( $icons ) ) {

            return unserialize(base64_decode($icons));

        } else {
            return false;
        }
    }

    function et_get_icon($dir) {

        $name = basename($dir);
        $name = str_replace(' ', '_', $name);

        if ( false === ( $icon = get_transient( 'et_icon_'.$name ) ) ) {
            
            $icon = file_get_contents($dir);

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $icon ) ) {
                $icon = base64_encode(serialize( $icon ));
                set_transient( 'et_icon_'.$name, $icon, apply_filters( 'null_icon_cache_time', 0 ) );
            }
        }

        if ( ! empty( $icon ) ) {

            return unserialize(base64_decode($icon));

        } else {
            return false;
        }
    }

    function et_get_social_icon() {

        $dir = get_template_directory().'/images/icons/social/';

        if ( false === ( $icons = get_transient( 'enovathemes-s-icons' ) ) ) {

            if (is_dir($dir)) {

                $icons = array_diff(scandir($dir), array('..', '.'));

                $icons_array = array();

                foreach ($icons as $icon) {

                    $name = basename($icon,'.svg');

                    $icons_array[$name] = file_get_contents(THEME_SVG.'social/'.$name.'.svg');
                }

                $icons = $icons_array;

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $icons ) ) {
                    $icons = base64_encode(serialize( $icons ));
                    set_transient( 'enovathemes-s-icons', $icons, apply_filters( 'null_icons_cache_time', 0 ) );
                }

            }
        }

        if ( ! empty( $icons ) ) {

            return unserialize(base64_decode($icons));

        } else {
            return false;
        }
    }

/* Social icons
/*-------------------*/

    function enovathemes_addons_social_icons($dir) {

        if ( false === ( $social = get_transient( 'enovathemes-social-icons' ) ) ) {

            $social = array_diff(scandir($dir), array('..', '.'));

            $social_array = array();

            foreach ($social as $icon) {
                array_push($social_array,basename($icon,'.svg'));
            }

            $social = $social_array;

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $social ) ) {
                $social = base64_encode(serialize( $social ));
                set_transient( 'enovathemes-social-icons', $social, apply_filters( 'null_social_cache_time', 0 ) );
            }
        }

        if ( ! empty( $social ) ) {

            return unserialize(base64_decode($social));

        } else {

            return new WP_Error( 'no_icons', esc_html__( 'No icons.', 'propharm' ) );

        }
    }

/*  Inline image placeholder
/*-------------------*/

    function enovathemes_addons_inline_image_placeholder($id,$size = 'full',$class = ''){

        $output = '';

        $image        = wp_get_attachment_image_src($id,$size);
        $image_src    = $image[0];
        $image_width  = $image[1];
        $image_height = $image[2];

        $thumbnail_alt = get_post_meta($id, '_wp_attachment_image_alt', true); 
        $image_caption = get_the_post_thumbnail_caption($image);
        $image_alt     = (empty($image_caption)) ? ((empty($thumbnail_alt)) ? get_bloginfo('name') : $thumbnail_alt) : $image_caption;
        
        $data_img      = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";

        $x_center = ($image_width/2);
        $y_center = ($image_height/2);

        $cl = array('lazy-inline-image');

        if(!empty($class)){
            $cl[]= $class;  
        }

        $output .= '<div style="max-width:'.$image_width.'px;max-height:'.$image_height.'px;" class="'.implode(' ', $cl).'">';
            $output .= '<img class="lazy" src="'.$data_img.'" width="'.esc_attr($image_width).'" height="'.esc_attr($image_height).'" data-src="'.esc_url($image_src).'" alt="'.esc_html($image_alt).'" />';
            $output .= '<svg class="media-placeholder" viewBox="0 0 '.$image_width.' '.$image_height.'"><path d="M0,0H'.$image_width.'V'.$image_height.'H0V0Z" /></svg>';
            if (et_get_theme_icon() && isset(et_get_theme_icon()['placeholder'])) {
                $output .= et_get_theme_icon()['placeholder'];
            }
        $output .= '</div>';

        if (!empty($output)) {
            return $output;
        }
        
    }

/*  Breadcrumbs
/*-------------------*/

    function enovathemes_addons_breadcrumbs() {

        global $post, $propharm_enovathemes;

        $text_before = '<span>';
        $text_after  = '</span>';
        $link_after  = (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? et_get_theme_icon()['arrow'] : '';
        $output      = '';

        $home_text     = esc_html__('Home','enovathemes-addons');

        if(!empty(get_option('page_on_front')))
        $home_text = get_the_title( get_option('page_on_front') );

        $category_text = esc_html__('Category "%s"','enovathemes-addons');
        $tax_text      = $category_text;
        $tag_text      = esc_html__('Posts tagged "%s"','enovathemes-addons');
        $author_text   = esc_html__('Articles posted by %s','enovathemes-addons');
        $error_text    = esc_html__('Error 404','enovathemes-addons');
        $search_text   = esc_html__('Search results for "%s" Query','enovathemes-addons');
        $wishlist_text = esc_html__("Wishlist", 'enovathemes-addons');

        $blog_text     = (isset($GLOBALS['propharm_enovathemes']['blog-title-text']) && $GLOBALS['propharm_enovathemes']['blog-title-text']) ? $GLOBALS['propharm_enovathemes']['blog-title-text'] : esc_html__("Blog", "enovathemes-addons");
        $product_text  = (isset($GLOBALS['propharm_enovathemes']['product-title-text']) && $GLOBALS['propharm_enovathemes']['product-title-text']) ? $GLOBALS['propharm_enovathemes']['product-title-text'] : esc_html__("Shop", "enovathemes-addons");

        $home_link = esc_url(home_url('/'));
        $blog_link = get_post_type_archive_link( 'post' );
        $shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';

        if (is_home() && is_front_page()) {
            // Post is frontpage
            $output .= $text_before . $blog_text . $text_after;
        } elseif (is_home() && !is_front_page()) {
            // Post is separate page
            $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
            if ( get_query_var('paged') ) {
               $output .= '<a href="' . $blog_link . '">' . $blog_text . '</a>'.$link_after;
            } else {
               $output .= $text_before . $blog_text . $text_after;
            }

        } elseif (is_front_page() && !is_home()) {
            // Front page and not post page
            $output .= $text_before . $home_text . $text_after;
        } else {

            /*  Page
            -------------------*/

                if (is_page()) {

                    $page_title = get_the_title();

                    $wishlistpage    = "false";
                    $wishlistpage_id = get_option('yith_wcwl_wishlist_page_id');
                    if (defined('YITH_WCWL') && !empty($wishlistpage_id)) {
                        $wishlistpage = (is_page(get_option('yith_wcwl_wishlist_page_id'))) ? "true" : "false";
                    }

                    if ($wishlistpage == "true") {
                        $page_title = $wishlist_text;
                    }

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;

                    if (class_exists('Woocommerce')) {

                        if (is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url() || $wishlistpage == "true") {
                            $output .= '<a href="' . $shop_link . '">' . $product_text . '</a>'.$link_after;
                        }

                    }

                    if ($post->post_parent) {

                        $this_parents = get_post_ancestors($post->ID);

                        foreach (array_reverse($this_parents) as $parent_ID) {
                            $output .= '<a href="'.get_page_link($parent_ID, false, false).'">'.get_the_title($parent_ID).'</a>'.$link_after;
                        }

                        $output .= $text_before.$page_title.$text_after;

                    } else {
                        $output .= $text_before.$page_title.$text_after;
                    }
                }

            /*  Single post
            -------------------*/

                if (is_singular( 'post' )) {

                    $this_cats         = get_the_category();
                    $first_cat         = $this_cats[0];
                    $first_cat_link    = get_category_link($first_cat->cat_ID);

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= '<a href="' . $blog_link . '">' . $blog_text . '</a>'.$link_after;

                    if ($first_cat->parent) {
                        $first_cat_parents = get_category_parents($first_cat->parent, true, '');
                        $output .= $first_cat_parents;
                    }

                    $output .= '<a href="'.$first_cat_link.'">'. $first_cat->name .'</a>'.$link_after;
                    $output .= $text_before.get_the_title().$text_after;

                }

            /*  Category / Tag / Taxonomy
            -------------------*/

                if ( is_category() ) {

                    $this_cat = get_category(get_query_var('cat'), false);

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= '<a href="' . $blog_link . '">' . $blog_text . '</a>'.$link_after;

                    if ($this_cat->parent != 0) {
                        $this_parents = get_category_parents($this_cat->parent, true, '');
                        $output .= $this_parents.$link_after;
                        $output .= $text_before . sprintf($category_text, single_cat_title('', false)) . $text_after;
                    } else {
                        $output .= $text_before . sprintf($category_text, single_cat_title('', false)) . $text_after;
                    }

                }

                if (is_tag()) {

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= '<a href="' . $blog_link . '">' . $blog_text . '</a>'.$link_after;
                    $output .= $text_before . sprintf($tag_text, single_tag_title('', false)) . $text_after;

                }

            /*  Date
            -------------------*/

                if ( is_day() ) {

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= '<a href="'.get_year_link(get_the_time('Y'),get_the_time('Y')).'">'. get_the_time('Y') .'</a>'.$link_after;
                    $output .= '<a href="'.get_month_link(get_the_time('Y'),get_the_time('m')).'">'. get_the_time('F') .'</a>'.$link_after;
                    $output .= $text_before . get_the_time('d') . $text_after;

                }

                if ( is_month() ) {

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= '<a href="'.get_year_link(get_the_time('Y'),get_the_time('Y')).'">'. get_the_time('Y') .'</a>'.$link_after;
                    $output .= $text_before . get_the_time('F') . $text_after;
                }

                if ( is_year() ) {

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= $text_before . get_the_time('Y') . $text_after;
                }

            /*  Misc
            -------------------*/

                if ( is_search() ) {

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;

                    $cpt_list = get_post_types( array(
                        'public' => true,
                        'publicly_queryable' => true,
                        'exclude_from_search'=> false,
                        '_builtin' => false,
                    ), 'objects', 'and' );

                    if (is_array($cpt_list)) {
                        foreach ($cpt_list as $cpt) {

                            $cpt_title = $cpt->labels->name;

                            switch ($cpt->name) {
                                case 'product':
                                    $cpt_title = $product_text;
                                    break;
                            }

                            if (is_post_type_archive($cpt->name)) {
                                $output .= '<a href="' . get_post_type_archive_link($cpt->name) . '">' . $cpt_title . '</a>'.$link_after;
                            }

                        }
                    }


                    $output .= $text_before . sprintf($search_text, get_search_query()) . $text_after;

                }

                if ( is_author() ) {
                    global $author;
                    $userdata = get_userdata($author);

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= $text_before . sprintf($author_text, $userdata->display_name) . $text_after;

                }

                if ( is_404() ) {

                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                    $output .= $text_before . $error_text . $text_after;
                }

            /*  CPT
            -------------------*/

                if (!is_search()  && !is_404()) {

                    $cpt_list = get_post_types( array(
                        'public' => true,
                        'publicly_queryable' => true,
                        'exclude_from_search'=> false,
                        '_builtin' => false,
                    ), 'objects', 'and' );

                    if (is_array($cpt_list)) {
                        foreach ($cpt_list as $cpt) {

                            $cpt_title = $cpt->labels->name;

                            switch ($cpt->name) {
                                case 'product':
                                    $cpt_title = $product_text;
                                    break;
                            }

                            /*  Archive
                            -------------------*/

                                if (is_post_type_archive($cpt->name)) {

                                    $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;

                                    if ( get_query_var('paged') ) {
                                       $output .= '<a href="' . get_post_type_archive_link($cpt->name) . '">' . $cpt_title . '</a>'.$link_after;
                                    } else {
                                       $output .= $text_before . $cpt_title . $text_after;
                                    }

                                }

                            /*  Taxonomy
                            -------------------*/

                                $sel  = (isset($_GET["sel"]) && !empty($_GET["sel"])) ? urldecode($_GET["sel"]) : false;

                                if ($sel == false) {

                                    $cpt_taxonomies = get_object_taxonomies($cpt->name);
                                    if (is_array($cpt_taxonomies)) {
                                        foreach ($cpt_taxonomies as $cpt_tax) {
                                            if (is_tax($cpt_tax)) {


                                                $this_tax    = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                                $this_parents = ($this_tax) ? get_ancestors( $this_tax->term_id, get_query_var('taxonomy') ) : '';

                                                $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                                                $output .= '<a href="'.get_post_type_archive_link($cpt->name).'">'. $cpt_title .'</a>'.$link_after;

                                                if (is_array($this_parents) && !empty($this_parents)) {
                                                    foreach (array_reverse($this_parents) as $this_parent_ID) {
                                                        $this_parent = get_term($this_parent_ID, get_query_var('taxonomy'));
                                                        $output .= '<a href="'.get_term_link( $this_parent->slug, get_query_var('taxonomy')).'">'. $this_parent->name .'</a>'.$link_after;
                                                    }
                                                    $output .= $text_before . sprintf($tax_text, single_cat_title('', false)) . $text_after;
                                                } else {
                                                    $output .= $text_before . sprintf($tax_text, single_cat_title('', false)) . $text_after;
                                                }

                                            }
                                        }
                                    } else {
                                        if (is_tax()) {

                                            $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                                            $output .= $text_before . sprintf($tax_text, single_cat_title('', false)) . $text_after;

                                        }
                                    }

                                }

                            /*  Single post
                            -------------------*/

                                if ($cpt->name == 'product') {

                                    if (is_singular( 'product' )) {

                                        $this_terms    = get_the_terms( $post->ID, 'product_cat');
                                        $this_terms_ID = array();
                                        $final_term    = array();
                                        $stop          = false;

                                        foreach ($this_terms as $term) {
                                            array_push($this_terms_ID, $term->term_id);
                                        }

                                        foreach ($this_terms as $term) {
                                            if ($term->parent) {
                                                if (in_array($term->parent, $this_terms_ID)) {
                                                    if ($stop == false) {
                                                        $final_term = array(
                                                            'id'     => $term->term_id,
                                                            'slug'   => $term->slug,
                                                            'name'   => $term->name,
                                                            'parent' => $term->parent,
                                                        );
                                                        $stop = true;
                                                    }
                                                }
                                            }
                                        }

                                        if (empty($final_term)) {
                                            $final_term = array(
                                                'id'   => $this_terms[0]->term_id,
                                                'slug' => $this_terms[0]->slug,
                                                'name' => $this_terms[0]->name
                                            );
                                        }

                                        $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                                        $output .= '<a href="' . $shop_link . '">' . $product_text . '</a>'.$link_after;

                                        if (!empty($final_term)) {
                                            if (!empty($final_term['parent'])) {
                                                $parent_term = get_term_by('id',$final_term['parent'],'product_cat');
                                                $output .= '<a href="'.get_term_link( $parent_term->slug, 'product_cat').'">'. $parent_term->name .'</a>'.$link_after;
                                            }
                                            $output .= '<a href="'.get_term_link( $final_term['slug'], 'product_cat').'">'. $final_term['name'] .'</a>'.$link_after;
                                        }

                                        $output .= $text_before.get_the_title().$text_after;

                                    }

                                } else {

                                    if (is_singular() && $cpt->name != 'product' && !is_single() && !is_page()) {
                                        $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                                        $output .= $text_before.get_the_title().$text_after;
                                    }

                                }

                        }
                    } else {
                        if (is_tax()) {

                            $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;
                            $output .= $text_before . sprintf($tax_text, single_cat_title('', false)) . $text_after;

                        }
                    }

                }

        }

        if ( get_query_var('paged') ) {
            $output .= $text_before.esc_html__('Page','enovathemes-addons') . ' ' . get_query_var('paged').$text_after;
        }

        return $output;
    }

/*  Hex to rgba
/*-------------------*/

    function enovathemes_addons_hex_to_rgba($hex, $o) {
        $hex = (string) $hex;
        $hex = str_replace("#", "", $hex);
        $hex = array_map('hexdec', str_split($hex, 2));
        return 'rgba('.implode(",", $hex).','.$o.')';
    }

/*  Hex to rgb shade
/*-------------------*/

    function enovathemes_addons_hex_to_rgb_shade($hex, $o) {
        $hex = (string) $hex;
        $hex = str_replace("#", "", $hex);
        $hex = array_map('hexdec', str_split($hex, 2));
        $hex[0] -= $o;
        $hex[1] -= $o;
        $hex[2] -= $o;
        return 'rgb('.implode(",", $hex).')';
    }

/*  Brightness detection
/*-------------------*/

    function enovathemes_addons_brightness($hex) {
        $hex = (string) $hex;
        $hex = str_replace("#", "", $hex);
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $output = 'dark';

        if($r + $g + $b > 382){
            $output = 'light';
        }else{
            $output = 'dark';
        }

        return $output;
    }

/*  Minify CSS
/*-------------------*/

    function enovathemes_addons_minify_css($css) {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }

/*  Woocommerce content
/*-------------------*/

    if ( ! function_exists( 'woocommerce_content' ) ) {

        function woocommerce_content() {

            global $propharm_enovathemes;

            $product_gap       = "false";

            $show = (isset($_GET['ajax']) && $_GET['ajax'] == "true")? false : true;

            if ( is_singular( 'product' ) ) {

                while ( have_posts() ) :
                    the_post();
                    wc_get_template_part( 'content', 'single-product' );
                endwhile;

            } else {
                ?>

                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

                    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

                <?php endif; ?>

                <?php do_action( 'woocommerce_archive_description' ); ?>

                <?php if ( have_posts() ) : ?>

                    <?php do_action( 'woocommerce_before_shop_loop' ); ?>

                    <?php woocommerce_product_loop_start(); ?>

                    <?php if ($show): ?>
                        
                        <?php if ( wc_get_loop_prop( 'total' ) ) : ?>
                            <?php while ( have_posts() ) : ?>
                                <?php the_post(); ?>
                                <?php include(ENOVATHEMES_ADDONS.'woocommerce/content-product.php'); ?>
                            <?php endwhile; ?>
                        <?php endif; ?>

                    <?php endif ?>

                    <?php woocommerce_product_loop_end(); ?>

                    <?php do_action( 'woocommerce_after_shop_loop' ); ?>

                <?php else : ?>

                    <?php do_action( 'woocommerce_no_products_found' ); ?>

                <?php
                endif;

            }
        }
    }

/*  Clear extra space from string
/*-------------------*/

    function enovathemes_addons_extra_white_space($text){
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = trim($text);
        return $text;
    }

/*  Option panel saved hook
/*-------------------*/

    $old_main_color = Redux::get_option('propharm_enovathemes','main-color');
    $old_area_color = Redux::get_option('propharm_enovathemes','area-color');

    add_action( "redux/options/propharm_enovathemes/saved", "enovathemes_addons_redux_save");
    function enovathemes_addons_redux_save(){

        global $wpdb, $old_main_color, $old_area_color;

        $new_main_color      = Redux::get_option('propharm_enovathemes','main-color');
        $new_area_color      = Redux::get_option('propharm_enovathemes','area-color');

        $posts_table = $wpdb->prefix . "posts";
        $meta_table  = $wpdb->prefix . "postmeta";

        if (isset($old_main_color) && !empty($old_main_color) && $old_main_color != $new_main_color) {

            $sql_1 = $wpdb->prepare( "UPDATE {$posts_table} SET post_content  = REPLACE (post_content, %s, '{$new_main_color}') ",$old_main_color);
            $sql_2 = $wpdb->prepare( "UPDATE {$meta_table} SET meta_value  = REPLACE (meta_value, %s, '{$new_main_color}') ",$old_main_color);

            $wpdb->query($sql_1);
            $wpdb->query($sql_2);

            Redux::set_option('propharm_enovathemes','mtt-back-color',array('hover'=> $new_main_color));
            Redux::set_option('propharm_enovathemes','form-button-back',array('regular'=> $new_main_color));
        }

        if (isset($old_area_color) && !empty($old_area_color) && $old_area_color != $new_area_color) {

            $sql_1 = $wpdb->prepare( "UPDATE {$posts_table} SET post_content  = REPLACE (post_content, %s, '{$new_area_color}') ",$old_area_color);
            $sql_2 = $wpdb->prepare( "UPDATE {$meta_table} SET meta_value  = REPLACE (meta_value, %s, '{$new_area_color}') ",$old_area_color);

            $wpdb->query($sql_1);
            $wpdb->query($sql_2);

        }

        delete_transient( 'dynamic-styles-cached' );
        delete_transient( 'enovathemes-banners' );
        delete_transient( 'enovathemes-megamenu' );
        delete_transient( 'enovathemes-headers' );
        delete_transient( 'enovathemes-footers' );
        delete_transient( 'enovathemes-title-sections' );
        delete_transients_with_prefix( 'et_products_' );
        delete_transients_with_prefix( 'et_posts_' );

    }

/*  CPT Templates
/*-------------------*/

    function enovathemes_addons_header_single_template($single_template) {
        global $post;
        if ($post->post_type == 'header') {
            if ( $theme_file = locate_template( array ( 'single-header.php' ) ) ) {
                $single_template = $theme_file;
            } else {
                $single_template = ENOVATHEMES_ADDONS . 'templates/single-header.php';
            }
        }
        return $single_template;
    }
    add_filter( "single_template", "enovathemes_addons_header_single_template", 20 );

    function enovathemes_addons_megamenus_single_template($single_template) {
        global $post;
        if ($post->post_type == 'megamenu') {
            if ( $theme_file = locate_template( array ( 'single-megamenu.php' ) ) ) {
                $single_template = $theme_file;
            } else {
                $single_template = ENOVATHEMES_ADDONS . 'templates/single-megamenu.php';
            }
        }
        return $single_template;
    }
    add_filter( "single_template", "enovathemes_addons_megamenus_single_template", 20 );

    function enovathemes_addons_footer_single_template($single_template) {
        global $post;
        if ($post->post_type == 'footer') {
            if ( $theme_file = locate_template( array ( 'single-footer.php' ) ) ) {
                $single_template = $theme_file;
            } else {
                $single_template = ENOVATHEMES_ADDONS . 'templates/single-footer.php';
            }
        }
        return $single_template;
    }
    add_filter( "single_template", "enovathemes_addons_footer_single_template", 20 );

    function enovathemes_addons_title_section_single_template($single_template) {
        global $post;
        if ($post->post_type == 'title_section') {
            if ( $theme_file = locate_template( array ( 'single-title-section.php' ) ) ) {
                $single_template = $theme_file;
            } else {
                $single_template = ENOVATHEMES_ADDONS . 'templates/single-title-section.php';
            }
        }
        return $single_template;
    }
    add_filter( "single_template", "enovathemes_addons_title_section_single_template", 20 );

    function enovathemes_addons_banner_single_template($single_template) {
        global $post;
        if ($post->post_type == 'banner') {
            if ( $theme_file = locate_template( array ( 'single-banner.php' ) ) ) {
                $single_template = $theme_file;
            } else {
                $single_template = ENOVATHEMES_ADDONS . 'templates/single-banner.php';
            }
        }
        return $single_template;
    }
    add_filter( "single_template", "enovathemes_addons_banner_single_template", 20 );

    add_filter( 'woocommerce_locate_template', 'enovathemes_addons_woocommerce_locate_template', 10, 3 );
    function enovathemes_addons_woocommerce_locate_template( $template, $template_name, $template_path ) {
      global $woocommerce;

      $_template = $template;

      if ( ! $template_path ) $template_path = $woocommerce->template_url;

      $plugin_path  = ENOVATHEMES_ADDONS . '/woocommerce/';

      // Look within passed path within the theme - this is priority
      $template = locate_template(

        array(
          $template_path . $template_name,
          $template_name
        )
      );

      // Modification: Get the template from this plugin, if it exists
      if ( ! $template && file_exists( $plugin_path . $template_name ) )
        $template = $plugin_path . $template_name;

      // Use default template
      if ( ! $template )
        $template = $_template;

      // Return what we found
      return $template;
    }

/*  Get taxonomy hierarchy
/*-------------------*/

    function get_taxonomy_hierarchy( $taxonomy, $parent = false, $include = false) {

        $children = array();

        $taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;

        $args = array(
            'parent'     => $parent,
            'hide_empty' => true,
            'meta_key'   => 'order',
            'orderby'    => 'meta_value_num'
        );

        if ($include && is_array($include)) {
            $args['include'] = $include;

            if (count($include) == 1 && count( get_term_children( $include[0], $taxonomy ) ) == 0) {

                $parent = wp_get_term_taxonomy_parent_id($include[0], $taxonomy);
                
                if ($parent) {

                    $args = false;

                    $parent_obj = get_term_by('id',$parent,$taxonomy);

                    if ($parent_obj) {
                        $parent_obj->children = array($include[0] => get_term_by('id',$include[0],$taxonomy));
                        $children[ $parent ] = $parent_obj;
                        $children[ $parent_obj->term_id ] = $parent_obj;
                    }
                } else {
                    $args = false;

                    $term_obj = get_term_by('id',$include[0],$taxonomy);

                    if ($term_obj) {
                        $children[ $term_obj->term_id ] = $term_obj;
                    }

                }

            }
        }

        if ($args) {
            $terms = get_terms( $taxonomy, $args);
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ( $terms as $term ){
                    $term->children = get_taxonomy_hierarchy( $taxonomy, $term->term_id, $include);
                    $children[ $term->term_id ] = $term;
                }
            }
        }

        return $children;

    }

/*  List taxonomy hierarchy
/*-------------------*/

    function set_current_cat($current,$slug,$output){
        if ($current == $slug) {
            return $output;
        }
    }

    function list_taxonomy_hierarchy_no_instance($taxonomies,$level = '',$display = 'default') {

        $option = $class = $current_cat = '';

        if (is_tax('product_cat')) {
            $current_cat = get_queried_object();
            $current_cat = $current_cat->slug;
        }

        if (!empty($current_cat)) {
            $class  = 'class="chosen"';
            $option = 'selected="selected"';
        }


        $output   = '';

        foreach ( $taxonomies as $taxonomy ) {

            $children = $taxonomy->children;
            $parent   = $taxonomy->parent;

            switch ($display) {
                case 'list':
                    $output .='<li><a href="#" title="'.esc_attr($taxonomy->name).'" data-value="'.$taxonomy->slug.'" data-id="'.$taxonomy->term_id.'">'.$taxonomy->name;
                        if (is_array($children) && !empty($children)){$output .='<span class="toggle"></span>';}
                    $output .='</a>';
                    break;
                case 'image-list':

                    $image = get_term_meta($taxonomy->term_id,'thumbnail_id',true);

                    $output .='<li><a href="#" title="'.esc_attr($taxonomy->name).'" data-value="'.$taxonomy->slug.'" data-id="'.$taxonomy->term_id.'">';
                    
                        if (!empty($image) && !is_wp_error($image)) {
                            $image = wp_get_attachment_image_src( $image, 'thumbnail' );
                            if (!is_wp_error($image)) {
                                $output .='<img src="'.$image[0].'" />';
                            }
                        }

                        $output .= $taxonomy->name;    

                        if (is_array($children) && !empty($children)){$output .='<span class="toggle"></span>';}

                    $output .='</a>';
                    break;
                case 'image':

                    // $children = false;
                    $image    = get_term_meta($taxonomy->term_id,'thumbnail_id',true);

                    $output .='<li><a href="#" title="'.esc_attr($taxonomy->name).'" data-value="'.$taxonomy->slug.'" data-id="'.$taxonomy->term_id.'">';
                        if (!empty($image) && !is_wp_error($image)) {
                            $image = wp_get_attachment_image_src( $image, 'thumbnail' );
                            if (!is_wp_error($image)) {
                                $output .='<img src="'.$image[0].'" />';
                            }
                        }
                    $output .= '<span class="cat-name">'.$taxonomy->name.'</span></a>';
                    break;
                default:
                    $output .='<option value="'.$taxonomy->slug.'" data-id="'.$taxonomy->term_id.'" '.set_current_cat($current_cat,$taxonomy->slug,$option).'>'.$level.$taxonomy->name.'</option>';
                    break;
            }

            if (is_array($children) && !empty($children)){
                if($display == 'default'){
                    $level .=  '&nbsp;&nbsp;&nbsp;';
                } else {
                    $level =  '';
                    if ($display == 'list' || $display == 'image' || $display == 'image-list') {
                        $output .= '<ul>';
                    }
                }
                $output .= list_taxonomy_hierarchy_no_instance($children,$level,$display);
                if($display == 'default'){
                    $level =  ($parent) ? '&nbsp;&nbsp;&nbsp;' : '';
                } else {
                    $level =  '';
                    if ($display == 'list' || $display == 'image'|| $display == 'image-list') {
                        $output .= '</ul>';
                    }
                }
            }

            if ($display == 'list' || $display == 'image' || $display == 'image-list') {
                $output .= '</li>';
            }
        }

        return $output;
                
    }

    function list_taxonomy_hierarchy_no_instance_widget($taxonomies,$instance,$level = '') {
    ?>
        <?php foreach ( $taxonomies as $taxonomy ) { ?>

            <?php

                $children = $taxonomy->children;
                $parent   = $taxonomy->parent;

            ?>

            <option value="<?php echo $taxonomy->term_id; ?>" <?php selected( $instance['category'], $taxonomy->term_id ); ?>><?php echo $level.$taxonomy->name; ?></option>

            <?php if (is_array($children) && !empty($children)): ?>
                <?php $level .= '&nbsp;&nbsp;&nbsp;'; ?>
                <?php list_taxonomy_hierarchy_no_instance_widget($children,$instance,$level); ?>
                <?php $level = ($parent) ? '&nbsp;&nbsp;&nbsp;' : ''; ?>
            <?php endif ?>

        <?php } ?>
                
    <?php
    }

/*  Tax and children
/*-------------------*/

    function enovathemes_addons_is_or_descendant_tax( $term,$taxonomy){

        $term        = get_term_by( 'id', $term, $taxonomy);
        $descendants = get_term_children( $term->term_id, $taxonomy);
        $is_child    = false;

        foreach ($descendants as $tax) {
            if (is_tax($taxonomy, $tax)) {
                $is_child = true;
            }
        }

        if (is_tax($taxonomy, $term) || $is_child){
            return true;
        }

        return false;

    }

/*  Product categories transient
/*-------------------*/

    function get_product_categories_hierarchy($cache = true,$include = false) {

        if ($cache) {
            if ( false === ( $categories = get_transient( 'product-categories-hierarchy' ) )) {

                $categories = get_taxonomy_hierarchy( 'product_cat', 0, 0);

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $categories ) ) {
                    $categories = base64_encode(serialize( $categories ));
                    set_transient( 'product-categories-hierarchy', $categories, apply_filters( 'null_categories_cache_time', 0 ) );
                }
            }
        } else {

            $categories = get_taxonomy_hierarchy( 'product_cat', 0, $include);
        }

        if ( ! empty( $categories ) ) {

            return $cache ? unserialize(base64_decode($categories)) : $categories;

        } else {

            return new WP_Error( 'no_categories', esc_html__( 'No categories.', 'enovathemes-addons' ) );

        }
    }

    function get_product_categories_raw() {

        if ( false === ( $categories = get_transient( 'product-categories-raw' ) )) {

            $categories = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            ));

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $categories ) ) {

                $categories_list = array();

                foreach ($categories as $category) {

                    $category_list = array();

                    $category_list['name'] = $category->name;
                    $category_list['link'] = get_term_link($category->term_id,'product_cat');

                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 

                    if (!empty($thumbnail_id)) {
                        $image = wp_get_attachment_image_src( $thumbnail_id, 'woocommerce_thumbnail');
                        if ($image) {
                            $category_list['image'] = $image[0];
                            $category_list['width'] = $image[1];
                            $category_list['height'] = $image[2];
                        }
                        
                    }

                    $children = get_term_children( $category->term_id, 'product_cat');

                    if (!empty($children)) {
                        $category_list_children = array();

                        foreach ($children as $child) {
                            $term = get_term_by( 'id', $child, 'product_cat');
                            $category_list_child['name'] = $term->name;
                            $category_list_child['link'] = get_term_link($term->term_id,'product_cat');

                            $category_list_children[$term->slug] = $category_list_child;
                        }

                        if (!empty($category_list_children)) {
                            $category_list['children'] = $category_list_children;
                        }
                    }

                    $categories_list[$category->slug] = $category_list;
                    
                }

                $categories = base64_encode(serialize( $categories_list ));
                set_transient( 'product-categories-raw', $categories, apply_filters( 'null_categories_cache_time', 0 ) );
            }
        }

        if ( ! empty( $categories ) ) {

            return unserialize(base64_decode($categories));

        } else {

            return new WP_Error( 'no_categories', esc_html__( 'No categories.', 'enovathemes-addons' ) );

        }
    }

/*  Delete transient on taxonomies
/*-------------------*/

    function enovathemes_addons_edit_product_term($term_id, $tt_id, $taxonomy) {
        $term = get_term($term_id,$taxonomy);
        if (!is_wp_error($term) && is_object($term)) {

            delete_transient( 'enovathemes-attributes-filter' );
            delete_transient( 'enovathemes-product-categories' );
            delete_transient( 'dynamic-styles-cached' );
            delete_transient( 'enovathemes-banners' );
            delete_transient( 'enovathemes-megamenu' );
            delete_transient( 'enovathemes-headers' );
            delete_transient( 'enovathemes-footers' );
            delete_transient( 'enovathemes-title-sections' );
            delete_transients_with_prefix( 'et_posts_' );

            $taxonomy = $term->taxonomy;
            if ($taxonomy == "product_cat") {
                delete_transient( 'product-categories-hierarchy' );
                delete_transient( 'product-categories-raw' );
                delete_transients_with_prefix( 'et_products_' );
            }
        }
    }

    function enovathemes_addons_delete_product_term($term_id, $tt_id, $taxonomy, $deleted_term) {
        if (!is_wp_error($deleted_term) && is_object($deleted_term)) {

            delete_transient( 'enovathemes-attributes-filter' );
            delete_transient( 'enovathemes-product-categories' );
            delete_transient( 'dynamic-styles-cached' );
            delete_transient( 'enovathemes-banners' );
            delete_transient( 'enovathemes-megamenu' );
            delete_transient( 'enovathemes-headers' );
            delete_transient( 'enovathemes-footers' );
            delete_transient( 'enovathemes-title-sections' );
            delete_transients_with_prefix( 'et_posts_' );

            $taxonomy = $deleted_term->taxonomy;
            if ($taxonomy == "product_cat") {
                delete_transient( 'product-categories-hierarchy' );
                delete_transient( 'product-categories-raw' );
                delete_transients_with_prefix( 'et_products_' );
            }
        }
    }
    add_action( 'create_term', 'enovathemes_addons_edit_product_term', 99, 3 );
    add_action( 'edit_term', 'enovathemes_addons_edit_product_term', 99, 3 );
    add_action( 'delete_term', 'enovathemes_addons_delete_product_term', 99, 4 );

/*  Banners
---------------------*/

    function enovathemes_addons_banners() {

        if ( false === ( $banners = get_transient( 'enovathemes-banners' ) ) ) {

            $query_options = array(
                'post_type'           => 'banner',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => -1,
                'suppress_filters'    => 'true',
                'lang'                => ''
            );

            $banners = array();
            $banner = new WP_Query($query_options);
            if ($banner->have_posts()){
                while($banner->have_posts()) { $banner->the_post();
                    $banner_id = get_the_ID();
                    $banners[$banner_id] = array($banner_id,get_the_title($banner_id),gzcompress(get_the_content($banner_id)));
                }
                wp_reset_postdata();
            }

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $banners ) ) {
                $banners = base64_encode(serialize($banners ));
                set_transient( 'enovathemes-banners', $banners, apply_filters( 'null_banners_cache_time', 0 ) );
            }
        }

        if ( ! empty( $banners ) ) {

            return unserialize(base64_decode($banners));

        } else {

            return new WP_Error( 'no_banners', esc_html__( 'No banners.', 'enovathemes-addons' ) );

        }
    }

/*  Megamenu
---------------------*/

    function enovathemes_addons_megamenus() {

        if ( false === ( $megamenu = get_transient( 'enovathemes-megamenu' ) ) ) {

            $query_options = array(
                'post_type'           => 'megamenu',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => -1,
                'suppress_filters'    => 'true',
                'lang'                => ''
            );

            $megamenu = array();
            $megam = new WP_Query($query_options);
            if ($megam->have_posts()){
                while($megam->have_posts()) { $megam->the_post();
                    $megam_id = get_the_ID();

                    $megamenu_html  = '';
                    $megamenu_data  = '';

                    $megamenu_width = get_post_meta($megam_id, 'enovathemes_addons_megamenu_width', true);
                    $megamenu_position = get_post_meta($megam_id, 'enovathemes_addons_megamenu_position', true);

                    if (!empty($megamenu_width)) {
                        $megamenu_data .= 'data-width="'.$megamenu_width.'" ';
                    }

                    if (!empty($megamenu_position)) {
                        $megamenu_data .= 'data-position="'.$megamenu_position.'"';
                    }

                    $megamenu_html .= '<div id="megamenu-'. $megam_id . '" class="sub-menu megamenu" '.$megamenu_data.'>';
                        $megamenu_html .= get_the_content();
                    $megamenu_html .= '</div>';


                    $megamenu[$megam_id] = array($megam_id,get_the_title($megam_id),gzcompress($megamenu_html),$megamenu_data);
                }
                wp_reset_postdata();
            }

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $megamenu ) ) {
                $megamenu = base64_encode(serialize($megamenu ));
                set_transient( 'enovathemes-megamenu', $megamenu, apply_filters( 'null_megamenu_cache_time', 0 ) );
            }
        }

        if ( ! empty( $megamenu ) ) {

            return unserialize(base64_decode($megamenu));

        } else {

            return new WP_Error( 'no_megamenu', esc_html__( 'No megamenu.', 'enovathemes-addons' ) );

        }
    }

    function enovathemes_addons_megamenu_load($megamenues) {

        if (isset($_POST["megamenues"]) && !empty($_POST["megamenues"])){
           
            $megamenu = enovathemes_addons_megamenus();
            $megamenues = explode("|", $_POST["megamenues"]);
            if (!is_wp_error($megamenu)) {
                $data = array();
                foreach ($megamenues as $mega_menu){
                    WPBMap::addAllMappedShortcodes();
                    $data[$mega_menu] = apply_filters('the_content', gzuncompress($megamenu[$mega_menu][2]));
                }
                if (!empty($data)) {
                    wp_send_json($data);
                }
            }
            
        }
      
        die();
    }
    add_action( 'wp_ajax_megamenu_load', 'enovathemes_addons_megamenu_load');
    add_action( 'wp_ajax_nopriv_megamenu_load', 'enovathemes_addons_megamenu_load');

/*  Search action
/*-------------------*/

    function keyword_filter($where, $wp_query){
        global $wpdb;

        if($search_term = $wp_query->get( 'search_prod_title' )){
            $where .= ' AND (';

                $i = 0;

                foreach ($search_term as $term) {
                    $term = $wpdb->esc_like($term);
                    $term = ' \'%' . $term . '%\'';
                    $where .= '('.$wpdb->posts . '.post_title LIKE '.$term.')';
                    if(++$i != count($search_term)) {
                        $where .= ' OR ';
                    }
                }
            $where .= ')';


            if ($wp_query->get( 'search_prod_content' )) {
                $where .= ' OR (';

                    $i = 0;

                    foreach ($search_term as $term) {
                        $term = $wpdb->esc_like($term);
                        $term = ' \'%' . $term . '%\'';
                        $where .= '('.$wpdb->posts . '.post_content LIKE '.$term.')';
                        if(++$i != count($search_term)) {
                            $where .= ' OR ';
                        }
                    }
                $where .= ')';
            }

        }

        return $where;
    }

    function search_product() {

        global $wpdb, $woocommerce;

        if (isset($_POST['keyword']) && !empty($_POST['keyword'])) {

            $category       = (isset($_POST['category']) && !empty($_POST['category'])) ? $_POST['category'] : false;
            $in_description = $_POST['description'];
            $in_sku         = $_POST['sku'];
            $in_attr        = $_POST['in_attr'];
            $in_tag         = $_POST['in_tag'];

            $transient_prefix = array('search_keyword',$_POST['keyword']);

            if ($category) {
                $transient_prefix[] =  $category;
            }

            if ($in_sku) {
                $transient_prefix[] =  'in_sku';
            }

            if ($in_attr) {
                $transient_prefix[] =  'in_attr';
            }

            if ($in_tag) {
                $transient_prefix[] =  'in_tag';
            }

            if (function_exists('get_woocommerce_currency')) {
                $transient_prefix[] = get_woocommerce_currency();
            }

            $transient_prefix = implode('_', $transient_prefix);

            if ( false === ( $search_results = get_transient( $transient_prefix ) ) ) {

                $keywords = array(); 

                $ignore = array('-','_','/');

                $keyword = explode(' ', $_POST['keyword']);

                foreach ($keyword as $term) {
                    if (strlen($term) >= 3) {
                        array_push($keywords,$term);
                        array_push($keywords,strtoupper($term));
                        array_push($keywords,strtolower($term));
                        array_push($keywords,ucfirst($term));
                        foreach ($ignore as $value) {
                            array_push($keywords,str_replace($value,'',$term)); 
                        }
                    }
                }

                $keywords = array_unique($keywords);
                $keywords = array_filter($keywords);


                $attribute_terms = $tag_terms = array();

                $sku_args = $args = array(
                    'post_type'           => 'product',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 0,
                    'orderby'             => 'title',
                    'order'               => 'DESC',
                    'posts_per_page'      => -1,
                );

                $tax_query  = array('relation' => 'AND');
                $meta_query = array('relation' => 'OR');

                if ($category) {
                    $tax_query[] = array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $category,
                        'operator' => 'IN'
                    );
                }

                $tax_query[] = array(
                    'taxonomy'  => 'product_visibility',
                    'terms'     => array( 'exclude-from-catalog' ),
                    'field'     => 'name',
                    'operator'  => 'NOT IN'
                );

                foreach ($keywords as $keyword) {

                    if ($in_tag) {

                        $terms = get_terms(array(
                            'taxonomy'      => 'product_tag', // taxonomy name
                            'hide_empty'    => true,
                            'fields'        => 'all',
                            'name__like'    => $keyword
                        ));

                        if (!is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                $tag_terms[$term->name] = get_term_link($term->term_id,'product_tag');
                            }
                        }
                    }

                    if ($in_attr) {

                        $woo_attributes = wc_get_attribute_taxonomies();

                        if (!empty($woo_attributes) && !is_wp_error($woo_attributes)) {
                            foreach( $woo_attributes as $attribute) {

                                $attr_terms = get_terms( array(
                                    'taxonomy'      => 'pa_'.$attribute->attribute_name,
                                    'hide_empty'    => true,
                                    'fields'        => 'all',
                                    'name__like'    => $keyword

                                ));

                                if (!empty($attr_terms) && !is_wp_error($attr_terms)) {

                                    $terms = array();

                                    foreach ($attr_terms as $term) {
                                        $terms[$term->name] = get_term_link($term->term_id,'pa_'.$attribute->attribute_name);
                                    }

                                    $attribute_terms[$attribute->attribute_name] = $terms;
                                }
                            }
                        }
                       
                    }

                    $meta_query[] = array(
                        'key' => '_sku',
                        'value'    => $keyword,
                        'compare' => 'LIKE'
                    );
                }

                if ($in_sku == 'true' && $in_description == 'true') {
                    $args['search_prod_title'] = $keywords;
                    $args['search_prod_content'] = true;
                    $sku_args['meta_query']  = $meta_query;
                } elseif ($in_sku == 'true' && $in_description == 'false') {
                    $sku_args['meta_query']  = $meta_query;
                    $args['search_prod_title'] = $keywords;
                } elseif ($in_sku == 'false' && $in_description == 'true') {
                    $args['search_prod_title'] = $keywords;
                    $args['search_prod_content'] = true;
                } elseif ($in_sku == 'false' && $in_description == 'false') {
                    $args['search_prod_title'] = $keywords;
                }

                $args['fields'] = 'ids';
                $sku_args['fields'] = 'ids';
             
                add_filter( 'posts_where', 'keyword_filter', 10, 2 );
                $query_results  = new WP_Query($args);
                $query_results2 = new WP_Query($sku_args);
                remove_filter( 'posts_where', 'keyword_filter', 10);

                $IDs = array();
                
                if(!empty($query_results->posts)){
                    $query_results = $query_results->posts;
                    foreach ($query_results as $result) {
                        array_push($IDs,$result);
                    }
                }
                
                if($in_sku == "true" && !empty($query_results2->posts)){
                    $query_results = $query_results2->posts;
                    foreach ($query_results as $result) {
                        array_push($IDs,$result);
                    }
                }

                $IDs = array_unique($IDs);
                $IDs = array_filter($IDs);

                if (!empty($IDs)) {

                    $query_arguments = array(
                        'post_type'           => 'product',
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => 0,
                        'orderby'             => 'title',
                        'order'               => 'DESC',
                        'posts_per_page'      => -1,
                        'post__in'            => $IDs
                    );

                    if (!empty($tax_query)) {
                        $query_arguments['tax_query'] = $tax_query;
                    }
                    
                    $query_results = new WP_Query($query_arguments);

                }

                $output_array = array();

                if ($query_results->have_posts()) {

                    $output = '';

                    $price_num_decimals = get_option('woocommerce_price_num_decimals');

                    while ( $query_results->have_posts() ) {

                        $query_results->the_post();

                        $post_id = get_the_ID();

                        $product = wc_get_product($post_id);

                        if ($product) {

                            $catalog_visibility = $product->get_catalog_visibility();

                            if (
                                !in_array($catalog_visibility,array('hidden','catalog'))
                            ) {

                                $price      = $product->get_regular_price();
                                $price_sale = $product->get_sale_price();



                                if($product->is_type( 'variable' ) )
                                {

                                    $variations = $product->get_available_variations();

                                    $all_variation_prices = array();

                                    foreach ($variations as $variation) {
                                        $variation_prices = array();
                                        $variation_prices['regular_price'] = $variation['display_regular_price'];
                                        $variation_prices['sale_price']    = $variation['display_price'];

                                        array_push($all_variation_prices, $variation_prices);
                                    }

                                    $all_regular_prices = array();
                                    $all_sale_prices    = array();

                                    foreach ($all_variation_prices as $variation_price) {
                                        array_push($all_regular_prices, $variation_price['regular_price']);
                                        array_push($all_sale_prices, $variation_price['sale_price']);
                                    }

                                    $price      = array_sum($all_regular_prices)/count($all_regular_prices);
                                    $price_sale = array_sum($all_sale_prices)/count($all_sale_prices);

                                }


                                if ($price) {
                                    $price = wc_price(round($price,$price_num_decimals));
                                }

                                if ($price_sale) {
                                    $price_sale = wc_price(round($price_sale,$price_num_decimals));
                                }

                                $sku   = $product->get_sku();

                                $categories = wp_get_post_terms($post_id, 'product_cat');

                                $output .= '<li id="'.$post_id.'">';
                                    $output .= '<a href="'.get_post_permalink($post_id).'">';
                                        if (get_the_post_thumbnail_url($post_id,'thumbnail')) {
                                            $output .= '<div class="product-image-wrapper">';
                                            $output .= '<div class="product-image">';
                                                $output .= '<img src="'.esc_url(get_the_post_thumbnail_url($post_id,'thumbnail')).'">';
                                                $output .= '</div>';
                                            $output .= '</div>';
                                        }
                                        $output .= '<div class="product-data">';
                                            if (!empty($categories)) {
                                                $output .= '<div class="product-categories">';
                                                    foreach ($categories as $category) {
                                                        $output .= '<span>'.$category->name.'</span>';
                                                    }
                                                    if (!empty($sku)) {
                                                        $output .= '<span class="product-sku">'.esc_html__( 'SKU:', 'enovathemes-addons' ).' '.$sku.'</span>';
                                                    }
                                                $output .= '</div>';
                                            }
                                            $output .= '<h3>'.get_the_title($post_id).'</h3>';
                                            
                                            if (!empty($price)) {
                                                $output .= '<div class="product-price">';
                                                    if (!empty($price_sale)) {
                                                        $output .= '<span class="sale-price">'.$price_sale.'</span>';
                                                    }
                                                    $output .= '<span class="regular-price">'.$price.'</span>';
                                                $output .= '</div>';
                                            }
                                        $output .= '</div>';
                                    $output .= '</a>';
                                $output .= '</li>';

                            }
                        }

                    }

                    if (!empty($output)) {
                        $output_array['output'] = $output;
                    }

                    wp_reset_postdata();

                } else {
                    $output_array['output'] = '<li class="no-results">'.esc_html__('No results','enovathemes-addons').'</li>';

                }

                $term_output = '';

                if (!empty($attribute_terms)) {

                    $term_output .= '<div class="term-output attribute-output">';

                    foreach ($attribute_terms as $attr => $terms) {


                        $term_output .= '<h4>'.esc_html(ucfirst($attr)).'</h4>';

                        foreach ($terms as $name => $link) {
                            $term_output .= '<a href="'.esc_url($link).'">'.$name.'</a>';
                        }
                    }

                    $term_output .= '</div>';

                }

                if (!empty($tag_terms)) {

                    $term_output .= '<div class="term-output tag-output"><h4>'.esc_html__("Tags","enovathemes-addons").'</h4>';

                    foreach ($tag_terms as $name => $link) {
                        $term_output .= '<a href="'.esc_url($link).'">'.$name.'</a>';
                    }

                    $term_output .= '</div>';

                }

                if (!empty($term_output)) {
                    $output_array['terms'] = '<div class="terms-output">'.$term_output.'</div>';
                }

                $output_array['args']   = ($in_sku == 'true') ? $sku_args : $args;

                $search_results = json_encode($output_array);

                if ( ! empty( $search_results ) ) {
                    $search_results = base64_encode(serialize($search_results ));
                    set_transient( $transient_prefix, $search_results, WEEK_IN_SECONDS );
                }

            }

            if ( ! empty( $search_results ) ) {
                echo unserialize(base64_decode($search_results));
            }
        }

        die();
    }
    add_action( 'wp_ajax_search_product', 'search_product' );
    add_action( 'wp_ajax_nopriv_search_product', 'search_product' );

/*  Wishlist
/*-------------------*/

    add_filter( 'woocommerce_account_menu_items', 'enovathemes_addons_myaccount_wishlist_endpoints' );
    function enovathemes_addons_myaccount_wishlist_endpoints( $items ) {
       $save_for_later = array( 'wishlist' => esc_html__('Wishlist','enovathemes-addons') ); // SAVE TAB
       $items = array_merge( array_slice( $items, 0, 1 ), $save_for_later, array_slice( $items, 1 ) ); // PLACE TAB AFTER POSITION 2
       return $items;
    }
    add_action( 'init', 'enovathemes_addons_myaccount_wishlist_endpoints_rewrite' );
    function enovathemes_addons_myaccount_wishlist_endpoints_rewrite() {
        add_rewrite_endpoint( 'wishlist', EP_PAGES );
    }
    add_action( 'woocommerce_account_wishlist_endpoint', 'enovathemes_addons_myaccount_wishlist_endpoints_content' );
    function enovathemes_addons_myaccount_wishlist_endpoints_content(){
        echo do_shortcode('[wishlist]');
    }

    // Get current user data
    function fetch_user_data() {
        if (is_user_logged_in()){
            $current_user = wp_get_current_user();
            $current_user_wishlist = get_user_meta( $current_user->ID, 'wishlist',true);
            echo json_encode(array('user_id' => $current_user->ID,'wishlist' => $current_user_wishlist));
        }
        die();
    }
    add_action( 'wp_ajax_fetch_user_data', 'fetch_user_data' );
    add_action( 'wp_ajax_nopriv_fetch_user_data', 'fetch_user_data' );

    // Wishlist option in the user profile
    add_action( 'show_user_profile', 'wishlist_user_profile_field' );
    add_action( 'edit_user_profile', 'wishlist_user_profile_field' );
    function wishlist_user_profile_field( $user ) { ?>
        <table class="form-table wishlist-data">
            <tr>
                <th><?php echo esc_attr__("Wishlist","enovathemes-addons"); ?></th>
                <td>
                    <input type="text" name="wishlist" id="wishlist" value="<?php echo esc_attr( get_the_author_meta( 'wishlist', $user->ID ) ); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
    <?php }

    

    add_action( 'personal_options_update', 'save_wishlist_user_profile_field' );
    add_action( 'edit_user_profile_update', 'save_wishlist_user_profile_field' );
    function save_wishlist_user_profile_field( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        update_user_meta( $user_id, 'wishlist', $_POST['wishlist'] );
    }

    function user_wishlist_update(){
        if (isset($_POST["user_id"]) && !empty($_POST["user_id"])) {
            $user_id   = $_POST["user_id"];
            $user_obj = get_user_by('id', $user_id);
            if (!is_wp_error($user_obj) && is_object($user_obj)) {
                update_user_meta( $user_id, 'wishlist', $_POST["wishlist"]);
            }
        }
        die();
    }
    add_action('admin_post_nopriv_user_wishlist_update', 'user_wishlist_update');
    add_action('admin_post_user_wishlist_update', 'user_wishlist_update');

    function wishlist_count_update(){
        if (isset($_POST["product_id"]) && !empty($_POST["product_id"])) {

            $product_id = $_POST["product_id"];
            $wishlist   = get_post_meta($product_id, 'enovathemes_addons_wishlist', true );

            $wishlist++;

            update_post_meta( $product_id, 'enovathemes_addons_wishlist',$wishlist);
        }
        die();
    }
    add_action('admin_post_nopriv_wishlist_count_update', 'wishlist_count_update');
    add_action('admin_post_wishlist_count_update', 'wishlist_count_update');

    // Get current user data
    function wishlist_fetch() {

        if (isset($_POST["wishlist"]) && !empty($_POST["wishlist"])) {
            $output = '';
            $wishlist = $_POST["wishlist"];
            $query_options = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post__in'       => explode(',', $wishlist)
            );

            $wishlist_query = new WP_Query($query_options);

            if($wishlist_query->have_posts()){

                $currency           = get_woocommerce_currency_symbol();
                $currency_pos       = get_option('woocommerce_currency_pos');
                $price_num_decimals = get_option('woocommerce_price_num_decimals');

                while ($wishlist_query->have_posts() ) {
                    $wishlist_query->the_post();

                    global $product;

                    $stock      = $product->get_stock_status();
                    $price      = $product->get_regular_price();
                    $price_sale = $product->get_sale_price();

                    $stock_status = 'In stock';

                    switch ($stock) {
                        case 'outofstock':
                            $stock_status = 'Out of stock';
                            break;
                        case 'onbackorder':
                            $stock_status = 'On backorder';
                            break;
                        default:
                            $stock_status = 'In stock';
                            break;
                    }
                    
                    if ($price) {
                        $price = round($price,$price_num_decimals);
                    }

                    if ($price_sale) {
                        $price_sale = round($price_sale,$price_num_decimals);
                    }

                    switch ($currency_pos) {
                        case 'left':
                            $price_output = $currency.$price;
                            $price_sale_output = $currency.$price_sale;
                            break;
                        case 'left_space':
                            $price_output = $currency.' '.$price;
                            $price_sale_output = $currency.' '.$price_sale;
                            break;
                        case 'right':
                            $price_output = $price.$currency;
                            $price_sale_output = $price_sale.$currency;
                            break;
                        case 'right_space':
                            $price_output = $price.' '.$currency;
                            $price_sale_output = $price_sale.' '.$currency;
                            break;
                    }

                    $output .='<li data-product="'.esc_attr($product->get_id()).'">';
                        $output .= '<a class="content" href="'.get_permalink( $product->get_id() ).'">';
                            $output .= '<div class="product-image-wrapper">';
                                $output .= '<div class="product-image">';
                                    $output .= $product->get_image('propharm_72X72');
                                $output .= '</div>';
                                if (!empty($stock)) {
                                    $output .= '<div class="product-stock '.$stock.'">'.$stock_status.'</div>';
                                }
                            $output .= '</div>';
                            $output .= '<div class="product-data">';
                                $output .= '<h3>'.$product->get_name().'</h3>';
                                if (!empty($price)) {
                                    $output .= '<div class="product-price">';
                                        if (!empty($price_sale)) {
                                            $output .= '<span class="sale-price">'.$price_sale_output.'</span>';
                                        }
                                        $output .= '<span class="regular-price">'.$price_output.'</span>';
                                    $output .= '</div>';
                                }
                            $output .= '</div>';
                        $output .= '</a>';

                        if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
                            $output .='<a class="wishlist-remove et-icon back-active" title="'.esc_attr__('Remove item','enovathemes-addons').'" href="#">'.et_get_theme_icon()['close'].'</a>';
                        }

                    $output .='</li>';

                }

                wp_reset_postdata();
            }

            if (!empty($output)) {
                echo $output;
            }

        }
        die();
    }
    add_action( 'wp_ajax_wishlist_fetch', 'wishlist_fetch' );
    add_action( 'wp_ajax_nopriv_wishlist_fetch', 'wishlist_fetch' );

    // Wishlist table shortcode
    add_shortcode('wishlist', 'wishlist');
    function wishlist( $atts, $content = null ) {

        extract(shortcode_atts(array(), $atts));

        if (is_user_logged_in()){
            return '<div class="wishlist-table loading"></div>';
        } else {

            $my_account_link   = get_permalink(get_option('woocommerce_myaccount_page_id') );
            $registration_link = $my_account_link;
            $forgot_link       = get_permalink(get_option('woocommerce_myaccount_page_id') ).'lost-password';

            if (!empty($my_account_link)) {
                $instance = array('title'=> '','registration_link'=>$registration_link,'forgot_link'=>$forgot_link,'my_account_link'=>$my_account_link);
                return enovathemes_addons_get_the_widget( 'Enovathemes_Addons_WP_Widget_Login', $instance,'');
            }

        }

    }

/*  Get the widget
/*-------------------*/

    if( !function_exists('enovathemes_addons_get_the_widget') ){
  
        function enovathemes_addons_get_the_widget( $widget, $instance = '', $args = '' ){
            ob_start();
            the_widget($widget, $instance, $args);
            return ob_get_clean();
        }
        
    }

/*  Compare
/*-------------------*/

    // Get current user data
    function compare_fetch($compare = '') {

        $native = false;

        if (isset($_POST["compare"]) && !empty($_POST["compare"]) && isset($_POST["aj"]) && !empty($_POST["aj"])) {
           $compare = $_POST["compare"];

           $native = true;
        }

        if (!empty($compare)) {

            global $product;

            $output = '';
            $first_image = '';

            $compare = explode(',', $compare);
            $length  = sizeof($compare) + 1;
            $query_options = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post__in'       => $compare,
                'orderby'        =>'post__in'
            );

            $compare_query = new WP_Query($query_options);

            if($compare_query->have_posts()){

                $currency           = get_woocommerce_currency_symbol();
                $currency_pos       = get_option('woocommerce_currency_pos');
                $price_num_decimals = get_option('woocommerce_price_num_decimals');

                $product_attributes = array();

                foreach ($compare as $p) {
                   $p = wc_get_product( $p );
                   $attributes = $p->get_attributes();
                   foreach ($attributes as $attribute => $options) {
                       array_push($product_attributes, $attribute);
                    }
                }

                $product_attributes = array_unique($product_attributes,SORT_REGULAR);

                while ($compare_query->have_posts() ) {
                    $compare_query->the_post();
                    
                    global $product;

                    if ($compare_query->current_post == 0) {
                        $first_image = $product->get_image();
                    }

                    $rating     = $product->get_average_rating();

                    if($product->is_type( 'variable' ) )
                    {
                        $price      = $product->get_variation_regular_price();
                        $price_sale = $product->get_variation_price();
                    } else {
                        $price       = $product->get_regular_price();
                        $price_sale  = $product->get_sale_price();
                    }

                    if ($price) {
                        $price = round($price,$price_num_decimals);
                    }

                    if ($price_sale) {
                        $price_sale = round($price_sale,$price_num_decimals);
                    }

                    switch ($currency_pos) {
                        case 'left':
                            $price_output = $currency.$price;
                            $price_sale_output = $currency.$price_sale;
                            break;
                        case 'left_space':
                            $price_output = $currency.' '.$price;
                            $price_sale_output = $currency.' '.$price_sale;
                            break;
                        case 'right':
                            $price_output = $price.$currency;
                            $price_sale_output = $price_sale.$currency;
                            break;
                        case 'right_space':
                            $price_output = $price.' '.$currency;
                            $price_sale_output = $price_sale.' '.$currency;
                            break;
                    }

                    $output .='<li data-product="'.esc_attr($product->get_id()).'">';

                        $output .= '<div class="product-data">';
                            $output .= '<div class="product-image image-container">';
                                $output .= $product->get_image();
                            $output .= '</div>';
                            $output .= '<div class="product-data-info">';
                                $output .= '<h3 class="post-title">'.$product->get_name().'</h3>';
                                $output .= '<a class="et-button woocommerce-button" href="'.get_permalink( $product->get_id() ).'">'.esc_attr__('More details','enovathemes-addons').'</a>';
                            $output .= '</div>';
                        $output .= '</div>';

                        $output .= '<div class="product-rating">';
                            if (!empty($rating)) {
                                $output .= wc_get_rating_html( $product->get_average_rating() );
                            } else {
                                $output .= esc_attr__('n/a','enovathemes-addons');
                            }
                        $output .= '</div>';
                        
                        $output .= '<div class="product-price">';
                            if (!empty($price)) {
                                if (!empty($price_sale)) {
                                    $output .= '<span class="sale-price">'.$price_sale_output.'</span>';
                                } else {
                                    $output .= '<span class="regular-price">'.$price_output.'</span>';
                                }
                            } else {
                                $output .= esc_attr__('n/a','enovathemes-addons');
                            }
                        $output .= '</div>';
                        
                        if (!empty($product_attributes)) {
                            $output .= '<div class="product-attributes">';
                                foreach ($product_attributes as $attribute) {
                                    $attr = get_the_terms($product->get_id(),$attribute);
                                    if ($attr) {
                                        if (sizeof($attr) > 1) {
                                            $output .= '<div class="attr overflow">';
                                                foreach ($attr as $key => $term) {
                                                    $color = get_term_meta($term->term_id,'color',true);
                                                    if ($color) {
                                                        $brightness = propharm_enovathemes_brightness($color);
                                                        $output .= '<span class="attr color '.$brightness.'"><span style="background:'.esc_attr($color).';" title="'.esc_attr($term->name).'"></span></span>';
                                                    } else {
                                                        $output .= '<span class="attr">'.$term->name.'</span>';
                                                    }
                                                }
                                            $output .= '</div>';
                                        } else {
                                            foreach ($attr as $key => $term) {
                                                $color = get_term_meta($term->term_id,'color',true);
                                                if ($color) {
                                                    $brightness = propharm_enovathemes_brightness($color);
                                                    $output .= '<div class="attr color '.$brightness.'"><span style="background:'.esc_attr($color).';" title="'.esc_attr($term->name).'"></span></div>';
                                                } else {
                                                    $output .= '<div class="attr">'.$term->name.'</div>';
                                                }
                                            }
                                        }
                                        
                                    } else {
                                        $output .= '<div class="attr">-</div>';
                                    }
                                    
                                }
                            $output .= '</div>';
                        }
                        if ($native) {

                            if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
                                $output .='<a class="compare-remove et-icon back-active" title="'.esc_attr__('Remove item','enovathemes-addons').'" href="#">'.et_get_theme_icon()['close'].'</a>';
                            }

                        }
                    $output .='</li>';

                }

                wp_reset_postdata();

            }

            if (!empty($output)) {

                $output_start = '';

                $output_start .= '<li class="labels">';

                    $output_start .= '<div class="product-data">';
                        $output_start .= '<div class="product-image image-container">';
                            $output_start .= $first_image;
                        $output_start .= '</div>';
                        $output_start .= '<div class="product-data-info">';
                            $output_start .= '<h3 class="post-title">'.esc_html__('Product title','enovathemes-addons').'</h3>';
                            $output_start .= '<a class="et-button woocommerce-button" href="#">'.esc_attr__('More details','enovathemes-addons').'</a>';
                        $output_start .= '</div>';
                    $output_start .= '</div>';

                    $output_start .= '<div class="product-rating">'.esc_html__('Rating','enovathemes-addons').'</div>';
                    $output_start .= '<div class="product-price">'.esc_html__('Price','enovathemes-addons').'</div>';
                    $output_start .= '<div class="product-attributes">';
                        foreach ($product_attributes as $attribute) {
                            $taxonomy = get_taxonomy($attribute);
                            if (is_object($taxonomy)) {
                                $output_start .= '<div>'.$taxonomy->labels->singular_name.'</div>';
                            }
                        }
                    $output_start .= '</div>';
                $output_start .= '</li>';

                echo '<ul class="compare-item-list" style="grid-template-columns: repeat('.$length.', '.$length.'fr);">';
                    echo $output_start;
                    echo $output;
                echo '</ul>';
            }

        }
        if ($native) {
            die();
        }
    }
    add_action( 'wp_ajax_compare_fetch', 'compare_fetch' );
    add_action( 'wp_ajax_nopriv_compare_fetch', 'compare_fetch' );

    // Wishlist table shortcode
    add_shortcode('compare', 'compare');
    function compare( $atts, $content = null ) {

        extract(shortcode_atts(array(), $atts));

        return '<div class="compare-table-wrapper loading"><div class="compare-table cbt"></div></div>';

    }

/*  FBT
---------------------*/

    function enovathemes_addons_add_to_cart_all_ajax_handler() {

        $products = isset($_POST['products']) ? json_decode($_POST['products']) : false;

        if ($products) {
            foreach ($products as $product) {
                WC()->cart->add_to_cart($product);
            }
        }
        wp_die();
    }

    add_action('wp_ajax_add_to_cart_all', 'enovathemes_addons_add_to_cart_all_ajax_handler');
    add_action('wp_ajax_nopriv_add_to_cart_all', 'enovathemes_addons_add_to_cart_all_ajax_handler');

    function enovathemes_addons_update_mini_cart_ajax_handler() {
        ob_start();

        // Output the updated mini cart content
        woocommerce_mini_cart();

        $mini_cart = ob_get_clean();

        echo $mini_cart;
        wp_die();
    }

    add_action('wp_ajax_update_mini_cart_content', 'enovathemes_addons_update_mini_cart_ajax_handler');
    add_action('wp_ajax_nopriv_update_mini_cart_content', 'enovathemes_addons_update_mini_cart_ajax_handler');

    function enovathemes_addons_update_mini_cart_contents_ajax_handler() {
        $cart_count = WC()->cart->get_cart_contents_count();
        echo $cart_count;
        wp_die();
    }

    add_action('wp_ajax_update_mini_cart_contents', 'enovathemes_addons_update_mini_cart_contents_ajax_handler');
    add_action('wp_ajax_nopriv_update_mini_cart_contents', 'enovathemes_addons_update_mini_cart_contents_ajax_handler');


/*  Filter by attributes
---------------------*/

    function et_get_min_max_price_meta_query( $args ) {

        $current_min_price = isset( $args['min_price'] ) ? floatval( $args['min_price'] ) : 0;
        $current_max_price = isset( $args['max_price'] ) ? floatval( $args['max_price'] ) : PHP_INT_MAX;

        return apply_filters(
            'woocommerce_get_min_max_price_meta_query',
            array(
                'key'     => '_price',
                'value'   => array( $current_min_price, $current_max_price ),
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL(10,' . wc_get_price_decimals() . ')',
            ),
            $args
        );
    }

    function enovathemes_addons_starts_with ($string, $startString) {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    function enovathemes_addons_get_filtered_price($include = false) {global $wpdb;

        $sql = "SELECT min( min_price ) as min_price, MAX( max_price ) as max_price";
        $sql .= " FROM {$wpdb->wc_product_meta_lookup}";

        if ($include) {
            $sql .= " WHERE product_id IN (".implode(',',$include).")";
        } else {
            $sql .= " WHERE product_id IN (";
            $sql .= "SELECT ID FROM {$wpdb->posts}";
            $sql .= " WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')";
            $sql .= " AND {$wpdb->posts}.post_status = 'publish')";
        }

        $sql = apply_filters( 'woocommerce_price_filter_sql', $sql);

        return $wpdb->get_row( $sql );
    }

    function enovathemes_addons_get_filtered_product_count($rating,$include = false) {global $wpdb;

        $product_visibility_terms = wc_get_product_visibility_term_ids();

        $tax_query[] = array(
            'taxonomy'      => 'product_visibility',
            'field'         => 'term_taxonomy_id',
            'terms'         => $product_visibility_terms[ 'rated-' . $rating ],
            'operator'      => 'IN',
            'rating_filter' => true,
        );

        $tax_query      = new WP_Tax_Query( $tax_query );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

        if ($include) {

            $sql  = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
            $sql .= $tax_query_sql['join'];
            $sql .= " WHERE {$wpdb->posts}.post_type = 'product'";
            $sql .= " AND {$wpdb->posts}.post_status = 'publish' ";

            if ($include) {
                $sql .= " AND {$wpdb->posts}.ID IN (".implode(',',$include).")";
            }

            $sql .= $tax_query_sql['where'];

        } else {

            $meta_query     = WC_Query::get_main_meta_query();
            $meta_query     = new WP_Meta_Query( $meta_query );
            $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );

            $sql  = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
            $sql .= $tax_query_sql['join'] . $meta_query_sql['join'];
            $sql .= " WHERE {$wpdb->posts}.post_type = 'product'";
            $sql .= " AND {$wpdb->posts}.post_status = 'publish' ";

            if ($include) {
                $sql .= " AND {$wpdb->posts}.ID IN (".implode(',',$include).")";
            }

            $sql .= $tax_query_sql['where'] . $meta_query_sql['where'];
        }

        return absint( $wpdb->get_var( $sql ) );
    }

    function enovathemes_addons_get_attribute_taxonomies($category = ''){ global $wpdb;

        $multilingual     = (class_exists('SitePress') || function_exists('pll_the_languages')) ? true : false;
        $woo_attributes   = wc_get_attribute_taxonomies();
        $attributes       = array();

        if (!empty($category) && !empty($woo_attributes) && !is_wp_error($woo_attributes)) {

            $args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 0,
                'orderby'             => 'title',
                'order'               => 'DESC',
                'posts_per_page'      => -1,
                'tax_query'           => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'id',
                        'terms'    => $category,
                        'operator' => 'IN'
                    )
                )
            );

            $querystr = "SELECT DISTINCT * FROM $wpdb->posts AS p
                LEFT JOIN $wpdb->term_relationships AS r ON (p.ID = r.object_id)
                INNER JOIN $wpdb->term_taxonomy AS x ON (r.term_taxonomy_id = x.term_taxonomy_id)
                INNER JOIN $wpdb->terms AS t ON (r.term_taxonomy_id = t.term_id)
                WHERE p.post_type IN ('product')
                AND p.post_status = 'publish'
                AND x.taxonomy = 'product_cat'
                AND (x.term_id = {$category})
                ORDER BY t.name ASC, p.post_date DESC;";

            if ($multilingual) {
                $query_results  = new WP_Query($args);
                $query_results  = $query_results->posts;
            } else {
                $query_results  = $wpdb->get_results($querystr);
            }

            if (!empty($query_results)) {

                $data = array();
                $post_woo_attributes = array();

                foreach ($query_results as $result) {

                    foreach( get_post_taxonomies($result->ID) as $attribute) {
                        if (enovathemes_addons_starts_with ($attribute, 'pa_')) {

                            $terms = get_the_terms( $result->ID, $attribute);

                            if($terms && !is_wp_error($terms)){

                                array_push($post_woo_attributes, substr($attribute, 3));

                                foreach ( $terms as $term ) {
                                    $data[substr($attribute, 3)][$term->term_id] = $term->name;
                                }

                            }
                        }
                    }

                }

                wp_reset_postdata();

            }

            if (!empty($woo_attributes) && !is_wp_error($woo_attributes)) {
                foreach( $woo_attributes as $attribute) {
                    if (in_array($attribute->attribute_name, $post_woo_attributes)) {
                        $attributes[$attribute->attribute_name]['name']  = $attribute->attribute_name;
                        $attributes[$attribute->attribute_name]['label'] = ucfirst($attribute->attribute_label);
                        $attributes[$attribute->attribute_name]['type']  = $attribute->attribute_type;
                        $attributes[$attribute->attribute_name]['terms'] = $data[$attribute->attribute_name];
                    }
                }
            }

        } else {

            if (!empty($woo_attributes) && !is_wp_error($woo_attributes)) {
                foreach( $woo_attributes as $attribute) {

                    $attribute_terms = get_terms( array(
                        'taxonomy' => 'pa_'.$attribute->attribute_name,
                        'hide_empty' => false,
                    ));

                    if ($attribute_terms) {

                        $data = array();

                        foreach ($attribute_terms as $term) {
                            $data[$term->term_id] = array($term->name,$term->slug);
                        }
                        $attributes[$attribute->attribute_name]['name']  = $attribute->attribute_name;
                        $attributes[$attribute->attribute_name]['label'] = ucfirst($attribute->attribute_label);
                        $attributes[$attribute->attribute_name]['type']  = $attribute->attribute_type;
                        $attributes[$attribute->attribute_name]['terms'] = $data;
                    }
                }
            }

        }

        return $attributes;
    }

    function enovathemes_addons_build_filter_attributes($cache = true,$category = '') {

        if (!empty($category)) {
            $cache = false;
        }

        if (class_exists('Woocommerce')) {

            if ($cache) {
                if ( false === ( $attributes = get_transient( 'enovathemes-attributes-filter' ) )) {

                    $attributes = enovathemes_addons_get_attribute_taxonomies();

                    // do not set an empty transient - should help catch private or empty accounts.
                    if ( ! empty( $attributes ) ) {
                        $attributes = base64_encode(serialize($attributes ));
                        set_transient( 'enovathemes-attributes-filter', $attributes, apply_filters( 'null_filter_cache_time', 0 ) );
                    }
                }
            }else {
                $attributes = enovathemes_addons_get_attribute_taxonomies($category);
            }

            if ( ! empty( $attributes ) ) {

                return $cache ? unserialize(base64_decode($attributes )) : $attributes;

            } else {

                return new WP_Error( 'no_filter_attributes', esc_html__( 'No filter.', 'enovathemes-addons' ) );

            }

        } else {
            return false;
        }
    }

    function list_attribute_no_instance($taxonomies,$display = 'select') {

        $output   = '';

        foreach ( $taxonomies as $term_id => $term ) {

            switch ($display) {
                case 'list':
                case 'label':
                    $output .='<li><a href="#" title="'.$term[0].'" data-value="'.$term[1].'" data-id="'.$term_id.'">'.ucfirst(strtolower($term[0])).'</a></li>';
                    break;
                case 'image':

                    $output .='<li><a href="#" data-value="'.$term[1].'" data-id="'.$term_id.'" title="'.esc_attr($term[0]).'">';

                        $image = get_term_meta($term_id,'image',true);

                        if (!empty($image) && !is_wp_error($image)) {
                            $image = wp_get_attachment_image_src( $image, 'full' );
                            if (!is_wp_error($image)) {
                                $output .='<img alt="'.ucfirst(strtolower($term[0])).'" src="'.$image[0].'" />';
                                $output .= '<span class="attr-name">'.ucfirst(strtolower($term[0])).'</span>';
                            }
                        } else {
                            $output .= '<span class="attr-name">'.ucfirst(strtolower($term[0])).'</span>';
                        }
                    $output .= '</a></li>';

                break;
                case 'col':

                    $color = get_term_meta($term_id,'color',true);
                    if (!empty($color) && !is_wp_error($color)) {
                        $output .='<li><a href="#" data-value="'.$term[1].'" title="'.esc_attr($term[0]).'" data-id="'.$term_id.'">';
                           $class = enovathemes_addons_brightness($color);
                           $output .= '<span class="attr-color '.esc_attr($class).'" style="background:'.$color.';"></span>';
                           $output .= '<span class="attr-name">'.ucfirst(strtolower($term[0])).'</span>';
                        $output .= '</a></li>';
                    } else {
                        $output = '';
                    }

                break;
                default:
                    $output .='<option value="'.$term[1].'" data-id="'.$term_id.'">'.ucfirst(strtolower($term[0])).'</option>';
                    break;
            }
        }

        return $output;
                
    }

    /* Filter attributes render
    /*----------------*/

        function enovathemes_addons_render_price_filter_attribute($include = false){
            // Round values to nearest 10 by default.
            $step = max( apply_filters( 'woocommerce_price_filter_widget_step', 10 ), 1 );

            // Find min and max price in current result set.
            $prices    = enovathemes_addons_get_filtered_price($include);
            $min_price = $prices->min_price;
            $max_price = $prices->max_price;

            // Check to see if we should add taxes to the prices if store are excl tax but display incl.
            $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

            if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
                $tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ); // Uses standard tax class.
                $tax_rates = WC_Tax::get_rates( $tax_class );

                if ( $tax_rates ) {
                    $min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
                    $max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
                }
            }

            $min_price = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $min_price / $step ) * $step );
            $max_price = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $max_price / $step ) * $step );

            $values = '';

            if ($include && isset($_POST['min_price'])) {

                $values = array();

                $values[] = $_POST['min_price'];

                if (isset($_POST['max_price']) && !empty($_POST['max_price'])) {
                    $values[] = $_POST['max_price'];
                }

                if (count($values) == 2) {
                    $values = implode(',', $values);
                    $values = 'data-values="'.$values.'"';
                }
            }

            // If both min and max are equal, we don't need a slider.
            if ( $min_price != $max_price ) {

                $current_min_price = $min_price; // WPCS: input var ok, CSRF ok.
                $current_max_price = $max_price; // WPCS: input var ok, CSRF ok.
                
                $currency     = get_woocommerce_currency_symbol();
                $currency_pos = get_option('woocommerce_currency_pos');
                $price_num_decimals = get_option('woocommerce_price_num_decimals');

                if(shortcode_exists('woocommerce_currency_switcher_link_list')) {
                    if ( 'yes' === get_option( 'alg_wc_currency_switcher_enabled', 'yes' ) ) {

                        $exchange_rate = alg_wc_cs_get_currency_exchange_rate( alg_get_current_currency_code() );

                        $current_min_price = round($current_min_price*$exchange_rate,$price_num_decimals);
                        $current_max_price = round($current_max_price*$exchange_rate,$price_num_decimals);
                    }
                }

                switch ($currency_pos) {
                    case 'left':
                        $display_price_min = $currency.$current_min_price;
                        $display_price_max = $currency.$current_max_price;
                        break;
                    case 'left_space':
                        $display_price_min = $currency.' '.$current_min_price;
                        $display_price_max = $currency.' '.$current_max_price;
                        break;
                    case 'right':
                        $display_price_min = $current_min_price.$currency;
                        $display_price_max = $current_max_price.$currency;
                        break;
                    case 'right_space':
                        $display_price_min = $current_min_price.' '.$currency;
                        $display_price_max = $current_max_price.' '.$currency;
                        break;
                }

                $attributes = array();

                if (!empty($values)) {
                    $attributes[] = $values;
                }

                $attributes[] = 'data-min="'.$current_min_price.'"';
                $attributes[] = 'data-max="'.$current_max_price.'"';
                $attributes[] = 'data-step="'.esc_attr( $step ).'"';
                $attributes[] = 'data-currency="'.esc_attr( $currency ).'"';
                $attributes[] = 'data-position="'.esc_attr( $currency_pos ).'"';

                $output = '<div class="pf-item price" data-attribute="price">';
                    $output .= '<h5 class="widget_title">'.esc_html__('Price','enovathemes-addons').'<span class="clear-attribute">'.esc_html__("Any price","enovathemes-addons").'</span></h5>';
                    $output .= '<div class="inner-wrap">';
                        $output .= '<div class="pf-slider slider" '.implode(' ',$attributes).'  >';
                            $output .= '<div class="ui-slider-handle"><span class="ui-slider-handle-bubble min">'.wc_price($current_min_price).'</span></div><div class="ui-slider-handle"><span class="ui-slider-handle-bubble max">'.wc_price($current_max_price).'</span></div>';
                        $output .= '</div>';
                        $output .= '<input type="number" name="min" value="'.esc_attr( $current_min_price ).'" />';
                        $output .= '<span class="desh">-</span>';
                        $output .= '<input type="number" name="max" value="'.esc_attr( $current_max_price ).'" />';
                    $output .= '</div>';
                $output .= '</div>';

                return $output;

            }
        }

        function enovathemes_addons_render_rating_filter_attribute($include = false){
            $rating_filter = array(); // WPCS: input var ok, CSRF ok, sanitization ok.
            $found         = false;
            $rating_output = '';

            for ( $rating = 5; $rating >= 1; $rating-- ) {
                $count = enovathemes_addons_get_filtered_product_count($rating,$include);
                if ( empty( $count ) ) {
                    continue;
                }
                $found = true;
                $rdata  = '';

                if ( in_array( $rating, $rating_filter, true ) ) {
                    $rdata_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
                } else {
                    $rdata_ratings = implode( ',', array_merge( $rating_filter, array( $rating ) ) );
                }

                $class       = in_array( $rating, $rating_filter, true ) ? 'wc-layered-nav-rating chosen' : 'wc-layered-nav-rating';
                $rdata       = apply_filters( 'woocommerce_rating_filter_link', $rdata_ratings ? $rdata_ratings : '' );
                $rating_html = wc_get_star_rating_html( $rating );
                $rating_output .= '<li class="'.esc_attr( $class ).'"><a href="#" data-value="'.$rdata.'" title="'.esc_html__('Rating:','enovathemes-addons').' '.$rating.'"><span class="star-rating">'.$rating_html.'</span></a></li>';
            }

            if ($found) {

                $output = '<div class="pf-item rating clickable widget_rating_filter" data-attribute="rating">';
                    $output .= '<h5 class="widget_title">'.esc_html__('Rating','enovathemes-addons').'<span class="clear-attribute">'.esc_html__("Any rating","enovathemes-addons").'</span></h5>';
                    $output .= '<div class="inner-wrap">';
                        $output .= '<ul>';
                            if (!empty($rating_output)) {
                                $output .= $rating_output;
                            }
                        $output .= '</ul>';
                    $output .= '</div>';
                $output .= '</div>';

                return $output;
            }
        }

        function enovathemes_addons_render_category_filter_attribute($attribute,$cache,$include = false){

            $categories = is_array($include) && $attribute['lock'] == 'false' ? get_product_categories_hierarchy(false,$include) : get_product_categories_hierarchy($cache);

            if ((!empty($categories) && !is_wp_error($categories))){

                $type = (in_array($attribute['display'],array('list','image','image-list'))) ? 'clickable' : 'selectable';

                $data   = array();
                $data[] = 'data-attribute="ca"';
                $data[] = 'data-display="'.esc_attr($attribute['display']).'"';
                $data[] = 'data-lock="'.esc_attr($attribute['lock']).'"';
                $data[] = 'data-label="'.esc_attr(esc_html__( 'Category', 'enovathemes-addons' )).'"';

                if ($attribute['columns']) {
                    $data[] = 'data-columns="'.esc_attr($attribute['columns']).'"';
                }

                $class = array();

                $class[] = $type;

                if ($attribute['display'] == 'image-list') {
                    $class[] = 'image-list';
                    $class[] = 'list';
                } else {
                    $class[] = $attribute['display'];
                }

                $class[] = 'cat';
                
                $output = '<div class="pf-item '.implode(' ', $class).'" '.implode(' ', $data).'>';

                    $display_output = '';

                    switch ($attribute['display']) {
                        case 'list':
                        case 'image-list':
                        case 'image':
                            $display_output .= '<h5 class="widget_title">'.esc_html__( 'Shop by Categories', 'enovathemes-addons' ).'</h5>';
                            $display_output .= '<div class="inner-wrap">';
                                

                                if (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) {
                                    $display_output .= '<span class="back clear-attribute">'.et_get_theme_icon()['arrow'].esc_html__( 'Clear', 'enovathemes-addons' ).'</span>';
                                }

                                $display_output .= '<ul data-name="category" class="category" data-columns="'.esc_attr($attribute['columns']).'">';
                                    if (!empty($categories) && !is_wp_error($categories)){
                                        $display_output .= list_taxonomy_hierarchy_no_instance($categories,'',$attribute['display']);
                                    }
                                $display_output .= '</ul>';
                            $display_output .= '</div>';
                            break;
                        default:
                            $display_output .= '<span class="clear-attribute">'.esc_html__("Clear","enovathemes-addons").'</span><select name="category" class="category">';
                                $display_output .= '<option class="default" value="">'.esc_html__( 'Category', 'enovathemes-addons' ).'</option>';
                                if (!empty($categories) && !is_wp_error($categories)){
                                    $display_output .= list_taxonomy_hierarchy_no_instance($categories,'','default');
                                }
                            $display_output .= '</select>';
                            break;
                    }

                    $output .= $display_output;
                    
                $output .= '</div>';

                return $output;

            }
        }

        function enovathemes_addons_render_attribute_filter_attribute($attribute,$include = false){

            $attribute_terms = $include ? $include : (isset($attribute['terms']) ? $attribute['terms'] : '');

            if ($attribute['lock'] == "true" && $include) {

                $get_attribute_terms = get_terms( array(
                    'taxonomy' => 'pa_'.$attribute['name'],
                    'hide_empty' => true,
                ));

                if ($get_attribute_terms) {

                    $attribute_terms = array();

                    foreach ($get_attribute_terms as $term) {
                        $attribute_terms[$term->term_id] = array($term->name,$term->slug);
                    }
                }

            }

            if (isset($attribute_terms) && !empty($attribute_terms)) {

                $data   = array();
                $class  = array();

                $type = ($attribute['display'] == 'list' || $attribute['display'] == 'image'  || $attribute['display'] == 'label' || $attribute['display'] == 'col') ? 'clickable' : 'selectable';

                $data[] = 'data-attribute="'.esc_attr($attribute['name']).'"';
                $data[] = 'data-display="'.esc_attr($attribute['display']).'"';
                $data[] = 'data-lock="'.esc_attr($attribute['lock']).'"';
                $data[] = 'data-label="'.esc_attr($attribute['label']).'"';

                if ($attribute['columns']) {
                    $data[] = 'data-columns="'.esc_attr($attribute['columns']).'"';
                }

                $class[] = 'pf-item';
                $class[] = esc_attr($attribute['display']);
                $class[] = $attribute['name'];
                $class[] = 'attr';
                $class[] = $type;

                if (isset($attribute['category']) && !empty($attribute['category'])) {

                    if (is_array($attribute['category'])) {

                        $category_array = array();
                        $children_array = array();

                        foreach($attribute['category'] as $cat) {
                            $category = get_term_by('slug',$cat,'product_cat');

                            if (is_object($category) && !is_wp_error($category)) {
                                array_push($category_array,$category->slug);

                                $children = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'parent'=>$category->term_id)
                                );

                                if (!empty($children)) {
                                    foreach ($children as $child) {
                                        array_push($children_array, $child->slug);
                                    }
                                }
                            }
                        }

                        if (!empty($category_array)) {
                            $data[] = 'data-category="'.implode(',', $category_array).'"';
                        }
                        
                        if ($attribute['children'] == "true") {
                            if (!empty($children_array)) {
                                $data[] = 'data-children="'.implode(',', $children_array).'"';
                            }
                        } else {
                            $data[] = 'data-children="false"';
                        }
                        
                        $class[] = 'cat-active';

                    } else {

                        $category = get_term_by('slug',$attribute['category'],'product_cat');

                        if (is_object($category) && !is_wp_error($category)) {
                            $data[] = 'data-category="'.esc_attr($category->slug).'"';
                            if ($attribute['children'] == "true") {
                                $children = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'parent'=>$category->term_id)
                                );
                                if (!empty($children)) {
                                    $children_array = array();
                                    foreach ($children as $child) {
                                        array_push($children_array, $child->slug);
                                    }
                                    if (!empty($children_array)) {
                                        $data[] = 'data-children="'.implode(',', $children_array).'"';
                                    }
                                }
                            } else {
                                $data[] = 'data-children="false"';
                            }
                            $class[] = 'cat-active';
                        }

                    }
                    
                }

                if (isset($attribute['category-hide']) && !empty($attribute['category-hide'])) {


                    if (is_array($attribute['category-hide'])) {

                        $category_array = array();
                        $children_array = array();

                        foreach($attribute['category-hide'] as $cat) {
                            $category = get_term_by('slug',$cat,'product_cat');

                            if (is_object($category) && !is_wp_error($category)) {
                                array_push($category_array,$category->slug);

                                $children = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'parent'=>$category->term_id)
                                );

                                if (!empty($children)) {
                                    foreach ($children as $child) {
                                        array_push($children_array, $child->slug);
                                    }
                                }
                            }
                        }

                        if (!empty($category_array)) {
                            $data[] = 'data-category-hide="'.implode(',', $category_array).'"';
                        }
                        
                        if ($attribute['children-hide'] == "true") {
                            if (!empty($children_array)) {
                                $data[] = 'data-children-hide="'.implode(',', $children_array).'"';
                            }
                        } else {
                            $data[] = 'data-children-hide="false"';
                        }
                        
                        $class[] = 'cat-hide-active';

                    } else {

                        $category = get_term_by('slug',$attribute['category-hide'],'product_cat');

                        if (is_object($category) && !is_wp_error($category)) {
                            $data[] = 'data-category-hide="'.esc_attr($category->slug).'"';
                            if ($attribute['children-hide'] == "true") {
                                $children = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'parent'=>$category->term_id)
                                );
                                if (!empty($children)) {
                                    $children_array = array();
                                    foreach ($children as $child) {
                                        array_push($children_array, $child->slug);
                                    }
                                    if (!empty($children_array)) {
                                        $data[] = 'data-children-hide="'.implode(',', $children_array).'"';
                                    }
                                }
                            } else {
                                $data[] = 'data-children-hide="false"';
                            }
                            $class[] = 'cat-hide-active';
                        }

                    }
                }

                $display_output = '';
                $display_output .= '<div class="'.implode(' ', $class).'" '.implode(' ', $data).'>';
                $clear = '';

                $term_count = count($attribute_terms);

                switch ($attribute['display']) {
                    case 'list':
                    case 'label':
                    case 'image':
                    case 'col':
                        $display_output .= '<h5 class="widget_title">'.esc_html($attribute['label']).'<span class="clear-attribute">'.esc_html__("Clear","enovathemes-addons").'</span></h5>';
                        $display_output .= '<div class="inner-wrap">';
                            

                            if (in_array($attribute['display'], array('list','image','label')) && $term_count  > 10) {
                                $display_output .= '<input class="attribute-search" type="text" placeholder="'.esc_attr__("Search for").' '.lcfirst($attribute['label']).'">';
                            }

                            $class = array(esc_attr($attribute['name']));

                            if ($term_count  > 10) {
                                $class[]= 'max';
                            }

                            $display_output .= '<ul data-name="'.esc_attr($attribute['name']).'" class="'.implode(' ', $class).'" data-columns="'.esc_attr($attribute['columns']).'">';
                                $display_output .= list_attribute_no_instance($attribute_terms,$attribute['display']);
                            $display_output .= '</ul>';
                        $display_output .= '</div>';
                    break;
                    case 'slider':

                        $list = array();

                        foreach ($attribute_terms as $term) {
                            array_push($list, intval($term[0]));
                        };

                        $min = esc_attr(min($list));
                        $max = esc_attr(max($list));
                        $min = floor(floatval($min));
                        $max = ceil(floatval($max));

                        $values = '';

                        if ($include && isset($_POST[$attribute['name'].'_min_value'])) {

                            $values = array();

                            $values[] = $_POST[$attribute['name'].'_min_value'];

                            if (isset($_POST[$attribute['name'].'_max_value']) && !empty($_POST[$attribute['name'].'_max_value'])) {
                                $values[] = $_POST[$attribute['name'].'_max_value'];
                            }

                            if (count($values) == 2) {
                                $values = implode(',', $values);
                                $values = 'data-values="'.$values.'"';
                            }
                        }

                        $attributes = array();

                        if (!empty($values)) {
                            $attributes[] = $values;
                        }

                        $attributes[] = 'data-min="'.$min.'"';
                        $attributes[] = 'data-max="'.$max.'"';
                        $attributes[] = 'data-name="'.esc_attr($attribute['name']).'"';

                        $display_output .= '<h5 class="widget_title">'.esc_html($attribute['label']).'<span class="clear-attribute">'.esc_html__("Clear","enovathemes-addons").'</span></h5>';
                        $display_output .= '<div class="inner-wrap">';
                            $display_output .= '<div '.implode(' ', $attributes).' class="pf-slider slider '.esc_attr($attribute['name']).'"><div class="ui-slider-handle"><span class="ui-slider-handle-bubble min">'.$min.'</span></div><div class="ui-slider-handle"><span class="ui-slider-handle-bubble max">'.$max.'</span></div></div>';
                            $display_output .= '<input type="number" value="'.$min.'" name="min"><span class="desh">-</span><input type="number" value="'.$max.'" name="max">';
                        $display_output .= '</div>';
                    break;
                    default:
                        $display_output .= '<span class="clear-attribute">'.esc_html__("Clear","enovathemes-addons").'</span><select name="'.esc_attr($attribute['name']).'">';
                            $display_output .= '<option class="default" value="">'.esc_html($attribute['label']).'</option>';
                            if ($attribute_terms){
                                $display_output .= list_attribute_no_instance($attribute_terms,$attribute['display']);
                            }
                        $display_output .= '</select>';
                    break;
                }
                    
                $display_output .= '</div>';

                return $display_output;

            }

        }

    /* Filter attributes action
    /*----------------*/

        function filter_breadcrumbs_output($params,$cs = ''){
            $cs = array($cs);
            return '<div class="filter-breadcrumbs'.implode(' ',$cs).'">'.implode('', $params).'<a href="#" class="clear-all-attribute active">'.esc_html__('Reset','enovathemes-addons').'</a><div class="share"><a href="#" class="active" title="'.esc_html__('Share search results','enovathemes-addons').'">'.esc_html__('Share','enovathemes-addons').'</a>'.enovathemes_addons_post_social_share('filter').'</div></div>';
        }

        function generate_breadcrumbs($cat){

            $output      = '';

            $text_before = '<span>';
            $text_after  = '</span>';
            $link_after  = (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? et_get_theme_icon()['arrow'] : '';
            $home_text   = esc_html__('Home','enovathemes-addons');

            if(!empty(get_option('page_on_front')))
            $home_text = get_the_title( get_option('page_on_front') );
            $product_text  = (!empty(get_option('woocommerce_shop_page_id'))) ? get_the_title( get_option('woocommerce_shop_page_id') ) : get_bloginfo();
            
            $home_link = esc_url(home_url('/'));
            $shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';

            $output .= '<a href="' . $home_link . '">' . $home_text . '</a>'.$link_after;

            if ( $cat ) {

                $this_tax    = get_term_by('slug', $cat, 'product_cat');
                if($this_tax){

                    $this_parents = ($this_tax) ? get_ancestors( $this_tax->term_id, 'product_cat' ) : '';

                    $output .= '<a href="' . get_post_type_archive_link('product') . '">' . $product_text . '</a>'.$link_after;

                    if (is_array($this_parents) && !empty($this_parents)) {
                        foreach (array_reverse($this_parents) as $this_parent_ID) {
                            $this_parent = get_term($this_parent_ID, 'product_cat');
                            $output .= '<a href="'.get_term_link( $this_parent->slug, 'product_cat').'">'. $this_parent->name .'</a>'.$link_after;
                        }
                        $output .= $text_before . $this_tax->name . $text_after;
                    } else {
                        $output .= $text_before . $this_tax->name . $text_after;
                    }
                }

            } else {
                    $output .= $text_before . $product_text . $text_after;
            }

            return $output;
        }

        function filter_attributes() {
            
            if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {

                $atts = $filter = $filter_output = array();

                $off = array(
                    'plt',
                    'psz',
                    'onsale',
                    'sel',
                    'action',
                    'display',
                    'value',
                    'attribute',
                    'ajax',
                    'pn',
                    'yr',
                    'alg_currency',
                    'lang',
                    'post_type',
                    'product_cat',
                    'rating_filter',
                    'vin',
                    'universal',
                    'data_shop',
                    'page',
                );

                if (isset($_POST['product_cat']) && !empty($_POST['product_cat'])) {
                    $_POST['ca'] = $_POST['product_cat'];
                    $_POST['product_cat'] = '';
                }

                if (isset($_POST['rating_filter']) && !empty($_POST['rating_filter'])) {
                    $_POST['rating'] = $_POST['rating_filter'];
                    $_POST['rating_filter'] = '';
                }


                foreach ($_POST as $key => $value) {
                    if (!empty($value)) {

                        if (!in_array($key, $off)) {

                            $atts[$key] = $value;

                            if ($key != 'attributes') {
                                $filter[$key] = $value;
                            }

                        }
                    }
                }

                if (!empty($atts)) {

                    $shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';
                    if ('' === get_option( 'permalink_structure' )) {
                        $shop_link = get_home_url().'?post_type=product';
                    }

                    $cat_title = (!empty(get_option('woocommerce_shop_page_id'))) ? get_the_title( get_option('woocommerce_shop_page_id') ) : get_bloginfo();

                    $nav_output = $nav_output = $products_output = $found_output = $cat_description = $cat_children = $bread_output = $title_section_breadcrumbs_output = '';

                    $total = 0;
                    $max   = 0;

                    $shop_layout     = (isset($GLOBALS['propharm_enovathemes']['product-post-layout']) && $GLOBALS['propharm_enovathemes']['product-post-layout']) ? $GLOBALS['propharm_enovathemes']['product-post-layout'] : "grid";
                    $shop_navigation = (isset($GLOBALS['propharm_enovathemes']['blog-navigation']) && $GLOBALS['propharm_enovathemes']['blog-navigation']) ? $GLOBALS['propharm_enovathemes']['blog-navigation'] : "pagination";
                    $product_number  = (isset($GLOBALS['propharm_enovathemes']['product-per-page']) && !empty($GLOBALS['propharm_enovathemes']['product-per-page'])) ? $GLOBALS['propharm_enovathemes']['product-per-page'] : get_option( 'posts_per_page' );
                    $cache           = (class_exists('SitePress') || function_exists('pll_the_languages')) ? false : true;
                    
                    $data_shop  = (isset($_GET["data_shop"]) && !empty($_GET["data_shop"])) ? $_GET["data_shop"] : "default";
                    if($data_shop == 'infinite'){
                        $shop_navigation = 'infinite';
                    } elseif($data_shop == 'loadmore'){
                        $shop_navigation = 'loadmore';
                    }

                    if (empty($product_number)) {
                        $product_number =  get_option( 'posts_per_page' );
                    }

                    if (empty($shop_navigation)) {
                        $shop_navigation = 'pagination';
                    }

                    if (empty($shop_layout)) {
                        $shop_layout = 'grid';
                    }

                    if (isset($_POST['plt']) && $_POST['plt']) {
                        $shop_layout = $_POST['plt'];
                    }

                    $args = array(
                        'post_type'           => 'product',
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => 0,
                        'orderby'             => 'menu_order title',
                        'order'               => 'ASC',
                        'posts_per_page'      => -1,
                        'fields'              => 'ids'
                    );

                    $meta_query = $tax_query = $filter_breadcrumbs = array();

                    if (isset($_POST['onsale']) && $_POST['onsale'] == 1) {
                        $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                    }

                    if (!empty($filter)) {

                        foreach ($filter as $key => $value) {
                            if (!empty($value)) {
                                switch ($key) {
                                    case 'ca':

                                        $tax_query[] = array(
                                            'taxonomy' => 'product_cat',
                                            'field'    => 'slug',
                                            'terms'    => $value,
                                            'operator' => 'IN'
                                        );

                                        $term = get_term_by('slug',$value,'product_cat');

                                        if (is_object($term) && !is_wp_error($term)) {
                                            $filter_breadcrumbs[] = '<span class="breadcrumbs-item">'.esc_html__('Category').': '.$term->name.'</span>';
                                        }

                                    break;
                                    case 'orderby':

                                        switch ($value) {
                                            case 'menu_order':
                                                $orderby  = 'menu_order title';
                                                $order    = 'ASC';
                                                $meta_key = '';
                                                break;
                                            case 'popularity':
                                                $orderby  = 'meta_value_num';
                                                $order    = 'DESC';
                                                $meta_key = 'total_sales';
                                                break;
                                            case 'rating':
                                                $orderby  = 'meta_value_num';
                                                $order    = 'DESC';
                                                $meta_key = '_wc_average_rating';
                                                break;
                                            case 'date':
                                                $orderby  = 'date';
                                                $order    = 'DESC';
                                                $meta_key = '';
                                                break;
                                            case 'price':
                                                $orderby  = 'meta_value_num';
                                                $order    = 'ASC';
                                                $meta_key = '_price';
                                                break;
                                            case 'price-desc':
                                                $orderby  = 'meta_value_num';
                                                $order    = 'DESC';
                                                $meta_key = '_price';
                                                break;
                                            default:
                                                $orderby  = 'menu_order title';
                                                $order    = 'ASC';
                                                $meta_key = '';
                                                break;
                                        }

                                        $args['orderby'] = $orderby;
                                        $args['order']   = $order;

                                        if (!empty($meta_key)) {
                                            $args['meta_key'] = $meta_key;
                                        }
                                        
                                    break;
                                    case 'rating':

                                        $from = absint($value) - 0.3;
                                        $to   = absint($value) + 0.3;

                                        $meta_query[] = array(
                                            'relation' => 'AND',
                                            array(
                                                'key'     => '_wc_average_rating',
                                                'value'   => $from,
                                                'compare' => '>=',
                                            ),
                                            array(
                                                'key'     => '_wc_average_rating',
                                                'value'   => $to,
                                                'compare' => '<=',
                                            )
                                        );

                                        $filter_breadcrumbs[] = '<span class="breadcrumbs-item">'.esc_html__('Rating').': '.$value.'</span>';
                                        
                                    break;
                                    case 'min_price':
                                    case 'max_price':
                                        
                                        $min_price = (isset($filter['min_price']) && !empty($filter['min_price'])) ? $filter['min_price'] : 0;
                                        $max_price = (isset($filter['max_price']) && !empty($filter['max_price'])) ? $filter['max_price'] : 0;

                                        $price_args = array('min_price' => $min_price,'max_price' => $max_price);
                                        $meta_query[] = et_get_min_max_price_meta_query($price_args);

                                        $currency           = get_woocommerce_currency_symbol();
                                        $currency_pos       = get_option('woocommerce_currency_pos');

                                        switch ($currency_pos) {
                                            case 'left':
                                                $min_price = $currency.$min_price;
                                                $max_price = $currency.$max_price;
                                                break;
                                            case 'left_space':
                                                $min_price = $currency.' '.$min_price;
                                                $max_price = $currency.' '.$max_price;
                                                break;
                                            case 'right':
                                                $min_price = $min_price.$currency;
                                                $max_price = $max_price.$currency;
                                                break;
                                            case 'right_space':
                                                $min_price = $min_price.' '.$currency;
                                                $max_price = $max_price.' '.$currency;
                                                break;
                                        }

                                        $filter_breadcrumbs[] = '<span class="breadcrumbs-item">'.esc_html__('Price').': '.$min_price.' - '.$max_price.'</span>';

                                    break;
                                    case 's':

                                        $args['s'] = $value;

                                        $filter_breadcrumbs[] = '<span class="breadcrumbs-item">'.esc_html__('Keyword').': "'.$value.'"</span>';

                                    break;
                                    default:

                                        $terms = array();

                                        if (strpos($key,'_max_value') !== false) {

                                            $key_terms_array = array();

                                            $key = explode('_', $key);
                                            $key = $key[0];

                                            $max = intval($filter[$key.'_max_value']);
                                            $min = intval($filter[$key.'_min_value']);

                                            $key_terms = get_terms(array('taxonomy' => 'pa_'.$key,'hide_empty' => true));

                                            if (!empty($key_terms)) {
                                                foreach ($key_terms as $term) {
                                                    $name = intval($term->name);
                                                    if ($name >= $min && $name <= $max) {
                                                        array_push($key_terms_array, $term->term_id);
                                                    }
                                                }
                                            }

                                            if (!empty($key_terms_array)) {

                                                $tax_query[] = array(
                                                    'taxonomy' => 'pa_'.$key,
                                                    'field'    => 'id',
                                                    'terms'    => $key_terms_array,
                                                    'operator' => 'IN'
                                                );

                                            } else {
                                                $args['post__in']   = array(0);
                                            }

                                            $min_value = ($filter[$key.'_min_value']) ? $filter[$key.'_min_value'] : '0';

                                            $the_taxonomy = get_taxonomy('pa_'.$key);

                                            if (is_object($the_taxonomy)) {
                                                $filter_breadcrumbs[] = '<span class="breadcrumbs-item">'.ucfirst($the_taxonomy->labels->name).': '.$min_value.'-'.$filter[$key.'_max_value'].'</span>';
                                            }


                                        } elseif (strpos($key,'_min_value') == false) {

                                            $key_display = str_replace('filter_', '', $key);
                                            $key_display = str_replace('pa_', '', $key_display);

                                            if (strpos($key,'pa_') !== false) {
                                                $key_term = get_term_by('slug',$value,$key);

                                                if (is_object($key_term) && !is_wp_error($key_term)) {
                                                    $terms = array($key_term->slug);
                                                }

                                            } elseif(strpos($key,'filter_') !== false){

                                                $key      = str_replace('filter_', 'pa_', $key);
                                                $key_term = get_term_by('slug',$value,$key);

                                                if (is_object($key_term) && !is_wp_error($key_term)) {
                                                    $terms = array($key_term->slug);
                                                }

                                            } else {


                                                $key = 'pa_'.$key;
                                                $terms = explode(',', $value);
                                            }

                                            if (!empty($terms)) {
                                                $tax_query[] = array(
                                                    'taxonomy' => $key,
                                                    'field'    => 'slug',
                                                    'terms'    => $terms,
                                                    'operator' => 'IN'
                                                );

                                                $terms_name = array();

                                                foreach ($terms as $term) {
                                                    $term = get_term_by('slug',$term,$key);

                                                    if (is_object($term) && !is_wp_error($term)) {
                                                        array_push($terms_name, $term->name);
                                                    }
                                                }

                                                if (!empty($terms_name)) {
                                                    $filter_breadcrumbs[] = '<span class="breadcrumbs-item">'.ucfirst($key_display).': '.implode(', ', $terms_name).'</span>';
                                                }

                                            }
                                        }

                                    break;
                                }
                            }
                        }

                    }

                    $tax_query[] = array(
                        'taxonomy'  => 'product_visibility',
                        'terms'     => array( 'exclude-from-catalog' ),
                        'field'     => 'name',
                        'operator'  => 'NOT IN',
                    );

                    if (!empty($tax_query)) {
                        $tax_query = array_unique($tax_query,SORT_REGULAR);
                        if (count($tax_query) > 1) {
                            $tax_query['relation'] = 'AND';
                        }
                        $args['tax_query'] = $tax_query;
                    }

                    if (!empty($meta_query)) {
                        $meta_query = array_unique($meta_query,SORT_REGULAR);
                        if (count($meta_query) > 1) {
                            $meta_query['relation'] = 'AND';
                        }
                        $args['meta_query'] = $meta_query;
                    }

                    $output = array();

                    $query_filter = (empty($filter)) ? false : true;

                    $query_results  = new WP_Query($args);

                    if ($query_results->have_posts()) {

                        $product_IDs  = $query_results->posts;
                        $total        = $query_results->post_count;
                        $current      = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;
                        $pages        = round($total/$product_number);
                        $max          = ceil($total/$product_number);

                        if(!$pages){$pages = 1;}

                        /*Found products
                        ----------------------------------*/

                            $layout = (isset($_POST['data_shop']) && !empty($_POST['data_shop'])) ? ((has_filter('efp_layout_filter')) ? apply_filters('efp_layout_filter',$_POST['data_shop'],10,1) : $shop_layout) : $shop_layout;

                            if ($layout == false) {
                                $layout = $shop_layout;
                            }

                            if (empty($products_output)) {

                                foreach ($atts['attributes'] as $att => $opt) {
                                    $name = $opt['name'];
                                    if ($name != 'price' && $name != 'rating') {
                                        $$name = array();
                                    }
                                }

                                if (!empty($product_IDs)) {

                                    foreach ($product_IDs as $product_ID) {

                                        if ($query_filter) {
                                            foreach ($atts['attributes'] as $att => $opt) {

                                                $name = $opt['name'];

                                                switch ($name){
                                                    case 'ca':

                                                        $product_terms = wp_get_post_terms($product_ID,'product_cat',array( 'fields' => 'ids' ));

                                                        if (!is_wp_error($product_terms) && !empty($product_terms)) {

                                                            foreach ($product_terms as $id) {
                                                                ${$name}[]= $id;
                                                            }
                                                        }

                                                    break;

                                                    default:

                                                        $product_terms = wp_get_post_terms($product_ID,'pa_'.$name,array( 'fields' => 'all' ));

                                                        if (!is_wp_error($product_terms) && !empty($product_terms)) {

                                                            foreach ($product_terms as $term => $term_opt) {
                                                                ${$name}[$term_opt->term_id] = array($term_opt->name,$term_opt->slug);
                                                            }
                                                        }

                                                    break;
                                                }
                                            }
                                        }

                                    }
                                    
                                    if ($query_filter) {
                                        foreach ($atts['attributes'] as $att => $opt) {

                                            $name = $opt['name'];

                                            if ($name != 'price' && $name != 'rating') {

                                                $$name = (!empty($$name)) ? array_unique($$name,SORT_REGULAR) : false;

                                            }

                                            switch ($name){
                                                case 'ca':
                                                    $filter_output[$name] = enovathemes_addons_render_category_filter_attribute($opt,$cache,$$name);
                                                break;
                                                case 'price':
                                                    $filter_output[$name] = enovathemes_addons_render_price_filter_attribute($product_IDs);
                                                break;
                                                case 'rating':
                                                    $filter_output[$name] = enovathemes_addons_render_rating_filter_attribute($product_IDs);
                                                break;
                                                default:
                                                    $filter_output[$name] = enovathemes_addons_render_attribute_filter_attribute($opt,$$name);
                                                break;
                                            }
                                           
                                        }
                                    }

                                    $page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : ((get_query_var('paged')) ? get_query_var('paged') : 1);
                                    $args['posts_per_page'] = $product_number;
                                    $args['paged']          = $page;

                                    $query_results = null;
                                    $query_results = new WP_Query($args);

                                    if ($query_results->have_posts()) {

                                        while ($query_results->have_posts() ) {
                                            $query_results->the_post();

                                            global $product;

                                            $products_output .= '<li class="'.join( ' ', get_post_class('post')).'" id="product-'.$product->get_id().'">';

                                                $products_output .='<div class="post-inner et-item-inner">';

                                                    if(get_option( 'woocommerce_enable_ajax_add_to_cart' ) === "yes"){
                                                        $products_output .='<div class="ajax-add-to-cart-loading">';
                                                            $products_output .='<svg viewBox="0 0 56 56"><circle class="loader-path" cx="28" cy="28" r="20" /></svg>';
                                                            $products_output .='<svg viewBox="0 0 511.999 511.999" class="tick"><path d="M506.231 75.508c-7.689-7.69-20.158-7.69-27.849 0l-319.21 319.211L33.617 269.163c-7.689-7.691-20.158-7.691-27.849 0-7.69 7.69-7.69 20.158 0 27.849l139.481 139.481c7.687 7.687 20.16 7.689 27.849 0l333.133-333.136c7.69-7.691 7.69-20.159 0-27.849z"/></svg>';
                                                        $products_output .='</div>';
                                                    }
                                                    
                                                    $products_output .= propharm_enovathemes_loop_product_thumbnail($layout);
                                                    $products_output .= propharm_enovathemes_loop_product_title($layout);
                                                    $products_output .= propharm_enovathemes_loop_product_inner_close($layout);

                                                $products_output .='</div>';
                                            $products_output .= '</li>';

                                        }

                                    }

                                    wp_reset_postdata();

                                }

                            }

                        /*Navigation
                        ----------------------------------*/

                            if ($pages > 1) {

                                if ($shop_navigation == "pagination") {

                                    $nav_output .= '<ul class="page-numbers ajax">';
                                        if ($current > 1) {
                                            $nav_output .= '<li><a class="prev page-numbers" href="#"></a></li>';
                                        }

                                        $inc = ($current >= 5 && $pages > 8) ? $current - 3 : 1;
                                        $inc = ($current > ($pages - 3) && $pages > 8) ? $pages - 8 : $inc;

                                        for ($i=$inc; $i <= $pages; $i++) {

                                            if ($pages > 8 && $i == (4+$inc) && $i != $current && $current != ($pages - 3)) {
                                                $nav_output .= '<li><span class="page-numbers dots">…</span></li>';
                                                $i = $pages - 3;
                                            } else {
                                                if ($current == $i) {
                                                    $nav_output .= '<li><span aria-current="page" class="page-numbers current">'.$i.'</span></li>';
                                                } else {
                                                    $nav_output .= '<li><a class="page-numbers" data-page="'.$i.'" href="'.$shop_link.'page/'.$i.'/">'.$i.'</a></li>';
                                                }
                                            }

                                        }
                                        if ($current != $pages) {
                                            $nav_output .= '<li><a class="next page-numbers" href="#"></a></li>';
                                        }
                                    $nav_output .= '</ul>';

                                }

                            } else {

                                $nav_output = '<ul class="page-numbers ajax"></ul>';
                            }

                        /*Found results
                        ----------------------------------*/

                            // phpcs:disable WordPress.Security
                            if ( 1 === intval( $total ) ) {
                                $found_output = esc_html__( 'Showing the single result', 'woocommerce' );
                            } elseif ( $total <= $product_number || -1 === $product_number ) {
                                /* translators: %d: total results */
                                $found_output = sprintf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'woocommerce' ), $total );
                            } else {

                                if ($shop_navigation == 'pagination') {
                                    $first = ( $product_number * $current ) - $product_number + 1;
                                    $last  = min( $total, $product_number * $current );
                                } else {
                                    if ($current > 1) {
                                        $first = 1;
                                        $last  = min( $total, $product_number * $current );
                                    } else {
                                        $first = ( $product_number * $current ) - $product_number + 1;
                                        $last  = min( $total, $product_number * $current );
                                    }
                                }

                                $found_output = sprintf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ), $first, $last, $total );

                            }

                    } else {

                        $products_output  = '<li class="no-products"><p>'.esc_html__("No products found matching the filter criteria.","enovathemes-addons").'</p>';

                        $product_notfound_form = get_theme_mod('product_notfound_form');

                        if (!isset($product_notfound_form) || empty($product_notfound_form)) {
                            $products_output .= '<a href="'.$shop_link.'" class="button et-button medium">'.esc_html__("Go back to shop","enovathemes-addons").'</a>';
                        }

                        $products_output .= '</li>';

                    }

                    /*Breadcrumbs
                    ----------------------------------*/

                        if (!empty($filter_breadcrumbs)) {
                            $filter_breadcrumbs = array_unique($filter_breadcrumbs,SORT_REGULAR);
                            $bread_output = filter_breadcrumbs_output($filter_breadcrumbs,' cs');
                        }
                        
                        if (isset($_POST['ca'])) {
                            $title_section_breadcrumbs_output = generate_breadcrumbs($_POST['ca']);
                        }

                    /*Cat description
                    ----------------------------------*/

                        $category_args = array(
                            'hide_empty' => true,
                            'meta_key'   => 'order',
                            'orderby'    => 'meta_value_num',
                            'parent'     => 0
                        );

                        if (isset($_POST['ca']) && !empty($_POST['ca'])) {

                            $the_category = get_term_by('slug', $_POST['ca'], 'product_cat');

                            if ($the_category) {

                                $cat_description = $the_category->description;
                                $cat_title = $the_category->name;

                                $category_id = get_term_by('slug', $_POST['ca'], 'product_cat');

                                if ($category_id) {

                                    $children = get_term_children($category_id->term_id,'product_cat');

                                    if (!is_wp_error($children) && is_array($children) && !empty($children)) {
                                        $category_args['include'] = $children;
                                        unset($category_args['parent']);
                                    } else {
                                        $category_args = array();
                                    }

                                }

                            }
                        } else {

                            $category_args = array(
                                'hide_empty' => true,
                                'meta_key'   => 'order',
                                'orderby'    => 'meta_value_num',
                                'parent'     => 0
                            );

                        }

                        if (!empty($category_args)) {
                            
                            $category_terms = get_terms( 'product_cat', $category_args);

                            if (!empty($category_terms) && !is_wp_error($category_terms)) {

                                $cat_children .= '<div class="loop-categories-wrapper swiper">';
                                    
                                    $cat_children .= '<ul id="loop-categories" class="loop-categories swiper-wrapper">';
                                        
                                        foreach ( $category_terms as $term ){
                                            $cat_children .= '<li class="category-item swiper-slide"><a href="'.get_term_link($term->term_id,'product_cat').'">';
                                                $cat_children .= '<div class="image-container">';
                                                    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                                                    if ($thumbnail_id) {
                                                        $cat_children .= propharm_enovathemes_build_post_media('thumbnail',$thumbnail_id,'product');
                                                    }
                                                $cat_children .= '</div>';
                                                $cat_children .= '<h5>'.esc_html($term->name).'</h5>';
                                            $cat_children .= '</a></li>';
                                        }

                                    $cat_children .= '</ul>';

                                $cat_children .= '</div>';

                                $cat_children .= '<div class="swiper-button swiper-button-prev loop-categories-prev"></div><div class="swiper-button swiper-button-next loop-categories-next"></div>';

                            }

                        }

                    if ($query_filter == false) {
                        $filter_output = get_transient('enovathemes-product-filter');
                    }

                    $output['found']              = $found_output;
                    $output['products']           = $products_output;
                    $output['filter_output']      = $filter_output;
                    $output['dev']                = $atts['attributes'];
                    $output['nav']                = $nav_output;
                    $output['total']              = $total;
                    $output['max']                = $max;
                    $output['next_posts']         = $shop_link.'page/2/';
                    $output['bread']              = $bread_output;
                    $output['breadcrumbs']        = $title_section_breadcrumbs_output;
                    $output['cat_description']    = $cat_description;
                    $output['cat_children']       = $cat_children;
                    $output['cat_title']          = $cat_title;


                    echo json_encode($output);

                }
            }

            die();
        }
        add_action( 'wp_ajax_filter_attributes', 'filter_attributes' );
        add_action( 'wp_ajax_nopriv_filter_attributes', 'filter_attributes' );

    /* Filter by select action
    /*----------------*/

        function filter_select() {

            $next = (isset($_POST["next"]) && !empty($_POST["next"])) ? $_POST["next"] : false;

            $output = array();
            $output_terms  = '';

            $atts       = (isset($_POST["atts"]) && !empty($_POST["atts"])) ? json_decode( stripslashes ($_POST["atts"]),true ) : false;
            $tax_query  = array();

            if ($atts) {

                foreach ($atts as $param => $value) {
                    if ($param == 'category') {
                        $tax_query[] = array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => $value,
                            'operator' => 'IN'
                        );
                    } else {
                        $tax_query[] = array(
                            'taxonomy' => 'pa_'.$param,
                            'field'    => 'slug',
                            'terms'    => $value,
                            'operator' => 'IN'
                        );
                    }
                }

            }

            $args = array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 0,
                'posts_per_page'      => -1,
            );

            if (!empty($tax_query)) {
                $tax_query = array_unique($tax_query,SORT_REGULAR);
                if (count($tax_query) > 1) {
                    $tax_query['relation'] = 'AND';
                }
                $args['tax_query'] = $tax_query;
            }

            $query_results  = new WP_Query($args);

            if ($query_results->have_posts() && $next) {

                $next  = (($next == 'category') ? 'product_cat' : 'pa_'.$next);
                $terms  = array();
                
                while ($query_results->have_posts() ) {
                    $query_results->the_post();
                    $id = get_the_ID();
                    $product_terms = get_the_terms($id,$next);
                    if (!is_wp_error($product_terms) && !empty($product_terms)) {
                        foreach ($product_terms as $term) {
                            $terms[$term->slug] = $term->name;
                        }
                    }
                }
                wp_reset_postdata();

                $terms         = array_unique($terms,SORT_REGULAR);

                if (!empty($terms)) {
                    foreach ($terms as $key => $value) {
                        $output_terms .= '<option value="'.$key.'">'.$value.'</option>';
                    }
                }

            } else {
                $output_terms = '';
            }

            if (!empty($output_terms)) {
                $output['terms'] = $output_terms;
            }

            $output['args'] = $args;

            echo json_encode($output);

            die();
        }
        add_action( 'wp_ajax_filter_select', 'filter_select' );
        add_action( 'wp_ajax_nopriv_filter_select', 'filter_select' );

/* Posts shortcode actions
/*----------------*/

    function et_get_transient_keys_with_prefix( $prefix ) {
        global $wpdb;

        $prefix = $wpdb->esc_like( '_transient_' . $prefix );
        $sql    = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%s'";
        $keys   = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );

        if ( is_wp_error( $keys ) ) {
            return [];
        }

        return array_map( function( $key ) {
            // Remove '_transient_' from the option name.
            return str_replace('_transient_','',$key['option_name']);
        }, $keys );
    }

    function delete_transients_with_prefix( $prefix ) {
        foreach ( et_get_transient_keys_with_prefix( $prefix ) as $key ) {
            delete_transient( $key );
        }
    }

    function et_posts_ajax_query($atts="",$query_options=""){

        $unique = 'et_posts_';

        foreach($atts as $key => $val){
            if ($key != 'ajax' && $key != 'element_id' && !empty($val)) {
               $unique.=$val;
            }
        }

        if ( false === ( $post_query = get_transient( $unique ) ) ) {

            extract($atts);

            $posts = new WP_Query($query_options);

            if($posts->have_posts()){

                $output     = '';
                $class      = array();
                $attributes = array();

                $full_images = $full_content = '';

                $class[] = 'loop-posts';
                $class[] = 'only-posts';
                $class[] = 'et-clearfix';

                if ($layout == "carousel") {

                    $class[] = 'et-carousel';
                    $class[] = 'navigation-'.$navigation_type;

                    $attributes[] = 'data-nav="'.$navigation_type.'"';
                    $attributes[] = 'data-autoplay="'.$autoplay.'"';
                    $attributes[] = 'data-columns="'.$columns_grid.'"';
                    
                }elseif ($layout == "comp") {
                    $class[] = 'manual-carousel';
                }

                $attributes[] = 'data-columns="'.$columns_grid.'"';

                $shortcode_class = array();
                $shortcode_class[] = 'et-shortcode-posts';
                $shortcode_class[] = 'blog-layout';
                $shortcode_class[] = 'blog-layout-'.esc_attr($layout);
                $shortcode_class[] = esc_attr($layout);

                $thumb_size = ($layout == "list") ? 'propharm_425X425' : (($layout == "comp") ? 'propharm_1240X580' : 'propharm_600X400');

                $output .= '<div id="et-posts-'.$element_id.'" class="'.implode(' ',$shortcode_class).'" '.implode(' ', $attributes).'>';
                    $output .= '<div class="'.esc_attr(implode(' ', $class)).'" data-columns="'.$columns_grid.'" data-nav="'.$navigation_type.'">';
                            
                        if ($layout == "carousel" || $layout == "comp") {$output .= '<div class="slides">';}

                        while ($posts->have_posts() ) {
                            $posts->the_post();
                            $output .= propharm_enovathemes_post($layout,$excerpt,$title_length,$thumb_size);
                        }

                        wp_reset_postdata();

                        if ($layout == "carousel" || $layout == "comp") {$output .= '</div>';}

                    $output .= '</div>';
                $output .= '</div>';

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $output ) ) {
                    $post_query = base64_encode(serialize($output ));
                    set_transient( $unique, $post_query, apply_filters( 'null_post_query_cache_time', 0 ) );
                }

            }

        }

        if ( ! empty( $post_query ) ) {

            return unserialize(base64_decode($post_query));

        } else {

            return new WP_Error( 'no_posts', esc_html__( 'No posts.', 'enovathemes-addons' ) );

        }
    }

    function et_posts_ajax($atts="",$query_options=""){ global $enovathemes_addons;

        $ajax = 'false';

        if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {

            $ajax       = 'true';
            $ajax_calls = $_POST['ajax_calls'];
            $ajax_calls = explode(',', $ajax_calls);

            $output = array();

            foreach($ajax_calls as $args){
                $args             = explode('|', $args);
                $atts             = json_decode(base64_decode($args[1]),true);
                $query_options    = json_decode(base64_decode($args[2]),true);
                $output[$args[0]] = et_posts_ajax_query($atts,$query_options);
                
            }
            if (!empty($output)) {
                echo json_encode($output);
            }

        } else {
            return et_posts_ajax_query($atts,$query_options);
        }

        if ($ajax == 'true') {
            die();
        }
    }
    add_action( 'wp_ajax_et_posts_ajax', 'et_posts_ajax' );
    add_action( 'wp_ajax_nopriv_et_posts_ajax', 'et_posts_ajax' );

/* Woocommerce shortcode actions
/*----------------*/

    function woo_products_ajax_query($atts="",$query_options=""){

        $unique = 'et_products_';

        if (function_exists('get_woocommerce_currency')) {
            $unique .= '_'.get_woocommerce_currency();
        }

        foreach($atts as $key => $val){
            if ($key != 'ajax' && $key != 'element_id' && !empty($val)) {
               $unique.=$val;
            }
        }

        if ( false === ( $product_query = get_transient( $unique ) ) ) {

            extract($atts);

            $products = new WP_Query($query_options);

            if($products->have_posts()){

                global $enovathemes_addons;

                $product_image_full   = (isset($GLOBALS['propharm_enovathemes']['product-image-full']) && $GLOBALS['propharm_enovathemes']['product-image-full'] == 1) ? "true" : "false";

                $class      = array();
                $list_class = array();
                $attributes = array();

                $list_class[] = 'loop-posts';
                $list_class[] = 'loop-products';
                

                switch ($layout) {
                    case 'list':
                        $columns = $columns_list;
                        break;
                    case 'full':
                        $columns = $columns_full;
                        break;
                    default:
                        $columns = $columns_grid;
                        break;
                }

                $class[] = 'et-woo-products';
                $class[] = 'only';
                $class[] = 'post-layout';
                $class[] = 'layout-sidebar-none';
                $class[] = $layout;
                $class[] = 'highlight-'.$highlight;
                $class[] = 'gap-false';
                $class[] = 'discount-'.$discount;
                $class[] = 'nav-pos-'.$navigation_position;

                if ($columns == '6') {
                    $class[] = 'small';
                } elseif ($columns == '5') {
                    $class[] = 'medium';
                } elseif ($columns == '3') {
                    $class[] = 'large';
                }

                $columns_tab_port = ($layout == 'grid' || $layout == 'simple-grid') ? $columns_grid_tab_port : $columns_list_tab_port;
                $columns_tab_land = ($layout == 'grid' || $layout == 'simple-grid') ? $columns_grid_tab_land : $columns_list_tab_land;

                if ($layout == 'full') {
                    $columns_tab_port = 1;
                    $columns_tab_land = 1;
                }

                $attributes[] = 'data-rows="'.esc_attr($rows).'"';
                $attributes[] = 'data-columns="'.esc_attr($columns).'"';
                $attributes[] = 'data-columns-tab-port="'.esc_attr($columns_tab_port).'"';
                $attributes[] = 'data-columns-tab-land="'.esc_attr($columns_tab_land).'"';

                if ($carousel == "true") {
                    $list_class[] = 'et-carousel';
                    $list_class[] = 'manual-carousel';
                    $class[] = $navigation_type;
                }

                $element_id   = (isset($element_id) && !empty($element_id)) ? $element_id : rand(1,1000000);
                $attributes[] = 'id="et-woo-products-'.$element_id .'"';
                $attributes[] = 'class="'.esc_attr(implode(' ', $class)).'"';

                $counter = 1;

                $output = '<div '.implode(' ', $attributes).'>';
                    $output .= '<ul class="'.esc_attr(implode(' ', $list_class)).'" data-columns="'.esc_attr($columns).'" data-autoplay="'.esc_attr($autoplay).'" data-nav="'.esc_attr($navigation_type).'">';

                        if ($carousel == "true") {$output .= '<div class="slides">';}

                            while ($products->have_posts() ) {
                                $products->the_post();

                                global $product;

                                if (($counter % 2 == 1 && $rows == 2) || ($counter % 3 == 1 && $rows == 3)){
                                    $output .= '<li class="row-item"><ul>';
                                }

                                $output .= '<li class="'.join( ' ', get_post_class('post')).'" id="product-'.esc_attr($product->get_id()).'">';

                                    $output .='<div class="post-inner et-item-inner et-clearfix">';
                                        if(get_option( 'woocommerce_enable_ajax_add_to_cart' ) === "yes"){
                                            $output .='<div class="ajax-add-to-cart-loading">';
                                                $output .='<svg viewBox="0 0 56 56"><circle class="loader-path" cx="28" cy="28" r="20" /></svg>';
                                                $output .= propharm_enovathemes_svg_icon('tick');
                                            $output .='</div>';
                                        }
                                        $output .= propharm_enovathemes_loop_product_thumbnail($layout,$discount);
                                        $output .= propharm_enovathemes_loop_product_title($layout);
                                        $output .= propharm_enovathemes_loop_product_inner_close($layout);
                                    $output .='</div>';

                                $output .= '</li>';

                                if (($counter % 2 == 0 && $rows == 2) || ($counter % 3 == 0 && $rows == 3) || ($counter % 4 == 0 && $rows == 4)){
                                    $output .= '</ul></li>';
                                }

                                $counter++;

                            }

                        if ($carousel == "true") {$output .= '</div>';}

                        wp_reset_postdata();

                    $output .= '</ul>';
                $output .= '</div>';

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $output ) ) {
                    $product_query = base64_encode(serialize($output ));
                    set_transient( $unique, $product_query, apply_filters( 'null_product_query_cache_time', 0 ) );
                }

            } else {
                $product_query = '';
            }

        }

        if ( ! empty( $product_query ) ) {

            return unserialize(base64_decode($product_query));

        } else {

            return new WP_Error( 'no_products', esc_html__( 'No products.', 'enovathemes-addons' ) );

        }
    }

    function woo_products_ajax($atts="",$query_options=""){ global $enovathemes_addons;
        
        $ajax = 'false';

        if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {

            $ajax       = 'true';
            $ajax_calls = $_POST['ajax_calls'];
            $ajax_calls = explode(',', $ajax_calls);

            $output = array();

            foreach($ajax_calls as $args){
                $args             = explode('|', $args);
                $atts             = json_decode(base64_decode($args[1]),true);
                $query_options    = json_decode(base64_decode($args[2]),true);
                $output[$args[0]] = woo_products_ajax_query($atts,$query_options);
                
            }
            if (!empty($output)) {
                echo json_encode($output);
            }

        } else {
            return woo_products_ajax_query($atts,$query_options);
        }

        if ($ajax == 'true') {
            die();
        }
    }
    add_action( 'wp_ajax_woo_products_ajax', 'woo_products_ajax' );
    add_action( 'wp_ajax_nopriv_woo_products_ajax', 'woo_products_ajax' );

/* Faster AJAX
/*----------------*/

    add_action('init',function(){
        add_rewrite_tag('%product_query%','([0-9]+)');
        add_rewrite_rule('ajax-api/product-query/([0-9]+)/?','index.php?product_query=$matches[1]','top');

        add_rewrite_tag('%post_query%','([0-9]+)');
        add_rewrite_rule('ajax-api/post-query/([0-9]+)/?','index.php?post_query=$matches[1]','top');

        add_rewrite_tag('%megamenu_query%','([0-9]+)');
        add_rewrite_rule('ajax-api/megamenu-query/([0-9]+)/?','index.php?megamenu_query=$matches[1]','top');

        add_rewrite_tag('%footer_query%','([0-9]+)');
        add_rewrite_rule('ajax-api/footer-query/([0-9]+)/?','index.php?footer_query=$matches[1]','top');

        add_rewrite_tag('%mobile_query%','([0-9]+)');
        add_rewrite_rule('ajax-api/mobile-query/([0-9]+)/?','index.php?mobile_query=$matches[1]','top');
    });

    add_action('template_redirect',function(){

        global $wp_query;

        $product_query = $wp_query->get('product_query');

        if (!empty($product_query)) {

            $params = trim(file_get_contents("php://input"));
            $decoded = json_decode($params, true);

            if (isset($decoded['ajax']) && !empty($decoded['ajax'])) {

                $ajax       = 'true';
                $ajax_calls = $decoded['ajax_calls'];
                $ajax_calls = explode(',', $ajax_calls);

                $output = array();

                foreach($ajax_calls as $args){
                    $args             = explode('|', $args);
                    $atts             = json_decode(base64_decode($args[1]),true);
                    $query_options    = json_decode(base64_decode($args[2]),true);
                    $output[$args[0]] = woo_products_ajax_query($atts,$query_options);
                    
                }
                if (!empty($output)) {
                    wp_send_json_success($output);
                }

            }

        }

    });

    add_action('template_redirect',function(){

        global $wp_query;

        $post_query = $wp_query->get('post_query');

        if (!empty($post_query)) {

            $params = trim(file_get_contents("php://input"));
            $decoded = json_decode($params, true);

            if (isset($decoded['ajax']) && !empty($decoded['ajax'])) {

                $ajax       = 'true';
                $ajax_calls = $decoded['ajax_calls'];
                $ajax_calls = explode(',', $ajax_calls);

                $output = array();

                foreach($ajax_calls as $args){
                    $args             = explode('|', $args);
                    $atts             = json_decode(base64_decode($args[1]),true);
                    $query_options    = json_decode(base64_decode($args[2]),true);
                    $output[$args[0]] = et_posts_ajax_query($atts,$query_options);
                    
                }
                if (!empty($output)) {
                    wp_send_json_success($output);
                }

            }

        }

    });

    add_action('template_redirect',function(){

        global $wp_query;

        $megamenu_query = $wp_query->get('megamenu_query');

        if (!empty($megamenu_query)) {

            $params = trim(file_get_contents("php://input"));
            $decoded = json_decode($params, true);

            if (isset($decoded["megamenues"]) && !empty($decoded["megamenues"])){
                $megamenu  = enovathemes_addons_megamenus();
                $megamenues = explode("|", $decoded["megamenues"]);
                if (!is_wp_error($megamenu)) {
                    $data = array();
                    foreach ($megamenues as $mega_menu){
                        WPBMap::addAllMappedShortcodes();
                        $data[$mega_menu] = apply_filters('the_content', gzuncompress($megamenu[$mega_menu][2]));
                    }
                    if (!empty($data)) {
                        wp_send_json_success($data);
                    }
                    
                }

            }


        }

    });

    add_action('template_redirect',function(){

        global $wp_query;

        $footer_query = $wp_query->get('footer_query');

        if (!empty($footer_query)) {

            $params = trim(file_get_contents("php://input"));
            $decoded = json_decode($params, true);

            if (isset($decoded["footer"]) && !empty($decoded["footer"])){

                $footers = enovathemes_addons_footers();
                if (!is_wp_error($footers)) {
                    $data = array();
                    WPBMap::addAllMappedShortcodes();
                    $data[$decoded["footer"]] = apply_filters('the_content', gzuncompress($footers[$decoded["footer"]]));
                    if (!empty($data)) {
                        wp_send_json_success($data);
                    }
                }

            }


        }

    });

    add_action('template_redirect',function(){

        global $wp_query;

        $mobile_query = $wp_query->get('mobile_query');

        if (!empty($mobile_query)) {

            $params = trim(file_get_contents("php://input"));
            $decoded = json_decode($params, true);

            if (isset($decoded["content"]) && !empty($decoded["content"])){
                $data = array();
                WPBMap::addAllMappedShortcodes();
                $data[$decoded["mobile"]] = apply_filters('the_content', gzuncompress(base64_decode($decoded["content"])));
                if (!empty($data)) {
                    wp_send_json_success($data);
                }
            }


        }

    });

?>