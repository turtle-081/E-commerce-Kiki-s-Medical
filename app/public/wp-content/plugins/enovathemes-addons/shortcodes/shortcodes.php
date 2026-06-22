<?php

/*  ELEMENTS
/*------------*/

	/* TYPOGRAPHY
	---------------*/

		/*	et_align
		--------------*/

			function et_align($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'align' => 'wide',
					), $atts)
				);

				$output = '';

				$class   = array();
				$class[] = 'et-align';
				$class[] = 'et-clearfix';
				$class[] = 'align'.$align;

				$output .= '<div class="'.implode(" ",$class).'">'.do_shortcode($content).'</div>';

				return $output;
			}

			add_shortcode('et_align', 'et_align');

		/*	et_heading
		--------------*/

			function et_heading($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'type'             => 'h1',
						'link'             => '',
						'target'           => '_self',
						'text_align'       => 'left',
						'highlight'        => 'false',
						'tablet_text_align'=> 'inherit',
						'mobile_text_align'=> 'inherit',
						'icon' 	           => '',
						'icon_position'    => 'left',
						'mfs'              => 'i',
						'mls'              => 'i',
						'mf'               => 'i',
						'ml'               => 'i',
						'tlf'              => 'i',
						'tll'              => 'i',
						'tpf'              => 'i',
						'tpl'              => 'i',
						'extra_class'      => '',
						'element_id'       => '',
						'animation_type'   => 'curtain',
						'animate'          => 'false',
						'delay'            => '0'
					), $atts)
				);

				static $id_counter = 1;

				$output = '';

				$class   = array();
				$class[] = 'et-heading';
				$class[] = 'text-align-'.$text_align;
				$class[] = 'highlight-'.$highlight;
				$class[] = 'icon-position-'.$icon_position;

				if ($tablet_text_align != 'inherit') {
					$class[] = 'text768-1023-align-'.$tablet_text_align;
				}

				if ($mobile_text_align != 'inherit') {
					$class[] = 'text767-align-'.$mobile_text_align;
				}

				if ($animate == "true") {
					$class[] = 'animate-'.$animate;
					$class[] = $animation_type;
				}

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$attributes   = array();

				if ($animate == "true") {
					$attributes[] = 'data-delay="'.esc_attr(absint($delay)).'"';
				}

				if ($mfs != 'i') {
					$attributes[] = 'data-374-f="'.esc_attr($mfs).'"';
				}
				if ($mls != 'i') {
					$attributes[] = 'data-374-lh="'.esc_attr($mls).'"';
				}

				if ($mf != 'i') {
					$attributes[] = 'data-375-767-f="'.esc_attr($mf).'"';
				}
				if ($ml != 'i') {
					$attributes[] = 'data-375-767-lh="'.esc_attr($ml).'"';
				}

				if ($tpf != 'i') {
					$attributes[] = 'data-768-1023-f="'.esc_attr($tpf).'"';
				}
				if ($tpf != 'i') {
					$attributes[] = 'data-768-1023-lh="'.esc_attr($tpl).'"';
				}

				if ($tlf != 'i') {
					$attributes[] = 'data-1024-1279-f="'.esc_attr($tlf).'"';
				}
				if ($tll != 'i') {
					$attributes[] = 'data-1024-1279-lh="'.esc_attr($tll).'"';
				}

				if (isset($content) && !empty($content)) {
					$output .= '<'.$type.' class="'.implode(" ",$class).'" id="et-heading-'.$element_id.'" '.implode(" ",$attributes).'>';

						if (isset($link) && !empty($link)) {
							$output .= '<a href="'.esc_url($link).'" target="'.esc_attr($target).'">';
						}
							$output .= '<span class="text-wrapper">';

								$content = preg_replace("/_br_/","[et_gap]",$content);

								$output .= '<span class="text">';

									$icon_output = '';

									if (isset($icon) && !empty($icon)) {
										$icon = get_post($icon);
										if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
											if (et_get_icon($icon->guid)) {
												$icon_output = '<span class="icon">'.et_get_icon($icon->guid).'</span>';
								            }
										}
									}

									if ($icon_position == "left" && !empty($icon_output)) {$output .= $icon_output;}
									$output .= do_shortcode($content);
									if ($icon_position == "right" && !empty($icon_output)) {$output .= $icon_output;}

								$output .= '</span>';

								if ($animation_type == "curtain") {
									$output .= '<span class="curtain"></span>';
								}
							$output .= '</span>';

						if (isset($link) && !empty($link)) {
							$output .= '</a>';
						}

					$output .= '</'.$type.'>';
				}

				$id_counter++;

				return $output;
			}

			add_shortcode('et_heading', 'et_heading');

		/*	et_blockquote
		--------------*/

			function et_blockquote($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'image'       => '',
						'text'        => '',
						'author'      => '',
						'title'       => '',
						'extra_class' => '',
						'element_id'  => '',
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';

				$class   = array();
				$class[] = 'et-blockquote';

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($text) && !empty($text)) {
					$output .= '<div class="'.implode(" ",$class).'" id="et-blockquote-'.$element_id.'">';

						$output .= '<blockquote>'.do_shortcode($text).'</blockquote>';
						$output .= '<div class="author-wrapper et-clearfix">';
							if ($image) {

								$image = wp_get_attachment_image_src($image, 'full');

								$image_src      = $image[0];
								$image_width    = $image[1];
								$image_height   = $image[2];
								$image_caption  = get_the_post_thumbnail_caption($image);
								$image_alt 	    = (empty($image_caption)) ? get_bloginfo('name') : $image_caption;

								$output .= '<img src="'.esc_url($image_src).'" width="'.$image_width.'" height="'.$image_height.'" alt="'.$image_alt.'" />';

							}
							$output .= '<div class="author-info-wrapper et-clearfix">';
								if (isset($author) && !empty($author)) {
									$output .= '<h5 class="author">'.esc_html($author).'</h5>';
								}
								if (isset($title) && !empty($title)) {
									$output .= '<span class="title">'.esc_html($title).'</span>';
								}
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				}
				$id_counter++;

				return $output;
			}

			add_shortcode('et_blockquote', 'et_blockquote');

	/* UI
	---------------*/

		/*	et_menu
		--------------*/

			function et_menu($atts, $content = null) {

				global $propharm_enovathemes;

				$main_color = (isset($GLOBALS['propharm_enovathemes']['main-color']) && $GLOBALS['propharm_enovathemes']['main-color']) ? $GLOBALS['propharm_enovathemes']['main-color'] : '#15a9e3';

				extract(shortcode_atts(
					array(
						'menu'            		=> '',
						'type'                  => 'horizontal',
						'align'                 => 'none',
						'menu_hover'            => 'none',
						'submenu_appear'        => 'none',
						'submenu_appear_from'   => 'bottom',
						'submenu_shadow'        => 'false',
						'submenu_indicator'     => 'false',
						'submenu_separator'     => 'false',
						'menu_separator'        => 'false',
						'menu_color'            => '',
						'menu_color_hover'      => $main_color,
						'submenu_submenu_indicator' => 'false',
						'extra_class'     		=> '',
						'element_id'            => '',
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-menu-container';
				$class[] = 'nav-menu-container';
				$class[] = 'type-'.$type;
				$class[] = 'menu-align-'.$align;
				$class[] = 'menu-hover-'.$menu_hover;
				$class[] = 'submenu-appear-'.$submenu_appear;
				$class[] = 'submenu-appear-from-'.$submenu_appear_from;
				$class[] = 'submenu-shadow-'.$submenu_shadow;
				$class[] = 'tl-submenu-ind-'.$submenu_indicator;
				$class[] = 'sl-submenu-ind-'.$submenu_submenu_indicator;
				$class[] = 'separator-'.$submenu_separator;
				$class[] = 'top-separator-'.$menu_separator;

				if($menu_hover == "underline") {

					if (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) {
						$link_after  = '<span class="effect"></span></span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>';
		            }

				} else {

					if (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) {
						$link_after  = '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span><span class="effect"></span>';
		            }


				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($menu) && !empty($menu)) {
					$menu_arg = array(
						'menu'  => $menu,
						'menu_class'      => 'et-menu nav-menu et-clearfix',
						'menu_id'         => 'et-menu-'.$element_id,
						'container'       => 'nav',
						'container_class' => implode(" ", $class),
						'container_id'    => 'et-menu-container-'.$element_id,
						'items_wrap'      => '<ul id="%1$s" class="%2$s" data-color="'.esc_attr($menu_color).'" data-color-hover="'.esc_attr($menu_color_hover).'">%3$s</ul>',
						'echo'            => false,
						'link_before'     => '<span class="txt">',
						'link_after'      => $link_after,
						'depth'           => 10,
						'walker'          => new et_scm_walker
					);
					

					$output .= wp_nav_menu($menu_arg);

					$id_counter++;

					return $output;

				}
			}

			add_shortcode('et_menu', 'et_menu');

		/*  et_button
	    --------------*/

		    function et_button( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'button_text' 		    => '',
					'button_link' 	        => '',
					'target'                => '_self',
					'button_link_modal'     => 'false',
					'button_shadow' 	    => 'false',
					'button_style' 	        => 'normal',
					'button_type'           => 'default',
					'button_size'           => 'medium',
					'button_size_custom'    => 'false',
					'button_color'          => '#ffffff',
					'button_color_hover'    => '#ffffff',
					'icon' 	                => '',
					'icon_position'         => 'left',
					'animate_hover' 	    => 'none',
					'animate_hover_outline' => 'none',
					'click_smooth' 	        => 'false',
					'animation'             => 'none',
					'animation_delay'       => '',
					'extra_class'           => '',
		            'element_id'            => '',
				), $atts));

				static $id_counter = 1;

	            $output      = '';

	            $class = array();


				if ($button_style == "outline") {
					$animate_hover = $animate_hover_outline;
				}

				if ($button_size_custom == "true") {
	            	$button_size = 'custom';
	            }

	            $class[] = 'et-button';
	            $class[] = 'icon-position-'.$icon_position;
	            $class[] = 'modal-'.$button_link_modal;
	            $class[] = 'hover-'.$animate_hover;
				$class[] = 'smooth-'.$click_smooth;
	            $class[] = 'shadow-'.$button_shadow;
	            $class[] = $animation;
				$class[] = $button_type;
				$class[] = $button_style;
				$class[] = $button_size;

				if ($button_link_modal == "true") {
					$target = "_self";
				}

				if (isset($click_smooth) && $click_smooth == "true") {
					$class[] = 'click-smooth';
				}

				if (isset($extra_class) && !empty($extra_class)) {
					$class[] = $extra_class;
				}

	            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if ($button_link_modal == "true") {$target = "_self";}

				$attributes   = array();
				$attributes[] = 'target="'.esc_attr($target).'"';
				$attributes[] = 'href="'.esc_url($button_link).'"';
				$attributes[] = 'data-effect="'.esc_attr($animate_hover).'"';

				if ($animation != "none") {

					if (
						$animation != 'top-to-bottom' &&
						$animation != 'bottom-to-top' &&
						$animation != 'left-to-right' &&
						$animation != 'right-to-left' &&
						$animation != 'appear'
					) {
						wp_enqueue_style( 'vc_animate-css' );
					}

					$attributes[] = 'data-del="'.esc_attr($animation_delay).'"';
					$class[]      = 'wpb_animate_when_almost_visible';

				}

				if ($animate_hover == 'fill') {
					$attributes[] = 'data-color="'.esc_attr($button_color).'"';
					$attributes[] = 'data-color-hover="'.esc_attr($button_color_hover).'"';
				}

				if (isset($button_text) && !empty($button_text) && isset($button_link) && !empty($button_link)) {
					$output .='<a id="et-button-'.$element_id.'" class="'.implode(" ", $class).'" '.implode(" ", $attributes).'>';

						$icon_output = '';

						if (isset($icon) && !empty($icon)) {

							$icon = get_post($icon);

							if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
								if (et_get_icon($icon->guid)) {
									$icon_output = '<span class="icon">'.et_get_icon($icon->guid).'</span>';
					            }
							}

						}

						if ($icon_position == "left" && !empty($icon_output)) {$output .= $icon_output;}
						$output .='<span class="text">'.esc_attr($button_text).'</span>';
						if ($icon_position == "right" && !empty($icon_output)) {$output .= $icon_output;}


						$output .='<span class="button-back">';
							$output .= '<span class="regular"></span>';
							if ($animate_hover == "fill") {
						    	$output .= '<span class="hover"></span>';
							}
						$output .='</span>';

					$output .='</a>';
				}

				$id_counter++;

				return $output;
			}
			add_shortcode('et_button', 'et_button');

		/*	et_separator
		--------------*/

			function et_separator($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'type'        => 'solid',
						'align'       => 'left',
						'extra_class' => '',
						'element_id'  => '',
						'animate'     => 'false',
						'start_delay' => '',
						'width'       => '',
						'height'      => '',
						'rv'          => '',
					), $atts)
				);

				static $id_counter = 1;

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$responsive_visibility = array();

				if (!empty($rv)) {
					$rv = explode(',', $rv);

					foreach ($rv as $key) {
						$responsive_visibility[] = 'hide'.$key;
					}

				}

				$class[] = 'et-separator';
				$class[] = 'et-clearfix';
				$class[] = 'animate-'.$animate;
				$class[] = $type;
				$class[] = $align;

				if (isset($width) && !empty($width)) {
					if ($width > $height) {
						$class[] = 'horizontal';
					} else {
						$class[] = 'vertical';
					}
				} else {
					$class[] = 'horizontal';
				}

		        $element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class[] = 'et-separator-'.$element_id;

				if (!empty($responsive_visibility)) {
					$class = array_merge($class,$responsive_visibility);
				}

				$output = '<div class="'.implode(" ", $class).'" data-delay="'.esc_attr($start_delay).'"><div class="line"></div></div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_separator', 'et_separator');

		/*	et_icon_separator
		--------------*/

			function et_icon_separator($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'type'        => 'solid',
						'align'       => 'left',
						'extra_class' => '',
						'element_id'  => '',
						'width'       => '120',
						'height'      => '',
						'icon'        => '',
						'icon_size'   => 'small',
					), $atts)
				);

				static $id_counter = 1;

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-icon-separator';
				$class[] = 'et-clearfix';
				$class[] = $align;

		        $element_id = (!empty($element_id)) ? $element_id : $id_counter;

		        $class[] = 'et-icon-separator-'.$element_id;

				if (isset($icon) && !empty($icon)) {

					$icon = get_post($icon);

					if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

						if (et_get_icon($icon->guid)) {
							$icon_output = et_get_icon($icon->guid);
			            }

						$output = '';

						$output .= '<div class="'.implode(" ", $class).'" >';
							if ($align != 'left') {
								$output .= '<span class="left line '.$icon_size.'"></span>';
							}
							$output .= '<span class="icon '.$icon_size.'">'.$icon_output.'</span>';
							if ($align != 'right') {
								$output .= '<span class="right line '.$icon_size.'"></span>';
							}
						$output .= '</div>';

					}

				}

				$id_counter++;

				return $output;
			}
			add_shortcode('et_icon_separator', 'et_icon_separator');

		/*  et_icon
	    --------------*/

	        function et_icon($atts, $content = null) {

	            extract(shortcode_atts(
	                array(
						'icon'                  => '',
						'size'                  => 'medium',
						'icon_link'             => '',
						'icon_background_color' => '',
						'icon_border_color'     => '',
						'target' 	            => '_self',
						'click'                 => 'false',
						'shadow'                => '',
	                    'extra_class'           => '',
	                    'element_id'            => '',
	                ), $atts)
	            );


	            static $id_counter = 1;

	            $output = $icon_output = '';

	            $class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

	            $class[] = 'et-icon';
				$class[] = 'size-'.$size;
	            $class[] = 'click-'.$click;
	            $class[] = 'shadow-'.$shadow;

	            if (
	            	(isset($icon_background_color) && !empty($icon_background_color)) ||
	            	(isset($icon_border_color) && !empty($icon_border_color))
	            ) {
	            	$class[] = 'back-active';
	            }

	            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

	            $class[] = 'et-icon-'.$element_id;

				if (isset($icon) && !empty($icon)) {

					$icon = get_post($icon);

					if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

						if (et_get_icon($icon->guid)) {
							$icon_output = et_get_icon($icon->guid);
			            }

						$size = 40;

						if ($size == "small") {
							$size = 32;
						}elseif ($size == "large") {
							$size = 48;
						}

						$s2  = $size/2;
						$size_hover = $size + 4;

						$d 	     = 'M'.$s2.','.($size + 2).'A'.$s2.','.$s2.',0,0,1,'.$s2.',2A'.$s2.','.$s2.',0,0,1,'.$s2.','.($size + 2).'Z';
						$d_hover = 'M'.$s2.','.$size_hover.'C -2 '.($size_hover+2).',-2 -2,'.$s2.' 0,C '.$size_hover.' -2,'.$size_hover.' '.($size_hover+2).','.$s2.' '.$size_hover.'Z';

						$icon_back   = '<svg viewBox="0 0 '.$size.' '.$size.'" class="icon-back">';
							$icon_back .='<path d="'.$d.'" data-hover="'.$d_hover.'"/>';
						$icon_back .='</svg>';

			            $output .= '<div class="'.implode(" ", $class).'">';
			            	if (!empty($icon_link)) {
			            		$output .= '<a href="'.esc_url($icon_link).'" target="'.esc_attr($target).'">';
									$output .= $icon_output;
									$output .= $icon_back;
								$output .= '</a>';
			            	} else {
								$output .= $icon_output;
								$output .= $icon_back;
			            	}
			            $output .= '</div>';

					} else {
						$output .= esc_html__("Please upload svg");
					}

		            $id_counter++;

		            return $output;

		        }
	        }

	        add_shortcode('et_icon', 'et_icon');

		/*	et_icon_list
		--------------*/

			function et_icon_list($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'icon_size'          => 'medium',
						'icon'               => '',
						'element_id'         => '',
						'icon_background_color'    => '',
						'icon_border_width'  => '0',
						'shadow'             => '',
						'animate'            => '',
					    'delay'              => '',
						'extra_class'        => ''
					), $atts)
				);


				$output = "";

				static $id_counter = 1;

				$class = array();
				$attributes = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-icon-list';
				$class[] = $icon_size;

				if (isset($shadow) && $shadow == 'true') {
					$class[] = 'shadow';
				}

				if ((isset($icon_border_width) && !empty($icon_border_width)) || isset($icon_background_color) && !empty($icon_background_color)) {
					$class[] = 'full';
				}

				if ($animate == "true") {
					$attributes[] = 'data-delay="'.esc_attr(absint($delay)).'"';
				}

				if ($animate == "true") {
					$class[] = 'animate-'.$animate;
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($icon) && !empty($icon)) {

					$icon = get_post($icon);

					if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

						if (et_get_icon($icon->guid)) {
							$icon_output = et_get_icon($icon->guid);
			            }

						$output .= '<ul id="et-icon-list-'.$element_id.'" class="'.implode(" ", $class).'" '.implode(" ", $attributes).'>';
							$split = preg_split("/(\r?\n)+|(<br\s*\/?>\s*)+/", $content);
							foreach($split as $haystack) {
					            $output .= '<li>';
					            	$output .= '<div class="icon-wrap">';
						            	$output .= '<div class="et-icon size-'.esc_attr($icon_size).'">';
					            			$output .= $icon_output;
					            		$output .= '</div>';
				            		$output .= '</div>';
					            	$output .= '<div>' . do_shortcode($haystack) . '</div>';
					            $output .= '</li>';
					        }
					    $output .= '</ul>';
					}
				}

				$id_counter++;

				return $output;
			}
			add_shortcode('et_icon_list', 'et_icon_list');

		/*	et_accordion
		--------------*/

			function et_accordion($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'collapsible' => 'false',
						'element_id'  => '',
					), $atts)
				);

				$output = '';
				static $id_counter = 1;

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$output .= '<div class="et-accordion-wrapper">';
					$output .='<div id="et-accordion-'.$element_id.'" class="et-accordion et-clearfix collapsible-'.esc_attr($collapsible).'">';
						$output .= do_shortcode($content);
					$output .= '</div>';
				$output .= '</div>';

				$id_counter++;

				return $output;

			}
			add_shortcode('et_accordion', 'et_accordion');

			function et_accordion_item($atts, $content = null) {

				extract(shortcode_atts(array(
					'title' => '',
					'icon'  => '',
					'open'  => 'false'
				), $atts));

				$output = '';
				static $id_counter = 1;

				$class = array();


				$class[] = 'toggle-title';
				$class[] = 'et-clearfix';

				if($open == 'true'){
					$class[] = "active";
				}

				if (isset($icon) && !empty($icon)) {
					$class[] = 'icon';
				}
				if($open == 'true'){
					$output .= '<div class="toggle-content-group active">';
				} else {
					$output .= '<div class="toggle-content-group">';
				}

					$output .= '<div class="'.implode(' ', $class).'">';

						if (isset($icon) && !empty($icon)) {
							$icon = get_post($icon);
							if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
								if (et_get_icon($icon->guid)) {
									$icon_output = et_get_icon($icon->guid);
					            }
								$output .= '<span class="toggle-icon">'.$icon_output.'</span>';
							}
						}

						if (isset($title) && !empty($title)) {
							$output .= esc_attr($title);
						}

						if (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) {
							$output .= '<span class="toggle-ind">'.et_get_theme_icon()['arrow'].'</span>';
			            }

						
						
					$output .= '</div> ';

					$output .= '<div id="toggle-content-'.$id_counter.'" class="toggle-content">';
						$output .= '<div class="toggle-content-inner et-clearfix">';
					    	$output .= do_shortcode($content);
					    $output .= '</div> ';
					$output .= '</div>';
				$output .= '</div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_accordion_item', 'et_accordion_item');

		/*	et_tab
		--------------*/

			function et_tab($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'type'   => 'horizontal',
						'center' => 'false',
						'element_id'  => '',
					), $atts)
				);

				$output = '';
				static $id_counter = 1;

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class = array();

				$class[] = 'et-tab';
				$class[] = 'et-clearfix';
				$class[] = 'center-'.esc_attr($center);
				$class[] = $type;

				$output .= '<div class="et-tab-wrapper">';
					$output .='<div id="et-tab-'.$element_id.'" class="'.implode(" ", $class).'">';
						$output .= do_shortcode($content);
					$output .= '</div>';
				$output .= '</div>';

				$id_counter++;

				return $output;

			}
			add_shortcode('et_tab', 'et_tab');

			function et_tab_item($atts, $content = null) {

				extract(shortcode_atts(array(
					'title'  => '',
					'icon'   => '',
					'active' => 'false',
				), $atts));

				$output = '';
				$class  = '';

				static $id_counter = 1;

				if($active == 'true'){
					$active = "active";
				}

				if (isset($icon) && !empty($icon)) {
					$class = 'icon';
				}


				$output .= '<div data-target="tab-'. $id_counter .'" class="'.esc_attr($active).' '.esc_attr($class).' tab et-clearfix">';
					if (isset($icon) && !empty($icon)) {
						$icon = get_post($icon);
						if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
							if (et_get_icon($icon->guid)) {
								$icon_output = et_get_icon($icon->guid);
				            }
							$output .= '<span class="icon">'.$icon_output.'</span>';
						}
					}
					if (isset($title) && !empty($title)) {
						$output .= esc_html($title);
					}
				$output .= '</div> ';
				$output .= '<div id="tab-'.$id_counter.'" class="tab-content et-clearfix">';
				    $output .= do_shortcode($content);
				$output .= '</div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_tab_item', 'et_tab_item');

		/*	et_stagger_box
		--------------*/

			function et_stagger_box($atts, $content = null) {

				$main_color = (isset($GLOBALS['propharm_enovathemes']['main-color']) && $GLOBALS['propharm_enovathemes']['main-color']) ? $GLOBALS['propharm_enovathemes']['main-color'] : '#15a9e3';

				extract(shortcode_atts(
					array(
						'element_id'    => '',
						'extra_class'   => '',
						'stagger'       => 'top',
						'delay'         => '0',
						'interval'      => '50',
					), $atts)
				);

				$output = "";

				static $id_counter = 1;

				$class      = array();
				$attributes = array();
				$padding_data = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-stagger-box';


				$attributes[] = 'data-delay="'.esc_attr($delay).'"';
				$attributes[] = 'data-interval="'.esc_attr($interval).'"';


				if (isset($stagger)) {
					$attributes[] = 'data-stagger="'.$stagger.'"';
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class[] = 'et-stagger-box-'.$element_id;

				$output .='<div id="et-stagger-box-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
					$output .='<div class="content">'.do_shortcode($content).'</div>';
				$output .='</div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_stagger_box', 'et_stagger_box');

	/* SOCIAL
	---------------*/

		/*	et_social_links
		--------------*/

			function et_social_links($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'extra_class'     		=> '',
						'element_id'            => '',
						'target' 				=> '_self',
						'styling_original'      => 'false',
						'size'                  => 'small',
						'icon_background_color' => '',
						'icon_border_color'     => '',
						'shadow'                => '',
						'stretching'            => 'false'
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-social-links';
				$class[] = 'styling-original-'.$styling_original;
				$class[] = 'size-'.$size;
				$class[] = 'stretching-'.$stretching;

				if ((!isset($icon_background_color) || empty($icon_background_color)) && (!isset($icon_border_color) || empty($icon_border_color)) && $styling_original == 'false') {
					$class[] = 'free';
				}

				if (isset($shadow) && !empty($shadow)) {
					$class[] = 'shadow-true';
				}


				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$output .= '<div id="et-social-links-'.$element_id.'" class="'.implode(" ", $class).'">';
					foreach($atts as $social => $href) {
						if($href && $social != 'target' && $social != 'icon_color' && $social != 'icon_color_hover' && $social != 'icon_background_color' && $social != 'icon_background_color' && $social != 'icon_background_color_hover' && $social != 'icon_border_color' && $social != 'icon_border_color_hover' && $social != 'icon_border_width' && $social != 'styling_original' && $social != 'shadow' && $social != 'element_id' && $social != 'element_css' && $social != 'size' && $social != 'stretching') {
							$output .='<a class="'.$social.'" href="'.$href.'" target="'.esc_attr($target).'" title="'.$social.'">';
								if (et_get_social_icon() && isset(et_get_social_icon()[$social])) {
					                $output .= et_get_social_icon()[$social];
					            }
							$output .='</a>';
						}
					}
				$output .= '</div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_social_links', 'et_social_links');

		/*	et_social_share
		--------------*/

			function et_social_share($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'extra_class'     		=> '',
						'element_id'            => '',
						'target' 				=> '_self',
						'styling_original'      => 'false',
						'icon_background_color' => '',
						'icon_border_color'     => '',
						'shadow'                => '',
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-social-share';
				$class[] = 'et-social-links';
				$class[] = 'styling-original-'.$styling_original;

				if (isset($shadow) && !empty($shadow)) {
					$class[] = 'shadow-true';
				}

				if ((!isset($icon_background_color) || empty($icon_background_color)) && (!isset($icon_border_color) || empty($icon_border_color)) && $styling_original == 'false') {
					$class[] = 'free';
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$output = '<div id="et-social-share-'.$element_id.'" class="'.implode(" ", $class).'">';
		            $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
		            $output .= '<div class="social-links et-clearfix">';

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

				$id_counter++;

				return $output;
			}
			add_shortcode('et_social_share', 'et_social_share');

		/*	et_social_share
		--------------*/

			function et_instagram($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'images' => ''
					), $atts)
				);

				$output = '';

				if (!empty($images)) {
					$output .= '<div class="et-carousel nav-pos-side" data-columns="6"><div class="insta-placeholder-grid">';
						$images = explode(',', $images);
						foreach ($images as $image) {
								$output .= '<div>'.enovathemes_addons_inline_image_placeholder($image,'full').'</div>';
						}
					$output .= '</div></div>';
				} else {

					if(shortcode_exists('instagram-feed')){
						$output .= do_shortcode('[instagram-feed]');
					} else {
						$output .= '<p class="insta-placeholder">'.sprintf('%s <a target="_blank" href="%s">%s</a>',esc_html__('Install and activate','enovathemes-addons'),esc_url( 'https://wordpress.org/plugins/instagram-feed/' ),esc_html__('Smash Balloon Social Photo Feed','enovathemes-addons')).'<p>';
						$output .= '<div class="et-carousel nav-pos-side" data-columns="4"><div class="insta-placeholder-grid">';
						for ($i=1; $i < 7; $i++) {
							$output .= '<div><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200"></div>';
						}
						$output .= '</div></div>';
					}

				}

				return $output;
			}
			add_shortcode('et_instagram', 'et_instagram');

		/*	et_autocomplete_address
		--------------*/

			function et_autocomplete_address($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'extra_class'=> '',
						'element_id' => '',
						'form_id' 	 => '',
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-autocomplete-address';

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$output = '<form id="et-autocomplete-address-'.$element_id.'" class="'.implode(" ", $class).'">';
		            $output .= '<input type="text" id="'.esc_attr($form_id).'" value="">';
		        $output .= '</form>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_autocomplete_address', 'et_autocomplete_address');

		/*  et_mailchimp
		/*------------*/

			function et_mailchimp($atts, $content = null) {

				extract(shortcode_atts(
					array(
			 			'list'        => '',
			 			'layout'      => 'simple',
			 			'terms'       => '',
			 			'element_id'  => '',
					), $atts)
				);

				$output = '';

				static $id_counter = 1;

					$element_id = (!empty($element_id)) ? $element_id : $id_counter;

					$args = array(
						'before_widget' => '<div id="et-mailchimp-'.$element_id.'" class="et-mailchimp '.$layout.' widget_mailchimp">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget_title">',
		                'after_title'   => '</h5>',
					);

					$name = ($layout == 'simple') ? false : true;

					$instance = array(
						'title'                => '',
			 			'description'          => '',
			 			'list'                 => $list,
			 			'first_name'           => $name,
			 			'terms'                => $terms,
			 			'last_name'            => false,
			 			'phone'                => false,
			 			'required_first_name'  => false,
			 			'required_last_name'   => false,
			 			'required_phone'       => false,
					);

					$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Mailchimp', $instance,$args);

				$id_counter++;

				return $output;
			}

			add_shortcode('et_mailchimp', 'et_mailchimp');

	/* SELFHOSTED
	---------------*/

		/*  et_icon_box
		/*------------*/

			function et_icon_box( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'icon_size'   		 => 'large',
					'icon'        		 => '',
					'title'              => '',
					'title_tag'          => 'default',
					'link'               => '',
					'target'             => '_self',
					'icon_position'      => 'top',
					'icon_alignment'     => 'left',
					'icon_back_color'    => '',
					'icon_border_color'  => '',
					'icon_border_width'  => '0',
					'box_color'          => '',
					'box_color_hover'    => '',
					'animation'          => 'none',
					'shadow'             => '',
					'crp'                => '',
					'element_id'         => '',
					'extra_class'        => '',
				), $atts));

				$output = '';

				$link_before = "";
				$link_after  = "";

				static $id_counter = 1;

				$class      = array();
				$attributes = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				if (isset($link) && !empty($link)) {
					$link_before = '<a href="'.$link.'" target="'.esc_attr($target).'">';
					$link_after  = '</a>';
					$class[] = 'link';
				}

				$class[] = 'et-icon-box';
				$class[] = 'vci';
				$class[] = 'icon-position-'.esc_attr($icon_position);
				$class[] = 'icon-alignment-'.esc_attr($icon_alignment);
				$class[] = 'animation-'.esc_attr($animation);
				$class[] = esc_attr($icon_size);

				if (!isset($content) || empty($content)) {
					$class[] = 'no-content';
				}

				if (isset($shadow) && $shadow == 'true') {
					$class[] = 'shadow';
				}

				if (
					(isset($icon_border_width) && !empty($icon_border_width)) ||
					(isset($icon_back_color) && !empty($icon_back_color))
				) {
					$class[] = 'full';
				}

				if (!empty($crp)) {
					$crp = explode(',', $crp);

					$query_array = array();

					foreach ($crp as $key => $value) {
						array_push($query_array, explode(':', $value));
					}

					foreach ($query_array as $key => $value) {
						if ($value[1] != "i") {
							$attributes[] = 'data-'.$value[0].'-l="'.$value[1].'" ';
						}
						if ($value[2] != "i") {
							$attributes[] = 'data-'.$value[0].'-r="'.$value[2].'" ';
						}
					}
				}

				$color = '';

				if (empty($box_color_hover) && !empty($box_color)) {
					$color = $box_color;
				}

				if ((empty($box_color) && !empty($box_color_hover)) || (!empty($box_color) && !empty($box_color_hover))) {
					$color = $box_color_hover;
				}

				if (!empty($color)) {
					$attributes[] = 'data-color="'.esc_attr($color).'"';
				}

				$attributes[] = 'data-effect="'.esc_attr($animation).'"';

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$icon_output = '';

				if (isset($icon) && !empty($icon)) {

					$icon = get_post($icon);

					if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
						$icon_output .= '<div class="et-icon '.esc_attr($icon_size).'">';
							if (et_get_icon($icon->guid)) {
								$icon_output .= et_get_icon($icon->guid);
				            }
							if ((isset($icon_border_width) && !empty($icon_border_width)) ||
								(isset($icon_back_color) && !empty($icon_back_color))) {
								$icon_output .='<div class="icon-back"></div>';
		                    }
						$icon_output .= '</div>';
					}

				}

		        $output .='<div id="et-icon-box-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';

	        		$output .= $link_before;

	        			$output .='<div class="before"></div>';

		        		$output .='<div class="et-icon-box-inner et-clearfix '.esc_attr($icon_size).'">';

		        			if ($icon_position == 'left' || $icon_position == "top") {
		        				$output .= $icon_output;
		        			}

							$output .='<div class="et-icon-content">';
								if (isset($title) && !empty($title)) {

									$title = preg_replace("/_br_/","[et_gap]",$title);

									if ($title_tag == 'default') {
										$output .='<h4 class="et-icon-box-title default">'.do_shortcode($title).'</h4>';
									} else {
										$output .='<'.$title_tag.' class="et-icon-box-title">'.do_shortcode($title).'</'.$title_tag.'>';
									}
								}
								if (isset($content) && !empty($content)) {
									$output .='<p class="et-icon-box-content">'.do_shortcode(preg_replace("/_br_/","[et_gap]",$content)).'</p>';
								}
							$output .='</div>';

							if ($icon_position == 'right') {
		        				$output .= $icon_output;
		        			}

						$output .='</div>';

					$output .= $link_after;

				$output .='</div>';

				$id_counter++;

				return $output;

			}
			add_shortcode('et_icon_box', 'et_icon_box');

		/*  et_icon_box_container
		/*------------*/

			function et_icon_box_container( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'animation'         => 'none',
					'gap'               => 'false',
					'guides'            => 'false',
					'border'            => 'false',
					'shadow'            => '',
					'columns'           => '1',
					'height'            => '0',
					'vertical_align'    => 'top',
					'content_alignment'  => 'left',
					'element_id'        => '',
					'extra_class'        => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

				$class 	    = array();
				$attributes = array();

				$class[] = 'columns-'.$columns;
				$class[] = 'et-icon-box-container';
				$class[] = 'gap-'.$gap;
				$class[] = 'guides-'.$guides;
				$class[] = 'border-'.$border;
				$class[] = 'content-alignment-'.esc_attr($content_alignment);

				if ($height != "0") {
					$class[] = 'full';
				}

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				if (isset($shadow) && !empty($shadow)) {
					$class[] = 'shadow';
				}

				$attributes[] = 'data-animation="'.$animation.'"';

				if (isset($vertical_align) && !empty($vertical_align)) {
					$class[] = $vertical_align;
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

		        $output .='<div id="et-icon-box-container-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
	        		$output .=do_shortcode($content);
				$output .='</div>';

				$id_counter++;

				return $output;


			}
			add_shortcode('et_icon_box_container', 'et_icon_box_container');

		/*  et_step_box
		/*------------*/

			function et_step_box( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'title'       => '',
					'title_tag'   => 'h6',
					'element_id'  => '',
					'extra_class' => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

				$class = array();
				$attributes = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-step-box';

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

		        $output .='<div id="et-step-box-'.$element_id.'" class="'.implode(' ', $class).'">';
		        		$output .='<div class="et-step-box-inner et-clearfix">';
							$output .='<div class="step-count"></div>';
							if (isset($title) && !empty($title)) {
								$title = preg_replace("/_br_/","[et_gap]",$title);
								$output .='<'.$title_tag.' class="et-step-box-title">'.do_shortcode($title).'</'.$title_tag.'>';
							}
							if (isset($content) && !empty($content)) {
								$output .='<p class="et-step-box-content">'.do_shortcode($content).'</p>';
							}
						$output .='</div>';
				$output .='</div>';

				$id_counter++;

				return $output;


			}
			add_shortcode('et_step_box', 'et_step_box');

		/*  et_step_box_container
		/*------------*/

			function et_step_box_container( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'columns'        => '1',
					'extra_class'    => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'columns-'.$columns;

				$class[] = 'et-step-box-container';

		        $output .='<div id="et-step-box-container-'.$id_counter.'" class="'.implode(' ', $class).'">';
	        		$output .=do_shortcode($content);
				$output .='</div>';

				$id_counter++;

				return $output;


			}
			add_shortcode('et_step_box_container', 'et_step_box_container');

		/*	et_carousel
		--------------*/

			function et_carousel($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'columns'             => '1',
						'navigation_type'     => 'arrows',
						'navigation_position' => 'top',
						'autoplay'            => 'false',
					), $atts)
				);

				$output = '';

				static $id_counter = 1;

				$attributes = array();

				$attributes[] = 'data-nav="'.$navigation_type.'"';
				$attributes[] = 'data-autoplay="'.$autoplay.'"';
				$attributes[] = 'data-columns="'.$columns.'"';

				$output .='<div id="et-carousel-'.$id_counter.'" class="et-carousel nav-pos-'.esc_attr($navigation_position).' '.esc_attr($navigation_type).'" '.implode(' ', $attributes).'>';
					$output .= '<div class="slides">';
						$output .= do_shortcode($content);
					$output .= '</div>';
				$output .= '</div>';

				$id_counter++;

				return $output;

			}
			add_shortcode('et_carousel', 'et_carousel');

			function et_carousel_item($atts, $content = null) {

				$output = '';

				$output .='<div class="et-carousel-item et-clearfix">';
					$output .= do_shortcode($content);
				$output .='</div>';

				return $output;
			}
			add_shortcode('et_carousel_item', 'et_carousel_item');

		/*  et_pricing_table
		/*------------*/

			function et_pricing_table_container($atts, $content = null) {

				extract(shortcode_atts(array(
					'columns'     => '1',
					'element_id'  => ''
				), $atts));

				static $id_counter = 1;

				$output = '';

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class   = array();
				$class[] = 'columns-'.$columns;
				$class[] = 'gap-true';

				$output .='<div id="et-pricing-table-container'.$element_id.'" class="et-pricing-table-container '.implode(' ', $class).'">';
					$output .=do_shortcode($content);
				$output .='</div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_pricing_table_container', 'et_pricing_table_container');

			function et_pricing_table($atts, $content = null) {

				$main_color = (isset($GLOBALS['propharm_enovathemes']['main-color']) && $GLOBALS['propharm_enovathemes']['main-color']) ? $GLOBALS['propharm_enovathemes']['main-color'] : '#15a9e3';

				extract(shortcode_atts(array(
					'color'       => $main_color,
					'highlight'   => 'false',
					'title'	      => '',
					'currency'    => '',
					'price'       => '',
					'plan'        => '',
					'button_text' => '',
					'button_link' => '',
					'target'      => '_self',
					'label'       => '',
					'element_id'  => ''
				), $atts));


				static $id_counter = 1;

				$output = '';

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$output .='<div id="et-pricing-table-'.$element_id.'" class="et-pricing-table highlight-'.$highlight.'" data-color="'.esc_attr($color).'">';

					$output .='<div class="pricing-table-inner">';

						$output .='<div class="pricing-table-head">';


							if (isset($title) && !empty($title)) {
								$output .= '<h4 class="title in">'.esc_attr($title).'</h4>';
							}
							if (isset($plan) && !empty($plan)) {
								$output .= '<div class="plan in">'.esc_attr($plan).'</div>';
							}

							if (isset($label) && !empty($label)) {
								$output .= '<span class="label">'.esc_attr($label).'</span>';
							}

							$output .='<div class="pricing-table-price in">';
								if (isset($currency) && !empty($currency)) {
									$output .= '<span class="currency">'.esc_attr($currency).'</span>';
								}
								if (isset($price) && !empty($price)) {
									$output .= '<span class="price">'.esc_attr($price).'</span>';
								}
							$output .='</div>';
						$output .='</div>';

						$output .='<div class="pricing-table-body">';
							$output .='<ul>';
								$split = preg_split("/(\r?\n)+|(<br\s*\/?>\s*)+/", $content);
								foreach($split as $haystack) {
						            $output .= '<li class="in">';
						            	$output .= $haystack;
						            $output .= '</li>';
						        }
					        $output .='</ul>';
				        $output .='</div>';

				        if (isset($button_text) && !empty($button_text) && isset($button_link) && !empty($button_link)) {
				        	$output .='<div class="pricing-table-footer in">';

				        		$button_args = array(
				        			'button_text' 	=> $button_text,
									'button_link' 	=> $button_link,
									'target'        => $target,
									'animate_hover' => 'scale',
				        			'button_size' 	=> 'medium',
						            'element_id'    => $element_id,
				        		);

				        		$button_args_string = array();


				        		foreach ($button_args as $key => $value) {
				        			array_push($button_args_string, $key.'="'.$value.'"');
				        		}

				        		$output .= do_shortcode('[et_button '.implode(' ', $button_args_string).']');


							$output .='</div>';
						}

					$output .='</div>';

				$output .='</div>';

				$id_counter++;

				return $output;
			}
			add_shortcode('et_pricing_table', 'et_pricing_table');

		/*	et_testimonial
		--------------*/

			function et_testimonial_container($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'columns'         => '1',
						'navigation_type' => 'arrows',
						'autoplay'        => 'false',
						'element_id'      => '',
					), $atts)
				);

				$output = '';

				static $id_counter = 1;

				$attributes = array();
				$class      = array();

				$attributes[] = 'data-nav="'.$navigation_type.'"';
				$attributes[] = 'data-autoplay="'.$autoplay.'"';
				$attributes[] = 'data-columns="'.$columns.'"';

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class[] = 'et-testimonial-container';
				$class[] = 'et-carousel';
				if ($navigation_type =! 'both') {
					$class[] = 'nav-pos-side';
				}
				$class[] = esc_attr($navigation_type);

				if ($columns != 1) {
					$class[] = 'mult';
				}

				$output .='<div id="et-testimonial-container-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
					$output .= '<div class="slides">';
						$output .= do_shortcode($content);
					$output .= '</div>';
				$output .= '</div>';

				$id_counter++;

				return $output;

			}
			add_shortcode('et_testimonial_container', 'et_testimonial_container');

			function et_testimonial($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'text'        => '',
						'author'      => '',
						'image'       => '',
						'rating'      => '5',
						'extra_class' => '',
						'element_id'  => '',
					), $atts)
				);

				static $id_counter = 1;

				$output       = '';
				$image_output = '';

				$class   = array();
				$class[] = 'et-testimonial';

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($text) && !empty($text)) {
					$output .= '<div id="et-testimonial-'.$element_id.'" data-rating="'.$rating.'" class="'.implode(" ",$class).'">';

						$output .= '<div class="et-testimonial-inner">';

							$output .= '<div class="rating"><span></span><span></span><span></span><span></span><span></span></div>';

							$output .= '<blockquote>'.do_shortcode($text).'</blockquote>';

							$output .= '<div class="testimonial-meta">';

								if (isset($image) && !empty($image)) {
									$image     = wp_get_attachment_image_src($image,'full');
									$image_src = $image[0];
									$output .= '<img class="regular" src="'.esc_url($image_src).'" alt="'.esc_attr($author).'" />';
								}

								if (isset($author) && !empty($author)) {
									$output .= '<h5 class="author">'.esc_html($author).'</h5>';
								}

							$output .= '</div>';

						$output .= '</div>';

					$output .= '</div>';
				}
				$id_counter++;

				return $output;
			}

			add_shortcode('et_testimonial', 'et_testimonial');

		/*	et_client
		--------------*/

			function et_client_container($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'type'            => 'grid',
						'columns'         => '1',
						'columns_tab'     => 'inherit',
						'columns_mob'     => 'inherit',
						'navigation_type' => 'arrows',
						'autoplay'        => 'false',
						'element_id'      => '',
					), $atts)
				);

				$output = '';

				static $id_counter = 1;

				$class   = array();
				$class[] = 'et-client-container';
				$class[] = $type;

				if (isset($type) && $type == "carousel") {
					$class[] = 'et-carousel';
					$class[] = $navigation_type;
				}

				$attributes = array();

				$attributes[] = 'data-nav="'.$navigation_type.'"';
				$attributes[] = 'data-autoplay="'.$autoplay.'"';
				$attributes[] = 'data-columns="'.$columns.'"';

				if ($columns_tab != 'inherit') {
					$attributes[] = 'data-columns-tab="'.$columns_tab.'"';
				}

				if ($columns_mob != 'inherit') {
					$attributes[] = 'data-columns-mob="'.$columns_mob.'"';
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$output .='<div id="et-client-container-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
					if (isset($type) && $type == "carousel") {
						$output .= '<div class="slides">';
							$output .= do_shortcode($content);
						$output .= '</div>';
					} else {
						$output .= do_shortcode($content);
					}
				$output .= '</div>';

				$id_counter++;

				return $output;

			}
			add_shortcode('et_client_container', 'et_client_container');

			function et_client($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'link'  => '',
						'title' => '',
						'image' => '',
						'element_id'  => '',
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';
				$link_before = '';
				$link_after  = '';

				if (isset($link) && !empty($link)) {
					$link_before = '<a href="'.esc_url($link).'">';
					$link_after  = '</a>';
				}

				$class   = array();
				$class[] = 'et-client';

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($image) && !empty($image)) {
					$output .= '<div id="et-client-'.$element_id.'" class="'.implode(" ",$class).'">';
						$output .= '<div class="client-inner">';
							$image     = wp_get_attachment_image_src($image,'full');
							$image_src = $image[0];
							$output .= $link_before;
								$output .= '<img class="regular" src="'.esc_url($image_src).'" alt="'.esc_attr($title).'" />';
							$output .= $link_after;
						$output .= '</div>';
					$output .= '</div>';
				}

				$id_counter++;

				return $output;
			}

			add_shortcode('et_client', 'et_client');

		/*	et_person
		--------------*/

			function et_person($atts, $content = null) {

				extract(shortcode_atts(
					array(
						'name'        => '',
						'title'       => '',
						'image'       => '',
						'extra_class' => '',
						'element_id'  => '',
					), $atts)
				);

				static $id_counter = 1;

				$output      = '';

				$class   = array();
				$class[] = 'et-person';

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($image) && !empty($image)) {
					$output .= '<div id="et-person-'.$element_id.'" class="'.implode(" ",$class).'">';
						$output .= '<div class="person-inner">';
							if (isset($image) && !empty($image)) {
								$output .= '<div class="person-img-wrap">';

									$output .= enovathemes_addons_inline_image_placeholder($image,'full','person-image');
									$output .= '<div class="person-info">';

										if (isset($name) && !empty($name)) {
											$output .= '<h4 class="name in">'.esc_html($name).'</h4>';
										}

										if (isset($title) && !empty($title)) {
											$output .= '<span class="title in">'.esc_html($title).'</span>';
										}

									$output .= '</div>';

								$output .= '</div>';
							}

							$output .= '<div class="person-content et-clearfix">';

								$output .= '<div class="styling-original-false et-social-links in">';
									foreach($atts as $social => $href) {
										if($href && $social != 'name' && $social != 'title' && $social != 'image' && $social != 'extra_class' && $social != 'element_id') {
											$output .='<a class="'.$social.'" href="'.$href.'" target="blank" title="'.$social.'">';
												if (et_get_social_icon() && isset(et_get_social_icon()[$social])) {
									                $output .= et_get_social_icon()[$social];
									            }
											$output .='</a>';
										}
									}
								$output .= '</div>';

							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				}
				$id_counter++;

				return $output;
			}

			add_shortcode('et_person', 'et_person');

		/*  et_popup_banner
		/*------------*/

			function et_popup_banner( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'visible_mob'   => '',
					'visible_tablet'=> '',
					'visible_desk'  => '',
					'cookie'        => '',
					'delay'         => '3000',
					'effect'        => 'fade-in-scale',
					'text_align'    => 'left',
					'element_id'    => '',
				), $atts));

				$output = '';

				wp_enqueue_script( 'cookie');

				static $id_counter = 1;

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$attributes = array();
				if (isset($cookie) && !empty($cookie)) {
					$attributes[] = 'data-cookie="'.esc_attr($cookie).'"';
				}

				if (isset($delay) && !empty($delay)) {
					$attributes[] = 'data-delay="'.absint($delay).'"';
				}

				$output .='<div id="et-popup-banner-wrapper-'.$element_id.'" class="et-popup-banner-wrapper" data-mob="'.$visible_mob.'" data-tablet="'.$visible_tablet.'" data-desktop="'.$visible_desk.'">';
					$output .='<div id="et-popup-banner-'.$element_id.'" class="et-popup-banner et-clearfix '.esc_attr($effect).' text-align-'.esc_attr($text_align).'" '.implode(" ", $attributes).'>';
						$output .='<div class="popup-banner-toggle"></div>';
						$output .= do_shortcode($content);
					$output .='</div>';
				$output .='</div>';

				$id_counter++;

				return $output;


			}
			add_shortcode('et_popup_banner', 'et_popup_banner');

		/*  et_toggle_banner
		/*------------*/

			function et_toggle_banner( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'visible_mob'   => '',
					'visible_tablet'=> '',
					'visible_desk'  => '',
					'cookie'        => '',
					'text_align'    => 'left',
					'element_id'    => '',
				), $atts));

				$output = '';

				wp_enqueue_script( 'cookie');

				static $id_counter = 1;

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$attributes = array();
				if (isset($cookie) && !empty($cookie)) {
					$attributes[] = 'data-cookie="'.esc_attr($cookie).'"';
				}

				$output .='<div id="et-toggle-banner-wrapper-'.$element_id.'" class="et-toggle-banner-wrapper" data-mob="'.$visible_mob.'" data-tablet="'.$visible_tablet.'" data-desktop="'.$visible_desk.'">';
					$output .='<div id="et-toggle-banner-'.$element_id.'" class="et-toggle-banner et-clearfix text-align-'.esc_attr($text_align).'" '.implode(" ", $attributes).'>';
						$output .='<div class="toggle-banner-toggle"></div>';
						$output .= do_shortcode($content);
					$output .='</div>';
				$output .='</div>';

				$id_counter++;

				return $output;


			}
			add_shortcode('et_toggle_banner', 'et_toggle_banner');

		/*  et_banner
		/*------------*/

			function et_banner( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'extra_class'    => '',
					'link'           => '',
					'overflow'       => '',
					'overflow_mobile'=> '',
					'overflow_tab_port'=> '',
					'overflow_tab_land'=> '',
					'highlight'      => '',
					'gradient'       => 'none',
					'align'          => 'none',
					'crp'            => '',
					'back_image'     => '',
					'image'          => '',
					'parallax_x'     => '0',
					'parallax_y'     => '0',
					'm_parallax_x'   => '',
					'm_parallax_y'   => '',
					'mm_parallax_x'  => '',
					'mm_parallax_y'  => '',
					'tp_parallax_x'  => '',
					'tp_parallax_y'  => '',
					'tl_parallax_x'  => '',
					'tl_parallax_y'  => '',
					'parallax'       => '',
					'parallax_speed' => '10',
					'parallax_limit' => '',
					'element_id'     => ''
				), $atts));

				$output = '';

				static $id_counter = 1;

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$attributes = $class = array();

				if (!empty($crp)) {

					$crp = explode(',', $crp);

					$query_array = $responsive_padding = array();

					foreach ($crp as $key => $value) {
						array_push($query_array, explode(':', $value));
					}

					foreach ($query_array as $key => $value) {
						if ($value[1] != "i") {
							$responsive_padding[] = 'data-'.$value[0].'-l="'.$value[1].'" ';
						}
						if ($value[2] != "i") {
							$responsive_padding[] = 'data-'.$value[0].'-r="'.$value[2].'" ';
						}
					}

					$attributes[] = implode(' ', $responsive_padding);
				}

				$class[] = 'et-banner';
				$class[] = 'vci';
				$class[] = 'align-'.$align;
				$class[] = 'gradient-'.$gradient;

				if (isset($overflow) && !empty($overflow)) {
					$class[] = 'overflow';
				}

				if (isset($overflow_mobile) && !empty($overflow_mobile)) {
					$class[] = 'overflow-mobile';
				}

				if (isset($overflow_tab_port) && !empty($overflow_tab_port)) {
					$class[] = 'overflow-tablet-portrate';
				}

				if (isset($overflow_tab_land) && !empty($overflow_tab_land)) {
					$class[] = 'overflow-tablet-landscape';
				}

				if (isset($highlight) && !empty($highlight)) {
					$class[] = 'highlight';
				}

				if (isset($extra_class) && !empty($extra_class)) {
					$class[] = $extra_class;
				}


				$output .='<div id="et-banner-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';

					if (isset($link) && !empty($link)) {
						$output .='<a href="'.esc_url($link).'">';
					}

						$output .='<div class="banner-inner">';
							$output .= do_shortcode($content);
						$output .='</div>';

						if (isset($image) && !empty($image)) {

							$image_class = $image_attributes = array();

							$image_class[] = 'banner-image';
							$image_class[] = 'lazy';

							if (isset($parallax) && $parallax == "true") {
								$image_class[]      = 'parallax';
								$image_attributes[] = 'data-coordinatex="'.esc_attr($parallax_x).'"';
								$image_attributes[] = 'data-coordinatey="'.esc_attr($parallax_y).'"';
							}

							if (isset($m_parallax_x) && !empty($m_parallax_x) && isset($m_parallax_y) && !empty($m_parallax_y)) {
								$image_attributes[] = 'data-m-style="transform: translate3d('.$m_parallax_x.'px, '.$m_parallax_y.'px, 0px);"';
							}

							if (isset($mm_parallax_x) && !empty($mm_parallax_x) && isset($mm_parallax_y) && !empty($mm_parallax_y)) {
								$image_attributes[] = 'data-mm-style="transform: translate3d('.$mm_parallax_x.'px, '.$mm_parallax_y.'px, 0px);"';
							}

							if (isset($tp_parallax_x) && !empty($tp_parallax_x) && isset($tp_parallax_y) && !empty($tp_parallax_y)) {
								$image_attributes[] = 'data-tp-style="transform: translate3d('.$tp_parallax_x.'px, '.$tp_parallax_y.'px, 0px);"';
							}

							if (isset($tl_parallax_x) && !empty($tl_parallax_x) && isset($tl_parallax_y) && !empty($tl_parallax_y)) {
								$image_attributes[] = 'data-tl-style="transform: translate3d('.$tl_parallax_x.'px, '.$tl_parallax_y.'px, 0px);"';
							}

							$image_attributes[] = 'data-speed="'.esc_attr($parallax_speed).'"';
							if (isset($parallax_limit) && !empty($parallax_limit)) {
								$image_attributes[] = 'data-limit="'.esc_attr($parallax_limit).'"';
							}

							$img      = wp_get_attachment_image_src($image,'full');
							if($img){
								$image_w  = $img[1];
								$image_h  = $img[2];
								$alt      = get_post_meta($image, '_wp_attachment_image_alt', true);

								$output .='<img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="'.$img[0].'" alt="'.esc_attr__($alt).'" width="'.$image_w.'" height="'.$image_h.'" class="'.implode(' ', $image_class).'" '.implode(' ', $image_attributes).' />';
							}

						}

						$banner_back_data = '';

						if (isset($back_image) && !empty($back_image)) {
							$img_back = wp_get_attachment_image_src($back_image,'full');
							if ($img_back) {
								$output .='<div class="banner-back image" data-background="'.esc_url($img_back[0]).'"></div>';
							} else {
								$output .='<div class="banner-back"></div>';
							}
						} else {
							$output .='<div class="banner-back"></div>';
						}

					if (isset($link) && !empty($link)) {
						$output .='</a>';
					}

				$output .='</div>';

				$id_counter++;

				return $output;


			}
			add_shortcode('et_banner', 'et_banner');

	/* MEDIA
	---------------*/

		/*  et_image
		/*------------*/

			function et_image( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'image' 				  => '',
					'size' 					  => 'full',
					'link' 					  => '',
					'link_target'             => '_self',
					'border_radius'           => 'false',
					'parallax'                => 'false',
					'parallax_speed'          => '10',
					'parallax_x'              => '0',
					'parallax_y'              => '0',
					'parallax_limit'          => '0',
					'alignment'               => 'none',
					'animate'                 => 'false',
					'animation_type'          => 'fade-blur',
					'delay'                   => '0',
					'element_id'              => ''
				), $atts));


				$output = '';

				static $id_counter = 1;

				$class      = array();
				$attributes = array();

				$class[] = 'et-image';
				$class[] = 'align'.$alignment;
				$class[] = 'border-radius-'.$border_radius;


				if (isset($parallax) && $parallax == "true") {
					$class[]      = 'parallax';
					$attributes[] = 'data-coordinatex="'.esc_attr($parallax_x).'"';
					$attributes[] = 'data-coordinatey="'.esc_attr($parallax_y).'"';
					$attributes[] = 'data-speed="'.esc_attr($parallax_speed).'"';
					$attributes[] = 'data-limit="'.esc_attr($parallax_limit).'"';

					$animate = "false";

				}

				if ($animate == "true") {
					$attributes[] = 'data-animation="'.esc_attr($animation_type).'"';
					$attributes[] = 'data-delay="'.esc_attr($delay).'"';
				}

				$class[] = 'animate-'.$animate;

				if (isset($image) && !empty($image)) {

					$link_before = '';
					$link_after  = '';

					if (isset($link) && !empty($link)) {
						$class[] = 'link';
						$link_before = '<a target="'.$link_target.'" href="'.esc_url($link).'">';
						$link_after  = '</a>';
					}

					$element_id = (!empty($element_id)) ? $element_id : $id_counter;

					$img      = wp_get_attachment_image_src($image,$size);
					$image_w  = $img[1];
					$image_h  = $img[2];


					$style = (!empty($image_w)) ? 'width:'.$image_w.'px;max-height:'.$image_h.'px;' : '';

					$output .='<div id="et-image-'.$element_id.'" style="'.$style.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
						$output .= $link_before;
							$output .= enovathemes_addons_inline_image_placeholder($image,$size);
							if (
								$animation_type == "curtain-left" ||
								$animation_type == "curtain-right" ||
								$animation_type == "curtain-top" ||
								$animation_type == "curtain-bottom"
							) {
								$output .='<div class="curtain"></div>';
							}
						$output .=$link_after;
					$output .='</div>';

					$id_counter++;

			    	return $output;
				}

			}
			add_shortcode('et_image', 'et_image');

		/*  et-gallery
		/*------------*/

			function et_gallery( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'images'     => '',
					'size'       => 'full',
					'border_radius' => 'false',
					'type'       => 'grid',
					'link'       => 'none',
					'columns'    => '1',
					'navigation_type' => 'arrows',
					'autoplay'        => 'false',
					'element_id' => ''
				), $atts));


				$output = '';

				static $id_counter = 1;

				$class      = array();
				$attributes = array();

				$class[] = 'et-gallery';
				$class[] = $type;
				$class[] = $navigation_type;
				$class[] = 'border-radius-'.$border_radius;

				if ($type == 'carousel') {
					$class[] = 'et-carousel';
					$attributes[] = 'data-nav="'.$navigation_type.'"';
					$attributes[] = 'data-autoplay="'.esc_attr($autoplay).'"';
				}

				$attributes[] = 'data-columns="'.esc_attr($columns).'"';

				if (isset($images) && !empty($images)) {

					$element_id = (!empty($element_id)) ? $element_id : $id_counter;

					$output .='<div id="et-gallery-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';

						$output .='<div class="slides">';

							$images = explode(',', $images);

							foreach ($images as $image) {

								$link_before = '';
								$link_after  = '';

								$image_full = wp_get_attachment_image_src($image, "full");

								if (isset($link) && $link != "none") {
									$link_before = ($link == "lightbox") ? '<a data-gallery="et-gallery-'.$element_id.'" href="'.esc_url($image_full[0]).'">' : '<a href="'.esc_url($image_full[0]).'">';
									$link_after  = '</a>';
								}

								$output .='<div class="et-gallery-item">';
									$output .=$link_before;
										$output .= enovathemes_addons_inline_image_placeholder($image,$size);
									$output .=$link_after;
								$output .='</div>';

							}

						$output .='</div>';

					$output .='</div>';

					$id_counter++;

			    	return $output;
				}

			}
			add_shortcode('et_gallery', 'et_gallery');

		/*  et-video
		/*------------*/

			function et_video( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'mp4'   => '',
					'embed' => '',
					'image' => '',
					'modal' => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

				if (isset($embed) && !empty($embed)) {
					$embed = str_replace('watch?v=', 'embed/', $embed);
	                $embed = str_replace('//vimeo.com/', '//player.vimeo.com/video/', $embed);
                }

				$output .='<div id="et-video-'.$id_counter.'" class="et-video post-video post-media">';

                    if ($image){

                    	$link_class = array('video-btn');
                    	$attributes = array();

                    	if (isset($modal) && !empty($modal)) {

                    		$url = (isset($mp4) && !empty($mp4)) ? $mp4 : ((isset($embed) && !empty($embed)) ? $embed : '');

                    		$link_class[] = 'video-modal';
                    		$attributes[] = 'data-source="'.$url.'"';
                    		$attributes[] = 'href="'.$url.'"';

	                    } else {
	                    	$attributes[] = 'href="#"';
	                    }

                        $attributes[] = 'class="'.implode(" ", $link_class).'"';

                        $output .='<div class="image-container">';

                            $output .= propharm_enovathemes_build_post_media('full','full',$image);

                            $output .='<a '.implode(" ", $attributes).'>';
                                $output .='<svg viewBox="0 0 512 512">';
                                    $output .='<path class="back" d="M512,256c0,141.38-114.62,256-256,256S0,397.38,0,256,114.62,0,256,0,512,114.62,512,256Z" />';
                                    $output .='<path class="play" d="M346.89,261.61,205.11,350c-4.76,3-11.11-.24-11.11-5.61V167.62c0-5.37,6.35-8.57,11.11-5.61l141.78,88.38A6.61,6.61,0,0,1,346.89,261.61Z"/>';
                                $output .='</svg>';
                            $output .='</a>';

                        $output .='</div>';
                    }

                    if(empty($modal)) {

                        if(!empty($embed) && empty($video)) {

                        	if (empty($image)) {
                        		$output .='<div class="flex-mod">';
                        	}

                            $output .='<iframe width="1280" height="720" allowfullscreen="allowfullscreen" allow="autoplay" frameBorder="0" src="'.$embed.'" class="iframevideo video-element"></iframe>';

                            if (empty($image)) {
                        		$output .='</div>';
                        	}

                        } elseif(!empty($mp4)) {

                            $output .='<video poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png'.'" id="video-'.get_the_ID().'" class="lazy video-element" playsinline controls>';
                                $output .='<source data-src="'.$mp4.'" src="'.PROPHARM_ENOVATHEMES_IMAGES.'/video_placeholder.mp4'.'" type="video/mp4">';
                            $output .='</video>';

                        }

                    }

				$output .='</div>';

				$id_counter++;

		    	return $output;

			}
			add_shortcode('et_video', 'et_video');

		/*  et-audio
		/*------------*/

			function et_audio( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'mp3'        => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

                if(!empty($mp3)) {

                	$output .='<div id="et-audio-'.$id_counter.'" class="et-audio">';
	                    $output .='<audio class="plyr-element" playsinline controls>';
	                    	$output .='<source src="'.$mp3.'" type="audio/mp3">';
	                    $output .='</audio>';
                    $output .='</div>';
                }

				$id_counter++;

		    	return $output;

			}
			add_shortcode('et_audio', 'et_audio');

	/* INFOGRAPHICS
	---------------*/

		/*  et_counter
		/*------------*/

			function et_counter( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'text_align'     => 'left',
					'title'          => '',
					'type'           => 'h4',
					'number'         => '',
					'number_postfix' => '',
					'icon'           => '',
					'delay'          => '',
					'element_id'     => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

				$class = array();
				$class[] = 'et-counter';
				$class[] = $text_align;

				if (isset($icon) && !empty($icon)) {
					$class[] = 'icon';
				}


				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$attributes   = array();
				$attributes[] = 'data-value="'.$number.'"';
				$attributes[] = 'data-delay="'.esc_attr($delay).'"';

				if (isset($number) && !empty($number)) {
			    	$output .='<div id="et-counter-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';

			    		$output .='<div class="et-counter-inner">';

			    			if (isset($icon) && !empty($icon)) {

								$icon = get_post($icon);

								if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

									if (et_get_icon($icon->guid)) {
										$icon_output = et_get_icon($icon->guid);
						            }

									$output .= '<div class="counter-icon et-icon size-large">';
										$output .= $icon_output;
									$output .= '</div>';

								}

							}

							$output .='<div class="counter-content">';

					    		$output .='<div class="counter-value in">';

						    		$output .='<span class="counter">0</span>';

					    			if (isset($number_postfix) && !empty($number_postfix)) {
						    			$output .='<span class="postfix">'.esc_attr($number_postfix).'</span>';
						    		}

						    	$output .='</div>';

					    		if (isset($title) && !empty($title)) {
					    			$output .='<'.$type.' class="counter-title in">'.esc_html($title).'</'.$type.'>';
					    		}

				    		$output .='</div>';

			    		$output .='</div>';

			    	$output .='</div>';

			    	$id_counter++;

			    	return $output;
				}

			}
			add_shortcode('et_counter', 'et_counter');

		/*  et_progress
		/*------------*/

			function et_progress( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'version'        => 'default',
					'title'	         => '',
					'type'           => 'h4',
					'percentage'	 => '',
					'element_id'     => '',
					'delay'          => '',
				), $atts));

				$output = '';

				static $id_counter = 1;

				$attributes = array();

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if(!is_numeric($percentage) || $percentage < 0){$percentage = "";}
				elseif ($percentage > 100) {$percentage = "100";}

				$attributes[] = 'data-delay="'.esc_attr($delay).'"';
				$attributes[] = 'data-percentage="'.absint($percentage).'"';

				if (isset($percentage) && !empty($percentage)) {

					$output .= '<div id="et-progress-'.$element_id.'" class="et-progress '.$version.'" '.implode(' ', $attributes).'>';

						if ($version == "circle") {

							$output .= '<div class="text">';
								$output .= '<span class="percent">0</span>';
					    		$output .='<'.$type.' class="title">'.esc_html($title).'</'.$type.'>';
							$output .= '</div>';

							$output .='<svg viewBox="0 0 56 56">';
		                        $output .='<circle class="track-circle" cx="28" cy="28" r="27" />';
		                        $output .='<circle class="bar-circle" cx="28" cy="28" r="27" />';
		                    $output .='</svg>';

						} else {

							$output .= '<div class="text">';
					    		$output .='<'.$type.' class="title">'.esc_html($title).'</'.$type.'>';
							$output .= '</div>';

							$output .= '<div class="track-bar">';
								$output .= '<div class="bar" data-percent=""></div>';
								$output .= '<div class="track"></div>';
							$output .= '</div>';
						}
					$output .= '</div>';

					$id_counter++;

			    	return $output;
				}

			}
			add_shortcode('et_progress', 'et_progress');

		/*  timer
		/*------------*/

			function et_timer( $atts, $content = null ) {

				extract(shortcode_atts(array(
					'number'  => '',
					'enddate' => '',
					'days'    => '',
					'hours'   => '',
					'minutes' => '',
					'seconds' => '',
					'gmt'     => '',
					'element_id'=> ''
				), $atts));

				static $id_counter = 1;

				$output 	  = '';

				$attributes = array();

				if (isset($number) && !empty($number)) {
					$attributes[] = 'data-number="'.absint($number).'"';
				}

				if (isset($number) && !empty($number)) {
					$attributes[] = 'data-gmt="'.absint($gmt).'"';
				}

				if (isset($enddate) && !empty($enddate)) {
					$attributes[] = 'data-enddate="'.esc_attr($enddate).'"';
				}

				if (isset($days) && !empty($days)) {
					$attributes[] = 'data-days="'.esc_attr($days).'"';
				} else {
					$attributes[] = 'data-days="Days"';
				}

				if (isset($hours) && !empty($hours)) {
					$attributes[] = 'data-hours="'.esc_attr($hours).'"';
				} else {
					$attributes[] = 'data-hours="Hours"';
				}

				if (isset($minutes) && !empty($minutes)) {
					$attributes[] = 'data-minutes="'.esc_attr($minutes).'"';
				} else {
					$attributes[] = 'data-minutes="Minutes"';
				}

				if (isset($seconds) && !empty($seconds)) {
					$attributes[] = 'data-seconds="'.esc_attr($seconds).'"';
				} else {
					$attributes[] = 'data-seconds="Seconds"';
				}

				$element_id = (!empty($element_id)) ? $element_id : $id_counter;

				if (isset($enddate) && !empty($enddate)) {

					$output .='<div id="et-timer-'.$element_id.'" '.implode(" ", $attributes).' class="et-timer et-clearfix">';
						$output .='<ul>';
						  $output .='<li><div><span class="timer-count days">00</span><h5 class="days_text timer-title">'.$days.'</h5></div></li>';
							$output .='<li><div><span class="timer-count hours">00</span><h5 class="hours_text timer-title">'.$hours.'</h5></div></li>';
							$output .='<li><div><span class="timer-count minutes">00</span><h5 class="minutes_text timer-title">'.$minutes.'</h5></div></li>';
							$output .='<li><div><span class="timer-count seconds">00</span><h5 class="seconds_text timer-title">'.$seconds.'</h5></div></li>';
						$output .='</ul>';
					$output .='</div>';

					$id_counter++;

			    	return $output;
				}

			}
			add_shortcode('et_timer', 'et_timer');

	/* OTHER
	---------------*/

		/*  et_gap
		/*------------*/

			function et_gap( $atts, $content = null ) {
				extract(shortcode_atts(array(
					'extra_class' => '',
					'element_id'  => '',
					'rv'          => '',
				), $atts));

				static $id_counter = 1;

				$responsive_visibility = array();

				if (!empty($rv)) {
					$rv = explode(',', $rv);

					foreach ($rv as $key) {
						$responsive_visibility[] = 'hide'.$key;
					}

				}

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-gap';
				$class[] = 'et-clearfix';

		        $element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class[] = 'et-gap-'.$element_id;

				if (!empty($responsive_visibility)) {
					$class = array_merge($class,$responsive_visibility);
				}

				return '<span class="'.implode(" ", $class).'"></span>';

				$id_counter++;
			}
			add_shortcode('et_gap', 'et_gap');

			function et_gap_inline( $atts, $content = null ) {
				extract(shortcode_atts(array(
					'extra_class' => '',
					'element_id'  => '',
					'rv'          => '',
				), $atts));

				static $id_counter = 1;

				$responsive_visibility = array();

				if (!empty($rv)) {
					$rv = explode(',', $rv);

					foreach ($rv as $key) {
						$responsive_visibility[] = 'hide'.$key;
					}

				}

				$class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'et-gap-inline';
				$class[] = 'et-clearfix';

		        $element_id = (!empty($element_id)) ? $element_id : $id_counter;

				$class[] = 'et-gap-inline-'.$element_id;

				if (!empty($responsive_visibility)) {
					$class = array_merge($class,$responsive_visibility);
				}

				return '<div class="'.implode(" ", $class).'"></div>';

				$id_counter++;
			}
			add_shortcode('et_gap_inline', 'et_gap_inline');

/*  HEADER BUILDER
/*------------*/

	/*	et_header_logo
	--------------*/

		function et_header_logo($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'logo'            		=> '',
					'retina_logo'           => '',
					'sticky_logo'           => '',
					'sticky_retina_logo'    => '',
					'align'                 => 'none',
					'extra_class'     		=> '',
					'element_id'            => '',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'hbe';
			$class[] = 'header-logo';
			$class[] = 'hbe-'.$align;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			// logo

			if (!empty($logo)) {

				$logo = get_post($logo);


				if (is_object($logo) && $logo->post_mime_type == 'image/svg+xml') {

					if (et_get_icon($logo->guid)) {
						$logo = et_get_icon($logo->guid);
		            }

				} else {

					$logo_src = $logo->guid;


					// retina logo
					if (!empty($retina_logo)) {

						$retina_logo = get_post($retina_logo);

						if (is_object($retina_logo) && $retina_logo->post_mime_type != 'image/svg+xml') {
							$logo_src = $retina_logo->guid;
						}
						
					}
				}

			}

			// sticky logo

			if (!empty($sticky_logo)) {

				$sticky_logo = get_post($sticky_logo);

				if (is_object($sticky_logo) && $sticky_logo->post_mime_type == 'image/svg+xml') {

				    if (et_get_icon($sticky_logo->guid)) {
						$sticky_logo = et_get_icon($sticky_logo->guid);
		            }

				} else {

				    $sticky_logo_src = $sticky_logo->guid;

				    // retina logo

				    if (!empty($sticky_retina_logo)) {
				    	$sticky_retina_logo = get_post($sticky_retina_logo);
				    	if (is_object($sticky_retina_logo) && $sticky_retina_logo->post_mime_type != 'image/svg+xml') {
				        	$sticky_logo_src = $sticky_retina_logo->guid;
				    	}
				    }
				}

			}

			$output .= '<div id="header-logo-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= '<a href="'.esc_url(home_url('/')).'" title="'.get_bloginfo('name').'">';
					if (!empty($logo)) {
						if (isset($logo_src) && !empty($logo_src)) {
							$output .= '<img class="logo" src="'.$logo_src.'" alt="'.get_bloginfo('name').'">';
						} else {
							$output .= '<div class="logo">'.$logo.'</div>';
						}
					}
					if (!empty($sticky_logo)) {
						if (isset($sticky_logo_src) && !empty($sticky_logo_src)) {
							$output .= '<img class="sticky-logo" src="'.$sticky_logo_src.'" alt="'.get_bloginfo('name').'">';
						} else {
							$output .= '<div class="sticky-logo">'.$sticky_logo.'</div>';
						}
					}
				$output .= '</a>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_header_logo', 'et_header_logo');

	/*	et_header_menu
	--------------*/

		function et_header_menu($atts, $content = null) {

			global $propharm_enovathemes;

			$main_color = (isset($GLOBALS['propharm_enovathemes']['main-color']) && $GLOBALS['propharm_enovathemes']['main-color']) ? $GLOBALS['propharm_enovathemes']['main-color'] : '#15a9e3';

			extract(shortcode_atts(
				array(
					'menu'            		=> '',
					'align'                 => 'none',
					'menu_hover'            => 'none',
					'submenu_appear'        => 'none',
					'submenu_shadow'        => 'false',
					'submenu_indicator'     => 'false',
					'submenu_separator'     => 'false',
					'menu_separator'        => 'false',
					'menu_color'            => '',
					'menu_color_hover'      => $main_color,
					'submenu_submenu_indicator' => 'false',
					'extra_class'     		=> '',
					'element_id'            => '',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
					'one_page'              => 'false',
					'offset'                => '0'
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-menu-container';
			$class[] = 'nav-menu-container';
			$class[] = 'hbe';
			$class[] = 'hbe-'.$align;
			$class[] = 'one-page-'.$one_page;
			$class[] = 'one-page-offset-'.$offset;
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'menu-hover-'.$menu_hover;
			$class[] = 'submenu-appear-'.$submenu_appear;
			$class[] = 'submenu-shadow-'.$submenu_shadow;
			$class[] = 'tl-submenu-ind-'.$submenu_indicator;
			$class[] = 'sl-submenu-ind-'.$submenu_submenu_indicator;
			$class[] = 'separator-'.$submenu_separator;
			$class[] = 'top-separator-'.$menu_separator;

			if($menu_hover == "underline") {

				$link_after = (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '<span class="effect"></span></span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '<span class="effect"></span></span>';

			} else {

				$link_after  = (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span><span class="effect"></span>' : '</span><span class="effect"></span>';

			}

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$menu_arg = array();

			if (empty($menu) || $menu == "choose" || !isset($menu)) {
				if (has_nav_menu( 'header-menu' )) {
					$menu_arg = array(
						'theme_location'  => 'header-menu',
						'menu_class'      => 'header-menu nav-menu hbe-inner et-clearfix',
						'menu_id'         => 'header-menu-'.$element_id,
						'container'       => 'nav',
						'container_class' => implode(" ", $class),
						'container_id'    => 'header-menu-container-'.$element_id,
						'items_wrap'      => '<ul id="%1$s" class="%2$s" data-color="'.esc_attr($menu_color).'" data-color-hover="'.esc_attr($menu_color_hover).'">%3$s</ul>',
						'echo'            => false,
						'link_before'     => '<span class="txt">',
						'link_after'      => $link_after,
						'depth'           => 10,
						'walker'          => new et_scm_walker
					);
				}
			} else {
				$menu_arg = array(
					'menu'  => $menu,
					'menu_class'      => 'header-menu nav-menu hbe-inner et-clearfix',
					'menu_id'         => 'header-menu-'.$element_id,
					'container'       => 'nav',
					'container_class' => implode(" ", $class),
					'container_id'    => 'header-menu-container-'.$element_id,
					'items_wrap'      => '<ul id="%1$s" class="%2$s" data-color="'.esc_attr($menu_color).'" data-color-hover="'.esc_attr($menu_color_hover).'">%3$s</ul>',
					'echo'            => false,
					'link_before'     => '<span class="txt">',
					'link_after'      => $link_after,
					'depth'           => 10,
					'walker'          => new et_scm_walker
				);
			}

			if (!empty($menu_arg)) {
				$output .= wp_nav_menu($menu_arg);
			}


			$id_counter++;

			return $output;
		}

		add_shortcode('et_header_menu', 'et_header_menu');

	/*	et_megamenu
	--------------*/

		function et_megamenu($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'menu'              => '',
					'submenu_hover'     => 'none',
					'submenu_separator' => 'false',
					'extra_class'       => '',
					'element_id'        => '',
					'columns'           => '1'
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'mm-container';
			$class[] = 'submenu-hover-'.$submenu_hover;
			$class[] = 'separator-'.$submenu_separator;
			$class[] = 'column-'.$columns;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			if (!empty($menu)) {

				$menu_arg = array(
					'menu'  => $menu,
					'menu_class'      => 'mm-'.$element_id.' et-mm et-clearfix',
					'container'       => 'div',
					'container_class' => implode(" ", $class),
					'container_id'    => 'mm-container-'.$element_id,
					'echo'            => false,
					'link_before'     => '<span class="txt">',
					'link_after'      => '</span>',
					'depth'           => 3,
					'walker'          => new et_scm_walker
				);

				$output .= wp_nav_menu($menu_arg);

				$id_counter++;

				return $output;

			}
		}

		add_shortcode('et_megamenu', 'et_megamenu');

	/*	et_megamenu_tab
	--------------*/

		function et_megamenu_tab($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'size'    => 'small',
					'action' => 'click',
					'element_id' => ''
				), $atts)
			);

			$output = '';

			$class = array();

			$class[] = 'megamenu-tab';
			$class[] = 'et-clearfix';
			$class[] = esc_attr($size);
			$class[] = 'action-'.$action;

			if (!isset($element_id) || empty($element_id)) {
				$element_id = rand(1,1000000);
			}

			$output .='<div id="megamenu-tab-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= do_shortcode($content);
			$output .= '</div>';

			return $output;

		}
		add_shortcode('et_megamenu_tab', 'et_megamenu_tab');

		function et_megamenu_tab_item($atts, $content = null) {

			extract(shortcode_atts(array(
				'title'  => '',
				'icon'   => '',
				'active' => 'false',
				'element_id' => ''
			), $atts));

			$output = '';

			if (!isset($element_id) || empty($element_id)) {
				$element_id = rand(1,1000000);
			}

			if($active == 'true'){
				$active = "active";
			}

			$output .= '<div data-target="tab-item-'. $element_id .'" class="'.esc_attr($active).' tab-item et-clearfix">';

				if (isset($icon) && !empty($icon)) {

					$icon = get_post($icon);

					if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

						if (et_get_icon($icon->guid)) {
							$icon_output = et_get_icon($icon->guid);
			            }

			            $output .= '<span class="icon" id="icon-'.$element_id.'"">'.$icon_output.'</span>';

					}

		        }

				if (isset($title) && !empty($title)) {
					$output .= '<span class="txt">'.esc_attr($title).'</span>';
				}
				if (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) {
					$output .= '<span class="arrow">'.et_get_theme_icon()['arrow'].'</span>';
	            }
			$output .= '</div> ';
			$output .= '<div id="tab-item-'.$element_id.'" class="tab-content et-clearfix">';
			    $output .= do_shortcode($content);
			$output .= '</div>';

			return $output;
		}
		add_shortcode('et_megamenu_tab_item', 'et_megamenu_tab_item');

	/*	et_search_toggle
	--------------*/

		function et_search_toggle($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'                 => 'none',
					'extra_class'     		=> '',
					'element_id'            => '',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-search';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="header-search-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= '<div id="search-toggle-'.$element_id.'" class="search-toggle hbe-toggle">';
	                if (et_get_theme_icon() && isset(et_get_theme_icon()['search'])) {
		                $output .= et_get_theme_icon()['search'];
		            }
				$output .= '</div>';
				$output .= '<div id="search-box-'.$element_id.'" class="search-box">';

					$output .= '<form class="search-form" action="'.esc_url(home_url('/')).'/" method="get">';
					    $output .= '<fieldset>';
					        $output .= '<input type="text" name="s" id="s" />';
					        $output .= '<input type="submit" id="searchsubmit" class="close-toggle" />';
					        if (et_get_theme_icon() && isset(et_get_theme_icon()['search'])) {
					        	$output .= '<div class="search-icon">'.et_get_theme_icon()['search'].'</div>';
				            }
					    $output .= '</fieldset>';
					$output .= '</form>';
	                if (et_get_theme_icon() && isset(et_get_theme_icon()['search-back'])) {
			        	$output .= et_get_theme_icon()['search-back'];
		            }

				$output .= '</div>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_search_toggle', 'et_search_toggle');

	/*	et_product_search
	--------------*/

		function et_product_search($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'                 => 'none',
					'extra_class'     		=> '',
					'element_id'            => '',
					'hide_category'         => 'false',
					'sku'                   => 'false',
					'description'           => 'false',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-product-search';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;

			if (!empty($extra_class)) {
				$class[] = $extra_class;
			}

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$args = array(
				'before_widget' => '<div id="header-product-search-'.$element_id.'" class="'.implode(" ", $class).'">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5 class="widget_title">',
                'after_title'   => '</h5>',
			);

			$instance = array('title' => '','category' => $hide_category,'SKU' => $sku,'description' => $description,'in_category' => false,'attribute' => false,'attribute_term'=>'');

			$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Product_Search', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('et_product_search', 'et_product_search');

	/*	et_product_search_toggle
	--------------*/

		function et_product_search_toggle($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'            => 'none',
					'extra_class'  	   => '',
					'element_id'       => '',
					'hide_category'    => 'false',
					'sku'              => 'false',
					'description'      => 'false',
					'hide_default'     => 'false',
					'hide_sticky'      => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-product-search-toggle';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="header-product-search-toggle-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= '<div id="search-toggle-'.$element_id.'" class="search-toggle hbe-toggle">';
	                if (et_get_theme_icon() && isset(et_get_theme_icon()['search'])) {
			        	$output .= et_get_theme_icon()['search'];
		            }
				$output .= '</div>';
				$output .= '<div id="search-box-'.$element_id.'" class="search-box">';

					$output .= '<div class="search-toggle-off et-icon size-medium">';
						if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
				        	$output .= et_get_theme_icon()['close'];
			            }
					$output .= '</div>';

					$output .= '<div class="et-clearfix"></div>';

					$args = array(
						'before_title'  => '<h5 class="widget_title">',
		                'after_title'   => '</h5>',
					);

					$instance = array('title' => '','category' => $hide_category,'SKU' => $sku,'description' => $description,'in_category' => false,'attribute' => false,'attribute_term'=>'');

					$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Product_Search', $instance,$args);

				$output .= '</div>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_product_search_toggle', 'et_product_search_toggle');

	/*	et_search_form
	--------------*/

		function et_search_form($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'                 => 'none',
					'extra_class'     		=> '',
					'element_id'            => '',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-search-form';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="header-search-form-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= '<form id="search-form-'.$element_id.'" class="search-form" action="'.esc_url(home_url("/")).'" method="get">';
				    $output .= '<fieldset>';
				        $output .= '<input type="text" name="s" id="s" placeholder="'.esc_attr__("Search...", "enovathemes-addons").'" />';
				        $output .= '<input type="submit" id="searchsubmit" />';
				    	if (et_get_theme_icon() && isset(et_get_theme_icon()['search'])) {
				    		$output .= '<div id="search-icon-'.$element_id.'" class="search-icon">'.et_get_theme_icon()['search'].'</div>';
			            }
				    $output .= '</fieldset>';
				$output .= '</form>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_search_form', 'et_search_form');

	/*  et_cart_toggle
    --------------*/

        function et_cart_toggle($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'                 => 'none',
                    'box_align'             => 'left',
                    'extra_class'           => '',
                    'element_id'            => '',
                    'hide_default'          => 'false',
					'hide_sticky'           => 'false',
                ), $atts)
            );


            static $id_counter = 1;

            global $woocommerce;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'header-cart';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
            $class[] = 'hbe-'.$align;
            $class[] = 'box-align-'.$box_align;

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

            $output .= '<div id="header-cart-'.$element_id.'" class="'.implode(" ", $class).'">';
                $output .= '<div id="cart-toggle-'.$element_id.'" class="cart-toggle hbe-toggle">';
                	if (et_get_theme_icon() && isset(et_get_theme_icon()['cart'])) {
			        	$output .= et_get_theme_icon()['cart'];
		            }
					if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
			        	$output .= et_get_theme_icon()['close'];
		            }
                	if (class_exists('Woocommerce')) {
                		if ($woocommerce->cart->cart_contents_count) {
							$output .= '<span class="cart-contents">';
                        		$output .= '<span class="cart-info">'.$GLOBALS['woocommerce']->cart->cart_contents_count.'</span>';
							$output .= '</span>';
						} else {
							$output .= '<span class="cart-contents">';
                        		$output .= '<span class="cart-info">0</span>';
							$output .= '</span>';
						}
                	} else {
                		$output .= '<span class="cart-contents">';
                    		$output .= '<span class="cart-info">0</span>';
						$output .= '</span>';
                	}
		        $output .= '</div>';


            	$output .= '<div id="cart-box-'.$element_id.'" class="cart-box box">';

            		if (class_exists('Woocommerce')){
            			$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Cart', 'title=Cart' );
            		} else {
            			$output .= esc_html__('Please install Woocommerce','enovathemes-addons');
            		}

            	$output .= '</div>';

            $output .= '</div>';

            $id_counter++;

            return $output;

        }

        add_shortcode('et_cart_toggle', 'et_cart_toggle');

    /*  et_wishlist
    --------------*/

        function et_wishlist($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'                 => 'none',
                    'link'                  => '',
                    'extra_class'           => '',
                    'element_id'            => '',
                    'hide_default'          => 'false',
					'hide_sticky'           => 'false',
                ), $atts)
            );


            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'header-wishlist';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
            $class[] = 'hbe-'.$align;

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

            $output .= '<div id="header-wishlist-'.$element_id.'" class="'.implode(" ", $class).'">';
                $output .= '<a href="'.esc_url($link).'" title="'.esc_attr__("Wishlist","enovathemes-addons").'" id="wishlist-toggle-'.$element_id.'" class="wishlist hbe-toggle">';
                	if (et_get_theme_icon() && isset(et_get_theme_icon()['heart'])) {
			        	$output .= et_get_theme_icon()['heart'];
		            }
                	$output .='<span class="wishlist-contents">0</span>';
		        $output .= '</a>';
            $output .= '</div>';

            $id_counter++;

            return $output;

        }

        add_shortcode('et_wishlist', 'et_wishlist');

    /*  et_compare
    --------------*/

        function et_compare($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'                 => 'none',
                    'link'                  => '',
                    'extra_class'           => '',
                    'element_id'            => '',
                    'hide_default'          => 'false',
					'hide_sticky'           => 'false',
                ), $atts)
            );


            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'header-compare';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
            $class[] = 'hbe-'.$align;

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

            $output .= '<div id="header-compare-'.$element_id.'" class="'.implode(" ", $class).'">';
                $output .= '<a href="'.esc_url($link).'" title="'.esc_attr__("Compare","enovathemes-addons").'" id="compare-toggle-'.$element_id.'" class="compare hbe-toggle">';
                	if (et_get_theme_icon() && isset(et_get_theme_icon()['compare-icon'])) {
			        	$output .= et_get_theme_icon()['compare-icon'];
		            }
                	$output .='<span class="compare-contents">0</span>';
		        $output .= '</a>';
            $output .= '</div>';

            $id_counter++;

            return $output;

        }

        add_shortcode('et_compare', 'et_compare');

    /*  et_language_switcher
    --------------*/

        function et_language_switcher($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'        => 'none',
                    'box_align'    => 'center',
                    'position'     => 'bottom',
                    'extra_class'  => '',
                    'element_id'   => '',
					'submenu_width'=> '200',
                    'hide_default' => 'false',
					'hide_sticky'  => 'false'
                ), $atts)
            );

	            static $id_counter = 1;

	            $output      = '';

	            $class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

				$class[] = 'hbe-icon-element';
	            $class[] = 'language-switcher';
	            $class[] = 'hbe';
	            $class[] = 'hide-default-'.$hide_default;
				$class[] = 'hide-sticky-'.$hide_sticky;
	            $class[] = 'hbe-'.$align;
				$class[] = 'box-align-'.$box_align;
				$class[] = $position;

	            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

	            $output .= '<div id="language-switcher-'.$element_id.'" class="'.implode(" ", $class).'" data-width="'.esc_attr($submenu_width).'">';

                	$output .= '<div class="language-toggle hbe-toggle">';
                		if(function_exists('pll_the_languages')){
                			$output .= '<span class="current-lang">'.pll_current_language().'</span>';
                		} elseif(class_exists('SitePress')) {
                			$output .= '<span class="current-lang">'.ICL_LANGUAGE_CODE.'</span>';
                		} else {
                			$output .= '<span class="current-lang">EN</span>';
                		}
						if (et_get_theme_icon() && isset(et_get_theme_icon()['arrow-down'])) {
				        	$output .= et_get_theme_icon()['arrow-down'];
			            }
					$output .= '</div>';

					$output .= '<div id="language-box-'.$element_id.'" class="language-box box">';

						$output .= '<div class="language-switcher-content">';

			            	if (class_exists('SitePress')){

			            		$languages = icl_get_languages('skip_missing=0');

			            		if(1 < count($languages)){
			            			$output .= '<ul class="wpml-ls">';
									    foreach($languages as $l){
									    	$output .= '<li><a href="'.$l['url'].'"><img src="'.$l['country_flag_url'].'" />'.$l['translated_name'].'</a><li>';
									    }
								    $output .= '</ul>';
								}

							}elseif(function_exists('pll_the_languages')) {
								$output .= '<ul class="polylang-ls">';
									$output .=pll_the_languages(
										array(
											'echo'=>0,
											'show_flags'=>1,
											'hide_if_empty'=>0
										)
									);
								$output .= '</ul>';
							} else {
								$output .= '<ul class="no-ls">';
									$output .= '<li><a target="_blank" href="//wordpress.org/plugins/polylang/">'.esc_html__("Polylang","enovathemes-addons").'</a></li>';
									$output .= '<li><a target="_blank" href="//wpml.org/">'.esc_html__("WPML","enovathemes-addons").'</a></li>';
								$output .= '</ul>';
							}

						$output .= '</div>';

					$output .= '</div>';

	            $output .= '</div>';

	            $id_counter++;

	            return $output;

        }

        add_shortcode('et_language_switcher', 'et_language_switcher');

    /*  et_currency_switcher
    --------------*/

        function et_currency_switcher($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'        => 'none',
                    'box_align'    => 'center',
                    'position'     => 'bottom',
                    'box_position' => 'top',
                    'extra_class'  => '',
                    'element_id'   => '',
                    'hide_default' => 'false',
					'hide_sticky'  => 'false',
					'size'         => 'medium',
                ), $atts)
            );

	            static $id_counter = 1;

	            $output      = '';

	            $class = array();

				if (!empty($extra_class)) {
					$class[] = esc_attr($extra_class);
				}

	            $class[] = 'currency-switcher';
	            $class[] = 'hbe';
	            $class[] = 'hide-default-'.$hide_default;
				$class[] = 'hide-sticky-'.$hide_sticky;
	            $class[] = 'hbe-'.$align;
				$class[] = $position;
	            $class[] = 'box-align-'.$box_align;

	            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

	            $output .= '<div id="currency-switcher-'.$element_id.'" class="'.implode(" ", $class).'">';

					if(shortcode_exists('yaycurrency-switcher')) {
						$output .= do_shortcode('[yaycurrency-switcher]');
					} else {
						$output .= '<a target="_blank" href="//wordpress.org/plugins/yaycurrency/">'.esc_html__("Currency switcher","enovathemes-addons").'</a>';
					}

	            $output .= '</div>';

	            $id_counter++;

	            return $output;

        }

        add_shortcode('et_currency_switcher', 'et_currency_switcher');

	/*	et_login_toggle
	--------------*/

		function et_login_toggle($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'       => 'none',
					'box_align'   => 'left',
					'box_position'=> 'top',
					'extra_class' => '',
					'element_id'  => '',
					'hide_default'=> 'false',
					'hide_sticky' => 'false',
					'registration_link' => '',
	 				'forgot_link'       => '',
	 				'my_account_link'   => ''
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-login';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;
			$class[] = 'box-align-'.$box_align;
			$class[] = 'box-position-'.$box_position;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="header-login-'.$element_id.'" class="'.implode(" ", $class).'">';

				$output .= '<div id="login-toggle-'.$element_id.'" class="login-toggle hbe-toggle">';
					if (et_get_theme_icon() && isset(et_get_theme_icon()['user'])) {
			        	$output .= et_get_theme_icon()['user'];
		            }
					if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
			        	$output .= et_get_theme_icon()['close'];
		            }
					$output .= '<div id="login-title-'.$element_id.'" class="login-title login">'.esc_html__("My account","enovathemes-addons").'</div>';
				$output .= '</div>';

				$output .= '<div id="login-box-'.$element_id.'" class="login-box box">';
					$instance = array('title'=> '','registration_link'=>$registration_link,'forgot_link'=>$forgot_link,'my_account_link'=>$my_account_link);
					$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Login', $instance,'');
				$output .= '</div>';

			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_login_toggle', 'et_login_toggle');

	/*	et_header_slogan
	--------------*/

		function et_header_slogan($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'       => 'none',
					'extra_class' => '',
					'element_id'  => '',
					'hide_default'=> 'false',
					'hide_sticky' => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'hbe';
			$class[] = 'header-slogan';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="header-slogan-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= do_shortcode($content);
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_header_slogan', 'et_header_slogan');

	/*	et_header_social_links
	--------------*/

		function et_header_social_links($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'                 => 'none',
					'size'                  => 'medium',
					'extra_class'     		=> '',
					'element_id'            => '',
					'target' 				=> '_self',
					'styling_original'      => 'false',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-social-links';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;
			$class[] = 'size-'.$size;
			$class[] = 'styling-original-'.$styling_original;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="header-social-links-'.$element_id.'" class="'.implode(" ", $class).'">';
				foreach($atts as $social => $href) {
					if($href && $social != 'target' && $social != 'icon_color' && $social != 'icon_color_hover' && $social != 'icon_background_color' && $social != 'icon_background_color' && $social != 'icon_background_color_hover' && $social != 'icon_border_color' && $social != 'icon_border_color_hover' && $social != 'icon_border_width' && $social != 'styling_original' && $social != 'size' && $social != 'icon_size' && $social != 'icon_box_size' && $social != 'margin' && $social != 'element_id' && $social != 'element_css' && $social != 'align') {
						$output .='<a class="'.$social.'" href="'.$href.'" target="'.esc_attr($target).'" title="'.$social.'">';
							$output .= et_get_social_icon()[$social];
						$output .='</a>';
					}
				}
			$output .= '</div>';

			$id_counter++;

			return $output;
		}
		add_shortcode('et_header_social_links', 'et_header_social_links');

	/*	et_header_vertical_separator
	--------------*/

		function et_header_vertical_separator($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'type'        => 'solid',
					'align'       => 'none',
					'extra_class' => '',
					'element_id'  => '',
					'width'       => '',
					'height'      => '',
					'hide_default'=> 'false',
					'hide_sticky' => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'header-vertical-separator';
            $class[] = 'hbe';
            $class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
            $class[] = 'hbe-'.$align;
            $class[] = $type;

			if (isset($width) && !empty($width)) {
				if ($width > $height) {
					$class[] = 'horizontal';
				} else {
					$class[] = 'vertical';
				}
			} else {
				$class[] = 'horizontal';
			}

	        $element_id = (!empty($element_id)) ? $element_id : $id_counter;

	        $class[] = 'header-vertical-separator-'.$element_id;

			$output = '<div class="'.implode(" ", $class).'"><div class="line"></div></div>';

			$id_counter++;

			return $output;
		}
		add_shortcode('et_header_vertical_separator', 'et_header_vertical_separator');

	/*  et_header_icon
    --------------*/

        function et_header_icon($atts, $content = null) {

            extract(shortcode_atts(
                array(
					'icon'          => '',
					'size'          => 'medium',
					'icon_box_size' => '',
					'icon_link'     => '',
					'target' 	    => '_self',
					'click'         => 'false',
                    'align'         => 'none',
                    'extra_class'   => '',
                    'element_id'    => '',
                    'hide_default'  => 'false',
					'hide_sticky'   => 'false',
                ), $atts)
            );


            static $id_counter = 1;

            $output = $icon_output = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'header-icon';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;
			$class[] = 'size-'.$size;
            $class[] = 'click-'.$click;

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

			if (isset($icon) && !empty($icon)) {

				$icon = get_post($icon);

				if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

					if (et_get_icon($icon->guid)) {
						$icon_output = et_get_icon($icon->guid);
		            }

					$size = 40;

					if ($size == "small") {
						$size = 32;
					}elseif ($size == "large") {
						$size = 48;
					}elseif ($size == "custom") {
						$size = $icon_box_size;
					}

					$s2  = $size/2;
					$size_hover = $size + 4;

					$d 	     = 'M'.$s2.','.($size + 2).'A'.$s2.','.$s2.',0,0,1,'.$s2.',2A'.$s2.','.$s2.',0,0,1,'.$s2.','.($size + 2).'Z';
					$d_hover = 'M'.$s2.','.$size_hover.'C -2 '.($size_hover+2).',-2 -2,'.$s2.' 0,C '.$size_hover.' -2,'.$size_hover.' '.($size_hover+2).','.$s2.' '.$size_hover.'Z';

					$icon_back   = '<svg viewBox="0 0 '.$size.' '.$size.'" class="icon-back">';
						$icon_back .='<path d="'.$d.'" data-hover="'.$d_hover.'"/>';
					$icon_back .='</svg>';

		            $output .= '<div id="header-icon-'.$element_id.'" class="'.implode(" ", $class).'">';
		            	if (!empty($icon_link)) {
		            		$output .= '<a href="'.esc_url($icon_link).'" target="'.esc_attr($target).'" class="hbe-toggle hicon">';
								$output .= $icon_output;
								$output .= $icon_back;
							$output .= '</a>';
		            	} else {
		            		$output .= '<span class="hbe-toggle hicon">';
								$output .= $icon_output;
								$output .= $icon_back;
							$output .= '</span>';
		            	}
		            $output .= '</div>';

				} else {
					$output .= esc_html__("Please upload svg");
				}

	            $id_counter++;

	            return $output;

	        }
        }

        add_shortcode('et_header_icon', 'et_header_icon');

    /*  et_header_button
    --------------*/

	    function et_header_button( $atts, $content = null ) {

			extract(shortcode_atts(array(
				'button_text' 		    => '',
				'button_link' 	        => '',
				'megamenu' 	            => '',
				'megamenu_ajax' 	    => 'false',
				'submenu_appear'        => 'none',
				'submenu_toggle'        => 'hover',
				'submenu_shadow'        => 'false',
				'target'                => '_self',
				'button_link_modal'     => 'false',
				'width'                 => '',
				'height'				=> 48,
				'button_shadow' 	    => 'false',
				'button_style' 	        => 'normal',
				'button_type'           => 'default',
				'button_size'           => 'medium',
				'button_size_custom'    => 'false',
				'button_color'          => '#ffffff',
				'button_color_hover'    => '#ffffff',
				'icon' 	                => '',
				'icon_position'         => 'left',
				'icon2' 	            => '',
				'icon2_position'        => 'left',
				'animate_hover' 	    => 'none',
				'animate_hover_outline' => 'none',
				'click_smooth' 	        => 'false',
				'align'                 => 'none',
				'extra_class'           => '',
	            'element_id'            => '',
	            'hide_default'          => 'false',
				'hide_sticky'           => 'false'
			), $atts));

			static $id_counter = 1;

            $output      = '';

            $class = array();
            $link_class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			if ($button_style == "outline") {
				$animate_hover = $animate_hover_outline;
			}

            $class[] = 'et-header-button';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
            $class[] = 'hbe-'.$align;
            $class[] = 'megamenu-ajax-'.$megamenu_ajax;
            $class[] = 'submenu-appear-'.$submenu_appear;
            $class[] = 'submenu-toggle-'.$submenu_toggle;
            $class[] = 'submenu-shadow-'.$submenu_shadow;

            if (!empty($megamenu)) {
            	$class[] = 'mm-true';
            }

            if ($button_size_custom == "true") {
            	$button_size = 'custom';
            }

            $link_class[] = 'et-button';
            $link_class[] = 'icon-position-'.$icon_position;
            $link_class[] = 'icon2-position-'.$icon2_position;
            $link_class[] = 'modal-'.$button_link_modal;
            $link_class[] = 'hover-'.$animate_hover;
			$link_class[] = 'smooth-'.$click_smooth;
            $link_class[] = 'shadow-'.$button_shadow;
			$link_class[] = $button_type;
			$link_class[] = $button_style;
			$link_class[] = $button_size;

			if ($button_link_modal == "true") {
				$target = "_self";
			}

			if (isset($icon) && !empty($icon)) {
				$class[] = 'has-icon';
			}

			if (isset($click_smooth) && $click_smooth == "true") {
				$class[] = 'click-smooth';
			}

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

			if ($button_link_modal == "true") {$target = "_self";}

			$attributes   = array();
			$attributes[] = 'target="'.esc_attr($target).'"';
			$attributes[] = 'href="'.esc_url($button_link).'"';
			$attributes[] = 'data-effect="'.esc_attr($animate_hover).'"';
			$attributes[] = 'class="'.implode(" ", $link_class).'"';

			if ($animate_hover == 'fill') {
				$attributes[] = 'data-color="'.esc_attr($button_color).'"';
				$attributes[] = 'data-color-hover="'.esc_attr($button_color_hover).'"';
			}

			if (isset($button_text) && !empty($button_text) && isset($button_link) && !empty($button_link)) {

				$output .='<div id="et-header-button-'.$element_id.'" class="'.implode(" ", $class).'" data-megamenu="'.esc_attr($megamenu).'">';

					$output .='<a '.implode(" ", $attributes).'>';

						$icon_output = '';

						if (isset($icon) && !empty($icon)) {

							$icon = get_post($icon);

							if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {

								$icon_output = '<span class="icon">';
							    	if (et_get_icon($icon->guid)) {
										$icon_output .= et_get_icon($icon->guid);
						            }
							    	if (!empty($megamenu) && $submenu_toggle == "click") {
							    		if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
								        	$icon_output .= et_get_theme_icon()['close'];
							            }
							    	}
							    $icon_output .= '</span>';
							}

						}

						$icon2_output = '';

						if (isset($icon2) && !empty($icon2)) {

							$icon2 = get_post($icon2);

							if (is_object($icon2) && $icon2->post_mime_type == 'image/svg+xml') {
								if (et_get_icon($icon2->guid)) {
									$icon2_output = '<span class="icon">'.et_get_icon($icon2->guid).'</span>';
					            }
							}

						}

						if ($icon_position == "left" && !empty($icon_output)) {$output .= $icon_output;}
						if ($icon2_position == "left" && !empty($icon2_output)) {$output .= $icon2_output;}
						$output .='<span class="text">'.esc_attr($button_text).'</span>';
						if ($icon_position == "right" && !empty($icon_output)) {$output .= $icon_output;}
						if ($icon2_position == "right" && !empty($icon2_output)) {$output .= $icon2_output;}

						$output .='<span class="button-back">';
							$output .= '<span class="regular"></span>';
							if ($animate_hover == "fill") {
						    	$output .= '<span class="hover"></span>';
							}
						$output .='</span>';

					$output .='</a>';

					if (!empty($megamenu) && $megamenu_ajax == 'false') {

						$megamenus = enovathemes_addons_megamenus();

						if (!is_wp_error($megamenus)) {
							$output .= do_shortcode(gzuncompress($megamenus[$megamenu][2]));
						}
					}

				$output .='</div>';
			}

			$id_counter++;

			return $output;
		}
		add_shortcode('et_header_button', 'et_header_button');

	/*  et_align_container
    --------------*/

        function et_align_container($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'extra_class' => '',
                    'align'       => 'none',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] ='align-container';
            $class[] ='align-'.$align;

            $output .= '<div id="align-container-'.$id_counter.'" class="'.implode(" ", $class).'">'.do_shortcode($content).'</div>';

			$id_counter++;

	        return $output;
        }

        add_shortcode('et_align_container', 'et_align_container');

	/*  et_header_mobile_container
    --------------*/

        function et_header_mobile_container($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'extra_class' => '',
                    'element_id'  => '',
                    'async'       => 'false'
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'mobile-container';

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="mobile-container-'.$element_id.'" class="'.implode(" ", $class).'" data-content="'.(($async == "true") ? base64_encode(gzcompress($content)) : '').'">';
				$output .= '<div class="mobile-container-inner et-clearfix">';
					if ($async == "false") {
						$output .= do_shortcode($content);
					}
				$output .= '</div>';
				$output .= do_shortcode('[et_mobile_close size="small"]');
			$output .= '</div>';
			$output .= '<div id="mobile-container-overlay-'.$element_id.'" class="mobile-container-overlay"></div>';

			$id_counter++;

	        return $output;
        }

        add_shortcode('et_header_mobile_container', 'et_header_mobile_container');

   /*	et_mobile_container_tab
	--------------*/

		function et_mobile_container_tab($atts, $content = null) {

			extract(shortcode_atts(array(
				'title'  => '',
				'icon'   => ''
			), $atts));

			$output = '';

			static $id_counter = 1;

			$output .= '<div data-target="mobile-container-tab-'.$id_counter.'" class="mobile-container-tab et-clearfix">';
				if (isset($icon) && !empty($icon)) {
					$icon = get_post($icon);
					if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
						if (et_get_icon($icon->guid)) {
							$icon_output = et_get_icon($icon->guid);
			            }
						$output .= '<span class="icon">'.$icon_output.'</span>';
					}
				}
				if (isset($title) && !empty($title)) {
					$output .= esc_html($title);
				}
			$output .= '</div>';

			$output .= '<div id="mobile-container-tab-'.$id_counter.'" class="mobile-container-tab-content et-clearfix">';
			    $output .= do_shortcode($content);
			$output .= '</div>';

			$id_counter++;

			return $output;
		}
		add_shortcode('et_mobile_container_tab', 'et_mobile_container_tab');

    /*	et_mobile_toggle
	--------------*/

		function et_mobile_toggle($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'                 => 'none',
					'size'                  => 'medium',
					'extra_class'     		=> '',
					'element_id'            => '',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'mobile-container-toggle';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;
			$class[] = 'size-'.$size;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="mobile-container-toggle-'.$element_id.'" class="'.implode(" ", $class).'">';
				if (et_get_theme_icon() && isset(et_get_theme_icon()['mobile-toggle'])) {
					$output .= '<div id="mobile-toggle-'.$element_id.'" class="mobile-toggle hbe-toggle">'.et_get_theme_icon()['mobile-toggle'].'</div>';
	            }
			$output .= '</div>';

			$id_counter++;

			return $output;

		}

		add_shortcode('et_mobile_toggle', 'et_mobile_toggle');

	/* et_mobile_close
    --------------*/

        function et_mobile_close($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'       => 'none',
                    'size'        => 'medium',
                    'extra_class' => '',
                    'element_id'  => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'mobile-container-toggle';
            $class[] = 'mobile-container-close';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hbe-'.$align;
            $class[] = 'size-'.$size;

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="mobile-container-toggle-'.$element_id.'" class="'.implode(" ", $class).'">';
				if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
					$output .= '<div id="mobile-toggle-'.$element_id.'" class="mobile-toggle hbe-toggle active">'.et_get_theme_icon()['close'].'</div>';
	            }
			$output .= '</div>';

            $id_counter++;

            return $output;

        }

        add_shortcode('et_mobile_close', 'et_mobile_close');

	/*	et_mobile_menu
	--------------*/

		function et_mobile_menu($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'menu'            		=> 'choose',
					'extra_class'     		=> '',
					'element_id'            => '',
					'text_align'            => 'left'
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'mobile-menu-container';
			$class[] = 'hbe';
			$class[] = 'text-align-'.$text_align;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$menu_arg = array();

			if (empty($menu) || $menu == "choose" || !isset($menu)) {
				if (has_nav_menu( 'header-menu' )) {
					$menu_arg = array(
						'theme_location'  => 'header-menu',
						'menu_class'      => 'mobile-menu hbe-inner et-clearfix',
						'menu_id'         => 'mobile-menu-'.$element_id,
						'container'       => 'div',
						'container_class' => implode(" ", $class),
						'container_id'    => 'mobile-menu-container-'.$element_id,
						'echo'            => false,
						'link_before'     => '<span class="txt">',
						'link_after'      => (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '</span>',
						'depth'           => 10,
						'walker'          => new et_scm_walker_light
					);
				}
			} else {
				$menu_arg = array(
					'menu'  => $menu,
					'menu_class'      => 'mobile-menu hbe-inner et-clearfix',
					'menu_id'         => 'mobile-menu-'.$element_id,
					'container'       => 'div',
					'container_class' => implode(" ", $class),
					'container_id'    => 'mobile-menu-container-'.$element_id,
					'echo'            => false,
					'link_before'     => '<span class="txt">',
					'link_after'      => (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '</span>',
					'depth'           => 10,
					'walker'          => new et_scm_walker_light
				);
			}

			if (!empty($menu_arg)) {
				$output .= wp_nav_menu($menu_arg);
			}


			$id_counter++;

			return $output;
		}

		add_shortcode('et_mobile_menu', 'et_mobile_menu');

	/*	et_mobile_tab
	--------------*/

		function et_mobile_tab($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'account'    => '',
					'cart'       => '',
					'wishlist'   => '',
					'compare'    => '',
					'home'       => esc_url(home_url('/')),
					'menu'       => '#',
					'element_id' => ''
				), $atts)
			);

			global $woocommerce;

			$output = '';
			static $id_counter = 1;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$class = array();

			$class[] = 'et-mobile-tab';
			$class[] = 'et-clearfix';

			$tabs = array(
				'home'       => $home,
				'account'    => $account,
				'cart'       => $cart,
				'wishlist'   => $wishlist,
				'compare'    => $compare,
			);

			$output .='<div id="et-mobile-tab-'.$element_id.'" class="'.implode(" ", $class).'">';

				$output .='<div class="mob-tabset-toggle et-icon back-active">';

				if (et_get_dashboard_icon() && isset(et_get_dashboard_icon()['account'])) {
	                $output .= et_get_dashboard_icon()['account'];
	            }

	            if (et_get_theme_icon() && isset(et_get_theme_icon()['close'])) {
	                $output .= et_get_theme_icon()['close'];
	            }

				$output .='</div>';

				$output .='<div class="mob-tabset">';

					foreach($tabs as $link => $href) {
						if (!empty($href)) {
							$output .='<a class="'.$link.' mob-tab-content tab" href="'.$href.'">';
								if (et_get_dashboard_icon() && isset(et_get_dashboard_icon()[$link])) {
									$output .= '<span class="icon">'.et_get_dashboard_icon()[$link].'</span>';
					            }
								if ($link == "cart") {
									if (class_exists('Woocommerce')) {
				                		if ($woocommerce->cart->cart_contents_count) {
											$output .= '<span class="cart-contents">';
				                        		$output .= '<span class="cart-info">'.$GLOBALS['woocommerce']->cart->cart_contents_count.'</span>';
											$output .= '</span>';
										} else {
											$output .= '<span class="cart-contents">';
				                        		$output .= '<span class="cart-info">0</span>';
											$output .= '</span>';
										}
				                	} else {
				                		$output .= '<span class="cart-contents">';
				                    		$output .= '<span class="cart-info">0</span>';
										$output .= '</span>';
				                	}
								}
								if ($link == "wishlist") {
									$output .= '<span class="wishlist-contents">0</span>';
								}
								if ($link == "compare") {
									$output .= '<span class="compare-contents">0</span>';
								}
							$output .='</a>';
						}
					}

				$output .='</div>';

			$output .= '</div>';

			$id_counter++;

			return $output;

		}
		add_shortcode('et_mobile_tab', 'et_mobile_tab');

	/*  et_header_modal_container
    --------------*/

		function et_header_modal_container($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'extra_class' => '',
					'element_id'  => '',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'modal-container';

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="modal-container-'.$element_id.'" class="'.implode(" ", $class).'">';
				$output .= '<div class="modal-container-inner et-clearfix">';
					$output .= do_shortcode($content);
				$output .= '</div>';
				if (et_get_theme_icon() && isset(et_get_theme_icon()['modal-container-back'])) {
	                $output .= et_get_theme_icon()['modal-container-back'];
	            }
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_header_modal_container', 'et_header_modal_container');

	/*	et_modal_toggle
	--------------*/

		function et_modal_toggle($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'                 => 'none',
					'size'                  => 'medium',
					'extra_class'     		=> '',
					'element_id'            => '',
					'hide_default'          => 'false',
					'hide_sticky'           => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'modal-container-toggle';
			$class[] = 'hbe hbe-icon-element';
			$class[] = 'hide-default-'.$hide_default;
			$class[] = 'hide-sticky-'.$hide_sticky;
			$class[] = 'hbe-'.$align;
			$class[] = 'size-'.$size;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="modal-container-toggle-'.$element_id.'" class="'.implode(" ", $class).'">';
				if (et_get_theme_icon() && isset(et_get_theme_icon()['mobile-toggle'])) {
					$output .= '<div id="modal-toggle-'.$element_id.'" class="modal-toggle hbe-toggle">'.et_get_theme_icon()['mobile-toggle'].'</div>';
	            }
			$output .= '</div>';

			$id_counter++;

			return $output;

		}

		add_shortcode('et_modal_toggle', 'et_modal_toggle');

	/* et_modal_close
    --------------*/

        function et_modal_close($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'align'       => 'none',
                    'size'        => 'medium',
                    'extra_class' => '',
                    'element_id'  => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'modal-container-toggle';
            $class[] = 'hbe hbe-icon-element';
            $class[] = 'hbe-'.$align;
            $class[] = 'size-'.$size;

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div id="modal-container-toggle-'.$element_id.'" class="'.implode(" ", $class).'">';
				if (et_get_theme_icon() && isset(et_get_theme_icon()['mobile-toggle'])) {
					$output .= '<div id="modal-toggle-'.$element_id.'" class="modal-toggle hbe-toggle active">'.et_get_theme_icon()['mobile-toggle'].'</div>';
	            }
			$output .= '</div>';

            $id_counter++;

            return $output;

        }

        add_shortcode('et_modal_close', 'et_modal_close');

    /*  et_modal_menu
    --------------*/

        function et_modal_menu($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'menu'         => 'choose',
                    'extra_class'  => '',
                    'element_id'   => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'modal-menu-container';
            $class[] = 'nav-menu-container';
            $class[] = 'hbe';
            $class[] = 'text-align-left';

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

            $menu_arg = array();

            if (empty($menu) || $menu == "choose" || !isset($menu)) {
				if (has_nav_menu( 'header-menu' )) {
					$menu_arg = array(
						'theme_location'  => 'header-menu',
						'menu_class'      => 'modal-menu nav-menu hbe-inner et-clearfix',
		                'menu_id'         => 'modal-menu-'.$element_id,
		                'container'       => 'div',
		                'container_class' => implode(" ", $class),
		                'container_id'    => 'modal-menu-container-'.$element_id,
		                'echo'            => false,
		                'link_before'     => '<span class="txt">',
		                'link_after'      => (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '</span>',
		                'depth'           => 2,
					);
				}
			} else {

	            $menu_arg = array(
	                'menu'  => $menu,
	                'menu_class'      => 'modal-menu nav-menu hbe-inner et-clearfix',
	                'menu_id'         => 'modal-menu-'.$element_id,
	                'container'       => 'div',
	                'container_class' => implode(" ", $class),
	                'container_id'    => 'modal-menu-container-'.$element_id,
	                'echo'            => false,
	                'link_before'     => '<span class="txt">',
	                'link_after'      => (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '</span>',
	                'depth'           => 2,
	            );

	        }

	        if (!empty($menu_arg)) {
            	$output .= wp_nav_menu($menu_arg);
	        }


            $id_counter++;

            return $output;
        }

        add_shortcode('et_modal_menu', 'et_modal_menu');

    /*  et_vertical_align_top
    --------------*/

        function et_vertical_align_top($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'extra_class' => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'snva';
            $class[] = 'vertical-align-top';

            $output .= '<div id="vertical-align-top-'.$id_counter.'" class="'.implode(" ", $class).'">'.do_shortcode($content).'</div>';

			$id_counter++;

	        return $output;
        }

        add_shortcode('et_vertical_align_top', 'et_vertical_align_top');

    /*  et_vertical_align_middle
    --------------*/

        function et_vertical_align_middle($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'extra_class' => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'snva';
            $class[] = 'vertical-align-middle';

            $output .= '<div id="vertical-align-middle-'.$id_counter.'" class="'.implode(" ", $class).'">'.do_shortcode($content).'</div>';

			$id_counter++;

	        return $output;
        }

        add_shortcode('et_vertical_align_middle', 'et_vertical_align_middle');

    /*  et_vertical_align_bottom
    --------------*/

        function et_vertical_align_bottom($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'extra_class' => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'snva';
            $class[] = 'vertical-align-bottom';

            $output .= '<div id="vertical-align-bottom-'.$id_counter.'" class="'.implode(" ", $class).'">'.do_shortcode($content).'</div>';

			$id_counter++;

	        return $output;
        }

        add_shortcode('et_vertical_align_bottom', 'et_vertical_align_bottom');

    /*  et_header_sidebar_container
    --------------*/

        function et_header_sidebar_container($atts, $content = null) {

            extract(shortcode_atts(
                array(
                    'extra_class' => '',
                    'element_id'  => '',
                ), $atts)
            );

            static $id_counter = 1;

            $output      = '';

            $class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

            $class[] = 'sidebar-container';

            $element_id = (!empty($element_id)) ? $element_id : $id_counter;

            $output .= '<div id="sidebar-container-'.$element_id.'" class="'.implode(" ", $class).'">';

				$output .= '<div id="sidebar-container-toggle-'.$element_id.'" class="sidebar-container-toggle">';
					if (et_get_theme_icon() && isset(et_get_theme_icon()['sidebar-toggle'])) {
						$output .= '<div id="sidebar-toggle-'.$element_id.'" class="sidebar-toggle hbe-toggle">'.et_get_theme_icon()['sidebar-toggle'].'</div>';
		            }
				$output .= '</div>';

				$output .= '<div class="sidebar-container-content">';
					$output .= do_shortcode($content);
				$output .= '</div>';

			$output .= '</div>';

			$id_counter++;

	        return $output;
        }

        add_shortcode('et_header_sidebar_container', 'et_header_sidebar_container');

    /*	et_sidebar_menu
	--------------*/

		function et_sidebar_menu($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'menu'            	=> 'choose',
					'submenu_indicator' => 'false',
					'extra_class'     	=> '',
					'element_id'        => ''
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'sidebar-menu-container';
			$class[] = 'nav-menu-container';
			$class[] = 'hbe';
			$class[] = 'tl-text-align-left';
			$class[] = 'tl-submenu-ind-'.$submenu_indicator;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$menu_arg = array();

			if (empty($menu) || $menu == "choose" || !isset($menu)) {
				if (has_nav_menu( 'header-menu' )) {
					$menu_arg = array(
						'theme_location'  => 'header-menu',
						'menu_class'      => 'sidebar-menu nav-menu hbe-inner et-clearfix',
						'menu_id'         => 'sidebar-menu-'.$element_id,
						'container'       => 'div',
						'container_class' => implode(" ", $class),
						'container_id'    => 'sidebar-menu-container-'.$element_id,
						'echo'            => false,
						'link_before'     => '<span class="txt">',
						'link_after'      => (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '</span>',
						'depth'           => 2,
						'walker'          => new et_scm_walker
					);
				}
			} else {

				$menu_arg = array(
					'menu'  => $menu,
					'menu_class'      => 'sidebar-menu nav-menu hbe-inner et-clearfix',
					'menu_id'         => 'sidebar-menu-'.$element_id,
					'container'       => 'div',
					'container_class' => implode(" ", $class),
					'container_id'    => 'sidebar-menu-container-'.$element_id,
					'echo'            => false,
					'link_before'     => '<span class="txt">',
					'link_after'      => (et_get_theme_icon() && isset(et_get_theme_icon()['arrow'])) ? '</span><span class="arrow">'.et_get_theme_icon()['arrow'].'</span>' : '</span>',
					'depth'           => 2,
					'walker'          => new et_scm_walker
				);

			}

			if (!empty($menu_arg)) {
				$output .= wp_nav_menu($menu_arg);
			}


			$id_counter++;

			return $output;
		}

		add_shortcode('et_sidebar_menu', 'et_sidebar_menu');

	/*	et_bullets
	--------------*/

		function et_bullets($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'menu'        => '',
					'extra_class' => '',
					'element_id'  => '',
					'offset'      => '0'
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'bullets-container';
			$class[] = 'one-page-true';
			$class[] = 'one-page-offset-'.$offset;

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			if (!empty($menu)) {

				$menu_arg = array(
					'menu'  => $menu,
					'menu_class'      => 'bullets-menu hbe-inner et-clearfix',
					'menu_id'         => 'bullets-menu-'.$element_id,
					'container'       => 'nav',
					'container_class' => implode(" ", $class),
					'container_id'    => 'bullets-menu-container-'.$element_id,
					'link_after'      => '<span class="effect"></span>',
					'echo'            => false,
					'depth'           => 1,
				);

				$output .= wp_nav_menu($menu_arg);

			}

			$id_counter++;

			return $output;
		}

		add_shortcode('et_bullets', 'et_bullets');

/*  TITLE SECTION
/*------------*/

	/*	et_title_section_title
	--------------*/

		function et_title_section_title($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'type'        => 'h1',
					'align'       => 'left',
					'tablet_align'=> 'left',
					'mobile_align'=> 'left',
					'text_align'  => 'left',
					'mfs'         => 'i',
					'mls'         => 'i',
					'mf'          => 'i',
					'ml'          => 'i',
					'tlf'         => 'i',
					'tll'         => 'i',
					'tpf'         => 'i',
					'tpl'         => 'i',
					'extra_class' => '',
					'element_id'  => '',
					'etp_title'   => '',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class   = array();
			$class[] = 'title-section-title-container tse';
			$class[] = 'text-align-'.$text_align;
			$class[] = 'align-'.$align;
			$class[] = 'tablet-align-'.$tablet_align;
			$class[] = 'mobile-align-'.$mobile_align;

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$attributes   = array();
			if ($mfs != 'i') {
				$attributes[] = 'data-374-f="'.esc_attr($mfs).'"';
			}
			if ($mls != 'i') {
				$attributes[] = 'data-374-lh="'.esc_attr($mls).'"';
			}

			if ($mf != 'i') {
				$attributes[] = 'data-375-767-f="'.esc_attr($mf).'"';
			}
			if ($ml != 'i') {
				$attributes[] = 'data-375-767-lh="'.esc_attr($ml).'"';
			}

			if ($tpf != 'i') {
				$attributes[] = 'data-768-1023-f="'.esc_attr($tpf).'"';
			}
			if ($tpf != 'i') {
				$attributes[] = 'data-768-1023-lh="'.esc_attr($tpl).'"';
			}

			if ($tlf != 'i') {
				$attributes[] = 'data-1024-1279-f="'.esc_attr($tlf).'"';
			}
			if ($tll != 'i') {
				$attributes[] = 'data-1024-1279-lh="'.esc_attr($tll).'"';
			}

			$output .= '<div class="'.implode(" ",$class).'">';
				$output .= '<'.$type.' class="title-section-title" id="title-section-title-'.$element_id.'" '.implode(" ",$attributes).'>';
					$output .= esc_html($etp_title);
				$output .= '</'.$type.'>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_title_section_title', 'et_title_section_title');

	/*	et_title_section_subtitle
	--------------*/

		function et_title_section_subtitle($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'type'        => 'p',
					'align'       => 'left',
					'tablet_align'=> 'left',
					'mobile_align'=> 'left',
					'text_align'  => 'left',
					'mf'             => 'inherit',
					'ml'           => 'inherit',
					'tlf'   => 'inherit',
					'tll' => 'inherit',
					'tpf'    => 'inherit',
					'tpf'  => 'inherit',
					'extra_class' => '',
					'element_id'  => '',
					'etp_subtitle'=> '',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class   = array();
			$class[] = 'title-section-subtitle-container tse';
			$class[] = 'text-align-'.$text_align;
			$class[] = 'align-'.$align;
			$class[] = 'tablet-align-'.$tablet_align;
			$class[] = 'mobile-align-'.$mobile_align;

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$attributes   = array();
			$attributes[] = 'data-mobile-font="'.esc_attr($mf).'"';
			$attributes[] = 'data-mobile-line-height="'.esc_attr($ml).'"';
			$attributes[] = 'data-tablet-landscape-font="'.esc_attr($tlf).'"';
			$attributes[] = 'data-tablet-portrait-font="'.esc_attr($tpf).'"';
			$attributes[] = 'data-tablet-landscape-line-height="'.esc_attr($tll).'"';
			$attributes[] = 'data-tablet-portrait-line-height="'.esc_attr($tpf).'"';

			$output .= '<div class="'.implode(" ",$class).'">';
				$output .= '<'.$type.' class="title-section-subtitle" id="title-section-subtitle-'.$element_id.'" '.implode(" ",$attributes).'>';
					$output .= esc_html($etp_subtitle);
				$output .= '</'.$type.'>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_title_section_subtitle', 'et_title_section_subtitle');

	/*	et_breadcrumbs
	--------------*/

		function et_breadcrumbs($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'align'            => 'left',
					'tablet_align'     => 'left',
					'mobile_align'     => 'left',
					'text_align'       => 'left',
					'extra_class'      => '',
					'element_id'       => '',
					'etp_breadcrumbs'  => '',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class   = array();
			$class[] = 'et-breadcrumbs-container tse';
			$class[] = 'text-align-'.$text_align;
			$class[] = 'align-'.$align;
			$class[] = 'tablet-align-'.$tablet_align;
			$class[] = 'mobile-align-'.$mobile_align;

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$output .= '<div class="'.implode(" ",$class).'" id="et-breadcrumbs-container-'.$element_id.'">';
				$output .= '<div id="et-breadcrumbs-'.$element_id.'" class="et-breadcrumbs">'.$etp_breadcrumbs.'</div>';
			$output .= '</div>';

			$id_counter++;

			return $output;
		}

		add_shortcode('et_breadcrumbs', 'et_breadcrumbs');

/*  WIDGETS
/*------------*/

	/*  widget_facebook
	/*------------*/

		function widget_facebook($atts, $content = null) {

			extract(shortcode_atts(
				array(
				'title'         	    => '',
	 			'app_id'        	    => '',
				'language_code' 	    => 'en_US',
				'href'          	    => '',
				'width'         	    => '',
				'height'        	    => '',
				'timeline'      	    => 'true',
				'messages'      	    => 'true',
				'events'        	    => 'true',
				'hide_cover'    	    => 'false',
				'show_facepile' 	    => 'true',
				'small_header'  	    => 'false',
				'adapt_container_width' => 'true',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-facebook-'.$id_counter.'" class="widget widget_facebook">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'title'         	    => $title,
		 			'app_id'        	    => $app_id,
					'language_code' 	    => $language_code,
					'href'          	    => $href,
					'width'         	    => $width,
					'height'        	    => $height,
					'timeline'      	    => $timeline,
					'messages'      	    => $messages,
					'events'        	    => $events,
					'hide_cover'    	    => $hide_cover,
					'show_facepile' 	    => $show_facepile,
					'small_header'  	    => $small_header,
					'adapt_container_width' => $adapt_container_width,
				);

				$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Facebook', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_facebook', 'widget_facebook');

	/*  widget_flickr
	/*------------*/

		function widget_flickr($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'          => '',
		 			'photos_number'  => '6',
		 			'flickr_id'      => '',
		 			'image_size'     => 'square',
		 			'display'        => 'latest',
		 			'columns_mob'    => '1',
		 			'columns_tablet' => '1',
		 			'columns_desk'   => '1',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-flickr-'.$id_counter.'" class="widget widget_flickr">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'title'       => $title,
					'photos_number'  => $photos_number,
		 			'flickr_id'      => $flickr_id,
		 			'image_size'     => $image_size,
		 			'display'        => $display,
		 			'columns_mob'    => $columns_mob,
		 			'columns_tablet' => $columns_tablet,
		 			'columns_desk'   => $columns_desk,
				);

				$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Flickr', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_flickr', 'widget_flickr');

	/*  widget_banner
	/*------------*/

		function widget_banner($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'   => '',
		 			'banner'  => '',
		 			'align'   => 'normal',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-banner-'.$id_counter.'" class="widget widget_banner align'.esc_attr($align).'">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'title'   => $title,
					'banner'  => $banner
				);

				$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Banner', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_banner', 'widget_banner');

	/*  widget_mailchimp
	/*------------*/

		function widget_mailchimp($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'       => '',
		 			'description' => '',
		 			'list'        => '',
		 			'first_name'  => 'false',
		 			'last_name'   => 'false',
		 			'phone'       => 'false',
		 			'required_first_name'  => 'false',
		 			'required_last_name'   => 'false',
		 			'required_phone'       => 'false',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-mailchimp-'.$id_counter.'" class="widget widget_mailchimp">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'terms'       => false,
					'title'       => $title,
		 			'description' => $description,
		 			'list'        => $list,
		 			'first_name'  => $first_name,
		 			'last_name'   => $last_name,
		 			'phone'       => $phone,
		 			'required_first_name'  => $required_first_name,
		 			'required_last_name'   => $required_last_name,
		 			'required_phone'       => $required_phone,
				);

				$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Mailchimp', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_mailchimp', 'widget_mailchimp');

	/*  widget_posts
	/*------------*/

		function widget_posts($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title' => '',
					'number'=> '',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-posts-'.$id_counter.'" class="widget widget_et_recent_entries">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'title'  => $title,
					'number' => intval($number),
				);

				$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Posts', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_posts', 'widget_posts');

	/*  widget_login
	/*------------*/

		function widget_login($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'             => '',
					'registration_link' => '',
					'forgot_link'       => '',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-login-'.$id_counter.'" class="widget widget_login widget_reglog">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'title'  => $title,
					'registration_link'=> $registration_link,
					'forgot_link'=> $forgot_link,
				);

				$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Widget_Login', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_login', 'widget_login');

	/*  widget_product_categories
	/*------------*/

		function widget_product_categories($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'              => '',
					'orderby'            => 'order',
					'dropdown'           => '',
					'count'              => '',
					'hierarchical'       => '',
					'show_children_only' => '',
					'hide_empty'         => '',
					'max_depth'          => '',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-product-categories-'.$id_counter.'" class="widget widget_product_categories">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
				);

				$instance = array(
					'title'  			 => $title,
					'orderby'            => $orderby,
					'dropdown'           => $dropdown,
					'count'              => $count,
					'hierarchical'       => $hierarchical,
					'show_children_only' => $show_children_only,
					'hide_empty'         => $hide_empty,
					'max_depth'          => $max_depth,
				);

				$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Product_Categories', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_product_categories', 'widget_product_categories');

	/*  widget_products_by_rating
	/*------------*/

		function widget_products_by_rating($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'  => '',
					'number' => ''
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-top-rated-products-'.$id_counter.'" class="widget widget_top_rated_products">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
	                'widget_id'     => $id_counter,
				);

				$instance = array(
					'title'  	=> $title,
					'number'    => $number
				);

				$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Top_Rated_Products', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_products_by_rating', 'widget_products_by_rating');

	/*  widget_products
	/*------------*/

		function widget_products($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'       => '',
					'number'      => '',
					'show'        => '',
					'orderby'     => '',
					'order'       => '',
					'hide_free'   => '',
					'show_hidden' => '',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget-products-'.$id_counter.'" class="widget widget_products">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
	                'widget_id'     => $id_counter,
				);

				$instance = array(
					'title'  	=> $title,
					'number'    => $number,
					'show'        => $show,
					'orderby'     => $orderby,
					'order'       => $order,
					'hide_free'   => $hide_free,
					'show_hidden' => $show_hidden,
				);

				$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Products', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_products', 'widget_products');

	/*  widget_recent_product_reviews
	/*------------*/

		function widget_recent_product_reviews($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'  => '',
					'number' => ''
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget_recent_reviews-'.$id_counter.'" class="widget widget_recent_reviews">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
	                'widget_id'     => $id_counter,
				);

				$instance = array(
					'title'  	=> $title,
					'number'    => $number
				);

				$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Recent_Reviews', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_recent_product_reviews', 'widget_recent_product_reviews');

	/*  widget_recent_viewed_products
	/*------------*/

		function widget_recent_viewed_products($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'  => '',
					'number' => ''
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget_recently_viewed_products-'.$id_counter.'" class="widget widget_recently_viewed_products">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
	                'widget_id'     => $id_counter,
				);

				$instance = array(
					'title'  	=> $title,
					'number'    => $number
				);

				$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Recently_Viewed', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_recent_viewed_products', 'widget_recent_viewed_products');

	/*  widget_product_tag_cloud
	/*------------*/

		function widget_product_tag_cloud($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'  => '',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				$args = array(
					'before_widget' => '<div id="widget_product_tag_cloud-'.$id_counter.'" class="widget widget_product_tag_cloud">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget_title">',
	                'after_title'   => '</h5>',
	                'widget_id'     => $id_counter,
				);

				$instance = array(
					'title'  	=> $title,
				);

				$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Product_Tag_Cloud', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_product_tag_cloud', 'widget_product_tag_cloud');

	/*  widget_cart
	/*------------*/

		function widget_cart($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'title'             => '',
					'registration_link' => '',
					'forgot_link'       => '',
				), $atts)
			);

			$output = '';

			static $id_counter = 1;

				if (class_exists('Woocommerce')){

					$args = array(
						'before_widget' => '<div id="widget_shopping_cart-'.$id_counter.'" class="widget woocommerce widget_shopping_cart">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget_title">',
		                'after_title'   => '</h5>',
					);

					$instance = array(
						'title'  => $title,
					);

					$output .= propharm_enovathemes_get_the_widget( 'WC_Widget_Cart', $instance,$args);

				} else {
        			$output .= esc_html__('Please install Woocommerce','enovathemes-addons');
        		}

			$id_counter++;

			return $output;
		}

		add_shortcode('widget_cart', 'widget_cart');

/*  WOOCOMMERCE
/*------------*/

	function et_woo_products($atts, $content = null) {

		$shortcode_atts = shortcode_atts(
			array(
				'ajax'                  => 'false',
				'layout' 		        => 'grid',
				'navigation_type'       => 'arrows',
				'navigation_position'   => 'top',
				'autoplay'              => 'false',
				'carousel'              => 'false',
				'columns_grid'          => '1',
				'columns_list'          => '1',
				'columns_full'          => '1',
				'columns_grid_tab_port' => '1',
				'columns_grid_tab_land' => '1',
				'columns_list_tab_port' => '1',
				'columns_list_tab_land' => '1',
				'rows'                  => '1',
				'highlight'             => 'false',
				'discount'              => 'false',
				'min_height'            => '',
				'max_height'            => '',
				'quantity' 		        => '12',
				'category' 		        => '',
				'operator' 		        => 'IN',
				'orderby' 		        => 'date',
				'order' 		        => 'ASC',
				'type' 			        => 'recent',
				'attribute' 	        => '',
				'filter' 	            => '',
				'ids' 			        => '',
				'element_id'            => ''
		), $atts);

		extract($shortcode_atts);

		if (class_exists('Woocommerce')) {

			global $post, $propharm_enovathemes, $woocommerce;

			$viewed = 'true';

			$query_options = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby'             => $orderby,
				'order'               => $order,
				'posts_per_page' 	  => absint($quantity),
			);

			if ($type == "custom"){
				if ( ! empty( $ids ) ) {
					$query_options['post__in'] = array_map( 'trim', explode( ',', $ids ) );
				}
			} elseif ($type == "featured"){
				$query_options = array(
					'post_type'           => 'product',
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'orderby'             => $orderby,
					'order'               => $order,
					'posts_per_page' 	  => absint($quantity),
					'tax_query'           => array(
						array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'featured',
							'operator' => 'IN',
						)
					),
				);
			} elseif($type == "related"){

				if ( $post && $post->post_type ) {
					$post_type = $post->post_type;
					if (!is_wp_error($post_type)) {
						if ( empty( $product ) || ! $product->is_visible() ) {
							return;
						}
						$terms = get_the_terms( $product->get_id() , 'product_tag');
						if ($terms) {
							$tagids = array();
							foreach($terms as $tag) {$tagids[] = $tag->term_id;}
						}
						$query_options = array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'ignore_sticky_posts' => 1,
							'posts_per_page'      => absint($quantity),
							'orderby'             => $orderby,
							'order'               => $order,
							'tax_query' => array(
			                    array(
			                        'taxonomy' => 'product_tag',
			                        'field'    => 'id',
			                        'terms'    => $tagids,
			                        'operator' => 'IN'
			                     )
			                ),
							'post__not_in'        => array($product->get_id())
						);
					}
				}
			} elseif($type == "sale"){


				$sales_ids = wc_get_product_ids_on_sale();


				if (!is_wp_error($sales_ids) && !empty($sales_ids)) {

					$query_options = array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby'             => $orderby,
						'order'               => $order,
						'posts_per_page' 	  => absint($quantity),
						'post__in'            => array_merge( array( 0 ), $sales_ids ),
					);

				}
			} elseif($type == "best_selling"){

				$orderby = 'meta_value_num';

				$query_options = array(
					'post_type'           => 'product',
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'orderby'             => $orderby,
					'order'               => $order,
					'posts_per_page' 	  => absint($quantity),
					'meta_key'            => 'total_sales',
				);
			} elseif($type == "attribute"){
				$query_options = array(
					'post_type'           => 'product',
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'orderby'             => $orderby,
					'order'               => $order,
					'posts_per_page' 	  => absint($quantity),
					'tax_query'           => array(
						array(
							'taxonomy' => strstr( $attribute, 'pa_' ) ? sanitize_title( $attribute ) : 'pa_' . sanitize_title( $attribute ),
							'terms'    => array_map( 'sanitize_title', explode( ',', $filter ) ),
							'field'    => 'slug',
						)
					),
				);
			} elseif($type == "viewed"){

				$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
				$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

				if ( empty( $viewed_products ) ){
					$viewed = 'false';
				} else {

					$quantity = count($viewed_products);

					$query_options = array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby'             => $orderby,
						'order'               => $order,
						'posts_per_page' 	  => absint($quantity),
						'post__in'            => $viewed_products,
					);
				}
			}

			if ($type != "custom" && $type != "related" && isset($category) && !empty($category)) {

				$query_options = array(
					'post_type'           => 'product',
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => true,
					'orderby'             => $orderby,
					'order'               => $order,
					'posts_per_page' 	  => absint($quantity),
					'tax_query'           => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => explode(',',$category),
							'operator' => $operator
						)
					)
				);

				if ($type == "featured"){
					$query_options = array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby'             => $orderby,
						'order'               => $order,
						'posts_per_page' 	  => absint($quantity),
						'tax_query'           => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => explode(',',$category),
								'operator' => $operator
							),
							array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
								'operator' => 'IN',
							)
						),
					);
				} elseif($type == "sale"){


					$sales_ids = wc_get_product_ids_on_sale();

					if (!is_wp_error($sales_ids) && !empty($sales_ids)) {
						$query_options = array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'ignore_sticky_posts' => 1,
							'orderby'             => $orderby,
							'order'               => $order,
							'posts_per_page' 	  => absint($quantity),
							'post__in'            => array_merge( array( 0 ), $sales_ids ),
							'tax_query'           => array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'slug',
									'terms'    => explode(',',$category),
									'operator' => $operator
								)
							)
						);
					}
				} elseif($type == "best_selling"){

					$orderby = 'meta_value_num';

					$query_options = array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby'             => $orderby,
						'order'               => $order,
						'posts_per_page' 	  => absint($quantity),
						'meta_key'            => 'total_sales',
						'tax_query'           => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => explode(',',$category),
								'operator' => $operator
							)
						)
					);
				} elseif($type == "attribute"){
					$query_options = array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby'             => $orderby,
						'order'               => $order,
						'posts_per_page' 	  => absint($quantity),
						'tax_query'           => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => explode(',',$category),
								'operator' => $operator
							),
							array(
								'taxonomy' => strstr( $attribute, 'pa_' ) ? sanitize_title( $attribute ) : 'pa_' . sanitize_title( $attribute ),
								'terms'    => array_map( 'sanitize_title', explode( ',', $filter ) ),
								'field'    => 'slug',
							)
						),
					);
				}

			}

			$output = '';

			if ($viewed == 'true') {

				if ($ajax == "false") {
					$output = woo_products_ajax($shortcode_atts,$query_options);
				} else {

					if ($quantity) {

						$class      = array();
						$list_class = array();
						$attributes = array();

						$class[] = 'ajax';

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

						$shortcode_atts = base64_encode(json_encode($shortcode_atts));
						$query_options = base64_encode(json_encode($query_options));

						$attributes[] = 'data-atts="'.$shortcode_atts.'"';
						$attributes[] = 'data-query="'.$query_options.'"';


						$counter = 1;

						$tag = ($carousel == "true") ? 'div' : 'ul';

						$output = '';

						$output = '<div '.implode(' ', $attributes).'>';
							$output .= '<'.$tag.' class="'.esc_attr(implode(' ', $list_class)).'" data-columns="'.esc_attr($columns).'" data-autoplay="'.esc_attr($autoplay).'" data-nav="'.esc_attr($navigation_type).'">';

								if ($carousel == "true") {$output .= '<ul class="slides">';}

								$placeholder = ($carousel == "true") ? $columns*$rows : (($quantity >= $columns*2) ? $columns*2 : $quantity);

								for ($i=1; $i <= $placeholder; $i++) {

									if (($counter % 2 == 1 && $rows == 2) || ($counter % 3 == 1 && $rows == 3)){
										$output .= '<li class="row-item"><ul>';
									}

									$output .= '<li class="product post placeholder">';
										$output .= '<div class="post-inner et-item-inner et-clearfix">';
											$output .= '<div class="post-image post-media overlay-hover">';
												$output .= '<div class="image-container loaded">';
            										$output .= '<svg viewBox="0 0 300 300"><path d="M0,0H300V300H0V0Z" /></svg>';
													$output .= propharm_enovathemes_svg_icon('placeholder');
												$output .= '</div>';
											$output .= '</div>';
											$output .= '<div class="post-body et-clearfix">';
												$output .= '<div class="post-body-inner">';
													if ($layout == 'list') {
										                $output .='<div class="star-rating-wrap"><div class="star-rating empty"></div></div>';
										                $output .='<div class="post-title et-clearfix"><span></span></div>';
										                $output .='<span class="price"></span>';
													} elseif($layout == 'full') {
														$output .='<div class="star-rating-wrap"><div class="star-rating empty"></div></div>';
										                $output .='<div class="post-title et-clearfix"><span></span></div>';
										                $output .='<span class="price"></span>';
										                $output .='<div class="product-short-description"><ul>
														 	<li><span></span></li>
														 	<li><span></span></li>
														 	<li><span></span></li>
														</ul></div>';
										                $output .='<div class="button"></div>';
									                } else {
										                $output .= '<div class="post-category et-clearfix"><span></span></div>';
										                $output .='<div class="post-title et-clearfix"><span></span></div>';
										                if ($layout != 'simple-grid') {
										                	$output .='<div class="star-rating-wrap"><div class="star-rating empty"></div></div>';
										                }
										                $output .='<span class="price"></span>';
										                if ($layout != 'simple-grid') {
										                	$output .='<div class="button"></div>';
										                }
									                }
												$output .= '</div>';
											$output .= '</div>';

										$output .= '</div>';
									$output .= '</li>';

									if (($counter % 2 == 0 && $rows == 2) || ($counter % 3 == 0 && $rows == 3) || ($counter % 4 == 0 && $rows == 4)){
										$output .= '</ul></li>';
									}

									$counter++;

								}

								if ($carousel == "true") {$output .= '</ul>';}

							$output .= '</'.$tag.'>';
						$output .= '</div>';

					}

				}

			} else {
				$output = esc_html__('You have not viewed any product yet!','enovathemes-addons');
			}

			if (!empty($output)) {
				return $output;
			}

		}
	}
	add_shortcode('et_woo_products', 'et_woo_products');

	function et_woo_categories($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'shadow'           => 'false',
				'overflow'         => 'false',
				'gap'              => 'false',
				'layout' 		   => 'grid',
				'carousel' 		   => 'false',
				'navigation_type'  => 'arrows',
				'navigation_position' => 'top',
				'autoplay'         => 'false',
				'columns_mob'      => '1',
				'columns_tab_port' => '1',
				'columns_tab_land' => '1',
				'columns_desktop'  => '1',
			), $atts)
		);

		if (class_exists('Woocommerce')) {

			$output = '';

			$element_id = rand(1,1000000);

			$class   = array();
			$class[] = $layout;
			$class[] = 'et-woo-categories';
			$class[] = 'border-false';
			$class[] = 'carousel-'.$carousel;
			$class[] = 'nav-pos-'.$navigation_position;
			$class[] = 'default-'.$shadow;
			$class[] = 'overflow-'.$overflow;
			$class[] = 'gap-'.$gap;

			$attributes   = array();
			$attributes[] = 'data-nav="'.$navigation_type.'"';
			$attributes[] = 'data-autoplay="'.$autoplay.'"';
			$attributes[] = 'data-columns-mob="'.$columns_mob.'"';
			$attributes[] = 'data-columns-tab-port="'.$columns_tab_port.'"';
			$attributes[] = 'data-columns-tab-land="'.$columns_tab_land.'"';
			$attributes[] = 'data-columns-desktop="'.$columns_desktop.'"';

			$output .='<div id="et-woo-categories-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
				$output .= '<ul class="slides">';
					$output .= do_shortcode($content);
				$output .= '</ul>';
			$output .= '</div>';

			return $output;
		}
	}
	add_shortcode('et_woo_categories', 'et_woo_categories');

	function et_woo_category($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'category'  => '',
				'image'     => '',
				'icon_size' => 'large',
				'title_tag' => 'h6',
				'children'  => 'false',
			), $atts)
		);

		if (class_exists('Woocommerce') && !empty($category)) {

			$categories_raw = get_product_categories_raw();

			$output = $icon_output = '';

			$category = $categories_raw[$category];

			$element_id = rand(1,1000000);

			$output .='<li class="et-woo-category children-'.$children.'">';
				if ($children != 'true') {
					$output .= '<a class="category-body" href="'.$category['link'].'" title="'.$category['name'].'">';
				} else {
					$output .= '<div class="category-body">';
				}

					$custom_image = false;

					if (isset($image) && !empty($image)) {

						$custom_image = true;

						$icon = get_post($image);

						if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
							if (et_get_icon($icon->guid)) {
								$icon_output = et_get_icon($icon->guid);
				            }
						} else {
							$image = wp_get_attachment_image_src($image, 'full');
							if($image){
								$width  = $image[1];
								$height = $image[2];
								$image  = $image[0];
							}
						}

					} elseif(isset($category['image']) && !empty($category['image'])) {
						$image = $category['image'];
						$height = $category['height'];
						$width = $category['width'];
					}

					if (!empty($image)) {

						if (!empty($icon_output)) {
				            $output .='<div class="et-icon size-'.$icon_size.'">';
		                        $output .= $icon_output;
		                    $output .='</div>';
						} else {
							$output .='<div class="post-media image-container custom-image-'.$custom_image.'">';
		            			$output .= '<img class="lazy" alt="'.esc_attr($category['name']).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="'.esc_url($image).'" />';
		                        $output .= propharm_enovathemes_svg_icon('placeholder');
		                    $output .='</div>';
						}
					}

					$output .= '<div class="category-content">';

						if ($children != 'true') {
							$output .='<'.$title_tag.' class="category-title">'.$category['name'].'</'.$title_tag.'>';

						} else {
							$output .='<'.$title_tag.' class="category-title"><a href="'.$category['link'].'" title="'.$category['name'].'">'.$category['name'].'</a></'.$title_tag.'>';
							if (isset($category['children']) && !empty($category['children'])) {
								$children_cat = $category['children'];
								if ($children_cat) {
									$output .= '<ul>';
										foreach ($children_cat as $child) {
											$output .= '<li>';
												$output .='<a href="'.$child['link'].'" title="'.$child['name'].'">'.$child['name'].'</a>';
											$output .= '</li>';
										}
									$output .= '</ul>';
								}
							}
						}

					$output .= '</div>';

				if ($children != 'true') {
					$output .= '</a>';
				} else {
					$output .='</div>';
				}
			$output .= '</li>';

			return $output;
		}
	}
	add_shortcode('et_woo_category', 'et_woo_category');

	function et_woo_category_single($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'image'     => '',
				'icon_size' => 'large',
				'title_tag' => 'h6',
			), $atts)
		);

		$category = get_queried_object();

        if (!is_wp_error($category) && is_object($category) && property_exists($category,'taxonomy')) {
        	if ($category->taxonomy == 'product_cat') {
        		$category = $category->slug;
        	}

			if (class_exists('Woocommerce') && !empty($category)) {

				$categories_raw = get_product_categories_raw();

				$output = $icon_output = '';

				$category = $categories_raw[$category];

				$element_id = rand(1,1000000);

				$output .='<div class="et-woo-category single">';
					$output .= '<a class="category-body" href="'.$category['link'].'" title="'.$category['name'].'">';

						if (isset($image) && !empty($image)) {

							$icon = get_post($image);

							if (is_object($icon) && $icon->post_mime_type == 'image/svg+xml') {
								if (et_get_icon($icon->guid)) {
									$icon_output = et_get_icon($icon->guid);
					            }
							} else {
								$image = wp_get_attachment_image_src($image, 'full');
								$width  = $image[1];
								$height = $image[2];
								$image  = $image[0];
							}

						} elseif(isset($category['image']) && !empty($category['image'])) {
							$image = $category['image'];
							$height = $category['height'];
							$width = $category['width'];
						}

						if (!empty($image)) {

							if (!empty($icon_output)) {
					            $output .='<div class="et-icon size-'.$icon_size.'">';
			                        $output .= $icon_output;
			                    $output .='</div>';
							} else {
								$output .='<div class="post-media image-container">';
			            			$output .= '<img class="lazy" alt="'.esc_attr($category['name']).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="'.esc_url($image).'" />';
			                        $output .= propharm_enovathemes_svg_icon('placeholder');
			                    $output .='</div>';
							}
						}

						$output .= '<div class="category-content">';
							$output .='<'.$title_tag.' class="category-title">'.$category['name'].'</'.$title_tag.'>';
						$output .= '</div>';

					$output .= '</a>';
				$output .= '</div>';

				return $output;
			}

		}
	}
	add_shortcode('et_woo_category_single', 'et_woo_category_single');


	function et_woo_attributes($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'carousel' 		      => 'false',
				'navigation_type'     => 'arrows',
				'navigation_position' => 'top',
				'autoplay'            => 'false',
				'columns_mob'         => '1',
				'columns_tab_port'    => '1',
				'columns_tab_land'    => '1',
				'columns_desktop'     => '1',
				'attribute'           => '',
				'title_tag'           => 'h6',
			), $atts)
		);

		if (class_exists('Woocommerce') && isset($attribute) && !empty($attribute)) {

			$output = '';

			$element_id = rand(1,1000000);

			$class   = array();
			$class[] = 'grid';
			$class[] = 'et-woo-categories';
			$class[] = 'et-woo-attributes';
			$class[] = 'border-true';
			$class[] = 'carousel-'.$carousel;
			$class[] = 'nav-pos-'.$navigation_position;

			$attributes   = array();
			$attributes[] = 'data-nav="'.$navigation_type.'"';
			$attributes[] = 'data-autoplay="'.$autoplay.'"';
			$attributes[] = 'data-columns-mob="'.$columns_mob.'"';
			$attributes[] = 'data-columns-tab-port="'.$columns_tab_port.'"';
			$attributes[] = 'data-columns-tab-land="'.$columns_tab_land.'"';
			$attributes[] = 'data-columns-desktop="'.$columns_desktop.'"';

			$shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';
            if ('' === get_option( 'permalink_structure' )) {
                $shop_link = get_home_url().'?post_type=product';
            }

			$output .='<div id="et-woo-categories-'.$element_id.'" class="'.implode(' ', $class).'" '.implode(' ', $attributes).'>';
				$output .= '<ul class="slides">';
					$attribute_terms = get_terms( array(
                        'taxonomy' => 'pa_'.$attribute,
                        'hide_empty' => false,
                    ));
                    if (!is_wp_error($attribute_terms)) {
                    	foreach ($attribute_terms as $term) {
                    		$output .='<li class="et-woo-category children-false">';
								$output .= '<a class="category-body" href="'.$shop_link.'?filter_'.$attribute.'='.$term->slug.'" title="'.$term->name.'">';
									if (defined('WCVS_PLUGIN_VERSION')) {

										$image = ('2.0.22' == WCVS_PLUGIN_VERSION) ? get_term_meta($term->term_id,'product_attribute_image',true) : get_term_meta($term->term_id,'image',true);

										if (isset($image) && !empty($image)) {

											$image_obj = get_post($image);

											if (is_object($image_obj) && $image_obj->post_mime_type == 'image/svg+xml') {
												$thumbnail_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
										        $image_caption = get_the_post_thumbnail_caption($image);
										        $image_alt     = (empty($image_caption)) ? ((empty($thumbnail_alt)) ? get_bloginfo('name') : $thumbnail_alt) : $image_caption;
            									$output .= '<img src="'.esc_url($image_obj->guid).'" alt="'.esc_html($image_alt).'" />';
											} else {
												$output .=enovathemes_addons_inline_image_placeholder($image,'full','attribute-image');
											}

										}

									}
									$output .='<'.$title_tag.' class="category-title">'.$term->name.'</'.$title_tag.'>';
								$output .= '</a>';
							$output .= '</li>';
                    	}
                    }
				$output .= '</ul>';
			$output .= '</div>';

			return $output;
		}
	}
	add_shortcode('et_woo_attributes', 'et_woo_attributes');

	/*	et_product_search
	--------------*/

		function et_product_search_page($atts, $content = null) {

			extract(shortcode_atts(
				array(
					'extra_class'   => '',
					'element_id'    => '',
					'in_category'   => 'false',
					'attribute'     => '',
					'attribute_term'=> '',
					'hide_category' => 'false',
					'sku'           => 'false',
					'description'   => 'false',
				), $atts)
			);

			static $id_counter = 1;

			$output      = '';

			$class = array();

			if (!empty($extra_class)) {
				$class[] = esc_attr($extra_class);
			}

			$class[] = 'page-product-search';

			if (!empty($extra_class)) {
				$class[] = $extra_class;
			}

			$element_id = (!empty($element_id)) ? $element_id : $id_counter;

			$args = array(
				'before_widget' => '<div id="page-product-search-'.$element_id.'" class="'.implode(" ", $class).'">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5 class="widget_title">',
                'after_title'   => '</h5>',
			);

			$instance = array(
				'title'       => '',
				'category'    => $hide_category,
				'in_category' => $in_category,
				'attribute'   => $attribute,
				'attribute_term'   => $attribute_term,
				'SKU'         => $sku,
				'description' => $description
			);

			$output .= propharm_enovathemes_get_the_widget( 'Enovathemes_Addons_WP_Product_Search', $instance,$args);

			$id_counter++;

			return $output;
		}

		add_shortcode('et_product_search_page', 'et_product_search_page');

		function et_woo_filter($atts, $content = null) {
			extract(shortcode_atts(
				array(
					'atts'             => '',
					'sku'              => 'false',
					'orientation'      => 'horizontal',
					'background_color' => '',
					'box_shadow'       => '',
					'element_id'       => ''
				), $atts)
			);

	        $cache  = (class_exists('SitePress') || function_exists('pll_the_languages')) ? false : true;

	        $shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';
	        if ('' === get_option( 'permalink_structure' )) {
	            $shop_link = get_home_url().'?post_type=product';
	        }

			if (class_exists('Woocommerce') && !empty($atts)) {

				wp_enqueue_script( 'widget-product-filter-select');

				$attributes  = enovathemes_addons_build_filter_attributes($cache);
		        $categories  = get_product_categories_hierarchy($cache);
	            $atts_filter = array();

	            $atts = explode('|', $atts);
	            $attsObject = array();

	            foreach ($atts as $att) {
	            	$attObj = array();
	            	$att    = explode(',', $att);

	            	foreach ($att as $key) {
	            		$key = explode(':', $key);
	            		$attObj[$key[0]] = $key[1];
	            	}

	            	array_push($attsObject, $attObj);
	            }

	            foreach ($attsObject as $att => $object) {
	            	$id = $object['attr'];
	                if ($id == 'cat') {
	                    $push = array();
	                    $push['name'] = $id;
	                    array_push($atts_filter, $push);
	                } else {
	                    if (!empty($attributes) && !is_wp_error($attributes)) {
	                        if (array_key_exists($id, $attributes)) {
	                            array_push($atts_filter, $attributes[$id]);
	                        }
	                    }
	                }
	            }

	            $class   = array();
	            $class[] = $orientation;

	            if ((isset($background_color) && !empty($background_color)) || isset($box_shadow) && !empty($box_shadow)) {
	            	$class[] ='boxy';
	            }

				$element_id   = (isset($element_id) && !empty($element_id)) ? $element_id : rand(1,1000000);

				$output ='<div id="select-filter-'.$element_id.'" class="select-filter '.implode(' ', $class).'" data-shop="'.esc_url($shop_link).'">';
					$output .='<div class="select-filter-inner">';
						if (isset($content) && !empty($content)) {
							$output .= '<div class="sfi-title">'.$content.'</div>';
		                }
		                $output .='<form name="select-filter" class="select-filter" method="POST">';
							if (!empty($atts_filter)){

								$first = $atts_filter[0];

								foreach ($atts_filter as $attribute) {
									if ($attribute['name'] == 'cat'){
				                        $output .= '<div class="sfi"><select name="category" class="category">';
			                                $output .= '<option class="default" value="">'.esc_html__( 'Category', 'enovathemes-addons' ).'</option>';
			                                if ($first['name'] == $attribute['name']) {
				                                if (!empty($categories) && !is_wp_error($categories)){
				                                    $output .= list_taxonomy_hierarchy_no_instance($categories,'','default');
				                                }
			                                }
			                            $output .= '</select></div>';
					                } else {
				                        $output .= '<div class="sfi"><select name="'.esc_attr($attribute['name']).'">';
				                            $output .= '<option class="default" value="">'.esc_html($attribute['label']).'</option>';
				                            if ($first['name'] == $attribute['name']) {
					                            if ($attribute['terms']){
					                                $output .= list_attribute_no_instance($attribute['terms'],'default');
					                            }
				                        	}
				                        $output .= '</select></div>';
					                }
								}
							}
							if ($sku == 'true') {
								$output .='<div class="sfi sku"><span>'.esc_html__('OR','enovathemes-addons').'</span><input type="text" placeholder="'.esc_html__('Enter SKU','enovathemes-addons').'"></div>';
							}
							$output .='<div class="sfi"><button type="submit">'.esc_html__('Shop now','enovathemes-addons').'</button></div>';
						$output .='</form>';
					$output .='</div>';
				$output .='</div>';

				return $output;

			}
		}
		add_shortcode('et_woo_filter', 'et_woo_filter');

/*  POSTS
/*------------*/

	function et_posts($atts, $content = null) {

		$shortcode_atts = shortcode_atts(
			array(
				'ajax'             => 'false',
				'layout' 		   => 'grid',
				'navigation_type'  => 'only-arrows',
				'autoplay'         => 'false',
				'quantity' 		   => '12',
				'category' 		   => '',
				'columns_grid'     => '1',
				'excerpt' 		   => '104',
				'title_length'     => '47',
				'operator' 		   => 'IN',
				'orderby' 		   => 'date',
				'order' 		   => 'ASC',
				'element_id'       => ''
		), $atts);

		extract($shortcode_atts);

		$output = '';

		global $post;

		$total = $quantity;

		$query_options = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => $orderby,
			'order'               => $order,
			'posts_per_page' 	  => absint($quantity),
		);

		if (isset($category) && !empty($category)) {
			$query_options = array(
				'post_type'           => 'post',
				'post_status' 	 	  => 'publish',
				'ignore_sticky_posts' => true,
				'orderby'             => $orderby,
				'order'               => $order,
				'posts_per_page' 	  => absint($quantity),
				'tax_query'           => array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => explode(',',$category),
						'operator' => $operator
					)
				)
			);

		}

		if ($ajax == "false") {
			$output = et_posts_ajax($shortcode_atts,$query_options);
		} else {

			$class 		= array();
			$attributes = array();

			$shortcode_atts = base64_encode(json_encode($shortcode_atts));
			$query_options  = base64_encode(json_encode($query_options));
			$attributes[]   = 'data-atts="'.$shortcode_atts.'"';
			$attributes[]   = 'data-query="'.$query_options.'"';

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

				$total = $columns_grid;

			}elseif ($layout == "comp") {
				$class[] = 'manual-carousel';
			}

			$shortcode_class   = array();
			$shortcode_class[] = 'ajax';
			$shortcode_class[] = 'et-shortcode-posts';
			$shortcode_class[] = 'blog-layout';
			$shortcode_class[] = 'blog-layout-'.esc_attr($layout);
			$shortcode_class[] = esc_attr($layout);

			$thumb_width  = ($layout == "list") ? '425' : (($layout == "comp") ? '1240': '600');
			$thumb_height = ($layout == "list") ? '425' : (($layout == "comp") ? '820': '400');

			$output .= '<div id="et-posts-'.$element_id.'" class="'.implode(' ',$shortcode_class).'" '.implode(' ', $attributes).'>';
				$output .= '<div class="'.esc_attr(implode(' ', $class)).'" data-columns="'.$columns_grid.'" data-nav="'.$navigation_type.'">';

					if ($layout == "carousel" || $layout == "comp") {$output .= '<div class="slides">';}

						for ($i=1; $i <= $total; $i++) {

							$output .='<article class="et-item post placeholder">';

					            $output .='<div class="post-inner et-item-inner et-clearfix">';

					            	$output .='<div class="post-image overlay-hover post-media">';
					            		$output .='<div class="image-container loaded">';
					            			if ($layout == "list") {
								               $data_img = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
								            } else {
								               $data_img = PROPHARM_SVG.'image_placeholder.svg';
								            }
								            $output .= '<img alt="'.esc_attr(get_bloginfo('name')).'" width="'.$thumb_width.'" height="'.$thumb_height.'" src="'.$data_img.'" />';
								            $output .= propharm_enovathemes_svg_icon('placeholder');
					            		$output .='</div>';
				            		$output .='</div>';

				            		$output .='<div class="post-body et-clearfix">';
				            			$output .='<div class="post-body-inner">';
				            				$output .='<div class="post-categories"><span></span></div>';
				            				if ($title_length != '0') {
				            					$output .='<h4 class="post-title entry-title"></h4>';
				            				}
				            				if ($layout != "comp") {
					            				if ($excerpt != '0') {
					            					$output .='<div class="post-excerpt"></div>';
					            				}
				            				}
				            				$output .='<div class="post-read-more"></div>';
					            			$output .='</div>';
				            		$output .='</div>';

					            $output .='</div>';

					        $output .='</article>';

				        }

					if ($layout == "carousel" || $layout == "comp") {$output .= '</div>';}

				$output .= '</div>';
			$output .= '</div>';

		}

		return $output;

	}
	add_shortcode('et_posts', 'et_posts');

/*	Content filter
/*------------*/

    add_filter("the_content", "enovathemes_addons_the_content_filter");
    function enovathemes_addons_the_content_filter($content) {

        $block = join("|",array("et_gap","et_gap_inline","et_icon","et_separator"));

        $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

        $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);

        return $rep;

    }

    function enovathemes_addons_shortcode_empty_paragraph_fix( $content ) {

        $array = array (
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']',
            ']<br/>' => ']',
        );

        $content = strtr( $content, $array );

        return $content;
    }

    add_filter( 'the_content', 'enovathemes_addons_shortcode_empty_paragraph_fix',1);
?>
