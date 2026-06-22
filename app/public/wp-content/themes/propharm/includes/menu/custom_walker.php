<?php

class et_scm_walker extends Walker_Nav_Menu{

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

		$class_names .= ' depth-'.$depth;

		$megamenu_output = '';
		$megamenu_data   = '';

		if (!empty( $item->megamenu_content) && $item->megamenu_content != "select" && !empty( $item->megamenu ) && $item->megamenu == "true") {

			$class_names .= ' mm-true';
			$class_names .= ' menu-item-has-children';

			$megamenu = enovathemes_addons_megamenus();

			if (!is_wp_error($megamenu)) {
				$megamenu_data    = $megamenu[$item->megamenu_content][3];
				if (!empty( $item->megamenu_ajax ) && $item->megamenu_ajax == "true") {
					$megamenu_data   .= ' data-megamenu="'.$item->megamenu_content.'"';
					$class_names .= ' mm-ajax';
				} else {
					$megamenu_output .= do_shortcode(gzuncompress($megamenu[$item->megamenu_content][2]));
				}
			}

		}

		if (function_exists('enovathemes_addons_extra_white_space')) {
			$class_names = enovathemes_addons_extra_white_space($class_names);
		}

		$output .= $indent . '<li id="menu-item-'. $item->ID . '" class="'. esc_attr( $class_names ) . '" '.$megamenu_data.'>';

			$attributes  = (!empty($item->attr_title)) ? ' title="'.esc_attr($item->attr_title).'"' : '';
			$attributes .= (!empty($item->target))     ? ' target="'.esc_attr($item->target).'"' : '';
			$attributes .= (!empty($item->xfn))        ? ' rel="'.esc_attr($item->xfn).'"' : '';
			$attributes .= (!empty($item->url))        ? ' href="'.esc_url($item->url).'"' : '';
			$attributes .= (!empty($item->icon))       ? ' class="mi-link has-icon"' : ' class="mi-link"';

			$prepend = $append  = '';

			if($depth != 0){$append = $prepend = "";}

			if (is_object($args)) {

				$item_output = $args->before;

					$item_output .= '<a'. $attributes .'>';

						if (!empty($item->icon)) {
							$icon  = '<span class="menu-icon" style="-webkit-mask: url('.$item->icon.');mask: url('.$item->icon.');"></span>';
						}

						if($depth == 0 && !empty( $item->icon )){$item_output .= $icon;}

						$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append.$args->link_after;

						if($depth != 0 && !empty( $item->icon )){$item_output .= $icon;}

						if (!empty( $item->ltext )) {
							$label_color = (!empty( $item->lcolor )) ? esc_attr($item->lcolor) : "#15a9e3";
							$label_textcolor = (!empty( $item->ltextcolor )) ? esc_attr($item->ltextcolor) : "#ffffff";
							$item_output .= '<span class="label" data-labelc="'.$label_color.'" style="background-color:'.$label_color.';color:'.$label_textcolor.';">'.esc_attr($item->ltext).'</span>';
						}

						if(!empty( $item->description )) {
							$item_output .='<span class="description">'.esc_attr( $item->description ).'</span>';
						}

					$item_output .= '</a>';

					if (!empty($megamenu_output)) {
						$item_output .= $megamenu_output;
					}

				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			}

	}

}

class et_scm_walker_light extends Walker_Nav_Menu{

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

		$class_names .= ' depth-'.$depth;

		if (function_exists('enovathemes_addons_extra_white_space')) {
			$class_names = enovathemes_addons_extra_white_space($class_names);
		}

		$output .= $indent . '<li id="menu-item-'. $item->ID . '" class="'. esc_attr( $class_names ) . '">';

			$attributes  = (!empty($item->attr_title)) ? ' title="'.esc_attr($item->attr_title).'"' : '';
			$attributes .= (!empty($item->target))     ? ' target="'.esc_attr($item->target).'"' : '';
			$attributes .= (!empty($item->xfn))        ? ' rel="'.esc_attr($item->xfn).'"' : '';
			$attributes .= (!empty($item->url))        ? ' href="'.esc_url($item->url).'"' : '';
			$attributes .= (!empty($item->icon))       ? ' class="mi-link has-icon"' : ' class="mi-link"';

			$prepend = $append  = '';

			if($depth != 0){$append = $prepend = "";}

			if (is_object($args)) {

				$item_output = $args->before;

					$item_output .= '<a'. $attributes .'>';

						if (!empty($item->icon)) {
							$icon  = '<span class="menu-icon" style="-webkit-mask: url('.$item->icon.');mask: url('.$item->icon.');"></span>';
						}

						if($depth == 0 && !empty( $item->icon )){$item_output .= $icon;}

						$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append.$args->link_after;

						if($depth != 0 && !empty( $item->icon )){$item_output .= $icon;}

						if (!empty( $item->ltext )) {
							$label_color = (!empty( $item->lcolor )) ? esc_attr($item->lcolor) : "#15a9e3";
							$label_textcolor = (!empty( $item->ltextcolor )) ? esc_attr($item->ltextcolor) : "#ffffff";
							$item_output .= '<span class="label" data-labelc="'.$label_color.'" style="background-color:'.$label_color.';color:'.$label_textcolor.';">'.esc_attr($item->ltext).'</span>';
						}

						if(!empty( $item->description )) {
							$item_output .='<span class="description">'.esc_attr( $item->description ).'</span>';
						}

					$item_output .= '</a>';

				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			}

	}

}

?>
