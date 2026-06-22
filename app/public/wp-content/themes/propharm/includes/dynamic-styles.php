<?php
function propharm_enovathemes_include_dynamic_styles() {

	wp_enqueue_style('dynamic-styles', get_template_directory_uri() . '/css/dynamic-styles.css');

	propharm_enovathemes_global_variables();

    $dynamic_css = "";

	/* Header/Footer/Page/Post/Banner
	---------------*/

		$title_section_id        = (isset($GLOBALS['propharm_enovathemes']['title-section-id']) && !empty($GLOBALS['propharm_enovathemes']['title-section-id'])) ? $GLOBALS['propharm_enovathemes']['title-section-id'] : "default";
	    $blog_title_id           = (isset($GLOBALS['propharm_enovathemes']['blog-title']) && !empty($GLOBALS['propharm_enovathemes']['blog-title'])) ? $GLOBALS['propharm_enovathemes']['blog-title'] : "none";
	    $blog_title_id_single    = (isset($GLOBALS['propharm_enovathemes']['blog-title-single']) && !empty($GLOBALS['propharm_enovathemes']['blog-title-single'])) ? $GLOBALS['propharm_enovathemes']['blog-title-single'] : "none";
        $product_title_id        = (isset($GLOBALS['propharm_enovathemes']['product-title']) && !empty($GLOBALS['propharm_enovathemes']['product-title'])) ? $GLOBALS['propharm_enovathemes']['product-title'] : "none";
		$product_title_id_single = (isset($GLOBALS['propharm_enovathemes']['product-title-single']) && !empty($GLOBALS['propharm_enovathemes']['product-title-single'])) ? $GLOBALS['propharm_enovathemes']['product-title-single'] : "none";

        $header_desktop_id    = (isset($GLOBALS['propharm_enovathemes']['header-desktop-id']) && !empty($GLOBALS['propharm_enovathemes']['header-desktop-id'])) ? $GLOBALS['propharm_enovathemes']['header-desktop-id'] : "default";
        $header_mobile_id     = (isset($GLOBALS['propharm_enovathemes']['header-mobile-id']) && !empty($GLOBALS['propharm_enovathemes']['header-mobile-id'])) ? $GLOBALS['propharm_enovathemes']['header-mobile-id'] : "default";
        $footer_id            = (isset($GLOBALS['propharm_enovathemes']['footer-id']) && !empty($GLOBALS['propharm_enovathemes']['footer-id'])) ? $GLOBALS['propharm_enovathemes']['footer-id'] : "default";
    	$error_id             = (isset($GLOBALS['propharm_enovathemes']['error-id']) && $GLOBALS['propharm_enovathemes']['error-id']) ? $GLOBALS['propharm_enovathemes']['error-id'] : "";

        /* WPML
        ---------------*/

	        if (class_exists('SitePress') || function_exists('pll_the_languages')){

	        	$current_lang = (function_exists('pll_the_languages')) ? pll_current_language() : ICL_LANGUAGE_CODE;

	            // WPML
	            $header_desktop_id_wpml = (isset($GLOBALS['propharm_enovathemes']['header-desktop-id-wpml']) && !empty($GLOBALS['propharm_enovathemes']['header-desktop-id-wpml'])) ? $GLOBALS['propharm_enovathemes']['header-desktop-id-wpml'] : $header_desktop_id;
	            $header_mobile_id_wpml  = (isset($GLOBALS['propharm_enovathemes']['header-mobile-id-wpml']) && !empty($GLOBALS['propharm_enovathemes']['header-mobile-id-wpml'])) ? $GLOBALS['propharm_enovathemes']['header-mobile-id-wpml'] : $header_mobile_id;
	            $footer_id_wpml         = (isset($GLOBALS['propharm_enovathemes']['footer-id-wpml']) && !empty($GLOBALS['propharm_enovathemes']['footer-id-wpml'])) ? $GLOBALS['propharm_enovathemes']['footer-id-wpml'] : $footer_id;
				$title_section_id_wpml  = (isset($GLOBALS['propharm_enovathemes']['title-section-id-wpml']) && !empty($GLOBALS['propharm_enovathemes']['title-section-id-wpml'])) ? $GLOBALS['propharm_enovathemes']['title-section-id-wpml'] : $title_section_id;

	            if ($header_desktop_id_wpml != $header_desktop_id && !empty($header_desktop_id_wpml)) {
	                $header_desktop_id_wpml = explode('|', $header_desktop_id_wpml);

	                $lang_header_obj = array();

	                foreach ($header_desktop_id_wpml as $wpml_lang_header) {
	                    $lang_header_set = explode(":",$wpml_lang_header);
	                    $lang_header_obj[$lang_header_set[0]] = $lang_header_set[1];
	                }

	                if (array_key_exists($current_lang,$lang_header_obj)) {
	                    $header_desktop_id = $lang_header_obj[$current_lang];
	                }

	            }

	            if ($header_mobile_id_wpml != $header_mobile_id && !empty($header_mobile_id_wpml)) {
	                $header_mobile_id_wpml = explode('|', $header_mobile_id_wpml);

	                $lang_header_obj = array();

	                foreach ($header_mobile_id_wpml as $wpml_lang_header) {
	                    $lang_header_set = explode(":",$wpml_lang_header);
	                    $lang_header_obj[$lang_header_set[0]] = $lang_header_set[1];
	                }

	                if (array_key_exists($current_lang,$lang_header_obj)) {
	                    $header_mobile_id = $lang_header_obj[$current_lang];
	                }

	            }

                if ($footer_id_wpml != $footer_id && !empty($footer_id_wpml)) {
                    $footer_id_wpml = explode('|', $footer_id_wpml);

                    $lang_footer_obj = array();

                    foreach ($footer_id_wpml as $wpml_lang_footer) {
                        $lang_footer_set = explode(":",$wpml_lang_footer);
                        $lang_footer_obj[$lang_footer_set[0]] = $lang_footer_set[1];
                    }

                    if (array_key_exists($current_lang,$lang_footer_obj)) {
                        $footer_id = $lang_footer_obj[$current_lang];
                    }

                }

                if ($title_section_id_wpml != $title_section_id && !empty($title_section_id_wpml)) {
                    $title_section_id_wpml = explode('|', $title_section_id_wpml);

                    $lang_titlesection_obj = array();

                    foreach ($title_section_id_wpml as $wpml_lang_titlesection) {
                        $lang_titlesection_set = explode(":",$wpml_lang_titlesection);
                        $lang_titlesection_obj[$lang_titlesection_set[0]] = $lang_titlesection_set[1];
                    }

                    if (array_key_exists($current_lang,$lang_titlesection_obj)) {
                        $title_section_id = $lang_titlesection_obj[$current_lang];
                    }

                }
	        }

		/* Page
        ---------------*/

	        if (is_page()) {


	            $page_header_desktop_id = get_post_meta( get_the_ID(), 'enovathemes_addons_desktop_header', true );
                $page_header_mobile_id  = get_post_meta( get_the_ID(), 'enovathemes_addons_mobile_header', true );
                $page_footer_id 		= get_post_meta( get_the_ID(), 'enovathemes_addons_footer', true );
                $page_title_section_id  = get_post_meta( get_the_ID(), 'enovathemes_addons_title_section', true );

                $page_back_color = get_post_meta( get_the_ID(), 'enovathemes_addons_page_back_color', true );
                $page_back_image = get_post_meta( get_the_ID(), 'enovathemes_addons_page_back_image', true );

                if (!empty($page_back_color)) {
                	$dynamic_css .= '.page-id'.'-'.get_the_ID().' .page-content-wrap {background-color:'.$page_back_color.';}';
                }

                if (!empty($page_back_image)) {
                	$dynamic_css .= '.page-id'.'-'.get_the_ID().' .page-content-wrap {background-image:url('.$page_back_image.');}';
                }

                if (empty($page_header_desktop_id) || !isset($page_header_desktop_id)) {
                	$page_header_desktop_id = "inherit";
                }

                if (empty($page_header_mobile_id) || !isset($page_header_mobile_id)) {
                	$page_header_mobile_id = "inherit";
                }

                if (empty($page_footer_id) || !isset($page_footer_id)) {
                	$page_footer_id = "inherit";
                }
                
                if (empty($page_title_section_id) || !isset($page_title_section_id)) {
                	$page_title_section_id = "inherit";
                }

                if ($page_header_desktop_id != "inherit") {
                    $header_desktop_id = $page_header_desktop_id;
                }

                if ($page_header_mobile_id != "inherit") {
                    $header_mobile_id = $page_header_mobile_id;
                }

                if ($page_footer_id != "inherit") {
                    $footer_id = $page_footer_id;
                }

                if ($page_title_section_id != "inherit") {
                    $title_section_id= $page_title_section_id;
                }

	            $element_css = get_post_meta(get_the_ID(), 'element_css', true);

	            if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}
	        }

	    /* Product category
        ---------------*/

        	elseif (is_tax('product_cat') && !is_search()) {
		        $product_title_id  = get_term_meta( get_queried_object()->term_id, 'enovathemes_addons_title_section', true );
                if ($product_title_id != "inherit" && !empty($product_title_id)) {
                    $title_section_id = $product_title_id;
                }
		    }

        /* Blog
        ---------------*/

		    elseif (is_home() || is_category() || is_tag() || is_day() || is_month() || is_year() || is_author() || is_search()) {
		        if ($blog_title_id != "inherit") {
		        	$title_section_id = $blog_title_id;
		        }
		    }

		    elseif (is_singular('post')) {
		    	if ($blog_title_id_single != "inherit") {
		        	$title_section_id = $blog_title_id_single;
		        }
		    } elseif(is_404() && !empty($error_id)){
		    	$element_css               = get_post_meta($error_id, 'element_css', true);
				$wpb_shortcodes_custom_css = get_post_meta($error_id, '_wpb_shortcodes_custom_css', true);

				if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}

				if (!empty($wpb_shortcodes_custom_css)) {
					$dynamic_css .= $wpb_shortcodes_custom_css;
				}
		    } else {

				/*  CPT
		        ---------------*/

		        	if (!is_search()  && !is_404()) {
		            
			            $post_info = get_post(get_the_ID());

			            if (!is_wp_error($post_info) && is_object($post_info)) {

			                $post_type   = $post_info->post_type;

			                if ($post_type != 'post' && $post_type != 'page') {
			                    switch ($post_type) {
			                        case 'product':
			                            if ($product_title_id != "inherit") {
								        	$title_section_id = $product_title_id;
								        }
			                            break;
			                        default :
			                            if ($blog_title_id != "inherit") {
								        	$title_section_id = $blog_title_id;
								        }
			                            break;
			                    }
			                }
			                
			            }

			        }

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
        		$element_css = get_post_meta(get_the_ID(), 'element_css', true);

	            if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}
        	}

        /*  Singular product
        ---------------*/

        	elseif (is_singular('product')) {
        		$element_css = get_post_meta(get_the_ID(), 'element_css', true);

	            if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}

				if ($product_title_id_single != "inherit") {
		        	$title_section_id = $product_title_id_single;
		        }
        	}

        if ($header_desktop_id == $header_mobile_id && $header_desktop_id != "default") {
        	$header_mobile_id = "none";
        }

        /*  Mobile header
        ---------------*/

			if ($header_mobile_id != "none" && $header_mobile_id != "default") {

				$element_css               = get_post_meta($header_mobile_id, 'element_css', true);
				$wpb_shortcodes_custom_css = get_post_meta($header_mobile_id, '_wpb_shortcodes_custom_css', true);

				if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}

				if (!empty($wpb_shortcodes_custom_css)) {
					$dynamic_css .= $wpb_shortcodes_custom_css;
				}
			}

		/*  Desktop header
        ---------------*/

			if ($header_desktop_id != "none" && $header_desktop_id != "default") {

				$element_css               = get_post_meta($header_desktop_id, 'element_css', true);
				$wpb_shortcodes_custom_css = get_post_meta($header_desktop_id, '_wpb_shortcodes_custom_css', true);

				if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}

				if (!empty($wpb_shortcodes_custom_css)) {
					$dynamic_css .= $wpb_shortcodes_custom_css;
				}
			}

		/*  Title section
        ---------------*/

			if ($title_section_id != "none" && $title_section_id != "default") {

				$element_css               = get_post_meta($title_section_id, 'element_css', true);
				$wpb_shortcodes_custom_css = get_post_meta($title_section_id, '_wpb_shortcodes_custom_css', true);

				if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}

				if (!empty($wpb_shortcodes_custom_css)) {
					$dynamic_css .= $wpb_shortcodes_custom_css;
				}
			}

		/*  Footer
        ---------------*/

			if ($footer_id != "none" && $footer_id != "default") {

				$element_css               = get_post_meta($footer_id, 'element_css', true);
				$wpb_shortcodes_custom_css = get_post_meta($footer_id, '_wpb_shortcodes_custom_css', true);

				if (!empty($element_css)) {
					$dynamic_css .= $element_css;
				}

				if (!empty($wpb_shortcodes_custom_css)) {
					$dynamic_css .= $wpb_shortcodes_custom_css;
				}

				/*  Footer forms
				---------------*/

					$footer_custom_form_styling      = get_post_meta($footer_id, 'enovathemes_addons_custom_form_styling', true);

					if ($footer_custom_form_styling == "on") {

						$footer_field_color              = get_post_meta($footer_id, 'enovathemes_addons_field_color', true);
						$footer_field_color_focus        = get_post_meta($footer_id, 'enovathemes_addons_field_color_focus', true);
						$footer_field_back_color         = get_post_meta($footer_id, 'enovathemes_addons_field_back_color', true);
						$footer_field_back_color_focus   = get_post_meta($footer_id, 'enovathemes_addons_field_back_color_focus', true);
						$footer_field_border_color       = get_post_meta($footer_id, 'enovathemes_addons_field_border_color', true);
						$footer_field_border_color_focus = get_post_meta($footer_id, 'enovathemes_addons_field_border_color_focus', true);
						$footer_button_color             = get_post_meta($footer_id, 'enovathemes_addons_button_color', true);
						$footer_button_color_hover       = get_post_meta($footer_id, 'enovathemes_addons_button_color_hover', true);
						$footer_button_back_color        = get_post_meta($footer_id, 'enovathemes_addons_button_back_color', true);
						$footer_button_back_color_hover  = get_post_meta($footer_id, 'enovathemes_addons_button_back_color_hover', true);

						$dynamic_css .='#et-footer'.'-'.$footer_id.' textarea, 
						#et-footer'.'-'.$footer_id.' select,
						#et-footer'.'-'.$footer_id.' input[type="date"], 
						#et-footer'.'-'.$footer_id.' input[type="datetime"],
						#et-footer'.'-'.$footer_id.' input[type="datetime-local"], 
						#et-footer'.'-'.$footer_id.' input[type="email"],
						#et-footer'.'-'.$footer_id.' input[type="month"], 
						#et-footer'.'-'.$footer_id.' input[type="number"],
						#et-footer'.'-'.$footer_id.' input[type="password"], 
						#et-footer'.'-'.$footer_id.' input[type="search"],
						#et-footer'.'-'.$footer_id.' input[type="tel"], 
						#et-footer'.'-'.$footer_id.' input[type="text"],
						#et-footer'.'-'.$footer_id.' input[type="time"], 
						#et-footer'.'-'.$footer_id.' input[type="url"],
						#et-footer'.'-'.$footer_id.' input[type="week"], 
						#et-footer'.'-'.$footer_id.' input[type="file"] {
							color:'.$footer_field_color.';
							background-color:'.$footer_field_back_color.';
							border-color:'.$footer_field_border_color.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' textarea:focus, 
						#et-footer'.'-'.$footer_id.' select:focus,
						#et-footer'.'-'.$footer_id.' input[type="date"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="datetime"]:focus,
						#et-footer'.'-'.$footer_id.' input[type="datetime-local"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="email"]:focus,
						#et-footer'.'-'.$footer_id.' input[type="month"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="number"]:focus,
						#et-footer'.'-'.$footer_id.' input[type="password"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="search"]:focus,
						#et-footer'.'-'.$footer_id.' input[type="tel"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="text"]:focus,
						#et-footer'.'-'.$footer_id.' input[type="time"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="url"]:focus,
						#et-footer'.'-'.$footer_id.' input[type="week"]:focus, 
						#et-footer'.'-'.$footer_id.' input[type="file"]:focus {
							color:'.$footer_field_color_focus.';
							background-color:'.$footer_field_back_color_focus.';
							border-color:'.$footer_field_border_color_focus.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' input[type="button"],
						#et-footer'.'-'.$footer_id.' input[type="reset"],
						#et-footer'.'-'.$footer_id.' input[type="submit"],
						#et-footer'.'-'.$footer_id.' .woocommerce-mini-cart__buttons > a,
						#et-footer'.'-'.$footer_id.' button {
							color:'.$footer_button_color.';
							background-color:'.$footer_button_back_color.';';
						$dynamic_css .='}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .woocommerce-product-search button[type="submit"],
						#et-footer'.'-'.$footer_id.' form #searchsubmit + .search-icon {
							background-color:'.$footer_button_back_color.' !important;
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' input[type="button"]:hover,
						#et-footer'.'-'.$footer_id.' input[type="reset"]:hover,
						#et-footer'.'-'.$footer_id.' input[type="submit"]:hover,
						#et-footer'.'-'.$footer_id.' button:hover,
						#et-footer'.'-'.$footer_id.' .woocommerce-mini-cart__buttons > a:hover,
						#et-footer'.'-'.$footer_id.' button:hover {
							color:'.$footer_button_color_hover.' !important;
							background-color:'.$footer_button_back_color_hover.';';
						$dynamic_css .='}';

					}

				/*  Footer widgets
				---------------*/

					$footer_custom_widget_styling = get_post_meta($footer_id, 'enovathemes_addons_custom_widget_styling', true);

					if ($footer_custom_widget_styling == "on") {

						$footer_widget_title_color      = get_post_meta($footer_id, 'enovathemes_addons_widget_title_color', true);
						$footer_widget_color            = get_post_meta($footer_id, 'enovathemes_addons_widget_color', true);
						$footer_widget_link_color       = get_post_meta($footer_id, 'enovathemes_addons_widget_link_color', true);
						$footer_widget_link_color_hover = get_post_meta($footer_id, 'enovathemes_addons_widget_link_color_hover', true);

						$footer_widget_color_brightness = propharm_enovathemes_brightness($footer_widget_color);

						if ($footer_widget_color_brightness == 'light') {
							$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget_calendar a.prev,
							#et-footer'.'-'.$footer_id.' .widget_calendar a.next,
							#et-footer'.'-'.$footer_id.' .wp-block-calendar a.prev,
							#et-footer'.'-'.$footer_id.' .wp-block-calendar a.next,
							#et-footer'.'-'.$footer_id.' .widget_nav_menu ul li a .toggle,
							#et-footer'.'-'.$footer_id.' .widget_product_categories ul li a .toggle {
							    background: url('.PROPHARM_SVG.'arrow-ed.svg) no-repeat 50% 50%;
							    background-size: 8px;
							}';
						}

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget_title,
						#et-footer'.'-'.$footer_id.' th{
							color:'.$footer_widget_title_color.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' form #searchsubmit + .search-icon {
							fill:'.$footer_widget_title_color.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget,
						#et-footer'.'-'.$footer_id.' .widget_price_filter .price_label,
						#et-footer'.'-'.$footer_id.' .widget_calendar td#today,
						#et-footer'.'-'.$footer_id.' .widget_tag_cloud .tagcloud a,
						#et-footer'.'-'.$footer_id.' .widget_product_tag_cloud .tagcloud a,
						#et-footer'.'-'.$footer_id.' .widget_mailchimp,
						#et-footer'.'-'.$footer_id.' .mailchimp-description {
							color:'.$footer_widget_color.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget_et_recent_entries .post-title a, 
						#et-footer'.'-'.$footer_id.' .widget_products .product_list_widget > li .product-title a, 
						#et-footer'.'-'.$footer_id.' .widget_recently_viewed_products .product_list_widget > li .product-title a, 
						#et-footer'.'-'.$footer_id.' .widget_recent_reviews .product_list_widget > li .product-title a, 
						#et-footer'.'-'.$footer_id.' .widget_top_rated_products .product_list_widget > li .product-title a,
						#et-footer'.'-'.$footer_id.' .widget_product_search form button:before,
						#et-footer'.'-'.$footer_id.' .widget_shopping_cart .cart_list li .remove,
						#et-footer'.'-'.$footer_id.' .widget_et_recent_entries .post-title a, 
						#et-footer'.'-'.$footer_id.' .widget_products .product_list_widget > li .product-title a, 
						#et-footer'.'-'.$footer_id.' .widget_recently_viewed_products .product_list_widget > li .product-title a, 
						#et-footer'.'-'.$footer_id.' .widget_recent_reviews .product_list_widget > li .product-title a, 
						#et-footer'.'-'.$footer_id.' .widget_top_rated_products .product_list_widget > li .product-title a,
						#et-footer'.'-'.$footer_id.' .widget_layered_nav ul li a,
						#et-footer'.'-'.$footer_id.' .widget_nav_menu ul li a,
						#et-footer'.'-'.$footer_id.' .widget_product_categories ul li a,
						#et-footer'.'-'.$footer_id.' .widget_categories ul li a,
						#et-footer'.'-'.$footer_id.' .post-single-navigation a,
						#et-footer'.'-'.$footer_id.' .widget_pages ul li a,
						#et-footer'.'-'.$footer_id.' .widget_archive ul li a,
						#et-footer'.'-'.$footer_id.' .widget_meta ul li a,
						#et-footer'.'-'.$footer_id.' .widget_recent_entries ul li a,
						#et-footer'.'-'.$footer_id.' .widget_rss ul li a,
						#et-footer'.'-'.$footer_id.' .widget_icl_lang_sel_widget li a,
						#et-footer'.'-'.$footer_id.' .recentcomments a,
						#et-footer'.'-'.$footer_id.' .widget_shopping_cart .cart-product-title a {
							color:'.$footer_widget_link_color.' !important;
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget_et_recent_entries .post-title:hover a, 
						#et-footer'.'-'.$footer_id.' .widget_products .product_list_widget > li .product-title:hover a, 
						#et-footer'.'-'.$footer_id.' .widget_recently_viewed_products .product_list_widget > li .product-title:hover a, 
						#et-footer'.'-'.$footer_id.' .widget_recent_reviews .product_list_widget > li .product-title:hover a, 
						#et-footer'.'-'.$footer_id.' .widget_top_rated_products .product_list_widget > li .product-title:hover a,
						#et-footer'.'-'.$footer_id.' .widget_layered_nav ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_nav_menu ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_product_categories ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_categories ul li a:hover,
						#et-footer'.'-'.$footer_id.' .post-single-navigation a:hover,
						#et-footer'.'-'.$footer_id.' .widget_pages ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_archive ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_meta ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_recent_entries ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_rss ul li a:hover,
						#et-footer'.'-'.$footer_id.' .widget_icl_lang_sel_widget li a:hover,
						#et-footer'.'-'.$footer_id.' .recentcomments a:hover,
						#et-footer'.'-'.$footer_id.' .widget_shopping_cart .cart-product-title a:hover{
							color:'.$footer_widget_link_color_hover.' !important;
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' form #searchsubmit + .search-icon,
						#et-footer'.'-'.$footer_id.' .woocommerce-product-search button[type="submit"],
						#et-footer'.'-'.$footer_id.' .widget_layered_nav ul li a:after{
							background-color:'.$footer_widget_link_color_hover.' !important;
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget .star-rating,
						#et-footer'.'-'.$footer_id.' .widget_categories ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_pages ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_archive ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_meta ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_layered_nav ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_nav_menu ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_product_categories ul li a:before,
						#et-footer'.'-'.$footer_id.' .widget_price_filter .ui-slider-horizontal,
						#et-footer'.'-'.$footer_id.' .widget_categories > ul > li > ul:before,
						#et-footer'.'-'.$footer_id.' .widget_pages > ul > li > ul:before,
						#et-footer'.'-'.$footer_id.' .widget_archive > ul > li > ul:before,
						#et-footer'.'-'.$footer_id.' .widget_meta > ul > li > ul:before,
						#et-footer'.'-'.$footer_id.' .widget_nav_menu > ul > li > ul:before,
						#et-footer'.'-'.$footer_id.' .widget_product_categories > ul > li > ul:before,
						#et-footer'.'-'.$footer_id.' .widget_nav_menu ul > li > ul:before {
							background-color:'.propharm_enovathemes_hex_to_rgba($footer_widget_color,0.3).';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget .image-container .placeholder circle,
						#et-footer'.'-'.$footer_id.' .mailchimp-icon svg {
							fill:'.$footer_widget_color.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget_tag_cloud .tagcloud a,
						#et-footer'.'-'.$footer_id.' .widget_product_tag_cloud .tagcloud a,
						#et-footer'.'-'.$footer_id.' .widget .image-container,
						#et-footer'.'-'.$footer_id.' .widget_calendar td#today {
							background-color:'.propharm_enovathemes_hex_to_rgba($footer_widget_color,0.1).';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .widget_tag_cloud .tagcloud a:hover,
						#et-footer'.'-'.$footer_id.' .widget_product_tag_cloud .tagcloud a:hover {
							background-color:'.$footer_widget_link_color_hover.';
						}';

						$dynamic_css .='#et-footer'.'-'.$footer_id.' .woocommerce-mini-cart__total,
						#et-footer'.'-'.$footer_id.' .widget_mailchimp,
						#et-footer'.'-'.$footer_id.' .widget_calendar caption,
						#et-footer'.'-'.$footer_id.' .widget_calendar td,
						#et-footer'.'-'.$footer_id.' .widget_calendar th,
						#et-footer'.'-'.$footer_id.' .widget_calendar table:after,
						#et-footer'.'-'.$footer_id.' .widget_calendar table:before,
						#et-footer'.'-'.$footer_id.' .product-search,
						#et-footer'.'-'.$footer_id.' .widget_et_recent_entries .post{
							border-color:'.propharm_enovathemes_hex_to_rgba($footer_widget_color,0.2).' !important;
						}';
						
					}
			}

	$dynamic_css = propharm_enovathemes_minify_css($dynamic_css);

	wp_add_inline_style( 'dynamic-styles', $dynamic_css );
}
add_action( 'wp_enqueue_scripts', 'propharm_enovathemes_include_dynamic_styles',20);
?>