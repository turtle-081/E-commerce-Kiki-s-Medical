<?php
function enovathemes_addons_responsive_styles($query,$typography = true,$row_column = false){

	$dynamic_css = '';

    if ($row_column) {

    	for ($i=0; $i <= 80; $i+=2) {

    		if ($query == 768) {

    			if ($i==0) {
    				$dynamic_css .='.vc_row.vc_column-gap-0 > .vc_column_container,
					.vc_row.vc_column-gap-0 > .container > .vc_column_container,
					.vc_row.vc_column-gap-0 > .vc_element > .vc_column_container,
					.vc_row.vc_column-gap-0 > .container > .vc_element > .vc_column_container
					{padding-left:0px !important;padding-right:0px !important;}';

					$dynamic_css .='.vc_row.vc_column-gap-0 {margin-left:0px !important;margin-right:0px !important;width:100%;}';
					$dynamic_css .='.vc_row.vc_column-gap-0 .grid-overlay {width:100%;left:0px;}';

					$dynamic_css .='.compose-mode .vc_element.vc_hold-hover>.wpb_row.vc_column-gap-0>.container>.vc_element:before,
					.compose-mode .vc_element.vc_hover>.wpb_row.vc_column-gap-0>.container>.vc_element:before,
					.compose-mode .vc_element:hover>.wpb_row.vc_column-gap-0>.container>.vc_element:before,
					.compose-mode .vc_element.vc_hold-hover>.vc_inner.vc_column-gap-0>.vc_element:before,
					.compose-mode .vc_element.vc_hover>.vc_inner.vc_column-gap-0>.vc_element:before,
					.compose-mode .vc_element:hover>.vc_inner.vc_column-gap-0>.vc_element:before,
					.view-mode .vc_element.vc_hold-hover>.wpb_row.vc_column-gap-0>.container>.vc_element:before,
					.view-mode .vc_element.vc_hover>.wpb_row.vc_column-gap-0>.container>.vc_element:before,
					.view-mode .vc_element:hover>.wpb_row.vc_column-gap-0>.container>.vc_element>.vc_column_container>:before{
					    left:0px;
					    width:100%;
					}';
    			} else {
    				$dynamic_css .='.vc_row.vc_column-gap-'.$i.' > .vc_column_container,
					.vc_row.vc_column-gap-'.$i.' > .container > .vc_column_container,
					.vc_row.vc_column-gap-'.$i.' > .vc_element > .vc_column_container,
					.vc_row.vc_column-gap-'.$i.' > .container > .vc_element > .vc_column_container
					{padding-left:'.($i/2).'px !important;padding-right:'.($i/2).'px !important;}';

					$dynamic_css .='.vc_row.vc_column-gap-'.$i.' {margin-left:-'.($i/2).'px !important;margin-right:-'.($i/2).'px !important;width: calc(100% + '.$i.'px);}';
					$dynamic_css .='.vc_row.vc_column-gap-'.$i.' .grid-overlay {width: calc(100% - '.$i.'px);left:'.($i/2).'px;}';

					$dynamic_css .='.compose-mode .vc_element.vc_hold-hover>.wpb_row.vc_column-gap-'.$i.'>.container>.vc_element:before,
					.compose-mode .vc_element.vc_hover>.wpb_row.vc_column-gap-'.$i.'>.container>.vc_element:before,
					.compose-mode .vc_element:hover>.wpb_row.vc_column-gap-'.$i.'>.container>.vc_element:before,
					.compose-mode .vc_element.vc_hold-hover>.vc_inner.vc_column-gap-'.$i.'>.vc_element:before,
					.compose-mode .vc_element.vc_hover>.vc_inner.vc_column-gap-'.$i.'>.vc_element:before,
					.compose-mode .vc_element:hover>.vc_inner.vc_column-gap-'.$i.'>.vc_element:before,
					.view-mode .vc_element.vc_hold-hover>.wpb_row.vc_column-gap-'.$i.'>.container>.vc_element:before,
					.view-mode .vc_element.vc_hover>.wpb_row.vc_column-gap-'.$i.'>.container>.vc_element:before,
					.view-mode .vc_element:hover>.wpb_row.vc_column-gap-'.$i.'>.container>.vc_element>.vc_column_container>:before{
					    left:'.($i/2).'px;
					    width:calc(100% - '.$i.'px);
					}';
    			}

			}elseif ($query == '1280') {
				$dynamic_css .='.vc_row.vc_column-gap-'.$i.'>.container {max-width:'.(1200 + $i).'px;min-width:'.(1200 + $i).'px}';
			}elseif ($query == '1366') {
				$dynamic_css .='.vc_row.vc_column-gap-'.$i.'>.container {max-width:'.(1240 + $i).'px;min-width:'.(1240 + $i).'px}';
			}

	    }
    } else {
    	for ($i=0; $i <= 50; $i++) {
	        $dynamic_css .='.vci[data-'.$query.'-l="'.$i.'"]{padding-left: '.$i.'% !important}';
			$dynamic_css .='.vci[data-'.$query.'-r="'.$i.'"]{padding-right: '.$i.'% !important}';
	    }

	    if ($typography) {
	    	for ($i=10; $i <= 80; $i++) {
		        $dynamic_css .='[data-'.$query.'-f="'.$i.'"],[data-'.$query.'-f="'.$i.'"] .text-wrapper{font-size: '.$i.'px !important}';
		        $dynamic_css .='[data-'.$query.'-lh="'.$i.'"],[data-'.$query.'-lh="'.$i.'"] .text-wrapper{line-height: '.$i.'px !important}';
		    }
	    }
    }

    return $dynamic_css;
}

function enovathemes_addons_include_dynamic_styles_cached() {

    if ( false === ( $dynamic_css = get_transient( 'dynamic-styles-cached' ) ) ) {

    	global $propharm_enovathemes, $woocommerce, $post, $product, $wp_query, $query_string;

	    $dynamic_css = "";

	    if(isset($GLOBALS['propharm_enovathemes']['custom-css']) && !empty($GLOBALS['propharm_enovathemes']['custom-css'])){
			$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css'];
		}

		/* Animation delay
		/*-------------*/

			for ($i=0; $i <= 2000; $i+=50) {
		        $dynamic_css .='[data-del="'.$i.'"]{animation-delay:'.$i.'ms !important;}';
		    }

		/* Typography
		/*-------------*/

			$et_main_font_size          = (isset($GLOBALS['propharm_enovathemes']['main-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['main-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['main-typo']['font-size'] : "14px";
			$et_main_font_weight        = (isset($GLOBALS['propharm_enovathemes']['main-typo']['font-weight']) && $GLOBALS['propharm_enovathemes']['main-typo']['font-weight']) ? $GLOBALS['propharm_enovathemes']['main-typo']['font-weight'] : "400";
			$et_main_line_height        = (isset($GLOBALS['propharm_enovathemes']['main-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['main-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['main-typo']['line-height'] : "24px";
			$et_main_letter_spacing     = (isset($GLOBALS['propharm_enovathemes']['main-typo']['letter-spacing']) && $GLOBALS['propharm_enovathemes']['main-typo']['letter-spacing']) ? $GLOBALS['propharm_enovathemes']['main-typo']['letter-spacing'] : "0px";
			$et_main_font_family        = (isset($GLOBALS['propharm_enovathemes']['main-typo']['font-family']) && $GLOBALS['propharm_enovathemes']['main-typo']['font-family']) ? $GLOBALS['propharm_enovathemes']['main-typo']['font-family'] : "PT Sans";
			$et_main_color              = (isset($GLOBALS['propharm_enovathemes']['main-typo']['color']) && $GLOBALS['propharm_enovathemes']['main-typo']['color']) ? $GLOBALS['propharm_enovathemes']['main-typo']['color'] : "#56778f";
			$et_headings_font_family    = (isset($GLOBALS['propharm_enovathemes']['headings-typo']['font-family']) && $GLOBALS['propharm_enovathemes']['headings-typo']['font-family']) ? $GLOBALS['propharm_enovathemes']['headings-typo']['font-family'] : "PT Sans";
			$et_headings_font_weight    = (isset($GLOBALS['propharm_enovathemes']['headings-typo']['font-weight']) && $GLOBALS['propharm_enovathemes']['headings-typo']['font-weight']) ? $GLOBALS['propharm_enovathemes']['headings-typo']['font-weight'] : '700';
			$et_headings_text_transform = (isset($GLOBALS['propharm_enovathemes']['headings-typo']['text-transform']) && $GLOBALS['propharm_enovathemes']['headings-typo']['text-transform']) ? $GLOBALS['propharm_enovathemes']['headings-typo']['text-transform'] : "none";
			$et_headings_letter_spacing = (isset($GLOBALS['propharm_enovathemes']['headings-typo']['letter-spacing']) && $GLOBALS['propharm_enovathemes']['headings-typo']['letter-spacing']) ? $GLOBALS['propharm_enovathemes']['headings-typo']['letter-spacing'] : "0px";
			$et_headings_color          = (isset($GLOBALS['propharm_enovathemes']['headings-typo']['color']) && $GLOBALS['propharm_enovathemes']['headings-typo']['color']) ? $GLOBALS['propharm_enovathemes']['headings-typo']['color'] : "#184363";
			$et_h1_font_size            = (isset($GLOBALS['propharm_enovathemes']['h1-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['h1-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['h1-typo']['font-size'] : "48px";
			$et_h1_line_height          = (isset($GLOBALS['propharm_enovathemes']['h1-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['h1-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['h1-typo']['line-height'] : "56px";
			$et_h2_font_size            = (isset($GLOBALS['propharm_enovathemes']['h2-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['h2-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['h2-typo']['font-size'] : "40px";
			$et_h2_line_height          = (isset($GLOBALS['propharm_enovathemes']['h2-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['h2-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['h2-typo']['line-height'] : "48px";
			$et_h3_font_size            = (isset($GLOBALS['propharm_enovathemes']['h3-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['h3-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['h3-typo']['font-size'] : "32px";
			$et_h3_line_height          = (isset($GLOBALS['propharm_enovathemes']['h3-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['h3-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['h3-typo']['line-height'] : "40px";
			$et_h4_font_size            = (isset($GLOBALS['propharm_enovathemes']['h4-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['h4-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['h4-typo']['font-size'] : "24px";
			$et_h4_line_height          = (isset($GLOBALS['propharm_enovathemes']['h4-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['h4-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['h4-typo']['line-height'] : "32px";
			$et_h5_font_size            = (isset($GLOBALS['propharm_enovathemes']['h5-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['h5-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['h5-typo']['font-size'] : "20px";
			$et_h5_line_height          = (isset($GLOBALS['propharm_enovathemes']['h5-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['h5-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['h5-typo']['line-height'] : "28px";
			$et_h6_font_size            = (isset($GLOBALS['propharm_enovathemes']['h6-typo']['font-size']) && $GLOBALS['propharm_enovathemes']['h6-typo']['font-size']) ? $GLOBALS['propharm_enovathemes']['h6-typo']['font-size'] : "18px";
			$et_h6_line_height          = (isset($GLOBALS['propharm_enovathemes']['h6-typo']['line-height']) && $GLOBALS['propharm_enovathemes']['h6-typo']['line-height']) ? $GLOBALS['propharm_enovathemes']['h6-typo']['line-height'] : "28px";

			$dynamic_css .='body,input,select,pre,code,kbd,samp,dt,
			#cancel-comment-reply-link,
			.box-item-content, textarea, 
			.widget_price_filter .price_label {
				font-size: '.$et_main_font_size.';
				font-weight: '.$et_main_font_weight.';
				font-family:'.$et_main_font_family.';
				line-height: '.$et_main_line_height.';
				letter-spacing: '.$et_main_letter_spacing.';
				color:'.$et_main_color.';
			}';
			
			$dynamic_css .='h1,h2,h3,h4,h5,h6, 
			.woocommerce-Tabs-panel .shop_attributes th,
			#reply-title,
			.et-timer .timer-count,
			.et-pricing-table .currency,
			.et-pricing-table .price,
			.et-counter .counter,
			.et-progress .percent,
			.error404-default-subtitle,
			.woocommerce-MyAccount-navigation ul li a,
			.woocommerce-tabs .tabs li a {
				font-family:'.$et_headings_font_family.';
				text-transform: '.$et_headings_text_transform.';
				font-weight: '.$et_headings_font_weight.';
				color:'.$et_headings_color.';
			}';

			$dynamic_css .='h1,h2 {
				letter-spacing: '.$et_headings_letter_spacing.';
			}';

			$dynamic_css .='.widget_layered_nav ul li a, 
			.pf-item.list.attr ul li a,
			.widget_nav_menu ul li a, 
			.widget_product_categories ul li a,
			.widget_categories ul li a,
			.wp-block-archives li a,
			.post-single-navigation a, 
			.widget_pages ul li a, 
			.widget_archive ul li a, 
			.widget_meta ul li a, 
			.widget_recent_entries ul li a, 
			.widget_rss ul li a, 
			.widget_icl_lang_sel_widget li a, 
			.recentcomments a, 
			.widget_product_search form button:before, 
			.wp-block-search form button:before, 
			.page-content-wrap .widget_shopping_cart .cart_list li .remove,
			.pricing-table-body ul li{
				font-family:'.$et_headings_font_family.';
				font-weight: '.$et_headings_font_weight.';
				letter-spacing: '.$et_headings_letter_spacing.';
				color:'.$et_headings_color.';
			}';

			$dynamic_css .='.widget_et_recent_entries .post-title a,
			.widget_products .product_list_widget > li .product-title a,
			.widget_recently_viewed_products .product_list_widget > li .product-title a,
			.widget_recent_reviews .product_list_widget > li .product-title a,
			.widget_top_rated_products .product_list_widget > li .product-title a,
			.product .button, .product .added_to_cart {
				color:'.$et_headings_color.' !important;
			}';

			$dynamic_css .='.page-content-wrap .widget_shopping_cart .cart-product-title a {
				color:'.$et_headings_color.';
			}';

			$dynamic_css .='.loop-products .product .button:before,
			.loop-products .product .added_to_cart:before,
			ul.products .product .button:before,
			ul.products .product .added_to_cart:before,
			.summary .minus, .summary .plus{
				background:'.$et_headings_color.';
			}';

			$dynamic_css .='h1 {font-size: '.$et_h1_font_size.'; line-height: '.$et_h1_line_height.';}';
			$dynamic_css .='h2 {font-size: '.$et_h2_font_size.'; line-height: '.$et_h2_line_height.';}';
			$dynamic_css .='h3 {font-size: '.$et_h3_font_size.'; line-height: '.$et_h3_line_height.';}';
			$dynamic_css .='h4 {font-size: '.$et_h4_font_size.'; line-height: '.$et_h4_line_height.';}';
			$dynamic_css .='h5 {font-size: '.$et_h5_font_size.'; line-height: '.$et_h5_line_height.';}';
			$dynamic_css .='h6 {font-size: '.$et_h6_font_size.'; line-height: '.$et_h6_line_height.';}';

			$dynamic_css .='.woocommerce-Tabs-panel h2{font-size: '.$et_h6_font_size.'; line-height: '.$et_h6_line_height.';}';

			$dynamic_css .='.et-timer .timer-count
			{font-size: '.$et_h1_font_size.'; line-height: '.$et_h1_line_height.';}';

		/* Color
		/*-------------*/

			$main_color      = (isset($GLOBALS['propharm_enovathemes']['main-color']) && $GLOBALS['propharm_enovathemes']['main-color']) ? $GLOBALS['propharm_enovathemes']['main-color'] : '#15a9e3';
			$area_color      = (isset($GLOBALS['propharm_enovathemes']['area-color']) && $GLOBALS['propharm_enovathemes']['area-color']) ? $GLOBALS['propharm_enovathemes']['area-color'] : '#edf4f6';

			/* $area_color
			/*-------------*/

				$dynamic_css .='.et-pricing-table .et-button svg,
				.full-images-placeholder .media-placeholder{
					fill: '.$area_color.';
				}';

				$dynamic_css .='.wp-block-archives li a:before,
				.widget_pages ul li a:before,
				.widget_archive ul li a:before,
				.widget_meta ul li a:before,
				.widget_layered_nav ul li a:before,
				.pf-item.list.attr ul li a:before,
				.widget_nav_menu ul li a:before,
				.widget_product_categories ul li a:before,
				.post-author-ind,
				ul.chat li > p,
				.widget_price_filter .ui-slider-horizontal,
				.et-pricing-table .label,
				.woocommerce-message,
				.fbt-list li:before,
				.pf-slider,
				.pf-slider:after,
				.cbt .woocommerce-button,
				.post-categories a,
				.related-posts-wrapper,
				.woocommerce-before-shop-loop .layout-control,
				.content-sidebar-toggle,
				body .woocommerce-tabs .tabs li.active a,
				.no-products a,
				.post-ajax-button .button-back{
				    background-color: '.$area_color.';
				}';

				$dynamic_css .='.woocommerce-message,
				.wc_payment_methods p,
				.widget_fast_contact_widget .alert:not(.final) {
				    color: '.$main_color.';
				}';

				$dynamic_css .='#customer_details {
				    border-color: '.$area_color.';
				}';

				$dynamic_css .='.wishlist-table li:not(.no-results):hover,
				#customer_details,
				.wc_payment_methods p,
				.widget_fast_contact_widget .alert:not(.final) {
				    background-color: '.$area_color.';
				}';

				$dynamic_css .='.product-search .search-results ul li:not(.no-results):hover {
				    background-color: '.enovathemes_addons_hex_to_rgba($area_color,0.5).';
				}';

				$dynamic_css .='.product .button,
				.product .added_to_cart {
				    background-color: '.$area_color.' !important;
				}';

			/* $main_color
			/*-------------*/

				$dynamic_css .='.post-read-more svg,
				.video-btn .back,
				.ajax-add-to-cart-loading svg.tick,
				.et-person .et-social-links a:hover svg,
				.wishlist-toggle svg,
				.compare-toggle svg,
				.en-quick-view svg,
				.ask-toggle svg,
				.clear-attribute:hover svg,
				.show .post-social-share.filter a:hover svg,
				.et-woo-categories .et-icon svg,
				#et-desktop-default .header-menu>.menu-item:not(.mm-true) .sub-menu .menu-item:hover>.mi-link>.arrow svg,
				#mobile-menu-default>.menu-item>a:hover .arrow svg,
				.wishlist-toggle:hover svg,
				.compare-toggle:hover svg,
				.post-social-share.styling-original-false a:hover svg {
					fill: '.$main_color.';
				}';

				$dynamic_css .='.loop-posts .post-title:hover,
				.loop-posts .post-title a:hover,
				.post-read-more,
				.instagram-follow a:hover,
				.product .summary .price ins,
				.page-content-wrap .widget_shopping_cart .cart-product-title a:hover,
				.page-content-wrap .widget_shopping_cart .cart-product-title:hover a,
				.widget_products .product_list_widget > li > a:hover .product-title,
				.widget_recently_viewed_products .product_list_widget > li > a:hover .product-title,
				.widget_recent_reviews .product_list_widget > li > a:hover .product-title,
				.widget_top_rated_products .product_list_widget > li > a:hover .product-title,
				.search-posts .post-title a:hover,
				.search-posts .post-title:hover a,
				.comment-meta .comment-date-time a:hover,
				.comment-author a:hover,
				.comment-content .edit-link a a,
				#cancel-comment-reply-link:hover,
				.woocommerce-review-link,
				.product .summary .price ins span,
				.star-rating,
				.comment-form-rating a,
				.comment-form-rating a:after,
				.border-true.et-client-container .et-client .plus,
				.widget_nav_menu ul li.current-menu-item a,
				.enovathemes-filter .filter.active,
	        	.woocommerce-MyAccount-navigation li.is-active a,
	        	.woocommerce-MyAccount-navigation li a:hover,
	        	.et-blockquote .author,
				.product-data .product-price .regular-price,
				.cbt .product-price span,
				.current-cat-name,
				.filter-breadcrumbs > a:hover,
				.share > a:hover,
				.et-woo-category.children-true a:hover,
				.et-step-box .step-count,
				#et-desktop-default .header-menu>.menu-item:not(.mm-true) .sub-menu .menu-item:hover>.mi-link,
				#mobile-menu-default>.menu-item>a:hover,
				#mobile-menu-default>.menu-item .sub-menu .menu-item>a:hover,
				.widget_categories ul li a:hover,
				.widget_product_categories ul li a:hover,
				.pf-item.list.cat ul li a:hover,
				.entry-summary .wishlist-title,
				.entry-summary .compare-title,
				.loop-posts .post-meta a: hover,
				.et-attribute-box-content ul li.all a,
				.layout2 .product .summary .price,
				.star-rating-wrap > a:hover,
				.comp .product .price,
				.product .summary .price,
				.woocommerce-variation-availability,
				.widget_shopping_cart li .quantity,
				.widget_products .product_list_widget > li .woocommerce-Price-amount,
				.widget_recently_viewed_products .product_list_widget > li .woocommerce-Price-amount,
				.widget_recent_reviews .product_list_widget > li .woocommerce-Price-amount,
				.widget_top_rated_products .product_list_widget > li .woocommerce-Price-amount,
				.et-woo-category:hover .category-title,
				.product .post-category a,
				.product_meta > *,
				dy .woocommerce-tabs .tabs li.active a,
				.cbt .woocommerce-button,
				.post-categories a,
				.et-person .name {
					color: '.$main_color.';
				}';

				$dynamic_css .='.post-single-navigation a:hover,
				.widget_et_recent_entries .post-title:hover a,
				.widget_categories ul li a:hover,
				.wp-block-archives li a:hover,
				.widget_pages ul li a:hover,
				.widget_archive ul li a:hover,
				.widget_meta ul li a:hover,
				.widget_nav_menu ul li a:hover,
				.widget_product_categories ul li a:hover,
				.widget_product_categories ul li.current-cat > a,
				.widget_recent_entries ul li a:hover, 
				.widget_rss ul li a:hover,
				.widget_icl_lang_sel_widget li a:hover,
				.widget_products .product_list_widget > li .product-title:hover a,
				.widget_recently_viewed_products .product_list_widget > li .product-title:hover a,
				.widget_recent_reviews .product_list_widget > li .product-title:hover a,
				.widget_top_rated_products .product_list_widget > li .product-title:hover a,
				.recentcomments a:hover,
				.page-content-wrap .widget_shopping_cart .cart_list li .remove:hover,
				.product_meta a:hover,
				.product-data .product-price .sale-price,
				.comment-reply-link:hover,
				.clear-attribute:hover,
				.clear-attribute:hover a,
				.pf-item.list.cat .chosen,
				.et-woo-categories.border-true li:hover .category-title,
				.et-woo-categories.list li:hover .category-title,
				.post-title-section .post-date-inline-single span,
				.woocommerce-tabs .tabs li.active a,
				.entry-summary .wishlist-title:hover,
				.entry-summary .compare-title:hover,
				.entry-summary .ask-title:hover {
					color: '.$main_color.' !important;
				}';

				$dynamic_css .='.post-read-more:after,
				.comment-reply-link:after,
				.post-sticky,
				.post-media .flex-direction-nav li a:hover,
				.post-media .flex-control-nav li a:hover,
				.post-media .flex-control-nav li a.flex-active,
				.slick-dots li button:hover,
				.slick-dots li.slick-active button,
				.owl-carousel .owl-nav > *:hover,
				.overlay-flip-hor .overlay-hover .post-image-overlay, 
				.overlay-flip-ver .overlay-hover .post-image-overlay,
				.image-move-up .post-image-overlay,
				.image-move-down .post-image-overlay,
				.image-move-left .post-image-overlay,
				.image-move-right .post-image-overlay,
				.overlay-image-move-up .post-image-overlay,
				.overlay-image-move-down .post-image-overlay,
				.overlay-image-move-left .post-image-overlay,
				.overlay-image-move-right .post-image-overlay,
				.product-quick-view:hover,
				.added_to_cart,
				.woocommerce-store-notice.demo_store,
				.shop_table .product-remove a:hover,
				.tabset .tab.active:before,
				.et-mailchimp input[type="text"] + .after,
				.owl-carousel .owl-dots > .owl-dot.active,
				.mob-menu-toggle-alt,
				.single-post-page > .format-link .format-container,
				.et-image .curtain,
				.nivo-lightbox-prev:hover,
				.nivo-lightbox-next:hover,
				.nivo-lightbox-close:hover,
				.added_to_cart:after,
				.et-person .name:after,
				form #searchsubmit:hover + .search-icon,
				.content-sidebar-toggle.active,
				.comment-reply-link:hover,
				.gsap-lightbox-controls:hover,
				.et-carousel .tns-nav button.tns-nav-active,
				.et-info-present .tns-nav button.tns-nav-active,
				.manual-carousel .tns-nav button.tns-nav-active,
				.widget_categories ul li a:before,
				.widget_layered_nav ul li a:after,
				.post-date-side,
				.fbt-list li:after,
				.product .et-progress .bar,
				form #searchsubmit + .search-icon,
				.widget_product_search button[type="submit"]:before,
				.wp-block-search button:before,
				#et-desktop-default .header-menu>.menu-item.depth-0>.mi-link .effect,
				.enovathemes-navigation li a:hover,
				.enovathemes-navigation li .current,
				.woocommerce-pagination li a:hover,
				.woocommerce-pagination li .current,
				.woocommerce-before-shop-loop .sale-products:after,
				ul .product .label,
				.wp-block-search .wp-block-search__button,
				button.pf-slider .ui-slider-handle,
				.pf-slider .ui-slider-range,
				.widget_layered_nav ul li.chosen a:after,
				.pf-item.list.attr ul li a.chosen:after,
				.pf-item.col.attr ul li a.chosen:after,
				.fbt-list li.chosen:after,
				li.pf-item.label.attr ul li a:hover,
				.pf-item.label.attr ul li a.chosen,
				.widget_tag_cloud .tagcloud a:hover,
				.post-tags a:hover,
				.widget_product_tag_cloud .tagcloud a:hover,
				.post-tags-single a:hover,
				.wp-block-tag-cloud a:hover,
				.pf-item.label.attr ul li a:hover,
				.pf-item.label.attr ul li a.chosen,
				.clear-all-attribute:hover,
				.filter-breadcrumbs > a:hover,
				.single-post-page blockquote:before,
				.single-post-page q:before,
				.post-categories.single a,
				.post-media .tns-controls button:hover,
				.et-carousel .tns-controls button:hover,
				.et-gallery.slider .tns-controls button:hover,
				.tns-controls-trigger button:hover,
				.et-blockquote .author-info-wrapper:before,
				.post-ajax-button:hover .button-back{
					background-color: '.$main_color.';
				}';

				$dynamic_css .='.mejs-controls .mejs-time-rail .mejs-time-current,
				.slick-slider .slick-prev:hover,
				.slick-slider .slick-next:hover,
				.widget_tag_cloud .tagcloud a:after,
				.widget_product_tag_cloud .tagcloud a:after,
				.widget_price_filter .ui-slider-horizontal .ui-slider-range,
				#cboxClose:hover,
				.woocommerce-product-search button[type="submit"]:hover,
				.product .single_add_to_cart_button,
				.product .add_to_cart_all,
				.manual-carousel .tns-controls button:hover,
				.et-carousel .tns-controls button:hover,
				.et-shortcode-posts.full .tns-controls button:hover {
					background-color: '.$main_color.' !important;
				}';

				$dynamic_css .='circle.loader-path {stroke:'.$main_color.' !important;}';

				$dynamic_css .='ul.chat li:nth-child(2n+2) > p, .add-to-cart-form-wrapper-inner {
					background-color: '.enovathemes_addons_hex_to_rgba($main_color,0.1).';
				}';

				$dynamic_css .='.widget_mailchimp {
					background-color: '.enovathemes_addons_hex_to_rgba($main_color,0.2).';
					border-color: '.enovathemes_addons_hex_to_rgba($main_color,0.2).';
				}';

				$dynamic_css .='body .widget-area .widget_mailchimp input[type="text"]:focus,
				body .widget-area .widget_mailchimp input[type="email"]:focus,
				body .widget-area .widget_mailchimp input[type="tel"]:focus {
					border-color: '.enovathemes_addons_hex_to_rgba($main_color,0.4).' !important;
				}';

		        $dynamic_css .='.widget_price_filter .ui-slider .ui-slider-handle {
		            border:1px solid '.$main_color.';
		        }';

				$dynamic_css .= '.counter-moving-child:before, blockquote,q,
				.pf-item.image.attr ul li:hover a,
				.pf-item.image.attr ul li a.chosen,
				.woocommerce-product-gallery .flex-control-nav li img.flex-active {
					border-color:'.$main_color.';
				}';

				$dynamic_css .= '.post-image-overlay {
					background-color: '.enovathemes_addons_hex_to_rgba($main_color,0.9).';
				}';

				$dynamic_css .= '.overlay-fall .overlay-hover .post-image-overlay {
					background-color: '.$main_color.';
				}';

				// Header defaults
				$dynamic_css .='#header-menu-default > .menu-item.depth-0 > .mi-link .txt:after {
				    border-bottom-color: '.$main_color.';
				}';

				$dynamic_css .='circle.loader-path {
					stroke:'.$main_color.';
				}';

				$dynamic_css .='.enovathemes-navigation li a:hover,
				.enovathemes-navigation li .current,
				.woocommerce-pagination li a:hover,
				.woocommerce-pagination li .current,
				.pf-slider .ui-slider-handle,
				.pf-slider .ui-slider-range{
					box-shadow:inset 0 0 0 1px '.$main_color.';
				}';

		/* Site background
		/*-------------*/

			$et_site_back_col   = (isset($GLOBALS['propharm_enovathemes']['site-background']['background-color']) && $GLOBALS['propharm_enovathemes']['site-background']['background-color']) ? $GLOBALS['propharm_enovathemes']['site-background']['background-color'] : "#ffffff";
			$et_site_back_img   = (isset($GLOBALS['propharm_enovathemes']['site-background']['background-image']) && $GLOBALS['propharm_enovathemes']['site-background']['background-image']) ? esc_url($GLOBALS['propharm_enovathemes']['site-background']['background-image']) : "";
			$et_site_back_r     = (isset($GLOBALS['propharm_enovathemes']['site-background']['background-repeat']) && $GLOBALS['propharm_enovathemes']['site-background']['background-repeat']) ? $GLOBALS['propharm_enovathemes']['site-background']['background-repeat'] : "no-repeat";
			$et_site_back_s     = (isset($GLOBALS['propharm_enovathemes']['site-background']['background-size']) && $GLOBALS['propharm_enovathemes']['site-background']['background-size']) ? $GLOBALS['propharm_enovathemes']['site-background']['background-size'] : "inherit";
			$et_site_back_a     = (isset($GLOBALS['propharm_enovathemes']['site-background']['background-attachment']) && $GLOBALS['propharm_enovathemes']['site-background']['background-attachment']) ? $GLOBALS['propharm_enovathemes']['site-background']['background-attachment'] : "inherit";
			$et_site_back_p     = (isset($GLOBALS['propharm_enovathemes']['site-background']['background-position']) && $GLOBALS['propharm_enovathemes']['site-background']['background-position']) ? $GLOBALS['propharm_enovathemes']['site-background']['background-position'] : "left top";

			$dynamic_css .='html,#gen-wrap {
				background-color:'.$et_site_back_col.';';
				if(!empty($et_site_back_img)){
					$dynamic_css .='background-image:url('.$et_site_back_img.');
					background-repeat:'.$et_site_back_r.';
					background-attachment: '.$et_site_back_a.';
					-webkit-background-size: '.$et_site_back_s.';
					-moz-background-size: '.$et_site_back_s.';
					background-size: '.$et_site_back_s.';
					background-position:'.$et_site_back_p;
				}
			$dynamic_css .='}';

		/* Forms
		---------------*/

			$form_text_color_reg         = (isset($GLOBALS['propharm_enovathemes']['form-text-color']['regular']) && !empty($GLOBALS['propharm_enovathemes']['form-text-color']['regular'])) ? $GLOBALS['propharm_enovathemes']['form-text-color']['regular'] : '#184363';
			$form_text_color_hov         = (isset($GLOBALS['propharm_enovathemes']['form-text-color']['hover']) && !empty($GLOBALS['propharm_enovathemes']['form-text-color']['hover'])) ? $GLOBALS['propharm_enovathemes']['form-text-color']['hover'] : '#184363';
			$form_back_color_reg         = (isset($GLOBALS['propharm_enovathemes']['form-back-color']['regular']) && $GLOBALS['propharm_enovathemes']['form-back-color']['regular']) ? $GLOBALS['propharm_enovathemes']['form-back-color']['regular'] : "#edf4f6";
			$form_back_color_hov         = (isset($GLOBALS['propharm_enovathemes']['form-back-color']['hover']) && $GLOBALS['propharm_enovathemes']['form-back-color']['hover']) ? $GLOBALS['propharm_enovathemes']['form-back-color']['hover'] : "#ffffff";
			$form_border_color_reg       = (isset($GLOBALS['propharm_enovathemes']['form-border-color']['regular']) && !empty($GLOBALS['propharm_enovathemes']['form-border-color']['regular'])) ? $GLOBALS['propharm_enovathemes']['form-border-color']['regular'] : "#edf4f6";
			$form_border_color_hov       = (isset($GLOBALS['propharm_enovathemes']['form-border-color']['hover']) && !empty($GLOBALS['propharm_enovathemes']['form-border-color']['hover'])) ? $GLOBALS['propharm_enovathemes']['form-border-color']['hover'] : "#15a9e3";

			$form_button_typo_font_family  	   = (isset($GLOBALS['propharm_enovathemes']['form-button-typo']['font-family']) && !empty($GLOBALS['propharm_enovathemes']['form-button-typo']['font-family'])) ? $GLOBALS['propharm_enovathemes']['form-button-typo']['font-family'] : "PT Sans";
			$form_button_typo_font_weight  	   = (isset($GLOBALS['propharm_enovathemes']['form-button-typo']['font-weight']) && !empty($GLOBALS['propharm_enovathemes']['form-button-typo']['font-weight'])) ? $GLOBALS['propharm_enovathemes']['form-button-typo']['font-weight'] : "700";
			$form_button_typo_letter_spacing   = (isset($GLOBALS['propharm_enovathemes']['form-button-typo']['letter-spacing']) && !empty($GLOBALS['propharm_enovathemes']['form-button-typo']['letter-spacing'])) ? $GLOBALS['propharm_enovathemes']['form-button-typo']['letter-spacing'] : "0";
			$form_button_color_reg             = (isset($GLOBALS['propharm_enovathemes']['form-button-color']['regular']) && $GLOBALS['propharm_enovathemes']['form-button-color']['regular']) ? $GLOBALS['propharm_enovathemes']['form-button-color']['regular'] : "#ffffff";
			$form_button_color_hov             = (isset($GLOBALS['propharm_enovathemes']['form-button-color']['hover']) && $GLOBALS['propharm_enovathemes']['form-button-color']['hover']) ? $GLOBALS['propharm_enovathemes']['form-button-color']['hover'] : "#ffffff";
			$form_button_back_reg              = (isset($GLOBALS['propharm_enovathemes']['form-button-back']['regular']) && $GLOBALS['propharm_enovathemes']['form-button-back']['regular']) ? $GLOBALS['propharm_enovathemes']['form-button-back']['regular'] : "#f2971f";
			$form_button_back_hov              = (isset($GLOBALS['propharm_enovathemes']['form-button-back']['hover']) && $GLOBALS['propharm_enovathemes']['form-button-back']['hover']) ? $GLOBALS['propharm_enovathemes']['form-button-back']['hover'] : "#184363";

			$dynamic_css .='textarea, select,
			 input[type="date"], input[type="datetime"],
			 input[type="datetime-local"], input[type="email"],
			 input[type="month"], input[type="number"],
			 input[type="password"], input[type="search"],
			 input[type="tel"], input[type="text"],
			 input[type="time"], input[type="url"],
			 input[type="week"], input[type="file"],
			 .select2-container .select2-selection--multiple {
				color:'.$form_text_color_reg.';
				background-color:'.$form_back_color_reg.';
				border-color:'.$form_border_color_reg.';
			}';

			$dynamic_css .='.tech-page-search-form .search-icon,
			.widget_search form input[type="submit"]#searchsubmit + .search-icon {
				color:'.$form_text_color_reg.' !important;
			}';

			$dynamic_css .='.select2-container--default .select2-selection--single,
			.select2-container--default .select2-selection--multiple {
				color:'.$form_text_color_reg.' !important;
				background-color:'.$form_back_color_reg.' !important;
				border-color:'.$form_border_color_reg.' !important;
			}';

			$dynamic_css .='.select2-container--default .select2-selection--single .select2-selection__rendered{
				color:'.$form_text_color_reg.' !important;
			}';

			$dynamic_css .='.select2-dropdown,
			.select2-container--default .select2-search--dropdown .select2-search__field,
			.select2-container .select2-selection--multiple:after {
				background-color:'.$form_back_color_reg.' !important;
			}';

			$dynamic_css .='textarea:focus, select:focus,
			 input[type="date"]:focus, input[type="datetime"]:focus,
			 input[type="datetime-local"]:focus, input[type="email"]:focus,
			 input[type="month"]:focus, input[type="number"]:focus,
			 input[type="password"]:focus, input[type="search"]:focus,
			 input[type="tel"]:focus, input[type="text"]:focus,
			 input[type="time"]:focus, input[type="url"]:focus,
			 input[type="week"]:focus, input[type="file"]:focus {
				color:'.$form_text_color_hov.';
				border-color:'.$form_border_color_hov.';
				background-color:'.$form_back_color_hov.';';
			$dynamic_css .='}';

			$dynamic_css .='.tech-page-search-form [type="submit"]#searchsubmit:hover + .search-icon,
			.widget_search form input[type="submit"]#searchsubmit:hover + .search-icon {
				color:'.$form_text_color_hov.' !important;
			}';

			$dynamic_css .='.select2-container--default .select2-selection--single:focus {
				color:'.$form_text_color_hov.' !important;
				border-color:'.$form_border_color_hov.' !important;
				background-color:'.$form_back_color_hov.' !important;';
			$dynamic_css .='}';

			$dynamic_css .='.select2-container--default .select2-selection--single .select2-selection__rendered:focus{
				color:'.$form_text_color_hov.' !important;
			}';

			$dynamic_css .='.select2-dropdown:focus,
			.select2-container--default .select2-search--dropdown .select2-search__field:focus {
				background-color:'.$form_back_color_hov.' !important;
			}';

			$dynamic_css .='input[type="button"],
			 input[type="reset"],
			 input[type="submit"],
			 button,
			 a.checkout-button,
			 .return-to-shop a,
			 a.woocommerce-button,
			 #page-links > a,
			 .edit-link a,
			 .page-content-wrap .woocommerce-mini-cart__buttons > a,
			 .woocommerce .wishlist_table td.product-add-to-cart a,
			 .woocommerce-message .button,
			 a.error404-button,
			.logout-button,
			.shop-top-widgets .product-search .input-after,
			.product .button,
			.product .added_to_cart,
			.my-account-buttons a {
				color:'.$form_button_color_reg.';
				font-family:'.$form_button_typo_font_family.'; 
				font-weight:'.$form_button_typo_font_weight.'; 
				letter-spacing:'.$form_button_typo_letter_spacing.'; 
				background-color:'.$form_button_back_reg.';';
			$dynamic_css .='}';

			$dynamic_css .='.product .buy-now-button{
				color:'.$form_button_color_reg.' !important;
				background-color:'.$form_button_back_reg.' !important;';
			$dynamic_css .='}';

			$dynamic_css .='.et-button,
			.post-read-more,
			.comment-reply-link,
			.enovathemes-filter .filter,
			.woocommerce-mini-cart__buttons > a,
			.widget_tag_cloud .tagcloud a,
			.post-tags a,
			.widget_product_tag_cloud .tagcloud a,
			.post-tags-single a {
				font-family:'.$form_button_typo_font_family.'; 
				font-weight:'.$form_button_typo_font_weight.'; 
				letter-spacing:'.$form_button_typo_letter_spacing.';
			}';

			$dynamic_css .='input[type="button"]:hover,
			input[type="reset"]:hover,
			input[type="submit"]:hover,
			button:hover,
			a.checkout-button:hover,
			.return-to-shop a:hover,
			.wishlist_table .product-add-to-cart a:hover,
			a.woocommerce-button:hover,
			.woocommerce-mini-cart__buttons > a:hover,
			#page-links > a:hover,
			.edit-link a:hover,
			.page-content-wrap .woocommerce-mini-cart__buttons > a:hover,
			.woocommerce .wishlist_table td.product-add-to-cart a:hover,
			.error404-button:hover,
			.logout-button:hover,
			.shop-top-widgets .product-search input[type="submit"]:hover + .input-after,
			.product .button:hover,
			.product .added_to_cart:hover,
			.my-account-buttons a:hover {
				color:'.$form_button_color_hov.' !important;
				background-color:'.$form_button_back_hov.';';
			$dynamic_css .='}';

			$dynamic_css .='.product .buy-now-button:hover, .wc-proceed-to-checkout + a:hover {
				color:'.$form_button_color_hov.' !important;
				background-color:'.$form_button_back_hov.' !important;';
			$dynamic_css .='}';

			$dynamic_css .='.widget_price_filter .ui-slider .ui-slider-handle {
				background-color:'.$form_button_back_reg.';
			}';

			$dynamic_css .='.woocommerce-checkout .col2-set .page-content textarea,
			.woocommerce-checkout .col2-set .page-content select,
			.woocommerce-checkout .col2-set .page-content input[type="date"],
			.woocommerce-checkout .col2-set .page-content input[type="datetime"],
			.woocommerce-checkout .col2-set .page-content input[type="datetime-local"],
			.woocommerce-checkout .col2-set .page-content input[type="email"],
			.woocommerce-checkout .col2-set .page-content input[type="month"],
			.woocommerce-checkout .col2-set .page-content input[type="number"],
			.woocommerce-checkout .col2-set .page-content input[type="password"],
			.woocommerce-checkout .col2-set .page-content input[type="search"],
			.woocommerce-checkout .col2-set .page-content input[type="tel"],
			.woocommerce-checkout .col2-set .page-content input[type="text"],
			.woocommerce-checkout .col2-set .page-content input[type="time"],
			.woocommerce-checkout .col2-set .page-content input[type="url"],
			.woocommerce-checkout .col2-set .page-content input[type="week"],
			.woocommerce-checkout .col2-set .page-content input[type="file"],
			.woocommerce-checkout .col2-set .page-content .select2-container .select2-selection--multiple,
			.woocommerce-checkout .col2-set .page-content .select2-container--default .select2-selection--single,
			.woocommerce-checkout .col2-set .page-content .select2-container--default .select2-selection--multiple {
	    		border-color: '.enovathemes_addons_hex_to_rgb_shade($area_color,30).' !important;
			}';

			$dynamic_css .='.woocommerce-checkout .col2-set .page-content textarea:focus,
			.woocommerce-checkout .col2-set .page-content select:focus,
			.woocommerce-checkout .col2-set .page-content input[type="date"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="datetime"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="datetime-local"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="email"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="month"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="number"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="password"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="search"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="tel"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="text"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="time"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="url"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="week"]:focus,
			.woocommerce-checkout .col2-set .page-content input[type="file"]:focus,
			.woocommerce-checkout .col2-set .page-content .select2-container .select2-selection--multiple:focus,
			.woocommerce-checkout .col2-set .page-content .select2-container--default .select2-selection--single:focus,
			.woocommerce-checkout .col2-set .page-content .select2-container--default .select2-selection--multiple:focus {
	    		border-color: '.enovathemes_addons_hex_to_rgb_shade($area_color,50).' !important;
			}';

		/* Products
		---------------*/

			$product_title_min_height   = (isset($GLOBALS['propharm_enovathemes']['product-title-min-height']) && !empty($GLOBALS['propharm_enovathemes']['product-title-min-height'])) ? $GLOBALS['propharm_enovathemes']['product-title-min-height'] : "0";       
			$product_title_max_height   = (isset($GLOBALS['propharm_enovathemes']['product-title-max-height']) && !empty($GLOBALS['propharm_enovathemes']['product-title-max-height'])) ? $GLOBALS['propharm_enovathemes']['product-title-max-height'] : "0";       

			$sale_color     = (isset($GLOBALS['propharm_enovathemes']['sale-color']) && !empty($GLOBALS['propharm_enovathemes']['sale-color'])) ? $GLOBALS['propharm_enovathemes']['sale-color'] : "#f25c05";
			$discount_color = (isset($GLOBALS['propharm_enovathemes']['discount-color']) && !empty($GLOBALS['propharm_enovathemes']['discount-color'])) ? $GLOBALS['propharm_enovathemes']['discount-color'] : "#66bbf2";

			$dynamic_css .='.loop-products .product .onsale,
			ul.products .product .onsale,
			.post-media .post-meta a{
				background:'.$sale_color.';
			}';

			$dynamic_css .='.comp-quantity span,
			body.woocommerce .woocommerce-variation-availability p {
				color:'.$sale_color.';
			}';

			$dynamic_css .='.loop-products .product .discount,
			.product-short-description ul li:before,
			.woocommerce-product-details__short-description ul li:before,
			.single-product-wrapper .onsale,
			.pricing-table-body ul li:before,
			.product-stock {
				background:'.$discount_color.';
			}';

			$dynamic_css .='.highlight-true.grid.post-layout.gap-false .loop-products,
			.highlight-true.grid.post-layout.gap-true .loop-products .product,
			.highlight-true.list.post-layout .loop-products .product,
			.highlight-true.full.post-layout .loop-products,
			.highlight-true.list.post-layout .loop-products .product .post-inner,
			.highlight-true.grid.gap-true .loop-products .product .post-inner{
				border-color:'.$discount_color.';
			}';


			if ($product_title_min_height != '0') {
				$dynamic_css .='.loop-products .post-title {
					min-height:'.$product_title_min_height.'px;
				}';
			}

			if ($product_title_max_height != '0') {
				$dynamic_css .='.loop-products .post-title {
					max-height:'.$product_title_max_height.'px;
					overflow:hidden;
				}';
			}

		/*  Megamenu
        ---------------*/

			$megamenu = enovathemes_addons_megamenus();
            if (!is_wp_error($megamenu)) {
                foreach ($megamenu as $megam => $atts) {
                    $megamenu_id = $megam;

					$megamenu_position         = get_post_meta($megamenu_id, 'enovathemes_addons_megamenu_position', true);
					$megamenu_width            = get_post_meta($megamenu_id, 'enovathemes_addons_megamenu_width', true);
					$megamenu_offset           = get_post_meta($megamenu_id, 'enovathemes_addons_megamenu_offset', true);
					$element_css               = get_post_meta($megamenu_id, 'element_css', true);
					$wpb_shortcodes_custom_css = get_post_meta($megamenu_id, '_wpb_shortcodes_custom_css', true);

					if (!empty($element_css)) {
						$dynamic_css .= $element_css;
					}

					if (!empty($wpb_shortcodes_custom_css)) {
						$dynamic_css .= $wpb_shortcodes_custom_css;
					}

					if (empty($megamenu_width)) {
						$megamenu_width = 1240;
					}

					if (!is_singular('megamenu')) {

						if ($megamenu_width != 1240 && $megamenu_width != 100) {
							$megamenu_width = abs((1240*($megamenu_width/100)));
							$dynamic_css .= '#megamenu'.'-'.$megamenu_id.' {width:'.$megamenu_width.'px;max-width:'.$megamenu_width.'px;}';
						} elseif($megamenu_width == 1240){
							$dynamic_css .= '#megamenu'.'-'.$megamenu_id.' {width:1240px;max-width:1240px;}';
						}

						if (!empty($megamenu_offset) && $megamenu_width != 100) {
							if ($megamenu_position == 'left' || $megamenu_position == 'center') {
								$dynamic_css .= '.header-menu #megamenu'.'-'.$megamenu_id.', .et-menu #megamenu'.'-'.$megamenu_id.' {margin-left:'.$megamenu_offset.'px !important;}';
							} elseif($megamenu_position == 'right') {
								$dynamic_css .= '.header-menu #megamenu'.'-'.$megamenu_id.', .et-menu #megamenu'.'-'.$megamenu_id.' {margin-right:'.$megamenu_offset.'px !important;}';
							}
						}

						if ($megamenu_width != 100 && $megamenu_position == 'center' && empty($megamenu_offset)) {
							$dynamic_css .= '.header-menu #megamenu'.'-'.$megamenu_id.', .et-menu #megamenu'.'-'.$megamenu_id.' {margin-left:-'.($megamenu_width/2).'px !important;}';
						}

					}

					/*  Megamenu forms
					---------------*/

						$megamenu_custom_form_styling      = get_post_meta($megamenu_id, 'enovathemes_addons_custom_form_styling', true);

						if ($megamenu_custom_form_styling == "on") {

							$megamenu_field_color              = get_post_meta($megamenu_id, 'enovathemes_addons_field_color', true);
							$megamenu_field_color_focus        = get_post_meta($megamenu_id, 'enovathemes_addons_field_color_focus', true);
							$megamenu_field_back_color         = get_post_meta($megamenu_id, 'enovathemes_addons_field_back_color', true);
							$megamenu_field_back_color_focus   = get_post_meta($megamenu_id, 'enovathemes_addons_field_back_color_focus', true);
							$megamenu_field_border_color       = get_post_meta($megamenu_id, 'enovathemes_addons_field_border_color', true);
							$megamenu_field_border_color_focus = get_post_meta($megamenu_id, 'enovathemes_addons_field_border_color_focus', true);
							$megamenu_button_color             = get_post_meta($megamenu_id, 'enovathemes_addons_button_color', true);
							$megamenu_button_color_hover       = get_post_meta($megamenu_id, 'enovathemes_addons_button_color_hover', true);
							$megamenu_button_back_color        = get_post_meta($megamenu_id, 'enovathemes_addons_button_back_color', true);
							$megamenu_button_back_color_hover  = get_post_meta($megamenu_id, 'enovathemes_addons_button_back_color_hover', true);

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' textarea, 
							#et-megamenu'.'-'.$megamenu_id.' select,
							#et-megamenu'.'-'.$megamenu_id.' input[type="date"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="datetime"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="datetime-local"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="email"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="month"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="number"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="password"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="search"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="tel"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="text"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="time"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="url"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="week"], 
							#et-megamenu'.'-'.$megamenu_id.' input[type="file"] {
								color:'.$megamenu_field_color.';
								background-color:'.$megamenu_field_back_color.';
								border-color:'.$megamenu_field_border_color.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' textarea:focus, 
							#et-megamenu'.'-'.$megamenu_id.' select:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="date"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="datetime"]:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="datetime-local"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="email"]:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="month"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="number"]:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="password"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="search"]:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="tel"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="text"]:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="time"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="url"]:focus,
							#et-megamenu'.'-'.$megamenu_id.' input[type="week"]:focus, 
							#et-megamenu'.'-'.$megamenu_id.' input[type="file"]:focus {
								color:'.$megamenu_field_color_focus.';
								background-color:'.$megamenu_field_back_color_focus.';
								border-color:'.$megamenu_field_border_color_focus.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' input[type="button"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="reset"],
							#et-megamenu'.'-'.$megamenu_id.' input[type="submit"],
							#et-megamenu'.'-'.$megamenu_id.' .woocommerce-mini-cart__buttons > a,
							#et-megamenu'.'-'.$megamenu_id.' button {
								color:'.$megamenu_button_color.';
								background-color:'.$megamenu_button_back_color.';';
							$dynamic_css .='}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .woocommerce-product-search button[type="submit"],
							#et-megamenu'.'-'.$megamenu_id.' form #searchsubmit + .search-icon {
								background-color:'.$megamenu_button_back_color.' !important;
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' input[type="button"]:hover,
							#et-megamenu'.'-'.$megamenu_id.' input[type="reset"]:hover,
							#et-megamenu'.'-'.$megamenu_id.' input[type="submit"]:hover,
							#et-megamenu'.'-'.$megamenu_id.' button:hover,
							#et-megamenu'.'-'.$megamenu_id.' .woocommerce-mini-cart__buttons > a:hover,
							#et-megamenu'.'-'.$megamenu_id.' button:hover {
								color:'.$megamenu_button_color_hover.' !important;
								background-color:'.$megamenu_button_back_color_hover.';';
							$dynamic_css .='}';

						}

					/*  Megamenu widgets
					---------------*/

						$megamenu_custom_widget_styling = get_post_meta($megamenu_id, 'enovathemes_addons_custom_widget_styling', true);

						if ($megamenu_custom_widget_styling == "on") {

							$megamenu_widget_title_color      = get_post_meta($megamenu_id, 'enovathemes_addons_widget_title_color', true);
							$megamenu_widget_color            = get_post_meta($megamenu_id, 'enovathemes_addons_widget_color', true);
							$megamenu_widget_link_color       = get_post_meta($megamenu_id, 'enovathemes_addons_widget_link_color', true);
							$megamenu_widget_link_color_hover = get_post_meta($megamenu_id, 'enovathemes_addons_widget_link_color_hover', true);

							$megamenu_widget_color_brightness = propharm_enovathemes_brightness($megamenu_widget_color);

							if ($megamenu_widget_color_brightness == 'light') {
								$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget_calendar a.prev,
								#et-megamenu'.'-'.$megamenu_id.' .widget_calendar a.next,
								#et-megamenu'.'-'.$megamenu_id.' .wp-block-calendar a.prev,
								#et-megamenu'.'-'.$megamenu_id.' .wp-block-calendar a.next,
								#et-megamenu'.'-'.$megamenu_id.' .widget_nav_menu ul li a .toggle,
								#et-megamenu'.'-'.$megamenu_id.' .widget_product_categories ul li a .toggle {
								    background: url('.PROPHARM_SVG.'arrow-ed.svg) no-repeat 50% 50%;
								    background-size: 8px;
								}';
							}

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget_title,
							#et-megamenu'.'-'.$megamenu_id.' th{
								color:'.$megamenu_widget_title_color.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' form #searchsubmit + .search-icon {
								fill:'.$megamenu_widget_title_color.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget,
							#et-megamenu'.'-'.$megamenu_id.' .widget_price_filter .price_label,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar td#today,
							#et-megamenu'.'-'.$megamenu_id.' .widget_tag_cloud .tagcloud a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_tag_cloud .tagcloud a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_mailchimp,
							#et-megamenu'.'-'.$megamenu_id.' .mailchimp-description {
								color:'.$megamenu_widget_color.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget_et_recent_entries .post-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_products .product_list_widget > li .product-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_recently_viewed_products .product_list_widget > li .product-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_recent_reviews .product_list_widget > li .product-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_top_rated_products .product_list_widget > li .product-title a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_search form button:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_shopping_cart .cart_list li .remove,
							#et-megamenu'.'-'.$megamenu_id.' .widget_et_recent_entries .post-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_products .product_list_widget > li .product-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_recently_viewed_products .product_list_widget > li .product-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_recent_reviews .product_list_widget > li .product-title a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_top_rated_products .product_list_widget > li .product-title a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_layered_nav ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_nav_menu ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_categories ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_categories ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .post-single-navigation a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_pages ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_archive ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_meta ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_recent_entries ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_rss ul li a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_icl_lang_sel_widget li a,
							#et-megamenu'.'-'.$megamenu_id.' .recentcomments a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_shopping_cart .cart-product-title a {
								color:'.$megamenu_widget_link_color.' !important;
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget_et_recent_entries .post-title:hover a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_products .product_list_widget > li .product-title:hover a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_recently_viewed_products .product_list_widget > li .product-title:hover a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_recent_reviews .product_list_widget > li .product-title:hover a, 
							#et-megamenu'.'-'.$megamenu_id.' .widget_top_rated_products .product_list_widget > li .product-title:hover a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_layered_nav ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_nav_menu ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_categories ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_categories ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .post-single-navigation a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_pages ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_archive ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_meta ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_recent_entries ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_rss ul li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_icl_lang_sel_widget li a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .recentcomments a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_shopping_cart .cart-product-title a:hover{
								color:'.$megamenu_widget_link_color_hover.' !important;
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' form #searchsubmit + .search-icon,
							#et-megamenu'.'-'.$megamenu_id.' .woocommerce-product-search button[type="submit"],
							#et-megamenu'.'-'.$megamenu_id.' .widget_layered_nav ul li a:after{
								background-color:'.$megamenu_widget_link_color_hover.' !important;
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget .star-rating,
							#et-megamenu'.'-'.$megamenu_id.' .widget_categories ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_pages ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_archive ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_meta ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_layered_nav ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_nav_menu ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_categories ul li a:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_price_filter .ui-slider-horizontal,
							#et-megamenu'.'-'.$megamenu_id.' .widget_categories > ul > li > ul:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_pages > ul > li > ul:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_archive > ul > li > ul:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_meta > ul > li > ul:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_nav_menu > ul > li > ul:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_categories > ul > li > ul:before,
							#et-megamenu'.'-'.$megamenu_id.' .widget_nav_menu ul > li > ul:before {
								background-color:'.propharm_enovathemes_hex_to_rgba($megamenu_widget_color,0.3).';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget .image-container .placeholder circle,
							#et-megamenu'.'-'.$megamenu_id.' .mailchimp-icon svg {
								fill:'.$megamenu_widget_color.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget_tag_cloud .tagcloud a,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_tag_cloud .tagcloud a,
							#et-megamenu'.'-'.$megamenu_id.' .widget .image-container,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar td#today {
								background-color:'.propharm_enovathemes_hex_to_rgba($megamenu_widget_color,0.1).';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .widget_tag_cloud .tagcloud a:hover,
							#et-megamenu'.'-'.$megamenu_id.' .widget_product_tag_cloud .tagcloud a:hover {
								background-color:'.$megamenu_widget_link_color_hover.';
							}';

							$dynamic_css .='#et-megamenu'.'-'.$megamenu_id.' .woocommerce-mini-cart__total,
							#et-megamenu'.'-'.$megamenu_id.' .widget_mailchimp,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar caption,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar td,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar th,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar table:after,
							#et-megamenu'.'-'.$megamenu_id.' .widget_calendar table:before,
							#et-megamenu'.'-'.$megamenu_id.' .product-search,
							#et-megamenu'.'-'.$megamenu_id.' .widget_et_recent_entries .post{
								border-color:'.propharm_enovathemes_hex_to_rgba($megamenu_widget_color,0.2).' !important;
							}';
							
						}
                }
            }

        /*  Banners
        ---------------*/

			$banners = enovathemes_addons_banners();
            if (!is_wp_error($banners)) {
                foreach ($banners as $banner => $atts) {

                    $element_css               = get_post_meta($banner, 'element_css', true);
					$wpb_shortcodes_custom_css = get_post_meta($banner, '_wpb_shortcodes_custom_css', true);

					if (!empty($element_css)) {
						$dynamic_css .= $element_css;
					}

					if (!empty($wpb_shortcodes_custom_css)) {
						$dynamic_css .= $wpb_shortcodes_custom_css;
					}
                }
            }

		/* Responsive
		---------------*/
			
			$dynamic_css .='@media only screen and (max-width: 374px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('374');
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-max-374']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-max-374'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-max-374'];
				}
			$dynamic_css .='}';
			
			if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-375']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-375'])){
				$dynamic_css .='@media only screen and (min-width: 375px)  {';
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-375'];
				$dynamic_css .='}';
			}

			$dynamic_css .='@media only screen and (min-width: 375px) and (max-width: 767px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('375-767');
			$dynamic_css .='}';

			$dynamic_css .='@media only screen and (max-width: 767px)  {';
				$dynamic_css .='h1 {font-size: '.$et_h3_font_size.'; line-height: '.$et_h3_line_height.';}';
				$dynamic_css .='h2 {font-size: '.$et_h4_font_size.'; line-height: '.$et_h4_line_height.';}';
				$dynamic_css .='h3 {font-size: '.$et_h5_font_size.'; line-height: '.$et_h5_line_height.';}';
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-max-767']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-max-767'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-max-767'];
				}
			$dynamic_css .='}';
			
			$dynamic_css .='@media only screen and (min-width: 768px) {';

				$dynamic_css .= enovathemes_addons_responsive_styles('768',false,true);

				if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-768']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-768'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-768'];
				}
			$dynamic_css .='}';
			
			$dynamic_css .='@media only screen and (min-width: 768px) and (max-width: 1023px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('768-1023');
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-768-max-1023']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-768-max-1023'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-768-max-1023'];
				}
			$dynamic_css .='}';
			
			if(isset($GLOBALS['propharm_enovathemes']['custom-css-max-1023']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-max-1023'])){
				$dynamic_css .='@media only screen and (max-width: 1023px)  {';
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-max-1023'];
				$dynamic_css .='}';
			}
			
			if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-1024']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-1024'])){
				$dynamic_css .='@media only screen and (min-width: 1024px) {';
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-1024'];
				$dynamic_css .='}';
			}

			$dynamic_css .='@media only screen and (min-width: 1024px) and (max-width: 1279px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('1024-1279');
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-1024-max-1279']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-1024-max-1279'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-1024-max-1279'];
				}
			$dynamic_css .='}';
				
			$dynamic_css .='@media only screen and (max-width: 1279px)  {';
				$dynamic_css .='.post-body .en-quick-view,
				.post-body .wishlist-toggle,
				.post-body .compare-toggle {background-color: '.$area_color.' !important;}';
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-max-1279']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-max-1279'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-max-1279'];
				}
			$dynamic_css .='}';

			$dynamic_css .='@media only screen and (min-width: 1280px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('1280',false,true);
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-1280']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-1280'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-1280'];
				}
			$dynamic_css .='}';

			$dynamic_css .='@media only screen and (min-width: 1366px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('1366',false,true);
			$dynamic_css .='}';
			
			$dynamic_css .='@media only screen and (min-width: 1280px) and (max-width: 1599px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('1280-1599',false);
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-1280-max-1599']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-1280-max-1599'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-1280-max-1599'];
				}
			$dynamic_css .='}';

			$dynamic_css .='@media only screen and (min-width: 1600px) and (max-width: 1919px)  {';
				$dynamic_css .= enovathemes_addons_responsive_styles('1600-1919',false);
				if(isset($GLOBALS['propharm_enovathemes']['custom-css-min-1600-max-1919']) && !empty($GLOBALS['propharm_enovathemes']['custom-css-min-1600-max-1919'])){
					$dynamic_css .= $GLOBALS['propharm_enovathemes']['custom-css-min-1600-max-1919'];
				}
			$dynamic_css .='}';

		$dynamic_css = enovathemes_addons_minify_css($dynamic_css);

        // do not set an empty transient - should help catch private or empty accounts.
        if ( ! empty( $dynamic_css ) ) {
            $dynamic_css = base64_encode(gzcompress ( serialize($dynamic_css) ));
            set_transient( 'dynamic-styles-cached', $dynamic_css, apply_filters( 'null_dynamic_css_cache_time', 0 ) );
        }
    }

    if ( ! empty( $dynamic_css ) ) {
        $dynamic_css = unserialize(gzuncompress(base64_decode($dynamic_css) ));

        $file = get_template_directory() . '/css/dynamic-styles-cached.css';

        if (is_file($file)) {
        	// LOCAL PATCH: this write sat outside the transient-cache branch above, so a
        	// ~270KB file was rewritten on every single request even on a cache hit --
        	// pure disk I/O per page view. Worse, concurrent requests raced on the same
        	// handle and Windows reported the sharing violation as
        	// "file_put_contents(...): Failed to open stream: Permission denied".
        	//
        	// Only write when the contents actually differ (md5 of 270KB is well under a
        	// millisecond against writing 270KB), and take an exclusive lock so any real
        	// write still cannot collide with a concurrent one. The enqueue must stay
        	// unconditional -- the stylesheet is needed on every request either way.
        	// Lost if this plugin is updated.
        	if (md5_file($file) !== md5($dynamic_css)) {
        		file_put_contents($file, $dynamic_css, LOCK_EX);
        	}
        	wp_enqueue_style('dynamic-styles-cached', get_template_directory_uri() . '/css/dynamic-styles-cached.css');
        }

    }

}
add_action( 'wp_enqueue_scripts', 'enovathemes_addons_include_dynamic_styles_cached',20);
?>