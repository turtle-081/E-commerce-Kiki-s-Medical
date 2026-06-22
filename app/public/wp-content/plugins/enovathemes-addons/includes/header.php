<?php

	function enovathemes_addons_header() {

		$labels = array(
			'name'               => esc_html__('Headers', 'enovathemes-addons'),
			'singular_name'      => esc_html__('Headers', 'enovathemes-addons'),
			'add_new'            => esc_html__('Add new', 'enovathemes-addons'),
			'add_new_item'       => esc_html__('Add new header', 'enovathemes-addons'),
			'edit_item'          => esc_html__('Edit header', 'enovathemes-addons'),
			'new_item'           => esc_html__('New header', 'enovathemes-addons'),
			'all_items'          => esc_html__('All headers', 'enovathemes-addons'),
			'view_item'          => esc_html__('View header', 'enovathemes-addons'),
			'search_items'       => esc_html__('Search header', 'enovathemes-addons'),
			'not_found'          => esc_html__('No header found', 'enovathemes-addons'),
			'not_found_in_trash' => esc_html__('No header found in trash', 'enovathemes-addons'), 
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Headers', 'enovathemes-addons')
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'exclude_from_search'=> true,
			'show_ui'            => true, 
			'show_in_menu'       => true, 
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'header','with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false, 
			'hierarchical'       => false,
			'menu_position'      => 50,
			'menu_icon'          => 'dashicons-star-filled',
			'supports'           => array( 'title', 'editor'),
		);

		register_post_type( 'header', $args );
	}

	add_action( 'init', 'enovathemes_addons_header');


	add_filter("manage_edit-header_columns", "enovathemes_addons_header_edit_columns");
	function enovathemes_addons_header_edit_columns($columns){
		$columns['cb']     = "<input type=\"checkbox\" />";
		$columns['title']  = esc_html__("Title", 'enovathemes-addons');
        $columns['type']   = esc_html__("Type", 'enovathemes-addons');
        $columns['visibility'] = esc_html__("Visibility", 'enovathemes-addons');

		unset($columns['comments']);
		return $columns;
	}

	add_action("manage_header_posts_custom_column", "enovathemes_addons_header_custom_columns");
	function enovathemes_addons_header_custom_columns($column){
		global $post, $propharm_enovathemes;

        $desktop_header_id = (isset($GLOBALS['propharm_enovathemes']['header-desktop-id']) && !empty($GLOBALS['propharm_enovathemes']['header-desktop-id'])) ? $GLOBALS['propharm_enovathemes']['header-desktop-id'] : "none";
        $mobile_header_id  = (isset($GLOBALS['propharm_enovathemes']['header-mobile-id']) && !empty($GLOBALS['propharm_enovathemes']['header-mobile-id'])) ? $GLOBALS['propharm_enovathemes']['header-mobile-id'] : "none";


        $type               = get_post_meta( $post->ID, 'enovathemes_addons_header_type', true );
        $mobile             = get_post_meta( $post->ID, 'enovathemes_addons_mobile', true );
        $tablet_portrait    = get_post_meta( $post->ID, 'enovathemes_addons_tablet_portrait', true );
        $tablet_landscape   = get_post_meta( $post->ID, 'enovathemes_addons_tablet_landscape', true );
        $desktop            = get_post_meta( $post->ID, 'enovathemes_addons_desktop', true );

        $active_header_text   = '';
        $main_header_text     = esc_html__("This header is set as site main desktop header", 'enovathemes-addons');
        $color_indicator_type = 'indicator-1';

        if ($type == "mobile") {
            $color_indicator_type = 'indicator-2';
        }

        if ($type == "sidebar") {
            $color_indicator_type = 'indicator-4';
        }

		switch ($column){
			case "type":
    			echo '<div class="custom-meta-ind '.$color_indicator_type.'">'.$type.'</div>';
                if ($post->ID == $desktop_header_id || $post->ID == $mobile_header_id) {
                    if ($post->ID == $mobile_header_id && $post->ID != $desktop_header_id) {
                        $main_header_text   = esc_html__("This header is set as site main mobile header", 'enovathemes-addons');
                    }
                    echo '<span class="custom-meta-ind header-active indicator-5" title="'.$main_header_text.'">'.esc_html__("active", 'enovathemes-addons').'</span>';
                }
            break;
            case "visibility":
                if ($mobile == "on") {
                    echo '<div class="custom-meta-ind indicator-3">'.esc_html__("Mobile", 'enovathemes-addons').'</div>';
                }
                if ($tablet_portrait == "on") {
                    echo '<div class="custom-meta-ind indicator-3">'.esc_html__("Tablet portrait", 'enovathemes-addons').'</div>';
                }
                if ($tablet_landscape == "on") {
                    echo '<div class="custom-meta-ind indicator-3">'.esc_html__("Tablet landscape", 'enovathemes-addons').'</div>';
                }
                if ($desktop == "on") {
                    echo '<div class="custom-meta-ind indicator-3">'.esc_html__("Desktop", 'enovathemes-addons').'</div>';
                }
            break;
		}
	}
?>