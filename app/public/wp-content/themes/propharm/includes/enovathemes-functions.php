<?php

/*  Default fonts
/*-------------------*/
    
    function propharm_enovathemes_fonts_url() {
        $font_url = '';
        if ( 'off' !== _x( 'on', 'Google font: on or off', 'propharm' ) ) {
            $font_url = add_query_arg( 'family', urlencode( 'PT Sans:ital,wght@0,400;0,700;1,400;1,700' ), "//fonts.googleapis.com/css2" );
        }
        return $font_url;
    }


/*  Post format chat
/*-------------------*/

    function propharm_enovathemes_post_chat_format($content) {
        global $post;
        if (has_post_format('chat')) {
            $chatoutput = "<ul class=\"chat\">\n";
            $split = preg_split("/(\r?\n)+|(<br\s*\/?>\s*)+/", $content);

            foreach($split as $haystack) {
                if (strpos($haystack, ":")) {
                    $string = explode(":", trim($haystack), 2);
                    $who = strip_tags(trim($string[0]));
                    $what = strip_tags(trim($string[1]));
                    $row_class = empty($row_class)? " class=\"chat-highlight\"" : "";
                    $chatoutput = $chatoutput . "<li><span class='name'>$who:</span><p>$what</p></li>\n";
                } else {
                    $chatoutput = $chatoutput . $haystack . "\n";
                }
            }

            $content = $chatoutput . "</ul>\n";
            return $content;
        } else { 
            return $content;
        }
    }
    add_filter( "the_content", "propharm_enovathemes_post_chat_format", 9);

/*  Get the widget
/*-------------------*/

    if( !function_exists('propharm_enovathemes_get_the_widget') ){
  
        function propharm_enovathemes_get_the_widget( $widget, $instance = '', $args = '' ){
            ob_start();
            the_widget($widget, $instance, $args);
            return ob_get_clean();
        }
        
    }

/*  get SVG contents
/*-------------------*/
    
    function propharm_enovathemes_svg_icon( $svg) {
        ob_start();
        get_template_part( 'images/icons/php/'.$svg);
        return ob_get_clean();
    }

/*  Post image overlay
/*-------------------*/

    function propharm_enovathemes_post_image_overlay($blog_post_layout){

        $post_format   = get_post_format(get_the_ID());
        $link_url      = get_post_meta( get_the_ID(), 'enovathemes_addons_link', true );

        $read_more_link = ($blog_post_layout == "full" && $post_format == "link" && !empty($link_url)) ? $link_url : get_the_permalink();

        $output = '';

        $output .='<a class="post-image-overlay" href="'.esc_url($read_more_link).'" title="'.esc_attr__("Read more about", 'propharm').' '.esc_attr(the_title_attribute( 'echo=0' )).'">';
        $output .='</a>';

        return $output;
    }

/*  Pagination
/*-------------------*/

    function propharm_enovathemes_post_nav_num($post_type){

        if( is_singular() ){
            return;
        }

        global $wp_query;

        $big    = 999999;
        $output = "";

        switch ($post_type) {
            case 'product':
                $posts_per_page = (isset($GLOBALS['propharm_enovathemes']['product-per-page']) && !empty($GLOBALS['propharm_enovathemes']['product-per-page'])) ? $GLOBALS['propharm_enovathemes']['product-per-page'] : get_option( 'posts_per_page' );
                break;
            default:
                $posts_per_page = '';
                break;
        }

        $total  = (empty($posts_per_page)) ? $wp_query->max_num_pages : ceil($wp_query->found_posts/$posts_per_page);

        $args = array(
        'base'      => str_replace($big, '%#%', get_pagenum_link($big)),
        'format'    => '?paged=%#%',
        'total'     => $total,
        'current'   => max(1, get_query_var('paged')),
        'show_all'  => false,
        'end_size'  => 2,
        'mid_size'  => 3,
        'prev_next' => true,
        'prev_text' => '',
        'next_text' => '',
        'type'      => 'list');

        if ($posts_per_page < $wp_query->found_posts) {
            $output .='<nav class="enovathemes-navigation">';
                $output .= paginate_links($args);
            $output .='</nav>';
        }
        
        echo propharm_enovathemes_output_html($output);
    }

/*  Simple pagination
/*-------------------*/
    
    function propharm_enovathemes_post_nav($post_type,$post_id){

            global $propharm_enovathemes;

            $single_nav_mob = "false";

            if ($post_type == "product") {
                $post_prev_text = esc_html__('Previous product', 'propharm');
                $post_next_text = esc_html__('Next product', 'propharm');
            } else {
                $post_prev_text = esc_html__('Previous post', 'propharm');
                $post_next_text = esc_html__('Next post', 'propharm');
            }

            $prev_post = get_adjacent_post(false, '', true);
            $next_post = get_adjacent_post(false, '', false);
            
        ?>
        <nav class="post-single-navigation <?php echo esc_attr($post_type) ?> mob-hide-false et-clearfix">  
          <?php if(!empty($next_post)) {echo '<a rel="prev" href="' . esc_url(get_permalink($next_post->ID)) . '" title="'.esc_attr__("Previous ","propharm").$post_type.'">'.$post_prev_text.'</a>'; } ?>
          <?php if(!empty($prev_post)) {echo '<a rel="next" href="' . esc_url(get_permalink($prev_post->ID)) . '" title="'.esc_attr__("Next ","propharm").$post_type.'">'.$post_next_text.'</a>'; } ?>
        </nav>
        <?php 
    }

/*  Navigation
/*-------------------*/

    function propharm_enovathemes_navigation($post_type, $navigation){

        $hidden  = (isset($_GET["ajax"]) && !empty($_GET["ajax"])) ? 'hidden' : '';

    ?>
        <div class="nav-wrapper <?php echo esc_attr($hidden); ?>">
        <?php 
        switch ($navigation) {
            case 'infinite':
            case 'loadmore':

                $attributes = array();
                $class      = array();
                $class[]    = 'post-ajax-button';
                $class[]    = 'et-button';
                $class[]    = 'hover-scale';
                $class[]    = 'rounded';
                $class[]    = 'medium';

                $attributes[] = 'href="#"';
                $attributes[] = 'data-effect="scale"';
                $attributes[] = 'class="'.implode(" ", $class).'"';
                $attributes[] = 'id="'.$navigation.'"';

                $output ='<a '.implode(" ", $attributes).' >';
                    $output .='<span class="text">'.esc_html__('Load more','propharm').'</span>';

                    $output .='<svg viewBox="0 0 48 48">';
                        $output .='<circle class="loader-path" cx="24" cy="24" r="20" />';
                    $output .='</svg>';

                    $output .='<span class="button-back"></span>';
                $output .='</a>';

                echo propharm_enovathemes_output_html($output);

                break;
            default:
                echo propharm_enovathemes_post_nav_num($post_type);
                break;
        }
        ?>
        </div>
    <?php }

/*  Excerpt
/*-------------------*/

    function propharm_enovathemes_substrwords($text, $maxchar, $end='..') {
        if (strlen($text) > $maxchar || $text == '') {
            $words = preg_split('/\s/', $text);      
            $output = '';
            $i      = 0;
            while (1) {
                $length = strlen($output)+strlen($words[$i]);
                if ($length > $maxchar) {
                    break;
                } 
                else {
                    $output .= " " . $words[$i];
                    ++$i;
                }
            }
            $output .= $end;
        } 
        else {
            $output = $text;
        }
        return $output;
    }

/*  Loop post content
/*-------------------*/

    function propharm_enovathemes_build_post_media($blog_post_layout,$thumb_size,$id,$post_type ='post'){

        global $propharm_enovathemes;

        $lazy = (isset($GLOBALS['propharm_enovathemes']['lazy']) && $GLOBALS['propharm_enovathemes']['lazy'] == 1) ? 'true' : 'false';

        $thumbnail_id  = ($id) ? $id: get_post_thumbnail_id( get_the_ID() );
        $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
        $thumbnail     = wp_get_attachment_image_src($thumbnail_id,$thumb_size);
        $single_thumbnail_resp = wp_get_attachment_image_src($thumbnail_id,'propharm_600X400');

        if (is_array($thumbnail)) {

            $image_caption = get_the_post_thumbnail_caption($thumbnail);
            $image_alt     = (empty($image_caption)) ? ((empty($thumbnail_alt)) ? get_bloginfo('name') : $thumbnail_alt) : $image_caption;

            $responsive_data = array();
            $responsive_data_clone = array();

            $class = 'lazy';

            if ($blog_post_layout == "list") {

               $data_img          = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

               if ($post_type == 'post') {
                  $thumbnail_600X400 = wp_get_attachment_image_src($thumbnail_id,'propharm_600X400');
                  $responsive_data[] = 'data-resp-src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHZpZXdCb3g9JzAgMCAzIDInPjwvc3ZnPg"';
                  $responsive_data[] = 'data-resp-src-original="'.esc_url($thumbnail_600X400[0]).'"';
                  $responsive_data[] = 'data-resp-width="'.esc_attr($thumbnail_600X400[1]).'"';
                  $responsive_data[] = 'data-resp-height="'.esc_attr($thumbnail_600X400[2]).'"';

                  $responsive_data_clone[] = 'data-clone-resp-src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHZpZXdCb3g9JzAgMCAzIDInPjwvc3ZnPg"';
                  $responsive_data_clone[] = 'data-clone-resp-src-original="'.esc_url($thumbnail[0]).'"';
                  $responsive_data_clone[] = 'data-clone-resp-width="'.esc_attr($thumbnail[1]).'"';
                  $responsive_data_clone[] = 'data-clone-resp-height="'.esc_attr($thumbnail[2]).'"';
               }
               

            } elseif ($blog_post_layout == "single") {
                $data_img = PROPHARM_SVG.'image_placeholder_single.svg';
                $class .= ' single';
            } elseif ($blog_post_layout == "full") {
                $data_img = PROPHARM_SVG.'image_placeholder_full.svg';
                $class .= ' single';
            } elseif ($blog_post_layout == "catalog") {
                $data_img = PROPHARM_SVG.'image_placeholder_catalog.svg';
            } else {
                $data_img = PROPHARM_SVG.'image_placeholder.svg';
            }


            $output = "";


            if ($lazy == "true") {
                $output .= '<img class="'.$class.'" data-img-resp="'.implode('|', $single_thumbnail_resp).'" data-img-desk="'.implode('|', $thumbnail).'" alt="'.esc_attr($image_alt).'" src="'.$data_img.'" width="'.esc_attr($thumbnail[1]).'" height="'.esc_attr($thumbnail[2]).'" data-src="'.esc_url($thumbnail[0]).'" '.implode(' ', $responsive_data).' '.implode(' ', $responsive_data_clone).' />';
                $output .= propharm_enovathemes_svg_icon('placeholder');
            } else {
                $output .= '<img alt="'.esc_attr($image_alt).'" src="'.esc_url($thumbnail[0]).'" width="'.esc_attr($thumbnail[1]).'" height="'.esc_attr($thumbnail[2]).'" />';
            }

            
            return $output;

        }
    }

    function propharm_enovathemes_post_media($blog_post_layout,$thumb_size){
        
        $post_format   = get_post_format(get_the_ID());
        $video         = get_post_meta( get_the_ID(), 'enovathemes_addons_video', true );
        $video_embed   = get_post_meta( get_the_ID(), 'enovathemes_addons_video_embed', true );
        $gallery       = get_post_meta( get_the_ID(), 'enovathemes_addons_gallery', true );

        $output_date = '<div class="post-date-side">';
            $output_date .= '<span>'.date_i18n('d', strtotime(get_the_date())).'</span>';
            $output_date .= '<span>'.date_i18n('M', strtotime(get_the_date())).'</span>';
        $output_date .= '</div>';

        $output = "";

        if ($blog_post_layout == "full"){

            if (
                $post_format == "0" || 
                $post_format == 'chat' || 
                $post_format == 'aside'  || 
                $post_format == 'quote' || 
                $post_format == 'status' || 
                $post_format == 'audio' || 
                $post_format == 'link'){
                if (has_post_thumbnail()){
                    $output .='<div class="post-image overlay-hover post-media">';
                        $output .= $output_date;
                        $output .= propharm_enovathemes_post_image_overlay($blog_post_layout);
                        $output .='<div class="image-container">';
                            $output .= propharm_enovathemes_build_post_media($blog_post_layout,$thumb_size,false);
                        $output .='</div>';
                        $output .= $output_date;
                    $output .='</div>';
                }
            } elseif($post_format == "gallery") {

                if (!empty($gallery)) {

                    $output .='<div class="post-gallery post-media overlay-hover" data-columns="1">';
                        $output .= $output_date;

                        $output .='<ul class="slides tns-slider tns-gallery tns-subpixel tns-calc tns-horizontal">';
                            foreach ($gallery as $image => $url){
                                $output .='<li>';
                                    $output .= propharm_enovathemes_post_image_overlay($blog_post_layout);
                                    $output .='<div class="image-container">';
                                        $output .= propharm_enovathemes_build_post_media($blog_post_layout,$thumb_size,$image);
                                    $output .='</div>';
                                $output .='</li>';
                            }
                        $output .='</ul>';
                        $output .= $output_date;
        
                        $output .='<div class="tns-controls-trigger">';
                            $output .='<button type="button" data-controls="prev" tabindex="-1" aria-controls="tns1"></button>';
                            $output .='<button type="button" data-controls="next" tabindex="-1" aria-controls="tns1"></button>';
                        $output .='</div>';

                    $output .='</div>';

                } else {

                    if (has_post_thumbnail()){
                        $output .='<div class="post-image overlay-hover post-media">';
                            $output .= $output_date;

                            $output .= propharm_enovathemes_post_image_overlay($blog_post_layout);
                            $output .='<div class="image-container">';
                                $output .= propharm_enovathemes_build_post_media($blog_post_layout,$thumb_size,false);
                            $output .='</div>';

                            $output .= $output_date;

                        $output .='</div>';
                    }

                }
            } elseif($post_format == "video") {
                if (!empty($video) || !empty($video_embed)){
                    $output .='<div class="post-video post-media">';
                        $output .= $output_date;

                        if (has_post_thumbnail()){

                            $link_class[] = 'video-btn';

                            $attributes   = array();
                            $attributes[] = 'href="#"';
                            $attributes[] = 'class="'.implode(" ", $link_class).'"';

                            $output .='<div class="image-container">';

                                $output .= propharm_enovathemes_build_post_media($blog_post_layout,$thumb_size,false);

                                $output .='<a '.implode(" ", $attributes).'>';
                                    $output .='<svg viewBox="0 0 512 512">';
                                        $output .='<path class="back" d="M512,256c0,141.38-114.62,256-256,256S0,397.38,0,256,114.62,0,256,0,512,114.62,512,256Z" />';
                                        $output .='<path class="play" d="M346.89,261.61,205.11,350c-4.76,3-11.11-.24-11.11-5.61V167.62c0-5.37,6.35-8.57,11.11-5.61l141.78,88.38A6.61,6.61,0,0,1,346.89,261.61Z"/>';
                                    $output .='</svg>';
                                $output .='</a>';
                                
                            $output .='</div>';
                        }

                        if(!empty($video_embed) && empty($video)) {

                            $video_embed = str_replace('watch?v=', 'embed/', $video_embed);
                            $video_embed = str_replace('//vimeo.com/', '//player.vimeo.com/video/', $video_embed);

                            $output .='<iframe allowfullscreen="allowfullscreen" allow="autoplay" frameBorder="0" src="'.$video_embed.'" class="iframevideo video-element"></iframe>';

                        } elseif(!empty($video)) {

                            $output .='<video poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png'.'" id="video-'.get_the_ID().'" class="lazy video-element" playsinline controls>';

                                if (!empty($video)) {
                                    $output .='<source data-src="'.$video.'" src="'.PROPHARM_ENOVATHEMES_IMAGES.'/video_placeholder.mp4'.'" type="video/mp4">';
                                }
                                
                            $output .='</video>';

                        }

                        $output .= $output_date;

                    $output .='</div>';
                }
            }
        } else {
            
            $output .='<div class="post-image overlay-hover post-media">';
                $output .= propharm_enovathemes_post_image_overlay($blog_post_layout);
                $output .='<div class="image-container">';
                    if (has_post_thumbnail()){
                        $output .=propharm_enovathemes_build_post_media($blog_post_layout,$thumb_size,false);
                    }
                $output .='</div>';

                $output .= $output_date;

            $output .='</div>';
            
        }

        return $output;
    }

    function propharm_enovathemes_post_body($blog_post_layout,$blog_post_excerpt,$blog_post_title_excerpt){

        global $propharm_enovathemes;

        $post_format   = get_post_format(get_the_ID());
        $link_url      = get_post_meta( get_the_ID(), 'enovathemes_addons_link', true );
        $status_author = get_post_meta( get_the_ID(), 'enovathemes_addons_status', true );
        $quote_author  = get_post_meta( get_the_ID(), 'enovathemes_addons_quote', true );
        $audio         = get_post_meta( get_the_ID(), 'enovathemes_addons_audio', true );
        $audio_embed   = get_post_meta( get_the_ID(), 'enovathemes_addons_audio_embed', true );

        $read_more_link = ($blog_post_layout == "full" && $post_format == "link" && !empty($link_url)) ? $link_url : get_the_permalink();
        
        $output = "";

        $output .='<div class="post-body et-clearfix">';


            $output .='<div class="post-body-inner">';

                $output .= '<div class="post-categories">';

                    $categories = get_the_category();

                    foreach( $categories as $category) {
                        $name = $category->name;
                        $category_link = get_category_link( $category->term_id );

                        $output .= '<a href="'.$category_link.'" title="'.esc_attr($name).'">'.esc_html($name).'</a>';
                    }

                $output .='</div>';

                if ( '' != the_title_attribute( 'echo=0' ) ){
                    $output .='<h4 class="post-title entry-title">';
                        $output .= '<a href="'.esc_url($read_more_link).'" title="'.esc_attr__("Read more about", 'propharm').' '.the_title_attribute( 'echo=0' ).'" rel="bookmark">';
                            $output .= propharm_enovathemes_substrwords(the_title_attribute( 'echo=0' ),$blog_post_title_excerpt);
                        $output .= '</a>';
                    $output .='</h4>';
                }
           
                if ($blog_post_layout == "full"){

                    if ($post_format == "aside" || $post_format == "quote" || $post_format == "status"){

                        if ( '' != get_the_content() ){
                            $output .='<div class="post-excerpt">';

                                $output .= get_the_content(); 
                                $defaults = array(
                                    'before'           => '<div id="page-links">',
                                    'after'            => '</div>',
                                    'link_before'      => '',
                                    'link_after'       => '',
                                    'next_or_number'   => 'next',
                                    'separator'        => ' ',
                                    'nextpagelink'     => esc_html__( 'Continue reading', 'propharm' ),
                                    'previouspagelink' => esc_html__( 'Go back' , 'propharm'),
                                    'pagelink'         => '%',
                                    'echo'             => 0
                                );
                                $output .= wp_link_pages($defaults);

                            $output .='</div>';
                        }

                        if (!empty($quote_author)){
                            $output .= '<div class="post-quote-author">'.esc_attr($quote_author).'</div>';
                        }

                        if (!empty($status_author)){
                            $output .= '<div class="post-status-author">'.esc_attr($status_author).'</div>';
                        }

                    } else {
                        if ( '' != get_the_excerpt() && $blog_post_excerpt > 0){
                            $output .='<div class="post-excerpt">'.propharm_enovathemes_substrwords(get_the_excerpt(),$blog_post_excerpt).'</div>';
                        }
                    }

                } else {
                    if ($blog_post_layout != 'comp') {
                        if ( '' != get_the_excerpt() && $blog_post_excerpt > 0){
                            $output .='<div class="post-excerpt">'.propharm_enovathemes_substrwords(get_the_excerpt(),$blog_post_excerpt).'</div>';
                        }
                    }
                }

                $output .='<a href="'.esc_url($read_more_link).'" class="post-read-more" title="'.esc_attr__("Read more about", 'propharm').' '.the_title_attribute( 'echo=0' ).'">'.esc_html__("Read more", 'propharm').propharm_enovathemes_svg_icon('arrow').'</a>';


            $output .='</div>';


        $output .='</div>';

        return $output;
        
    }

    function propharm_enovathemes_post($blog_post_layout,$blog_post_excerpt,$blog_post_title_excerpt,$thumb_size){

        $output = "";
        $class  = "";

        if (!has_post_thumbnail()){
            $class = ' no-media';
        }

        $output .='<article class="'.join( ' ', get_post_class('post')).$class.'" id="post-'.get_the_ID().'">';
        
            $output .='<div class="post-inner et-item-inner et-clearfix">';

                if (has_post_thumbnail(get_the_ID())) {
                    // Post media
                    $output .= propharm_enovathemes_post_media($blog_post_layout,$thumb_size);
                }
                
                // Post body
                $output .= propharm_enovathemes_post_body($blog_post_layout,$blog_post_excerpt,$blog_post_title_excerpt);

            $output .='</div>';
        $output .='</article>';

        return $output;

    }

/*  Not found
/*-------------------*/

    function propharm_enovathemes_not_found($post_type){

        $output = '';

        $output .= '<p class="enovathemes-not-found">';

        switch ($post_type) {

            case 'products':
                $output .= esc_html__('No products found.', 'propharm');
                break;

            case 'general':
                $output .= esc_html__('No search results found. Try a different search', 'propharm');
                break;
            
            default:
                $output .= esc_html__('No posts found.', 'propharm');
                break;
        }

        $output .= '</p>';

        return $output;
    }

/*  Hex to rgba
/*-------------------*/

    function propharm_enovathemes_hex_to_rgba($hex, $o) {
        $hex = (string) $hex;
        $hex = str_replace("#", "", $hex);
        $hex = array_map('hexdec', str_split($hex, 2));
        return 'rgba('.implode(",", $hex).','.$o.')';
    }

/*  Hex to rgb shade
/*-------------------*/

    function propharm_enovathemes_hex_to_rgb_shade($hex, $o) {
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

    function propharm_enovathemes_brightness($hex) {
        $hex = (string) $hex;
        $hex = str_replace("#", "", $hex);
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $output = 'dark';

        if($r + $g + $b > 650){
            $output = 'light';
        }else{
            $output = 'dark';
        }

        return $output;
    }

/*  Minify CSS
/*-------------------*/

    function propharm_enovathemes_minify_css($css) {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }

/*  Output html
/*-------------------*/

    function propharm_enovathemes_output_html($html) {
        $html = preg_replace('~>\s+<~', '><', $html);
        return $html;
    }

/*  Get all menus
/*-------------------*/

    function propharm_enovathemes_get_all_menus(){
        return get_terms( 'nav_menu', array( 'hide_empty' => false ) ); 
    }

/*  Default header
/*-------------------*/

    function propharm_enovathemes_default_header($header_type){

        if ($header_type == "mobile") { ?>

            <header id="et-mobile-default" class="header et-mobile et-clearfix transparent-false sticky-false shadow-true mobile-true desktop-false et-mobile-default">
                <div class="vc_row wpb_row vc_row-fluid vc-row-default">
                    <div class="container et-clearfix">
                        <div class="wpb_column vc_column_container vc_col-sm-12 text-align-none">
                            <div class="vc_column-inner vci ">
                                <div class="wpb_wrapper">


                                    <div id="mctd" class="mobile-container-toggle mctd hbe hbe-icon-element hide-default-false hide-sticky-false hbe-right size-small">
                                        <div id="mobile-toggle-default" class="mobile-toggle hbe-toggle">
                                            <?php echo propharm_enovathemes_svg_icon('mobile-toggle'); ?>
                                        </div>
                                    </div>

                                    <?php

                                        $output = "";

                                        $class = array();
                                        $class[] = 'hbe';
                                        $class[] = 'header-logo';
                                        $class[] = 'hbe-left';

                                        $output .= '<div id="mobile-header-logo-default" class="'.implode(" ", $class).'">';
                                            $output .= '<a href="'.esc_url(home_url('/')).'" title="'.get_bloginfo('name').'">';
                                                $output .= '<img class="logo" src="'.PROPHARM_ENOVATHEMES_IMAGES.'/logo.svg" alt="'.get_bloginfo('name').'">';
                                            $output .= '</a>';
                                        $output .= '</div>';

                                        echo propharm_enovathemes_output_html($output);

                                    ?>

                                    <div id="mobile-container-default" class="mobile-container">
                                        <div class="mobile-container-inner et-clearfix">
                                            <div id="vertical-align-top-default" class="snva vertical-align-top">


                                                
                                                <?php

                                                    $output  = '';
                                                    $class   = array();
                                                    $class[] = 'mobile-menu-container';
                                                    $class[] = 'hbe';
                                                    $class[] = 'text-align-left';

                                                    if (has_nav_menu( 'header-menu' )) {
                                                        $menu_arg = array(
                                                            'theme_location'  => 'header-menu',
                                                            'menu_class'      => 'mobile-menu hbe-inner et-clearfix',
                                                            'menu_id'         => 'mobile-menu-default',
                                                            'container'       => 'div',
                                                            'container_class' => implode(" ", $class),
                                                            'container_id'    => 'mobile-menu-container-default',
                                                            'echo'            => false,
                                                            'link_before'     => '<span class="txt">',
                                                            'link_after'      => '</span><span class="arrow">'.propharm_enovathemes_svg_icon('arrow').'</span>',
                                                            'depth'           => 10,
                                                        );

                                                        $output .= wp_nav_menu($menu_arg);

                                                        echo propharm_enovathemes_output_html($output);
                                                    }

                                                ?>
                                            </div>
                                        </div>
                                        <div class="mobile-container-toggle mobile-container-toggle-default hbe hbe-icon-element hbe-none size-small"><div class="mobile-toggle hbe-toggle active"><?php echo propharm_enovathemes_svg_icon('close'); ?></div></div>
                                    </div>
                                    <div id="mobile-container-overlay-default" class="mobile-container-overlay"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

        <?php } elseif($header_type == "desktop"){ ?>
            <header id="et-desktop-default" class="header et-desktop et-clearfix transparent-false sticky-false shadow-true mobile-false desktop-true">
                <div class="vc_row wpb_row vc_row-fluid vc_row-has-fill vc_row-o-equal-height vc_row-flex vc-row-default">
                    <div class="container et-clearfix">
                        
                        <?php

                            $output = "";

                            $class = array();
                            $class[] = 'hbe';
                            $class[] = 'header-logo';
                            $class[] = 'hbe-left';

                            $output .= '<div id="header-logo-default" class="'.implode(" ", $class).'">';
                                $output .= '<a href="'.esc_url(home_url('/')).'" title="'.get_bloginfo('name').'">';
                                    $output .= '<img class="logo" src="'.PROPHARM_ENOVATHEMES_IMAGES.'/logo.svg" alt="'.get_bloginfo('name').'">';
                                $output .= '</a>';
                            $output .= '</div>';

                            echo propharm_enovathemes_output_html($output);

                        ?>
                        
                        <?php

                            $output  = "";

                            $class   = array();
                            $class[] = 'header-menu-container';
                            $class[] = 'nav-menu-container';
                            $class[] = 'hbe';
                            $class[] = 'hbe-left';
                            $class[] = 'one-page-false';
                            $class[] = 'one-page-offset-0';
                            $class[] = 'hide-default-false';
                            $class[] = 'hide-sticky-false';
                            $class[] = 'menu-hover-none';
                            $class[] = 'submenu-appear-none';
                            $class[] = 'submenu-shadow-true';
                            $class[] = 'tl-submenu-ind-false';
                            $class[] = 'sl-submenu-ind-true';
                            $class[] = 'separator-false';
                            $class[] = 'top-separator-false';

                            $link_after  = '</span><span class="arrow">'.propharm_enovathemes_svg_icon('arrow').'</span>';
                            
                            $menu_arg = array(
                                'theme_location'  => 'header-menu',
                                'menu_class'      => 'header-menu nav-menu hbe-inner et-clearfix',
                                'menu_id'         => 'header-menu-default',
                                'container'       => 'nav',
                                'container_class' => implode(" ", $class),
                                'container_id'    => 'header-menu-container-default',
                                'items_wrap'      => '<ul id="%1$s" class="%2$s" data-color="#4D4D4D" data-color-hover="#4D4D4D">%3$s</ul>',
                                'echo'            => false,
                                'link_before'     => '<span class="txt">',
                                'link_after'      => $link_after,
                                'depth'           => 10,
                                'walker'          => new et_scm_walker
                            );

                            if (has_nav_menu('header-menu')) {
                                $output .= wp_nav_menu($menu_arg);
                                echo propharm_enovathemes_output_html($output);
                            }

                        ?>
                                
                    </div>
                </div>
            </header>
        <?php }
    }

/*  Default title section
/*-------------------*/

    function propharm_enovathemes_default_title_section($etp_title, $etp_subtitle, $etp_breadcrumbs){ ?>

        <section id="title-section-default" class="title-section et-clearfix">
            <div class="container et-clearfix">
                <div class="title-section-title-container tse text-align-left align-left tablet-align-left mobile-align-left">
                    <h1 class="title-section-title" id="title-section-title-default">
                        <?php echo esc_html($etp_title); ?>
                    </h1>
                </div>
            </div>
        </section>

    <?php }

/*  Default footer
/*-------------------*/

    function propharm_enovathemes_default_footer(){ ?>

        <footer id="et-footer-default" class="footer et-footer et-clearfix sticky-false">
            <?php echo '&copy; '.date("Y").' '.esc_html__( 'Copyright', 'propharm' ).' '.esc_html(get_bloginfo('name')); ?>        
        </footer>

    <?php }

/*  Woo Hooks
/*-------------------*/

    function is_woo_pcc(){
        return (is_product() || is_cart() || is_checkout()) ? true : false;
    }

    function propharm_enovathemes_wishlist_compare_quickview($product){
        $wishlist = (isset($GLOBALS['propharm_enovathemes']['wishlist']) && $GLOBALS['propharm_enovathemes']['wishlist'] == 1) ? "true" : "false";
        $compare  = (isset($GLOBALS['propharm_enovathemes']['compare']) && $GLOBALS['propharm_enovathemes']['compare'] == 1) ? "true" : "false";
        $quickview = (isset($GLOBALS['propharm_enovathemes']['quickview']) && $GLOBALS['propharm_enovathemes']['quickview'] == 1) ? "true" : "false";
        
        $output   = '';

        if ($quickview == "true") {
            $output.='<div title="'.esc_html__("Quick view","propharm").'" class="en-quick-view">'.propharm_enovathemes_svg_icon('view').'</div>';
        }

        if($wishlist == "true"){
            $product_wishlist_page = (isset($GLOBALS['propharm_enovathemes']['product-wishlist-page']) && $GLOBALS['propharm_enovathemes']['product-wishlist-page']) ? $GLOBALS['propharm_enovathemes']['product-wishlist-page'] : "#";
            $output.= '<a class="wishlist-toggle" data-product="'.esc_attr($product->get_id()).'" href="'.esc_url($product_wishlist_page).'" title="'.esc_attr__("Add to wishlist","propharm").'">'.propharm_enovathemes_svg_icon('wishlist').'</a><span class="wishlist-title">'.esc_attr__("+ wishlist","propharm").'</span>';
        }
        if($compare == "true"){
            $product_compare_page  = (isset($GLOBALS['propharm_enovathemes']['product-compare-page']) && $GLOBALS['propharm_enovathemes']['product-compare-page']) ? $GLOBALS['propharm_enovathemes']['product-compare-page'] : "#";
            $output.= '<a class="compare-toggle" data-product="'.esc_attr($product->get_id()).'" href="'.esc_url($product_compare_page).'" title="'.esc_attr__("Add to compare","propharm").'">'.propharm_enovathemes_svg_icon('compare').'</a><span class="compare-title">'.esc_attr__("+ compare","propharm").'</span>';
        }
        if (!empty($output)) {
            return $output;
        }
    }

    function propharm_enovathemes_loop_product_thumbnail($layout,$discount = false) { ?>

        <?php

            global $post, $product, $propharm_enovathemes;

            $product_image_full   = (isset($GLOBALS['propharm_enovathemes']['product-image-full']) && $GLOBALS['propharm_enovathemes']['product-image-full'] == 1) ? "true" : "false";
            $quickview            = (isset($GLOBALS['propharm_enovathemes']['quickview']) && $GLOBALS['propharm_enovathemes']['quickview'] == 1) ? "true" : "false";

            $product_id = $product->get_id();
            $thumb_size = ($product_image_full == "true") ? 'full': 'shop_catalog';

            if ($layout == 'full') {
                $thumb_size = 'woocommerce_single';
            }

            $image_class = array();
            $image_class[] = 'post-image';
            $image_class[] = 'post-media';
            $image_class[] = 'overlay-hover';

            $output = '';

            $output.='<div class="'.implode(' ', $image_class).'">';

                if (is_woo_pcc() || $layout == 'grid' || $layout == 'full'){
                    $output.=propharm_enovathemes_wishlist_compare_quickview($product);
                }

                $output.='<a href="'.get_the_permalink().'" >';

                    if ( $product->is_on_sale() ){
                        if ($discount == "true"){

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

                                $regular_price = array_sum($all_regular_prices)/count($all_regular_prices);
                                $sale_price    = array_sum($all_sale_prices)/count($all_sale_prices);

                            } else {
                                $regular_price = $product->get_regular_price();
                                $sale_price    = $product->get_sale_price();
                            }

                            if (!$product->is_type( 'grouped' )) {

                                if (!empty($regular_price)) {
                                    
                                    if ($layout == 'full') {

                                        $currency           = get_woocommerce_currency_symbol();
                                        $currency_pos       = get_option('woocommerce_currency_pos');
                                        $price_num_decimals = get_option('woocommerce_price_num_decimals');

                                        $regular_price = round(($regular_price - $sale_price),$price_num_decimals);

                                        switch ($currency_pos) {
                                            case 'left':
                                                $off = $currency.$regular_price;
                                                break;
                                            case 'left_space':
                                                $off = $currency.' '.$regular_price;
                                                break;
                                            case 'right':
                                                $off = $regular_price.$currency;
                                                break;
                                            case 'right_space':
                                                $off = $regular_price.' '.$currency;
                                                break;
                                        }

                                        $output.='<span class="onsale discount"><span class="onsale-inner">'.esc_html__('Save','propharm').'<br>'.$off . '</span></span>';
                                    } else {

                                        $off = round((($regular_price-$sale_price)/$regular_price)*100,0);

                                        $output.='<span class="onsale discount"><span class="onsale-inner">-'.$off . '%</span></span>';
                                    }
                                }

                            }

                        } else {
                            $output.=apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale', 'propharm' ) . '</span>', $post, $product );
                        }
                    }

                    $propharm_enovathemes_label1 = get_post_meta($product->get_id(),'propharm_enovathemes_label1',true);
                    $propharm_enovathemes_label2 = get_post_meta($product->get_id(),'propharm_enovathemes_label2',true);

                    if (isset($propharm_enovathemes_label1) && !empty($propharm_enovathemes_label1)) {
                        $propharm_enovathemes_label1_color = get_post_meta($product->get_id(),'propharm_enovathemes_label1_color',true);

                        $style = '';

                        if (isset($propharm_enovathemes_label1_color) && !empty($propharm_enovathemes_label1_color)) {
                            $brightness = propharm_enovathemes_brightness($propharm_enovathemes_label1_color);
                            $style = 'style="background:'.$propharm_enovathemes_label1_color.';';
                            if ($brightness == 'light') {
                                $style .= 'color:#184363;';
                            }
                            $style .='"';
                        }

                         $output.='<span class="label" '.$style.'>' . esc_html($propharm_enovathemes_label1) . '</span>';

                    }

                    if (isset($propharm_enovathemes_label2) && !empty($propharm_enovathemes_label2)) {
                        $propharm_enovathemes_label2_color = get_post_meta($product->get_id(),'propharm_enovathemes_label2_color',true);

                        $style = '';

                        if (isset($propharm_enovathemes_label2_color) && !empty($propharm_enovathemes_label2_color)) {
                            $style = 'style="background:'.$propharm_enovathemes_label2_color.';';
                            $brightness = propharm_enovathemes_brightness($propharm_enovathemes_label2_color);
                            if ($brightness == 'light') {
                                $style .= 'color:#184363;';
                            }
                            $style .='"';
                        }

                        $output.='<span class="label" '.$style.'>' . esc_html($propharm_enovathemes_label2) . '</span>';

                    }

                    $output.='<div class="image-container">';
                        $output.=propharm_enovathemes_build_post_media('catalog',$thumb_size,false,'product');
                    $output.='</div>';

                $output.='</a>';
            $output.='</div>';

            if (!empty($output)) {
                return $output;
            }

        ?>

    <?php }

    function woocommerce_template_loop_add_to_cart_theme( $args = array() ) {
        global $product;

        if ( $product ) {
            $defaults = array(
                'quantity'   => 1,
                'class'      => implode(
                    ' ',
                    array_filter(
                        array(
                            'button',
                            'product_type_' . $product->get_type(),
                            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                            $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                        )
                    )
                ),
                'attributes' => array(
                    'data-product_id'  => $product->get_id(),
                    'data-product_sku' => $product->get_sku(),
                    'aria-label'       => $product->add_to_cart_description(),
                    'rel'              => 'nofollow',
                ),
            );

            $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

            if ( isset( $args['attributes']['aria-label'] ) ) {
                $args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
            }

            return apply_filters(
                'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                sprintf(
                    '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                    esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                    isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                    esc_html( $product->add_to_cart_text() )
                ),
                $product,
                $args
            );
        }
    }

    function propharm_enovathemes_loop_product_rating(){ global $product;
        $rating_count = $product->get_rating_count();

        $output = '';

        if(get_option( 'woocommerce_enable_reviews' ) === "yes"){

            if ($rating_count){
                if ( wc_review_ratings_enabled() ) {
                    $output.='<div class="star-rating-wrap">';
                        $output.= wc_get_rating_html( $product->get_average_rating() );
                        $output.='<span>'.esc_html($rating_count).'</span>';
                    $output.='</div>';
                }
            } else {
                $output.='<div class="star-rating-wrap">';
                    $output.='<div class="star-rating empty"></div>';
                $output.='</div>';
            }

        }

        if (!empty($output)) {
            return $output;
        }
    }

    function propharm_enovathemes_loop_product_title($layout) { ?>

        <?php

            global $product, $propharm_enovathemes;
            $product_title_excerpt = (isset($GLOBALS['propharm_enovathemes']['product-title-excerpt']) && $GLOBALS['propharm_enovathemes']['product-title-excerpt']) ? $GLOBALS['propharm_enovathemes']['product-title-excerpt'] : "22";
            $output ='';

        $output.='<div class="post-body et-clearfix">';
            $output.='<div class="post-body-inner">';

                if (($layout == 'grid' || $layout == 'full' || is_woo_pcc()) && $layout != 'simple-grid'){
                    $output.= propharm_enovathemes_wishlist_compare_quickview($product);
                }

                if ($layout == 'list' || $layout == 'full'){
                    $output.=propharm_enovathemes_loop_product_rating();
                }

                $categories = wp_get_post_terms($product->get_id(),'product_cat');
 
                if ( ! empty( $categories ) && $layout != 'list' && $layout != 'full') {
                    $output.='<div class="post-category et-clearfix">';
                        foreach ($categories as $category) {
                            $output.='<a href="'.get_term_link($category->term_id,'product_cat').'" title="'.$category->name.'">'.$category->name.'</a>';
                        }
                    $output.='</div>';
                }

                $output.='<h4 class="post-title et-clearfix">';
                    $output.='<a href="'.get_the_permalink().'" title="'.esc_attr__("Read more avbout", 'propharm').' '.the_title_attribute( 'echo=0' ).'">'.mb_strimwidth(the_title_attribute( 'echo=0' ),0,$product_title_excerpt,'').'</a>';
                $output.='</h4>';

                if (($layout == 'grid' || is_woo_pcc()) && $layout != 'simple-grid'){
                    $output.=propharm_enovathemes_loop_product_rating();
                }

                if ( $price_html = $product->get_price_html() ){
                    $output.='<span class="price">'.$price_html.'</span>';
                }

                if ($layout == 'full') {
                    $output .= '<div class="product-short-description">';
                       $output .= $product->get_short_description();
                    $output .= '</div>';
                }

                if (($layout == 'grid' || $layout == 'full' || is_woo_pcc()) && $layout != 'simple-grid'){
                    $output.= woocommerce_template_loop_add_to_cart_theme();
                }

                if (!empty($output)) {
                    return $output;
                }

            ?>

    <?php }

    function propharm_enovathemes_loop_product_inner_close($layout) {global $product;

        $output = '';

            if (!is_woo_pcc() && $layout == 'comp'){

                $output.=propharm_enovathemes_loop_product_rating();

                $output .= '<div class="product-short-description">';
                   $output .= $product->get_short_description();
                $output .= '</div>';
            }

            $output .= '</div>';
        $output .= '</div>';

        if(!is_woo_pcc() && $layout == 'comp'){
            $output .= '<div class="comp-body">';
                $output .= '<div class="comp-body-inner">';

                    $stock_quantity = $product->get_stock_quantity();

                    if (!empty($stock_quantity) && $stock_quantity < 10) {
                        $output .= '<div class="comp-quantity">'.esc_html__("Quantity","propharm").' <span>'.esc_html__("Only","propharm").' '.$stock_quantity.' '.esc_html__("left in stock!","propharm").'</span></div>';
                    }
                 
                    if($product->is_type( 'simple' )){
                        $output .= '<div class="comp-counter"><span class="minus">-</span><input type="text" value="1"><span class="plus">+</span></div>';
                    }

                    $output.= woocommerce_template_loop_add_to_cart_theme();
                    $output .= propharm_enovathemes_wishlist_compare_quickview($product);
               $output .= '</div>';
            $output .= '</div>';
        }

        if (!empty($output)) {
            return $output;
        }

    }

    function propharm_enovathemes_fbt_output(){

        global $propharm_enovathemes, $post;

        $product_title_excerpt       = (isset($GLOBALS['propharm_enovathemes']['product-title-excerpt']) && $GLOBALS['propharm_enovathemes']['product-title-excerpt']) ? $GLOBALS['propharm_enovathemes']['product-title-excerpt'] : "22";
        $product_image_full          = (isset($GLOBALS['propharm_enovathemes']['product-image-full']) && $GLOBALS['propharm_enovathemes']['product-image-full'] == 1) ? "true" : "false";

        $fbt_ids = get_post_meta( get_the_ID(), 'fbt_ids', true );

        if (!empty($fbt_ids)) {

            $currency           = get_woocommerce_currency_symbol();
            $currency_pos       = get_option('woocommerce_currency_pos');
            $price_num_decimals = get_option('woocommerce_price_num_decimals');

            $column = count($fbt_ids);

            $style  = '';

            $class   = array();
            $class[] = 'loop-posts';
            $class[] = 'loop-products';
            $class[] = 'fbt';

            if ($column == 4 || $column == 5) {
                $class[] = 'mult';
            }

            if ($column > 4) {
                $class[] = 'grid';
            } else {
                $class[] = 'default';
            }

            if ($column >= 6) {
                $class[] = 'inf';
                $column = intval($column/2);
            }

            if ($column > 0) {
                $style = 'grid-template-columns: repeat('.$column.', '.$column.'fr);';
            }

            $output = '<div data-column="'.esc_attr($column).'" class="fbt-products post-layout product-layout medium grid gap-false layout-sidebar-none">';

                $output .= '<h4>'.esc_html__('Frequently bought together','propharm').'</h4>';

                $output .= '<div class="fbt-products-inner">';

                    $checkbox_output = '<ul class="fbt-list">';
                    $all_prices      = array();

                    $output .= '<ul data-column="'.esc_attr($column).'" class="'.implode(' ', $class).'" style="'. $style.'">';
                        foreach ( $fbt_ids as $fbt_id ) {
                            $product = wc_get_product( $fbt_id );
                            if ( is_object( $product ) ) {

                                if($product->is_type( 'variable' ) )
                                {
                                    $price      = $product->get_variation_regular_price();
                                    $price_sale = $product->get_variation_price();
                                } else {
                                    $price       = $product->get_regular_price();
                                    $price_sale  = $product->get_sale_price();
                                }

                                $final_price = ($price_sale) ? $price_sale : $price;

                                if (!empty($final_price)) {

                                    $final_price = round($final_price,$price_num_decimals);

                                    array_push($all_prices, $final_price);
                                }

                                $checkbox_output .= '<li data-product="'.esc_attr($product->get_id()).'" data-price="'.esc_attr($final_price).'" class="chosen fbt-item">'.$product->get_name().'<div class="product-price">'.$product->get_price_html().'</div></li>';


                                $output .= '<li class="'.join( ' ', get_post_class('post')).'" id="product-'.esc_attr($product->get_id()).'">';

                                    $output .= '<div class="post-inner et-item-inner et-clearfix">';

                                        if ( $product->is_on_sale() ){
                                            $output.=apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale', 'propharm' ) . '</span>', $post, $product );
                                        }

                                        $thumb_size = ($product_image_full == "true") ? 'full': 'shop_catalog';

                                        $image_class = array();
                                        $image_class[] = 'post-image';
                                        $image_class[] = 'post-media';
                                        $image_class[] = 'overlay-hover';

                                        $output .= '<div class="'.implode(' ', $image_class).'">';

                                            $output .= '<a href="'.get_permalink( $product->get_id() ).'" >';
                                                $output .='<div class="image-container">';
                                                    $output .= propharm_enovathemes_build_post_media('catalog',$thumb_size,$product->get_image_id(),'product');
                                                $output .='</div>';
                                            $output .= '</a>';

                                        $output .= '</div>';

                                        $output .= '<div class="post-body et-clearfix">';
                                            $output .= '<div class="post-body-inner">';
                                                $output .= '<h4 class="post-title et-clearfix">';
                                                    $output .= '<a href="'.get_permalink( $product->get_id() ).'" title="'.esc_attr__("Read more avbout", "propharm").' '.$product->get_name().'">'.mb_strimwidth($product->get_name(),0,$product_title_excerpt,'').'</a>';
                                                $output .= '</h4>';

                                                $rating_count = $product->get_rating_count();

                                                if(get_option( 'woocommerce_enable_reviews' ) === "yes"){

                                                    if ($rating_count){
                                                        if ( wc_review_ratings_enabled() ) {
                                                            $output.='<div class="star-rating-wrap">';
                                                                $output.= wc_get_rating_html( $product->get_average_rating() );
                                                                $output.='<span>'.esc_html($rating_count).'</span>';
                                                            $output.='</div>';
                                                        }
                                                    } else {
                                                        $output.='<div class="star-rating-wrap">';
                                                            $output.='<div class="star-rating empty"></div>';
                                                        $output.='</div>';
                                                    }

                                                }

                                                if ( $price_html = $product->get_price_html() ){
                                                    $output .= '<div class="product-price">';
                                                        $output .= '<span class="price">'.$price_html.'</span>';
                                                    $output .= '</div>';
                                                }

                                                $args = array();

                                                $defaults = array(
                                                    'quantity'   => 1,
                                                    'class'      => implode(
                                                        ' ',
                                                        array_filter(
                                                            array(
                                                                'button',
                                                                'product_type_' . $product->get_type(),
                                                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                                $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                                                            )
                                                        )
                                                    ),
                                                    'attributes' => array(
                                                        'data-product_id'  => $product->get_id(),
                                                        'data-product_sku' => $product->get_sku(),
                                                        'aria-label'       => $product->add_to_cart_description(),
                                                        'rel'              => 'nofollow',
                                                    ),
                                                );

                                                $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

                                                if ( isset( $args['attributes']['aria-label'] ) ) {
                                                    $args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
                                                }

                                                $output .= apply_filters(
                                                    'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                                                    sprintf(
                                                        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                                        esc_url( $product->add_to_cart_url() ),
                                                        esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                                        esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                                                        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                                        esc_html( $product->add_to_cart_text() )
                                                    ),
                                                    $product,
                                                    $args
                                                );

                                            $output .= '</div>';
                                        $output .= '</div>';

                                    $output .= '</div>';

                                $output .= '</li>';
                            }
                        }
                    $output .= '</ul>';

                    $checkbox_output .= '</ul>';

                    $total_price = '<span>'.array_sum($all_prices).'</span>';

                    switch ($currency_pos) {
                        case 'left':
                            $total_price = $currency.$total_price;
                            break;
                        case 'left_space':
                            $total_price = $currency.' '.$total_price;
                            break;
                        case 'right':
                            $total_price = $total_price.$currency;
                            break;
                        case 'right_space':
                            $total_price = $total_price.' '.$currency;
                            break;
                    }

                    $output .= '<div class="fbt-info">';
                        $output .= $checkbox_output;
                        $output .= '<div class="selected">';
                            $output .= '<div>'.esc_html__('Buy selected for','propharm').'</div>';
                            $output .= '<div class="total-price">'.$total_price.'</div>'; 
                            $output .= '<a class="add_to_cart_all et-button medium button" href="#">'.esc_html__('Add all to cart','propharm').'</a>';
                        $output .= '</div>';
                    $output .= '</div>';

                $output .= '</div>';

            $output .= '</div>';
            echo propharm_enovathemes_output_html($output);
        }
    }

?>