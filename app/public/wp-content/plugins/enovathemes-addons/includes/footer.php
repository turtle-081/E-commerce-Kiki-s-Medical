<?php

	function enovathemes_addons_footer() {

		$labels = array(
			'name'               => esc_html__('Footers', 'enovathemes-addons'),
			'singular_name'      => esc_html__('Footers', 'enovathemes-addons'),
			'add_new'            => esc_html__('Add new', 'enovathemes-addons'),
			'add_new_item'       => esc_html__('Add new footer', 'enovathemes-addons'),
			'edit_item'          => esc_html__('Edit footer', 'enovathemes-addons'),
			'new_item'           => esc_html__('New footer', 'enovathemes-addons'),
			'all_items'          => esc_html__('All footers', 'enovathemes-addons'),
			'view_item'          => esc_html__('View footer', 'enovathemes-addons'),
			'search_items'       => esc_html__('Search footer', 'enovathemes-addons'),
			'not_found'          => esc_html__('No footer found', 'enovathemes-addons'),
			'not_found_in_trash' => esc_html__('No footer found in trash', 'enovathemes-addons'), 
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Footers', 'enovathemes-addons')
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'exclude_from_search'=> true,
			'show_ui'            => true, 
			'show_in_menu'       => true, 
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'footer','with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false, 
			'hierarchical'       => false,
			'menu_position'      => 50,
			'menu_icon'          => 'dashicons-star-filled',
			'supports'           => array( 'title', 'editor'),
		);

		register_post_type( 'footer', $args );
	}

	add_action( 'init', 'enovathemes_addons_footer');


	add_filter("manage_edit-footer_columns", "enovathemes_addons_footer_edit_columns");
	function enovathemes_addons_footer_edit_columns($columns){
		$columns['cb']             = "<input type=\"checkbox\" />";
		$columns['title']          = esc_html__("Title", 'enovathemes-addons');
		$columns['active']         = esc_html__("Active footer", 'enovathemes-addons');

		unset($columns['comments']);
		return $columns;
	}

	add_action("manage_footer_posts_custom_column", "enovathemes_addons_footer_custom_columns");
	function enovathemes_addons_footer_custom_columns($column){
		global $post, $propharm_enovathemes;

        $footer_id  = (isset($GLOBALS['propharm_enovathemes']['footer-id']) && !empty($GLOBALS['propharm_enovathemes']['footer-id'])) ? $GLOBALS['propharm_enovathemes']['footer-id'] : "none";

		switch ($column){
			case "active":
			if ($footer_id == $post->ID) {
				echo '<div class="custom-meta-ind active-footer">'.esc_html__("Active", 'enovathemes-addons').'</div>';
			}
			break;
		}
	}

?>