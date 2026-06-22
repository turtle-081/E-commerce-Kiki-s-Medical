<?php

	function enovathemes_addons_megamenu() {

		$labels = array(
			'name'               => esc_html__('Megamenu', 'enovathemes-addons'),
			'singular_name'      => esc_html__('Megamenu', 'enovathemes-addons'),
			'add_new'            => esc_html__('Add new', 'enovathemes-addons'),
			'add_new_item'       => esc_html__('Add new megamenu', 'enovathemes-addons'),
			'edit_item'          => esc_html__('Edit megamenu', 'enovathemes-addons'),
			'new_item'           => esc_html__('New megamenu', 'enovathemes-addons'),
			'all_items'          => esc_html__('All', 'enovathemes-addons'),
			'view_item'          => esc_html__('View megamenu', 'enovathemes-addons'),
			'search_items'       => esc_html__('Search megamenu', 'enovathemes-addons'),
			'not_found'          => esc_html__('No megamenu found', 'enovathemes-addons'),
			'not_found_in_trash' => esc_html__('No megamenu found in trash', 'enovathemes-addons'), 
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Megamenu', 'enovathemes-addons')
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'exclude_from_search'=> true,
			'show_ui'            => true, 
			'show_in_menu'       => true, 
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'megamenu','with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false, 
			'hierarchical'       => false,
			'menu_position'      => 50,
			'menu_icon'          => 'dashicons-star-filled',
			'supports'           => array( 'title', 'editor'),
		);

		register_post_type( 'megamenu', $args );
	}

	add_action( 'init', 'enovathemes_addons_megamenu');

?>