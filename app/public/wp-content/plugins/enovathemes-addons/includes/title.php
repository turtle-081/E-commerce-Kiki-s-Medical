<?php

function enovathemes_addons_title_section() {

	$labels = array(
		'name'               => esc_html__('Title section', 'enovathemes-addons'),
		'singular_name'      => esc_html__('Title section', 'enovathemes-addons'),
		'add_new'            => esc_html__('Add new', 'enovathemes-addons'),
		'add_new_item'       => esc_html__('Add new title section', 'enovathemes-addons'),
		'edit_item'          => esc_html__('Edit title section', 'enovathemes-addons'),
		'new_item'           => esc_html__('New title section', 'enovathemes-addons'),
		'all_items'          => esc_html__('All title sections', 'enovathemes-addons'),
		'view_item'          => esc_html__('View title section', 'enovathemes-addons'),
		'search_items'       => esc_html__('Search title section', 'enovathemes-addons'),
		'not_found'          => esc_html__('No title section found', 'enovathemes-addons'),
		'not_found_in_trash' => esc_html__('No title section found in trash', 'enovathemes-addons'), 
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__('Title section', 'enovathemes-addons')
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'exclude_from_search'=> true,
		'show_ui'            => true, 
		'show_in_menu'       => true, 
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'title_section','with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => false, 
		'hierarchical'       => false,
		'menu_position'      => 50,
		'menu_icon'          => 'dashicons-star-filled',
		'supports'           => array( 'title', 'editor'),
	);

	register_post_type( 'title_section', $args );
}

add_action( 'init', 'enovathemes_addons_title_section');

?>