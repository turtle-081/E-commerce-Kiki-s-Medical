<?php

/* vc defaults
----*/

	vc_remove_param('vc_section', 'full_width');
	vc_remove_param('vc_row', 'full_width');
	vc_remove_param('vc_row_inner', 'gap');
	vc_remove_param('vc_row', 'gap');
	vc_remove_param('vc_row', 'parallax');
	vc_remove_param('vc_row', 'parallax_image');
	vc_remove_param('vc_row', 'video_bg');
	vc_remove_param('vc_row', 'video_bg_url');
	vc_remove_param('vc_row', 'video_bg_parallax');
	vc_remove_param('vc_row', 'parallax_speed_bg');
	vc_remove_param('vc_row', 'parallax_speed_video');

/* vc_row
----*/

	/* defaults
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Row stretch', 'propharm' ),
			'param_name' => 'full_width',
			'value'      => array(
				esc_html__( 'No stretching', 'propharm' )           => 'stretch_no',
				esc_html__( 'Stretch row and content', 'propharm' ) => 'stretch_row_content',
			),
			'weight' => 1,
			'description' => esc_html__( '"No stretching" alignes the row with the main theme container, Stretch row and content" makes the row and content full width', 'propharm' )
		));

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Columns reverse on tablet landscape', 'propharm' ),
			'param_name' => 'reverse_tab_land',
			'value'      => array(
				esc_html__( 'No', 'propharm' )  => 'false',
				esc_html__( 'Yes', 'propharm' ) => 'true',
			),
			'weight' => 1,
		));

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Columns reverse on tablet portrait', 'propharm' ),
			'param_name' => 'reverse_tab',
			'value'      => array(
				esc_html__( 'No', 'propharm' )  => 'false',
				esc_html__( 'Yes', 'propharm' ) => 'true',
			),
			'weight' => 1,
		));

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Columns reverse on mobile', 'propharm' ),
			'param_name' => 'reverse_mob',
			'value'      => array(
				esc_html__( 'No', 'propharm' )  => 'false',
				esc_html__( 'Yes', 'propharm' ) => 'true',
			),
			'weight' => 1,
		));

		$column_gap_values = array();

		for ($i=0; $i <= 80; $i+=2) {
			$column_gap_values[esc_html__($i.'px', 'propharm')] = $i;
		}

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Columns gap', 'propharm' ),
			'param_name' => 'gap',
			'weight'     => 1,
			'value'      => $column_gap_values,
			'std' => '24'
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Columns gap', 'propharm' ),
			'param_name' => 'gap',
			'weight'     => 1,
			'value'      => $column_gap_values,
			'std' => '24'
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Z index (integer without any string)', 'propharm' ),
			'description'=> esc_html__( 'Higher value places the row on top', 'propharm' ),
			'param_name' => 'z_index',
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Height in px (without any string)', 'propharm' ),
			'param_name' => 'row_height',
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Height in px for sticky header version (without any string)', 'propharm' ),
			'param_name' => 'row_height_sticky',
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'checkbox',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Hide on sticky header version?', 'propharm' ),
			'param_name' => 'hide_row_sticky',
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'textfield',
			'heading'    => esc_html__('Element id','propharm'),
			'group'      => esc_html__('Header builder','propharm'),
			"class"      => "element-attr-hide",
			'param_name' => 'element_id',
			'value'      => '',
		));

		vc_add_param('vc_row_inner', array(
			'type'       => 'textarea',
			'heading'    => esc_html__('Element css','propharm'),
			'group'      => esc_html__('Header builder','propharm'),
			"class"      => "element-attr-hide",
			'param_name' => 'element_css',
			'value'      => '',
		));

	/* parallax
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Parallax background', 'propharm' ),
			'param_name' => 'parallax',
			'group'      => esc_html__('Background options','propharm'),
		));

		vc_add_param('vc_row', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Parallax image', 'propharm' ),
			'param_name' => 'parallax_image',
			'dependency' => Array('element' => 'parallax', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Parallax duration', 'propharm' ),
			'param_name' => 'parallax_duration_bg',
			'description'=> esc_html__('Enter parallax duration in ms','propharm'),
			'dependency' => Array('element' => 'parallax', 'value' => 'true'),
			'default'    => '0'
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Parallax speed', 'propharm' ),
			'param_name' => 'parallax_speed_bg',
			'description'=> esc_html__('Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)','propharm'),
			'dependency' => Array('element' => 'parallax', 'value' => 'true'),
			'default'    => '1.5'
		));

	/* video
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Background video', 'propharm' ),
			'param_name' => 'video_bg',
			'group'      => esc_html__('Background options','propharm'),
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video mp4 file url', 'propharm' ),
			'param_name' => 'video_bg_mp4',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video webm file url', 'propharm' ),
			'param_name' => 'video_bg_webm',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video ogv file url', 'propharm' ),
			'param_name' => 'video_bg_ogv',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Video overlay', 'propharm' ),
			'param_name' => 'video_bg_overlay',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Video placeholder', 'propharm' ),
			'param_name' => 'video_bg_placeholder',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Video parallax', 'propharm' ),
			'param_name' => 'video_bg_parallax',
			'group'      => esc_html__('Background options','propharm'),
			'dependency' => Array(
				'element' => 'video_bg', 'value' => 'true',

			)
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video parallax speed', 'propharm' ),
			'param_name' => 'video_bg_parallax_speed',
			'description'=> esc_html__('Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)','propharm'),
			'dependency' => Array(
				'element' => 'video_bg_parallax', 'value' => 'true',
			),
			'default'    => '1.5'
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video parallax duration', 'propharm' ),
			'param_name' => 'video_bg_parallax_duration',
			'description'=> esc_html__('Enter parallax duration in ms','propharm'),
			'dependency' => Array(
				'element' => 'video_bg_parallax', 'value' => 'true',
			),
			'default'    => '0'
		));

	/* fixed
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Fixed background', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'fixed_bg',
		));

		vc_add_param('vc_row', array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Fixed background image', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'fixed_bg_image',
			'dependency' => Array('element' => 'fixed_bg', 'value' => 'true')
		));

	/* animated
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Animated background', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg',
		));

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Animated background direction', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg_dir',
			'value'     => array(
				esc_html__('Horizontal','propharm')  => 'horizontal',
				esc_html__('Vertical','propharm')  => 'vertical',
			),
			'dependency' => Array('element' => 'animated_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Animated background speed in ms (default is 35000)', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg_speed',
			'dependency' => Array('element' => 'animated_bg', 'value' => 'true')
		));

		vc_add_param('vc_row', array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Animated background image', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg_image',
			'dependency' => Array('element' => 'animated_bg', 'value' => 'true')
		));

	/* header buiilder tab
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Height in px (without any string)', 'propharm' ),
			'param_name' => 'row_height',
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Z index (integer without any string)', 'propharm' ),
			'description'=> esc_html__( 'Higher value places the row on top', 'propharm' ),
			'param_name' => 'z_index',
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Height in px for sticky header version (without any string)', 'propharm' ),
			'param_name' => 'row_height_sticky',
		));

		vc_add_param('vc_row', array(
			'type'       => 'colorpicker',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Background color of sticky header version', 'propharm' ),
			'param_name' => 'row_background_sticky',
		));

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Hide from default header version?', 'propharm' ),
			'param_name' => 'hide_row_default',
		));

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'group'      => esc_html__('Header builder','propharm'),
			'heading'    => esc_html__( 'Hide on sticky header version?', 'propharm' ),
			'param_name' => 'hide_row_sticky',
		));

		vc_add_param('vc_row', array(
			'type'       => 'textfield',
			'heading'    => esc_html__('Element id','propharm'),
			'group'      => esc_html__('Header builder','propharm'),
			"class"      => "element-attr-hide",
			'param_name' => 'element_id',
			'value'      => '',
		));

		vc_add_param('vc_row', array(
			'type'       => 'textarea',
			'heading'    => esc_html__('Element css','propharm'),
			'group'      => esc_html__('Header builder','propharm'),
			"class"      => "element-attr-hide",
			'param_name' => 'element_css',
			'value'      => '',
		));

	/* propharm
	----*/

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Top gradient border', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'top_gradient',
		));

		vc_add_param('vc_row', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Bottom gradient border', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'bottom_gradient',
		));

		vc_add_param('vc_row', array(
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Gradient border color', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'gradient_color',
			'default'    => '#ffffff'
		));

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Grid overlay', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'grid_overlay',
			'value'     => array(
				esc_html__('None','propharm')  => 'none',
				esc_html__('White','propharm')  => 'white',
				esc_html__('Black','propharm')  => 'black',
			),
		));

		vc_add_param('vc_row', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Curtain', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'curtain',
			'value'     => array(
				esc_html__('None', 'propharm')   => 'none',
				esc_html__('Curtain from left', 'propharm')   => 'curtain-left',
				esc_html__('Curtain from right', 'propharm')  => 'curtain-right',
				esc_html__('Curtain from top', 'propharm')    => 'curtain-top',
				esc_html__('Curtain from bottom', 'propharm') => 'curtain-bottom',
			),
		));

		vc_add_param('vc_row', array(
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Curtain color', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'curtain_color',
			'dependency' => Array('element' => 'curtain', 'value' => array('curtain-left','curtain-right','curtain-top','curtain-bottom'))
		));

		vc_add_param('vc_row', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Mobile background image', 'propharm' ),
			'param_name' => 'mobile_image',
		));

/* vc_column
----*/

	vc_remove_param('vc_column', 'parallax');
	vc_remove_param('vc_column', 'parallax_image');
	vc_remove_param('vc_column', 'video_bg');
	vc_remove_param('vc_column', 'video_bg_url');
	vc_remove_param('vc_column', 'video_bg_parallax');
	vc_remove_param('vc_column', 'parallax_speed_bg');
	vc_remove_param('vc_column', 'parallax_speed_video');

	$animation_delay_values = array();

	for ($i=0; $i <= 2000; $i = $i + 50) {
		$animation_delay_values[$i.esc_html__('ms', 'propharm')] = $i;
	}

	vc_add_param('vc_column', array(
		'type'       => 'dropdown',
		'heading'    => esc_html__( 'Animation delay in ms (example 300)', 'propharm' ),
		'param_name' => 'animation_delay',
		'weight'     => 1,
		'value'      => $animation_delay_values
	));

	vc_add_param('vc_column', array(
		'type'       => 'dropdown',
		'heading'    => esc_html__( 'Text align', 'propharm' ),
		'param_name' => 'text_align',
		'value'      => array(
			esc_html__('None','propharm')   => 'none',
			esc_html__('Left','propharm')   => 'left',
			esc_html__('Right','propharm')  => 'right',
			esc_html__('Center','propharm') => 'center'
		)
	));

	vc_add_param('vc_column', array(
		'type'       => 'checkbox',
		'heading'    => esc_html__( 'Shadow', 'propharm' ),
		'group'      => esc_html__( 'Design Options', 'propharm' ),
		'param_name' => 'shadow',
		'weight'     => 1,
		'value'      => ''
	));

	vc_add_param('vc_column', array(
		'type'       => 'crp',
		'heading'    => esc_html__( 'Responsive padding', 'propharm' ),
		'group'      => esc_html__('Responsive Options','propharm'),
		'param_name' => 'crp',
	));

	vc_add_param('vc_column', array(
		'type'       => 'textfield',
		'heading'    => esc_html__('Element id','propharm'),
		"class"      => "element-attr-hide",
		'param_name' => 'element_id',
		'value'      => '',
	));

	vc_add_param('vc_column', array(
		'type'       => 'textarea',
		'heading'    => esc_html__('Element css','propharm'),
		"class"      => "element-attr-hide",
		'param_name' => 'element_css',
		'value'      => '',
	));

	/* parallax
	----*/

		vc_add_param('vc_column', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Parallax background', 'propharm' ),
			'param_name' => 'parallax',
			'group'      => esc_html__('Background options','propharm'),
		));

		vc_add_param('vc_column', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Parallax image', 'propharm' ),
			'param_name' => 'parallax_image',
			'dependency' => Array('element' => 'parallax', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Parallax speed', 'propharm' ),
			'param_name' => 'parallax_speed_bg',
			'description'=> esc_html__('Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)','propharm'),
			'dependency' => Array('element' => 'parallax', 'value' => 'true'),
			'default'    => '1.5'
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Parallax duration', 'propharm' ),
			'param_name' => 'parallax_duration_bg',
			'description'=> esc_html__('Enter parallax duration in ms','propharm'),
			'dependency' => Array('element' => 'parallax', 'value' => 'true'),
			'default'    => '0'
		));

	/* video
	----*/

		vc_add_param('vc_column', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Background video', 'propharm' ),
			'param_name' => 'video_bg',
			'group'      => esc_html__('Background options','propharm'),
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video mp4 file url', 'propharm' ),
			'param_name' => 'video_bg_mp4',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video webm file url', 'propharm' ),
			'param_name' => 'video_bg_webm',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video ogv file url', 'propharm' ),
			'param_name' => 'video_bg_ogv',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Video overlay', 'propharm' ),
			'param_name' => 'video_bg_overlay',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'attach_image',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Video placeholder', 'propharm' ),
			'param_name' => 'video_bg_placeholder',
			'dependency' => Array('element' => 'video_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Video parallax', 'propharm' ),
			'param_name' => 'video_bg_parallax',
			'group'      => esc_html__('Background options','propharm'),
			'dependency' => Array(
				'element' => 'video_bg', 'value' => 'true',

			)
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video parallax speed', 'propharm' ),
			'param_name' => 'video_bg_parallax_speed',
			'description'=> esc_html__('Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)','propharm'),
			'dependency' => Array(
				'element' => 'video_bg_parallax', 'value' => 'true',
			),
			'default'    => '1.5'
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'group'      => esc_html__('Background options','propharm'),
			'heading'    => esc_html__( 'Background video parallax duration', 'propharm' ),
			'param_name' => 'video_bg_parallax_duration',
			'description'=> esc_html__('Enter parallax duration in ms','propharm'),
			'dependency' => Array(
				'element' => 'video_bg_parallax', 'value' => 'true',
			),
			'default'    => '1.5'
		));

	/* fixed
	----*/

		vc_add_param('vc_column', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Fixed background', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'fixed_bg',
		));

		vc_add_param('vc_column', array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Fixed background image', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'fixed_bg_image',
			'dependency' => Array('element' => 'fixed_bg', 'value' => 'true')
		));

	/* animated
	----*/

		vc_add_param('vc_column', array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Animated background', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg',
		));

		vc_add_param('vc_column', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Animated background direction', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg_dir',
			'value'     => array(
				esc_html__('Horizontal','propharm')  => 'horizontal',
				esc_html__('Vertical','propharm')  => 'vertical',
			),
			'dependency' => Array('element' => 'animated_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Animated background speed in ms (default is 35000)', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg_speed',
			'dependency' => Array('element' => 'animated_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Animated background image', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'animated_bg_image',
			'dependency' => Array('element' => 'animated_bg', 'value' => 'true')
		));

		vc_add_param('vc_column', array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Curtain', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'curtain',
			'value'     => array(
				esc_html__('None', 'propharm')   => 'none',
				esc_html__('Curtain from left', 'propharm')   => 'curtain-left',
				esc_html__('Curtain from right', 'propharm')  => 'curtain-right',
				esc_html__('Curtain from top', 'propharm')    => 'curtain-top',
				esc_html__('Curtain from bottom', 'propharm') => 'curtain-bottom',
			),
		));

		vc_add_param('vc_column', array(
			'type'       => 'colorpicker',
			'heading'    => esc_html__( 'Curtain color', 'propharm' ),
			'group'      => esc_html__('Background options','propharm'),
			'param_name' => 'curtain_color',
			'dependency' => Array('element' => 'curtain', 'value' => array('curtain-left','curtain-right','curtain-top','curtain-bottom'))
		));

/* vc_column_text
----*/

	vc_add_param('vc_column_text', array(
		'type'       => 'textfield',
		'heading'    => esc_html__('Element id','propharm'),
		"class"      => "element-attr-hide",
		'param_name' => 'element_id',
		'value'      => '',
	));

	vc_add_param('vc_column_text', array(
		'type'       => 'textarea',
		'heading'    => esc_html__('Element css','propharm'),
		"class"      => "element-attr-hide",
		'param_name' => 'element_css',
		'value'      => '',
	));

	vc_add_param('vc_column_text', array(
		'type'       => 'dropdown',
		'heading'    => esc_html__( 'Animation delay in ms (example 300)', 'propharm' ),
		'param_name' => 'animation_delay',
		'weight'     => 1,
		'value'      => $animation_delay_values
	));

	function propharm_enovathemes_remove_woocommerce() {
	    if (class_exists('Woocommerce')) {
	        vc_remove_element( 'recent_products' );
			vc_remove_element( 'featured_products' );
			vc_remove_element( 'product' );
			vc_remove_element( 'products' );
			vc_remove_element( 'product_category' );
			vc_remove_element( 'product_categories' );
			vc_remove_element( 'sale_products' );
			vc_remove_element( 'best_selling_products' );
			vc_remove_element( 'top_rated_products' );
			vc_remove_element( 'related_products' );
			vc_remove_element( 'product_attribute' );
	    }
	}
	add_action( 'vc_build_admin_page', 'propharm_enovathemes_remove_woocommerce', 11 );
	add_action( 'vc_load_shortcode', 'propharm_enovathemes_remove_woocommerce', 11 );

if (defined( 'ENOVATHEMES_ADDONS' )) {
	add_action( 'init', 'propharm_enovathemes_integrateVC');
    function propharm_enovathemes_integrateVC() {

    	global $propharm_enovathemes;

		$main_color = (isset($GLOBALS['propharm_enovathemes']['main-color']) && $GLOBALS['propharm_enovathemes']['main-color']) ? $GLOBALS['propharm_enovathemes']['main-color'] : '#15a9e3';
		$area_color = (isset($GLOBALS['propharm_enovathemes']['area-color']) && $GLOBALS['propharm_enovathemes']['area-color']) ? $GLOBALS['propharm_enovathemes']['area-color'] : '#edf4f6';

    	$google_fonts_family = array('Theme default');

		$google_fonts = enovathemes_addons_google_fonts();

		if (!is_wp_error( $google_fonts ) ) {

			foreach ( $google_fonts as $font ) {
				array_push($google_fonts_family, $font['family']);
			}

		}

    	$animation_delay_values = array();

		for ($i=0; $i <= 2000; $i = $i + 50) {
			$animation_delay_values[$i.esc_html__('ms', 'propharm')] = $i;
		}

		$typography_values = array('Inherit'=>'i');
		for ($i=10; $i <= 80; $i++) {
	        $typography_values[$i.esc_html__('px', 'propharm')] = $i;
	    }

    	$order_by_values = array(
			esc_html__( 'Date', 'propharm' ) => 'date',
			esc_html__( 'ID', 'propharm' ) => 'ID',
			esc_html__( 'Author', 'propharm' ) => 'author',
			esc_html__( 'Title', 'propharm' ) => 'title',
			esc_html__( 'Modified', 'propharm' ) => 'modified',
			esc_html__( 'Random', 'propharm' ) => 'rand',
			esc_html__( 'Comment count', 'propharm' ) => 'comment_count',
			esc_html__( 'Menu order', 'propharm' ) => 'menu_order',
		);

		$order_way_values = array(
			esc_html__( 'Ascending', 'propharm' ) => 'ASC',
			esc_html__( 'Descending', 'propharm' ) => 'DESC',
		);

		$operator_values = array(
			esc_html__( 'IN', 'propharm' ) => 'IN',
			esc_html__( 'NOT IN', 'propharm' ) => 'NOT IN',
			esc_html__( 'AND', 'propharm' ) => 'AND',
		);

		$animation_values = array(
			esc_html__('None', 'propharm')     => 'none',
			esc_html__('Fade In', 'propharm')  => 'fadeIn',
			esc_html__('Move Up', 'propharm')  => 'moveUp',
		);

		$size_values_box = array(
			esc_html__('Small', 'propharm')        => 'small',
			esc_html__('Medium', 'propharm')       => 'medium',
			esc_html__('Large', 'propharm')        => 'large'
		);

		$size_values_default = array(
			esc_html__('Small', 'propharm')        => 'small',
			esc_html__('Medium', 'propharm')       => 'medium',
			esc_html__('Large', 'propharm')        => 'large'
		);

		$size_values_extra = array(
			esc_html__('Extra small', 'propharm')  => 'extra-small',
			esc_html__('Small', 'propharm')        => 'small',
			esc_html__('Medium', 'propharm')       => 'medium',
			esc_html__('Large', 'propharm')        => 'large',
			esc_html__('Extra large', 'propharm')  => 'large-x',
			esc_html__('Extra Extra large', 'propharm')  => 'large-xx'
		);

		$font_weight_values = array(
			'initial'  => 'initial',
			'100'  => '100',
			'200'  => '200',
			'300'  => '300',
			'400'  => '400',
			'500'  => '500',
			'600'  => '600',
			'700'  => '700',
			'800'  => '800',
			'900'  => '900',
		);

		$font_size_values = array(esc_html__('Inherit', 'propharm') => 'inherit');
		for ($i=0; $i <= 72; $i++) {
			$font_size_values[$i.esc_html__('px', 'propharm')] = $i.'px';
		}

		$line_height_values = array(esc_html__('Inherit', 'propharm') => 'inherit');
		for ($i=0; $i <= 80; $i++) {
			$line_height_values[$i.esc_html__('px', 'propharm')] = $i.'px';
		}

		$align_values = array(
			esc_html__('Left','propharm')   => 'left',
			esc_html__('Right','propharm')  => 'right',
			esc_html__('Center','propharm') => 'center'
		);

		$align_values_extended = array(
			esc_html__('None','propharm')   => 'none',
			esc_html__('Left','propharm')   => 'left',
			esc_html__('Right','propharm')  => 'right',
			esc_html__('Center','propharm') => 'center'
		);

		$logic_values = array(
			esc_html__('False','propharm')   => 'false',
			esc_html__('True','propharm')  => 'true',
		);

		$animation_type_values = array(
			esc_html__('Sequential','propharm')  => 'sequential',
			esc_html__('Random','propharm')      => 'random'
		);

		$image_size_values = array(
			'full'      => 'full',
			'large'     => 'large',
			'medium'    => 'medium',
			'thumbnail' => 'thumbnail',
		);

		$image_overlay_values = array(
			esc_html__('Overlay fade','propharm') 						 => 'overlay-fade',
			esc_html__('Overlay fade with image zoom','propharm')         => 'overlay-fade-zoom',
			esc_html__('Overlay fade with extreme image zoom','propharm') => 'overlay-fade-zoom-extreme',
			esc_html__('Overlay move fluid','propharm')                   => 'overlay-move',
			esc_html__('Transform','propharm')                            => 'transform'
		);

		$image_caption_values = array(
			esc_html__('Caption up','propharm') 					  => 'caption-up',
			esc_html__("Caption up and image up", 'propharm') => "caption-up-image"
		);

		$layout_type_values = array(
			esc_html__('Grid', 'propharm')     => 'grid',
			esc_html__('Carousel', 'propharm') => 'carousel',
		);

		$gap_values = array();

		for ($i=0; $i <= 80; $i = $i + 2) {
			$gap_values[$i.esc_html__('px', 'propharm')] = $i;
		}

		$social_links_array = enovathemes_addons_social_icons(get_template_directory().'/images/icons/social/');

		$menus = propharm_enovathemes_get_all_menus();

		$menu_list = array("choose" => esc_html__('Choose','propharm'));

		foreach ($menus as $menu => $attr) {
			$menu_list[$attr->slug] = $attr->name;
		}

		$menu_list = array_flip($menu_list);

		/* ELEMENTS
		----*/

			/* TYPOGRAPHY
			----*/

				/* et_heading
				----*/

			    	vc_map(array(
			    		'name'                    => esc_html__('Heading','propharm'),
			    		'description'             => esc_html__('Add/animate heading','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_heading',
			    		'class'                   => 'et_heading font',
			    		'icon'                    => 'et_heading',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-heading.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-heading.js',
			    		'params'                  => array(
			    			array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'content',
								'description'=> esc_html__('If you want to highlight/style a separate word, wrap it inside the span like this <span style="color: #XXXXXX">word</span>. If you need to break the sentence use the "_br_" special word','propharm'),
							),
							array(
								'param_name'=>'text_align',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Text align', 'propharm'),
								'value'     => $align_values
							),
							array(
								'param_name'=>'highlight',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Highlight', 'propharm'),
								'value'     => $logic_values
							),
							array(
								'param_name'=>'type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Tag', 'propharm'),
								'value'     => array(
									'H1'  => 'h1',
									'H2'  => 'h2',
									'H3'  => 'h3',
									'H4'  => 'h4',
									'H5'  => 'h5',
									'H6'  => 'h6',
									'p'   => 'p',
									'div' => 'div',
								),
								'std' => 'h1'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Link','propharm'),
								'param_name' => 'link',
								'value'      => '',
							),
							array(
								'param_name'=>'target',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Target', 'propharm'),
								'value'     => array(
									'_self'  => '_self',
									'_blank' => '_blank'
								)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Text color','propharm'),
								'param_name' => 'text_color',
								'value'      => '#184363',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Background color','propharm'),
								'param_name' => 'background_color',
								'value'      => '',
							),
							array(
								'param_name'=>'font_family',
								'type'      => 'dropdown',
								'group'     => esc_html__('Typography', 'propharm'),
								'heading'   => esc_html__('Font family', 'propharm'),
								'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
								'value'     => $google_fonts_family,
							),
							array(
								'param_name'=>'font_weight',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Font weight', 'propharm'),
								'group'     => esc_html__('Typography', 'propharm'),
								'value'     => $font_weight_values,
							),
							array(
								'param_name'=>'font_subsets',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Font subsets', 'propharm'),
								'group'     => esc_html__('Typography', 'propharm'),
								'value'     => array(
									'latin' => 'latin',
								)
							),
							array(
								'type'       => 'textfield',
								'group'      => esc_html__('Typography', 'propharm'),
								'heading'    => esc_html__('Font size (without any string)','propharm'),
								'param_name' => 'font_size',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'group'   	 => esc_html__('Typography', 'propharm'),
								'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
								'param_name' => 'letter_spacing',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'group'   	 => esc_html__('Typography', 'propharm'),
								'heading'    => esc_html__('Line height (without any string)','propharm'),
								'param_name' => 'line_height',
								'value'      => ''
							),
							array(
								'type'       => 'dropdown',
								'group'   	 => esc_html__('Typography', 'propharm'),
								'heading'    => esc_html__('Text transform','propharm'),
								'param_name' => 'text_transform',
								'value'      => array(
									esc_html__('None','propharm')       => 'none',
									esc_html__('Uppercase','propharm')  => 'uppercase',
									esc_html__('Lowercase','propharm')  => 'lowercase',
									esc_html__('Capitalize','propharm') => 'capitalize',
								)
							),

							/* icon
							----*/

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Icon','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon size (without any string)','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon_font_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon margin (without any string)','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon_margin',
									'value'      => '8',
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Icon position','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon_position',
									'value'      => array(
										esc_html__('Left','propharm')  => 'left',
										esc_html__('Right','propharm')  => 'right',
									)
								),

							/* tablet
							----*/

								array(
									'param_name'=>'tablet_text_align',
									'type'      => 'dropdown',
									'group'      => esc_html__('Tablet','propharm'),
									'heading'   => esc_html__('Text align', 'propharm'),
									'value'      => array(
										esc_html__('Inherit','propharm') => 'inherit',
										esc_html__('Left','propharm')    => 'left',
										esc_html__('Right','propharm')   => 'right',
										esc_html__('Center','propharm')  => 'center',
									)
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Font size (min-width 1024px and max-width 1279px)','propharm'),
									'group'      => esc_html__('Tablet','propharm'),
									'param_name' => 'tlf',
									'value'      => $typography_values,
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Line height (min-width 1024px and max-width 1279px)','propharm'),
									'group'      => esc_html__('Tablet','propharm'),
									'param_name' => 'tll',
									'value'      => $typography_values,
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Font size (min-width 768px and max-width 1023px)','propharm'),
									'group'      => esc_html__('Tablet','propharm'),
									'param_name' => 'tpf',
									'value'      => $typography_values,
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Line height (min-width 768px and max-width 1023px)','propharm'),
									'group'      => esc_html__('Tablet','propharm'),
									'param_name' => 'tpl',
									'value'      => $typography_values,
								),

							/* mobile
							----*/

								array(
									'param_name'=>'mobile_text_align',
									'type'      => 'dropdown',
									'group'      => esc_html__('Mobile','propharm'),
									'heading'   => esc_html__('Text align', 'propharm'),
									'value'      => array(
										esc_html__('Inherit','propharm') => 'inherit',
										esc_html__('Left','propharm')    => 'left',
										esc_html__('Right','propharm')   => 'right',
										esc_html__('Center','propharm')  => 'center',
									)
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Font size (min-width 375px and max-width 767px)','propharm'),
									'group'      => esc_html__('Mobile','propharm'),
									'param_name' => 'mf',
									'value'      => $typography_values,
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Line height (min-width 375px and max-width 767px)','propharm'),
									'group'      => esc_html__('Mobile','propharm'),
									'param_name' => 'ml',
									'value'      => $typography_values,
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Font size (max-width 374px)','propharm'),
									'group'      => esc_html__('Mobile','propharm'),
									'param_name' => 'mfs',
									'value'      => $typography_values,
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Line height (max-width 374px)','propharm'),
									'group'      => esc_html__('Mobile','propharm'),
									'param_name' => 'mls',
									'value'      => $typography_values,
								),

							/* animation
							----*/

								array(
									'type'       => 'checkbox',
									'heading'    => esc_html__('Animate','propharm'),
									'group'      => 'Animation',
									'param_name' => 'animate',
									'dependency' => Array('element' => 'highlight', 'value' => 'false')
								),
								array(
									'type'       => 'dropdown',
									'group'      => esc_html__('Animation','propharm'),
									'heading'    => esc_html__('Animation type','propharm'),
									'param_name' => 'animation_type',
									'value'     => array(
										esc_html__('Curtain', 'propharm') => 'curtain',
										esc_html__('Letter', 'propharm')  => 'letter',
										esc_html__('Words', 'propharm')   => 'words',
										esc_html__('Rows', 'propharm')    => 'rows',
									),
									'dependency' => Array('element' => 'animate', 'value' => 'true')
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Curtain Color','propharm'),
									'group'      => esc_html__('Animation','propharm'),
									'param_name' => 'element_color',
									'value'      => $main_color,
									'dependency' => Array(
										'element' => 'animate', 'value' => 'true',
										'element' => 'animation_type', 'value' => array('curtain')
									)
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Animation','propharm'),
									'heading'    => esc_html__('Start delay in ms (enter only integer number)','propharm'),
									'param_name' => 'delay',
									'value'      => '0',
									'dependency' => Array('element' => 'animate', 'value' => 'true')
								),
				
							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* padding
							----*/

								array(
									'type'       => 'padding',
									'group'      => esc_html__('Padding','propharm'),
									'heading'    => esc_html__('Padding','propharm'),
									'param_name' => 'padding',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element font','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_font',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

				/* et_blockquote
				----*/

			    	vc_map(array(
			    		'name'                    => esc_html__('Blockquote','propharm'),
			    		'description'             => esc_html__('Add blockquote','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_blockquote',
			    		'class'                   => 'et_blockquote',
			    		'icon'                    => 'et_blockquote',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-blockquote.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-blockquote.js',
			    		'params'                  => array(
			    			array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Upload image','propharm'),
								'param_name' => 'image',
							),
			    			array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Content','propharm'),
								'param_name' => 'text',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Author','propharm'),
								'param_name' => 'author',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),

							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

			/* UI
			----*/

				/* et_menu
				----*/

			    	vc_map(array(
			    		'name'                    => esc_html__('Navigation menu','propharm'),
			    		'description'             => esc_html__('Do not use with header builder','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_menu',
			    		'class'                   => 'et_menu hbe font',
			    		'icon'                    => 'et_menu',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-menu.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-menu.js',
			    		'params'                  => array(
			    			array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Menu name','propharm'),
								'param_name' => 'menu',
								'value'      => $menu_list,
								'default'    => 'choose'
							),
							array(
								'param_name'=>'type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Type', 'propharm'),
								'value'     => array(
									esc_html__('Horizontal','propharm')=> 'horizontal',
									esc_html__('Vertical','propharm')  => 'vertical'
								)
							),
							array(
								'param_name'=>'align',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Align', 'propharm'),
								'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
								'value'     => $align_values_extended
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Height in px (without any string)','propharm'),
								'group'      => 'Height',
								'param_name' => 'height',
								'value'      => '40',
								'dependency' => Array('element' => 'type', 'value' => 'horizontal')
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),

							/* top level
							----*/

								/* styling
								----*/

									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Space between menu items in px (without any string)','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_space',
										'value'      => '40',
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Items separator','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_separator',
										'value'      => $logic_values
									),
									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Items separator color','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_separator_color',
										'value'      => '#e0e0e0',
										'dependency' => Array('element' => 'menu_separator', 'value' => 'true')
									),
									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Items separator height (without any string)','propharm'),
										'description'=> esc_html__('Leave blank if you want 100% height','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_separator_height',
										'value'      => '',
										'dependency' => Array(
											'element' => 'menu_separator', 'value' => 'true',
											'element' => 'type', 'value' => 'horizontal'
										)
									),
									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu indicator','propharm'),
										'group'      => 'Top level',
										'param_name' => 'submenu_indicator',
										'value'      => $logic_values
									),

									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Menu color','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_color',
										'value'      => '#184363',
									),

									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Menu color hover','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_color_hover',
										'value'      => $main_color,
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Menu hover effect','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_hover',
										'value'      => array(
											esc_html__('None','propharm')      => 'none',
											esc_html__('Underline','propharm') => 'underline',
											esc_html__('Overline','propharm')  => 'overline',
											esc_html__('Outline','propharm')   => 'outline',
											esc_html__('Box','propharm')       => 'box',
											esc_html__('Fill','propharm')      => 'fill',
										),
										'dependency' => Array('element' => 'type', 'value' => 'horizontal')
									),

									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Menu hover effect color','propharm'),
										'group'      => 'Top level',
										'param_name' => 'menu_effect_color',
										'value'      => '',
										'dependency' => Array(
											'element' => 'menu_hover', 'value' => array('underline','overline','outline','box','fill'),
											'element' => 'type', 'value' => 'horizontal'
										)
									),

								/* typography
								----*/

									array(
										'param_name'=>'font_family',
										'type'      => 'dropdown',
										'group'     => esc_html__('Top level','propharm'),
										'heading'   => esc_html__('Font family', 'propharm'),
										'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
										'value'     => $google_fonts_family,
									),
									array(
										'param_name'=>'font_weight',
										'type'      => 'dropdown',
										'group'     => esc_html__('Top level','propharm'),
										'heading'   => esc_html__('Font weight', 'propharm'),
										'value'     => $font_weight_values,
										'std'       => '700'
									),
									array(
										'param_name'=>'font_subsets',
										'type'      => 'dropdown',
										'group'     => esc_html__('Top level','propharm'),
										'heading'   => esc_html__('Font subsets', 'propharm'),
										'value'      => array(
											'latin' => 'latin',
										)
									),
									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Font size (without any string)','propharm'),
										'group'      => esc_html__('Top level','propharm'),
										'param_name' => 'font_size',
										'value'      => '16',
									),
									array(
										'type'       => 'textfield',
										'group'      => esc_html__('Top level','propharm'),
										'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
										'param_name' => 'letter_spacing',
										'value'      => ''
									),
									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Text transform','propharm'),
										'group'      => 'Top level',
										'param_name' => 'text_transform',
										'value'      => array(
											esc_html__('None','propharm')       => 'none',
											esc_html__('Uppercase','propharm')  => 'uppercase',
											esc_html__('Lowercase','propharm')  => 'lowercase',
											esc_html__('Capitalize','propharm') => 'capitalize',
										)
									),

							/* submenu
							----*/

								/* styling
								----*/

									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Offset','propharm'),
										'description'=> esc_html__('Leave blank to have 100% offset','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenuoffset',
										'value'      => '',
									),
									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Submenu color','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_color',
										'value'      => '#184363',
									),

									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Submenu color hover','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_color_hover',
										'value'      => $main_color,
									),

									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Submenu background color','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_back_color',
										'value'      => '#ffffff',
									),

									array(
										'type'       => 'colorpicker',
										'heading'    => esc_html__('Submenu background color hover','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_back_color_hover',
										'value'      => '',
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu shadow','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_shadow',
										'value'      => $logic_values
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu indicator','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_submenu_indicator',
										'value'      => $logic_values
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu items separator','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_separator',
										'value'      => $logic_values
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu appear effect','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_appear',
										'value'      => array(
											esc_html__('Default','propharm')   => 'none',
											esc_html__('Fade','propharm')      => 'fade',
										),
									),

									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu appear from','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'submenu_appear_from',
										'value'      => array(
											esc_html__('From bottom','propharm') => 'bottom',
											esc_html__('From top','propharm')    => 'top'
										),
										'dependency' => Array('element' => 'type', 'value' => 'horizontal')
									),


								/* typography
								----*/

									array(
										'param_name'=>'subfont_family',
										'type'      => 'dropdown',
										'group'     => esc_html__('Submenu','propharm'),
										'heading'   => esc_html__('Submenu font family', 'propharm'),
										'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
										'value'     => $google_fonts_family,
									),
									array(
										'param_name'=>'subfont_weight',
										'type'      => 'dropdown',
										'group'     => esc_html__('Submenu','propharm'),
										'heading'   => esc_html__('Submenu font weight', 'propharm'),
										'value'     => $font_weight_values
									),
									array(
										'param_name'=>'subfont_subsets',
										'type'      => 'dropdown',
										'group'     => esc_html__('Submenu','propharm'),
										'heading'   => esc_html__('Submenu font subsets', 'propharm'),
										'value'      => array(
											'latin' => 'latin',
										)
									),
									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Submenu font size (without any string)','propharm'),
										'group'      => esc_html__('Submenu','propharm'),
										'param_name' => 'subfont_size',
										'value'      => '16',
									),
									array(
										'type'       => 'textfield',
										'group'      => esc_html__('Submenu','propharm'),
										'heading'    => esc_html__('Submenu letter spacing (without any string)','propharm'),
										'param_name' => 'subletter_spacing',
										'value'      => ''
									),
									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu text transform','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'subtext_transform',
										'value'      => array(
											esc_html__('None','propharm')       => 'none',
											esc_html__('Uppercase','propharm')  => 'uppercase',
											esc_html__('Lowercase','propharm')  => 'lowercase',
											esc_html__('Capitalize','propharm') => 'capitalize',
										)
									),

							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element font','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_font',
									'value'      => '',
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element font','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'subelement_font',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

		    	/* et_button
				----*/

					vc_map(array(
		    			'name'                    => esc_html__('Button','propharm'),
			    		'description'             => esc_html__('Do not use with header builder','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_button',
			    		'class'                   => 'et_button',
			    		'icon'                    => 'et_button',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-button.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-button.js',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button text','propharm'),
								'param_name' => 'button_text',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button link','propharm'),
								'param_name' => 'button_link',
								'value'      => '',
							),
							array(
								'param_name'=>'target',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Target', 'propharm'),
								'value'     => array(
									'_self'  => '_self',
									'_blank' => '_blank'
								)
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Open link in modal window?', 'propharm'),
								'param_name' => 'button_link_modal',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),

			    			/* typography
							----*/

								array(
									'param_name'=>'font_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Typography', 'propharm'),
									'heading'   => esc_html__('Font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'font_weight',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Font weight', 'propharm'),
									'group'     => esc_html__('Typography', 'propharm'),
									'value'     => $font_weight_values,
									'std'       => '400'
								),
								array(
									'param_name'=>'font_subsets',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Font subsets', 'propharm'),
									'group'     => esc_html__('Typography', 'propharm'),
									'value'     => array(
										'latin' => 'latin',
									)
								),
				    			array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Button font size (without any string)','propharm'),
									'group'      => esc_html__('Typography','propharm'),
									'param_name' => 'button_font_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Button letter spacing (without any string)','propharm'),
									'group'      => esc_html__('Typography','propharm'),
									'param_name' => 'button_letter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Button line height (without any string)','propharm'),
									'group'      => esc_html__('Typography','propharm'),
									'param_name' => 'button_line_height',
									'value'      => '22'
								),
								array(
									'type'       => 'dropdown',
									'group'   	 => esc_html__('Typography', 'propharm'),
									'heading'    => esc_html__('Text transform','propharm'),
									'param_name' => 'button_text_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

							/* styling
							----*/

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Button size','propharm'),
									'group'      => 'Styling',
									'param_name' => 'button_size',
									'value'      => array(
										esc_html__('Small','propharm')  => 'small',
										esc_html__('Medium','propharm') => 'medium',
										esc_html__('Large','propharm')  => 'large',
									),
									'std' => 'medium',
									'dependency' => Array('element' => 'button_size_custom', 'value' => 'false')
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Button custom size','propharm'),
									'group'      => 'Styling',
									'param_name' => 'button_size_custom',
									'value'      => $logic_values
								),

								array(
									'type'       => 'textfield',
									'group'      => 'Styling',
									'heading'    => esc_html__('Button width in px (without any string)','propharm'),
									'param_name' => 'width',
									'value'      => '',
									'dependency' => Array('element' => 'button_size_custom', 'value' => 'true')
								),

								array(
									'type'       => 'textfield',
									'group'      => 'Styling',
									'heading'    => esc_html__('Button height in px (without any string)','propharm'),
									'param_name' => 'height',
									'value'      => '',
									'dependency' => Array('element' => 'button_size_custom', 'value' => 'true')
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Button style','propharm'),
									'group'      => 'Styling',
									'param_name' => 'button_style',
									'value'      => array(
										esc_html__('Normal','propharm')  => 'normal',
										esc_html__('Outline','propharm') => 'outline',
									)
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Button type','propharm'),
									'group'      => 'Styling',
									'param_name' => 'button_type',
									'value'      => array(
										esc_html__('Default','propharm') => 'default',
										esc_html__('Rounded','propharm') => 'rounded',
									)
								),
								array(
				    				'type'       => 'checkbox',
									'heading'    => esc_html__('Button shadow', 'propharm'),
									'group'      => esc_html__('Styling','propharm'),
									'param_name' => 'button_shadow',
									'value'      => '',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button color','propharm'),
									'group'      => esc_html__('Styling','propharm'),
									'param_name' => 'button_color',
									'value'      => '#ffffff'
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button background color','propharm'),
									'group'      => esc_html__('Styling','propharm'),
									'param_name' => 'button_back_color',
									'value'      => $main_color,
									'dependency' => Array('element' => 'button_style', 'value' => 'normal')
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button border color','propharm'),
									'group'      => esc_html__('Styling','propharm'),
									'param_name' => 'button_border_color',
									'value'      => $main_color,
									'dependency' => Array('element' => 'button_style', 'value' => 'outline')
								),

							/* hover
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button color hover','propharm'),
									'group'      => esc_html__('Hover','propharm'),
									'param_name' => 'button_color_hover',
									'value'      => '#ffffff'
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button background color hover','propharm'),
									'group'      => esc_html__('Hover','propharm'),
									'param_name' => 'button_back_color_hover',
									'value'      => '#184363',
									'dependency' => Array('element' => 'button_style', 'value' => 'normal')
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button border color hover','propharm'),
									'group'      => esc_html__('Hover','propharm'),
									'param_name' => 'button_border_color_hover',
									'value'      => '#184363',
									'dependency' => Array('element' => 'button_style', 'value' => 'outline')
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Hover animation','propharm'),
									'group'      => esc_html__('Hover','propharm'),
									'param_name' => 'animate_hover',
									'value'      => array(
										esc_html__('Normal','propharm')  	  => 'none',
										esc_html__('Fill effect','propharm')   => 'fill',
										esc_html__('Scale effect','propharm')  => 'scale',
										esc_html__('Move effect','propharm')   => 'move',
									),
									'dependency' => Array('element' => 'button_style', 'value' => 'normal')
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Hover animation','propharm'),
									'group'      => esc_html__('Hover','propharm'),
									'param_name' => 'animate_hover_outline',
									'value'      => array(
										esc_html__('Normal','propharm')  	  => 'none',
										esc_html__('Fill effect','propharm')   => 'fill',
										esc_html__('Scale effect','propharm')  => 'scale',
									),
									'dependency' => Array('element' => 'button_style', 'value' => 'outline')
								),

							/* click
							----*/

								array(
									'type'       => 'checkbox',
									'heading'    => esc_html__('Smooth Click animation','propharm'),
									'group'      => esc_html__('Click','propharm'),
									'param_name' => 'click_smooth',
									'value'      => ''
								),

							/* icon
							----*/

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Icon','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon size (without any string)','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon_font_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon margin (without any string)','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon_margin',
									'value'      => '8',
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Icon position','propharm'),
									'group'      => esc_html__('Icon','propharm'),
									'param_name' => 'icon_position',
									'value'      => array(
										esc_html__('Left','propharm')  => 'left',
										esc_html__('Right','propharm')  => 'right',
									)
								),

							/* animation
							----*/

								array(
					                'type'       => 'animation_style',
					                'heading'    => esc_html__('Animation','propharm'),
									'group'      => esc_html__('Animation','propharm'),
					                'param_name' => 'animation',
					                'weight'     => 0,
					            ),
					            array(
									'type'       => 'dropdown',
									'heading'    => esc_html__( 'Animation delay in ms (example 300)', 'propharm' ),
									'param_name' => 'animation_delay',
									'group'      => esc_html__('Animation','propharm'),
									'value'      => $animation_delay_values,
									'dependency' => Array('element' => 'animate', 'value' => 'true')
								),
							
							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element font','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_font',
									'value'      => '',
								),
			    		)
		    		));

				/* et_separator
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Separator','propharm'),
						'description'             => esc_html__('Use this element to separate content','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_separator',
						'class'                   => 'et_separator',
						'icon'                    => 'et_separator',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-separator.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-separator.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Type','propharm'),
								'param_name' => 'type',
								'value'      => array(
									esc_html__('solid','propharm')  => 'solid',
									esc_html__('dotted','propharm') => 'dotted',
									esc_html__('dashed','propharm') => 'dashed',
								)
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Color','propharm'),
								'param_name' => 'color',
								'value'      => '#e0e0e0'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Width (without any string, if you want 100% leave blank)','propharm'),
								'param_name' => 'width',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Height (without any string, if you want 1px leave blank)','propharm'),
								'param_name' => 'height',
								'value'      => ''
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Align','propharm'),
								'param_name' => 'align',
								'value'      => $align_values
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),

							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* responsive visibility
							----*/

								array(
									'type'       => 'rv',
									'heading'    => esc_html__( 'Responsive visibility', 'propharm' ),
									'group'      => esc_html__('Responsive visibility','propharm'),
									'param_name' => 'rv',
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

			    /* et_icon_separator
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Icon separator','propharm'),
						'description'             => esc_html__('Use this element to separate content','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_icon_separator',
						'class'                   => 'et_icon_separator',
						'icon'                    => 'et_icon_separator',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-separator.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-separator.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon','propharm'),
								'param_name' => 'icon',
							),
							array(
								'param_name'=>'icon_size',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Icon size', 'propharm'),
								'value'     => $size_values_default
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Separator color','propharm'),
								'param_name' => 'color_sep',
								'value'      => '#e0e0e0'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'param_name' => 'color_icon',
								'value'      => $main_color
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Gap from top (without any string)','propharm'),
								'param_name' => 'top',
								'value'      => '24'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Gap from bottom (without any string)','propharm'),
								'param_name' => 'bottom',
								'value'      => '24'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Separator width (without any string)','propharm'),
								'param_name' => 'width',
								'value'      => '120'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Height (without any string, if you want 1px leave blank)','propharm'),
								'param_name' => 'height',
								'value'      => '1'
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Align','propharm'),
								'param_name' => 'align',
								'value'      => $align_values
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

			    /* et_icon
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Icon','propharm'),
						'description'             => esc_html__('Insert icon','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_icon',
						'class'                   => 'et_icon',
						'icon'                    => 'et_icon',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon.js',
						'show_settings_on_create' => true,
						'params'                  => array(

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon','propharm'),
								'param_name' => 'icon',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon link','propharm'),
								'param_name' => 'icon_link',
								'value'      => '',
							),

							array(
								'param_name'=>'target',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Target', 'propharm'),
								'value'     => array(
									'_self'  => '_self',
									'_blank' => '_blank'
								),
								'dependency' => Array('element' => 'icon_link', 'not_empty' => true)
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Elastic click','propharm'),
								'param_name' => 'click',
								'value'      => $logic_values
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_color',
									'value'      => '#4D4D4D',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color hover','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_color_hover',
									'value'      => $main_color,
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_background_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color hover','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_background_color_hover',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_border_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color hover','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_border_color_hover',
									'value'      => '',
								),

								array(
									'type'       => 'textfield',
									'group'      => 'Styling',
									'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
									'param_name' => 'icon_border_width',
								),

								array(
									'type'       => 'checkbox',
									'group'      => 'Styling',
									'heading'    => esc_html__('Shadow','propharm'),
									'param_name' => 'shadow',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Size','propharm'),
									'group'      => 'Styling',
									'param_name' => 'size',
									'value'      => array(
										esc_html__('Small','propharm')  => 'small',
										esc_html__('Medium','propharm') => 'medium',
										esc_html__('Large','propharm')  => 'large',
										esc_html__('Custom','propharm') => 'custom',
									),
									'std' => 'medium'
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon size in px without any string','propharm'),
									'group'      => 'Styling',
									'param_name' => 'icon_size',
									'value'      => '',
									'dependency' => Array('element' => 'size', 'value' => 'custom')
								),

							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

			    /* et_icon_list
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Icon list','propharm'),
						'description'             => esc_html__('Insert icon list','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_icon_list',
						'class'                   => 'et_icon_list',
						'icon'                    => 'et_icon_list',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-list.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-list.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon','propharm'),
								'param_name' => 'icon',
								'value'      => '',
							),
			    			array(
								'param_name'=>'icon_size',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Size', 'propharm'),
								'value'     => array(
									esc_html__('Extra small', 'propharm')  => 'extra-small',
									esc_html__('Small', 'propharm')        => 'small',
									esc_html__('Medium', 'propharm')       => 'medium',
									esc_html__('Large', 'propharm')        => 'large'
								),
								'std'       => 'medium'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'param_name' => 'icon_color',
								'value'      => ''
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'param_name' => 'icon_background_color',
								'value'      => ''
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'param_name' => 'icon_border_color',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon border width (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Shadow','propharm'),
								'param_name' => 'shadow',
								'value'      => ''
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('List items','propharm'),
								'param_name' => 'content',
								'value'      => '',
								'description' => esc_html__('Use line break (press Enter) to separate between items','propharm'),
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),

							/* animation
							----*/

								array(
					                'type'       => 'checkbox',
					                'heading'    => esc_html__('Animate','propharm'),
									'group'      => esc_html__('Animation','propharm'),
					                'param_name' => 'animate',
					                'weight'     => 0,
					            ),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Animation','propharm'),
									'heading'    => esc_html__('Start delay in ms (enter only integer number)','propharm'),
									'param_name' => 'delay',
									'value'      => '0',
									'dependency' => Array('element' => 'animate', 'value' => 'true')
								),

							/* margin
							----*/

								array(
									'type'       => 'margin',
									'group'      => esc_html__('Margin','propharm'),
									'heading'    => esc_html__('Margin','propharm'),
									'param_name' => 'margin',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

			    /* et_accordion
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Accordion','propharm'),
			    		'description'             => esc_html__('Insert accordion','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_accordion',
			    		'class'                   => 'et_accordion',
			    		'icon'                    => 'et_accordion',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-accordion.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-accordion.js',
			    		'as_parent'               => array('only' => 'et_accordion_item'),
			    		'content_element'         => true,
			    		'show_settings_on_create' => true,
			    		'is_container'            => true,
			    		'js_view'                 => 'VcColumnView',
			    		'params'                  => array(
			    			array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Collapsible','propharm'),
								'param_name' => 'collapsible',
								'value'      => $logic_values
							),

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title color active','propharm'),
									'group'      => 'Styling',
									'param_name' => 'color_active',
									'value'      => $main_color,
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title border color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'border_color',
									'value'      => '#eeeeee',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title border color active','propharm'),
									'group'      => 'Styling',
									'param_name' => 'border_color_active',
									'value'      => '#e0e0e0',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title background color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'background_color',
									'value'      => $area_color,
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title background color active','propharm'),
									'group'      => 'Styling',
									'param_name' => 'background_color_active',
									'value'      => '#ffffff',
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));


			    	vc_map(array(
						'name'                    => esc_html__('Accordion item','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_accordion_item',
						'class'                   => 'et_accordion_item',
						'icon'                    => 'et_accordion_item',
						'as_child'                => array('only' => 'et_accordion'),
	    				"as_parent"               => array('except' => 'vc_section'),
	    				'content_element'         => true,
						"js_view"                 => 'VcColumnView',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Open','propharm'),
								'param_name' => 'open',
								'value'      => $logic_values
							),
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon','propharm'),
								'param_name' => 'icon',
								'value'      => '',
							),
							array(
			    				'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => ''
							),

						)
					));

			    /* et_tabs
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Tabs','propharm'),
			    		'description'             => esc_html__('Insert tabs','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_tab',
			    		'class'                   => 'et_tab',
			    		'icon'                    => 'et_tab',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-tab.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-tab.js',
			    		'as_parent'               => array('only' => 'et_tab_item'),
			    		'content_element'         => true,
			    		'show_settings_on_create' => true,
			    		'is_container'            => true,
			    		'js_view'                 => 'VcColumnView',
			    		'params'                  => array(
			    			array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Type','propharm'),
								'param_name' => 'type',
								'value'      => array(
									esc_html__('Horizontal','propharm')  => 'horizontal',
									esc_html__('Vertical','propharm')  => 'vertical',
								)
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Tabs center','propharm'),
								'param_name' => 'center',
							),

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Tab color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Tab color active','propharm'),
									'group'      => 'Styling',
									'param_name' => 'color_active',
									'value'      => '#ffffff',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Tab background color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'background_color',
									'value'      => '#f0f0f0',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Tab background color active','propharm'),
									'group'      => 'Styling',
									'param_name' => 'background_color_active',
									'value'      => $main_color,
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));


			    	vc_map(array(
						'name'                    => esc_html__('Tab','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_tab_item',
						'class'                   => 'et_tab_item',
						'icon'                    => 'et_tab_item',
						'as_child'                => array('only' => 'et_tab'),
	    				"as_parent"               => array('except' => 'vc_section'),
	    				'content_element'         => true,
						"js_view"                 => 'VcColumnView',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Active','propharm'),
							'param_name' => 'active',
							'value'      => array(
								'false' => 'false',
								'true'  => 'true'
							)
						),
						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__('Icon','propharm'),
							'param_name' => 'icon',
							'value'      => '',
						),
						array(
		    				'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => ''
						),
						)
					));


			    /* et_stagger_box
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Stagger box','propharm'),
						'description'             => esc_html__('Insert stagger box with any content','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_stagger_box',
						'class'                   => 'et_stagger_box',
						'icon'                    => 'et_stagger_box',
						"as_parent"               => array('except' => 'vc_row, vc_section'),
	    				'content_element'         => true,
						"js_view"                 => 'VcColumnView',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-stagger-box.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-stagger-box.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),

							/* animation
							----*/

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Content animation','propharm'),
									'param_name' => 'stagger',
									'value'      => array(
										esc_html__('Stagger from top','propharm')  => 'top',
										esc_html__('Stagger from bottom','propharm') => 'bottom',
										esc_html__('Stagger from left','propharm') => 'left',
										esc_html__('Stagger from right','propharm') => 'right'
									)
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Stagger interval in ms (enter only integer number)','propharm'),
									'param_name' => 'interval',
									'value'      => '50',
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Start delay in ms (enter only integer number)','propharm'),
									'param_name' => 'delay',
									'value'      => '0',
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								)
								
						)
					));

			/* SOCIAL
			----*/

				/* et_social_icons
				----*/

					foreach ($social_links_array as $social) {
						vc_add_param('et_social_links', array(
							'type'       => 'textfield',
							'heading'    => ucfirst($social).' link',
							'param_name' => $social,
							'value'      => '',
							'weight' => 1
						));
					}

			    	vc_map(array(
						'name'                    => esc_html__('Social links','propharm'),
			    		'description'             => esc_html__('Use to add social links','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_social_links',
			    		'class'                   => 'et_social_links',
			    		'icon'                    => 'et_social_links',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-social-links.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-social-links.js',
						'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),
							array(
								'param_name'=>'target',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Target', 'propharm'),
								'value'     => array(
									'_self'  => '_self',
									'_blank' => '_blank'
								)
							),
							array(
								'param_name'=>'stretching',
								'type'      => 'checkbox',
								'heading'   => esc_html__('Stretching', 'propharm'),
							),

							/* styling
							----*/

								array(
									'param_name'=>'shadow',
									'type'      => 'checkbox',
									'group'     => esc_html__('Styling','propharm'),
									'heading'   => esc_html__('Shadow', 'propharm'),
									'value'     => ''
								),

								array(
									'param_name'=>'size',
									'type'      => 'dropdown',
									'group'     => esc_html__('Styling','propharm'),
									'heading'   => esc_html__('Size', 'propharm'),
									'value'     => $size_values_default
								),

								array(
									'param_name'=>'styling_original',
									'type'      => 'dropdown',
									'group'     => esc_html__('Styling','propharm'),
									'heading'   => esc_html__('Original styling', 'propharm'),
									'value'     => $logic_values
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_color',
									'value'      => '#4D4D4D',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color hover','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_color_hover',
									'value'      => '#184363',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_background_color',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color hover','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_background_color_hover',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_border_color',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color hover','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_border_color_hover',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon border width (without any string)','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'icon_border_width',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

				/* et_social_icons
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Social share','propharm'),
			    		'description'             => esc_html__('Use to add social sharing','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_social_share',
			    		'class'                   => 'et_social_share',
			    		'icon'                    => 'et_social_share',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-social-share.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-social-share.js',
						'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),

							/* styling
							----*/

								array(
									'param_name'=>'shadow',
									'type'      => 'checkbox',
									'heading'   => esc_html__('Shadow', 'propharm'),
									'value'     => ''
								),

								array(
									'param_name'=>'styling_original',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Original styling', 'propharm'),
									'value'     => $logic_values
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color','propharm'),
									'param_name' => 'icon_color',
									'value'      => '#4D4D4D',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color hover','propharm'),
									'param_name' => 'icon_color_hover',
									'value'      => '#184363',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color','propharm'),
									'param_name' => 'icon_background_color',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color hover','propharm'),
									'param_name' => 'icon_background_color_hover',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color','propharm'),
									'param_name' => 'icon_border_color',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color hover','propharm'),
									'param_name' => 'icon_border_color_hover',
									'value'      => '',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon border width (without any string)','propharm'),
									'param_name' => 'icon_border_width',
									'dependency' => Array('element' => 'styling_original', 'value' => 'false')
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

				/* et_mailchimp
				----*/

	 				$list_array = enovathemes_addons_mailchimp_list();

	 				$list_values = array(''=>esc_html__('Choose','propharm'));

	 				if ( !is_wp_error( $list_array ) && is_array($list_array)){

	 					foreach ( $list_array as $list){
	 						$list_values[$list['id']] = $list['name'];
	 					}
	 				}

					$list_values = array_flip($list_values);

					if (empty($list_values)) {
						array_push($list_values, esc_html__('Mailchimp did not return any list','propharm'));
					}

			    	vc_map(array(
			    		'name'                    => esc_html__('Mailchimp','propharm'),
			    		'description'             => esc_html__('Use to add AJAX mailchimp subscribe','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_mailchimp',
			    		'class'                   => 'et_mailchimp',
			    		'icon'                    => 'et_mailchimp',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mailchimp.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mailchimp.js',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('List','propharm'),
								'description'=> esc_html__('Make sure you have the Mailchimp API key and at least one list in your Mailchimp dashboard. Go to theme options >> general >> Mailchimp API key','propharm'),
								'param_name' => 'list',
								'value'      => $list_values,
							),

							array(
								'param_name'=>'layout',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Layout', 'propharm'),
								'value'     => array(
									esc_html__('Simple', 'propharm')     => 'simple',
									esc_html__('Alternative', 'propharm') => 'alt'
								)
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Terms & conditions page link','propharm'),
								'param_name' => 'terms',
								'value'      => ''
							),


							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Text field color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'text_color',
									'value'      => '#184363',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Text field background color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'text_background_color',
									'value'      => '#ffffff',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Text field border color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'text_border_color',
									'value'      =>'#eeeeee',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'button_color',
									'value'      => '#ffffff',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button color hover','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'button_color_hover',
									'value'      => '#ffffff',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button background color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'button_background_color',
									'value'      => $main_color,
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Button background color hover','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'button_background_color_hover',
									'value'      => '#184363',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Terms link color','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'terms_color',
									'value'      => '#184363',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Terms link color hover','propharm'),
									'group'     => esc_html__('Styling','propharm'),
									'param_name' => 'terms_color_hover',
									'value'      => $main_color,
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

				/* et_social_icons
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Instagram feed','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_instagram',
			    		'class'                   => 'et_instagram',
			    		'icon'                    => 'et_instagram',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
			    			array(
								'type'       => 'attach_images',
								'heading'    => esc_html__('Upload placeholder images','propharm'),
								'description'=> esc_html__('Once uploaded it will show the placeholder images in case you have issue with instagram account','propharm'),
								'param_name' => 'images',
							)
			    		)
					));
	
			/* SELFHOSTED
			----*/

				/* et_icon_box_container
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Icon box container','propharm'),
						'description'             => esc_html__('Insert icon box container','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_icon_box_container',
						'class'                   => 'et_icon_box_container',
						'icon'                    => 'et_icon_box_container',
						"as_parent"               => array('only' => 'et_icon_box'),
	    				'content_element'         => true,
						"js_view"                 => 'VcColumnView',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-box-container.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-box-container.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Gap','propharm'),
								'param_name' => 'gap',
								'value'      => ''
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Guides arrows','propharm'),
								'param_name' => 'guides',
								'value'      => ''
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Guides arrows color','propharm'),
								'param_name' => 'guides_color',
								'value'      => '',
								'dependency' => Array(
									'element' => 'guides', 'value' => 'true',
								)
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Box Shadow','propharm'),
								'param_name' => 'shadow',
								'value'      => '',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Border','propharm'),
								'param_name' => 'border',
								'value'      => ''
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Border color','propharm'),
								'param_name' => 'border_color',
								'value'      => '',
								'dependency' => Array(
									'element' => 'border', 'value' => array('true'),
								)
							),
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
								)
							),
							array(
								'param_name'=>'height',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Min height', 'propharm'),
								'value'     => array(
									'0'      => '0',
									'100vh'  => '100vh',
									'70vh'   => '70vh',
									'60vh'   => '60vh',
									'50vh'   => '50vh',
									'custom'  => 'custom',
								)
							),
							array(
								'param_name'=>'custom-height',
								'type'      => 'textfield',
								'heading'   => esc_html__('Custom min height value (enter any value you need using all available units)', 'propharm'),
								'value'     => '',
								'dependency' => Array(
									'element' => 'height', 'value' => 'custom',
								)
							),
							array(
								'param_name'=>'vertical_align',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Vertical align boxes', 'propharm'),
								'value'     => array(
									'top'     => 'top',
									'middle'  => 'middle',
									'bottom'  => 'bottom',
								)
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Box alignment','propharm'),
								'param_name' => 'content_alignment',
								'value'      => array(
									esc_html__('Left','propharm')   => 'left',
									esc_html__('Center','propharm') => 'center',
									esc_html__('Right','propharm')  => 'right',
								)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Box animation','propharm'),
								'param_name' => 'animation',
								'value'      => array(
									esc_html__('None','propharm')   => 'none',
									esc_html__('Fade','propharm')   => 'fade',
									esc_html__('Appear','propharm') => 'appear',
								)
							),
						
							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

				/* et_icon_box
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Icon box','propharm'),
						'description'             => esc_html__('Insert icon box','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_icon_box',
						'class'                   => 'et_icon_box',
						'icon'                    => 'et_icon_box',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-box.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-icon-box.js',
						'show_settings_on_create' => true,
						'params'                  => array(

							/* icon
							----*/

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Icon','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon',
									'value'      => '',
								),
				    			array(
									'param_name'=>'icon_size',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Icon size', 'propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'value'     => array(
										esc_html__('Extra small','propharm')    => 'small-x',
										esc_html__('Small','propharm')    => 'small',
										esc_html__('Medium','propharm')   => 'medium',
										esc_html__('Large','propharm')    => 'large',
									),
									'std' => 'large'
								),
								array(
									'param_name'=>'icon_position',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Icon position', 'propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'value'     => array(
										esc_html__('Top','propharm')  => 'top',
										esc_html__('Left','propharm')  => 'left',
										esc_html__('Right','propharm')  => 'right',
									),
								),
								array(
									'param_name'=>'icon_alignment',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Icon alignment', 'propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'value'     => $align_values,
									'dependency' => Array(
										'element' => 'icon_position', 'value' => 'top',
									)
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_color',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon color hover','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_color_hover',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_back_color',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon background color hover','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_back_color_hover',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_border_color',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Icon border color hover','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_border_color_hover',
									'value'      => ''
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Icon border width (without any string)','propharm'),
									'group'      => esc_html__('Icon', 'propharm'),
									'param_name' => 'icon_border_width',
								),
								
							/* content
							----*/

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Title','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'title',
									'value'      => ''
								),
								array(
									'param_name'=>'title_tag',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Tag', 'propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'value'     => array(
										'default'  => 'default',
										'H1'  => 'h1',
										'H2'  => 'h2',
										'H3'  => 'h3',
										'H4'  => 'h4',
										'H5'  => 'h5',
										'H6'  => 'h6',
										'p'   => 'p',
										'div' => 'div',
									),
									'std' => 'default'
								),
								array(
									'param_name'=>'font_weight',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Title font weight', 'propharm'),
									'group'     => esc_html__('Content', 'propharm'),
									'value'     => $font_weight_values,
									'std'       => 'initial'
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Content', 'propharm'),
									'heading'    => esc_html__('Title font size (without any string)','propharm'),
									'param_name' => 'font_size',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'group'   	 => esc_html__('Content', 'propharm'),
									'heading'    => esc_html__('Title letter spacing (without any string)','propharm'),
									'param_name' => 'letter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'textfield',
									'group'   	 => esc_html__('Content', 'propharm'),
									'heading'    => esc_html__('Title line height (without any string)','propharm'),
									'param_name' => 'line_height',
									'value'      => ''
								),
								array(
									'type'       => 'textarea_html',
									'heading'    => esc_html__('Content','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'content',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Link','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'link',
									'value'      => ''
								),
								array(
								'param_name'=>'target',
									'type'      => 'dropdown',
									'group'      => esc_html__('Content', 'propharm'),
									'heading'   => esc_html__('Target', 'propharm'),
									'value'     => array(
										'_self'  => '_self',
										'_blank' => '_blank'
									)
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title color','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'title_color',
									'value'      => '#184363'
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title color hover','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'title_color_hover',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Content color','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'text_color',
									'value'      => '#4D4D4D'
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Content color hover','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'text_color_hover',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Box background color','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'box_color',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Box background color hover','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'box_color_hover',
									'value'      => ''
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Box border width (without any string)','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'box_border_width',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Box border color','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'box_border_color',
									'value'      => ''
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Box border color hover','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'box_border_color_hover',
									'value'      => ''
								),
							
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Hover animation','propharm'),
								'param_name' => 'animation',
								'value'      => array(
									esc_html__('None','propharm')          => 'none',
									esc_html__('Icon scale','propharm')    => 'scale',
									esc_html__('Box transform','propharm') => 'transform',
								)
							),

							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Box Shadow','propharm'),
								'param_name' => 'shadow',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),

							/* padding
							----*/

								array(
									'type'       => 'padding',
									'group'      => esc_html__('Padding','propharm'),
									'heading'    => esc_html__('Padding','propharm'),
									'param_name' => 'padding',
									'value'      => '48,32,48,32'
								),

								array(
									'type'       => 'crp',
									'heading'    => esc_html__( 'Responsive padding', 'propharm' ),
									'group'      => esc_html__('Responsive padding','propharm'),
									'param_name' => 'crp',
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

				/* et_step_box_container
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Step box','propharm'),
						'description'             => esc_html__('Insert step box','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_step_box_container',
						'class'                   => 'et_step_box_container',
						'icon'                    => 'et_step_box_container',
						"as_parent"               => array('only' => 'et_step_box'),
	    				'content_element'         => true,
						"js_view"                 => 'VcColumnView',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
								)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),

						)
					));

				/* et_step_box
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Step box','propharm'),
						'description'             => esc_html__('Insert step box','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						"as_child"                => array('only' => 'et_step_box_container'),
						'base'                    => 'et_step_box',
						'class'                   => 'et_step_box',
						'icon'                    => 'et_step_box',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-step-box.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-step-box.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),


							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Title color','propharm'),
									'group'      => esc_html__('Styling', 'propharm'),
									'param_name' => 'title_color',
									'value'      => $main_color
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Content color','propharm'),
									'group'      => esc_html__('Styling', 'propharm'),
									'param_name' => 'text_color',
									'value'      => '#4D4D4D'
								),

							/* content
							----*/

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Title','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'title',
									'value'      => ''
								),
								array(
									'param_name'=>'title_tag',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Tag', 'propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'value'     => array(
										'H1'  => 'h1',
										'H2'  => 'h2',
										'H3'  => 'h3',
										'H4'  => 'h4',
										'H5'  => 'h5',
										'H6'  => 'h6',
										'p'   => 'p',
										'div' => 'div',
									),
									'std' => 'h6'
								),
								array(
									'type'       => 'textarea_html',
									'heading'    => esc_html__('Content','propharm'),
									'group'      => esc_html__('Content', 'propharm'),
									'param_name' => 'content',
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
						)
					));

				/* et_carousel
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Carousel','propharm'),
			    		'description'             => esc_html__('Insert carousel with any content you want','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_carousel',
			    		'class'                   => 'et_carousel',
			    		'icon'                    => 'et_carousel',
			    		'show_settings_on_create' => true,
				    	'content_element'         => true,
						"js_view"                 => 'VcColumnView',
			    		'as_parent'               => array('only' => 'et_carousel_item'),
			    		'params'                  => array(
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
									'6'  => '6',
								)
							),
							array(
								'param_name'=>'navigation_type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Navigation type', 'propharm'),
								'value'     => array(
									esc_html__('Only arrows','propharm')  => 'arrows',
									esc_html__('Only dottes','propharm')  => 'dottes',
									esc_html__('Both arrows and dottes','propharm')  => 'both'
								)
							),
							array(
								'param_name'=>'navigation_position',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Navigation position', 'propharm'),
								'value'     => array(
									esc_html__('Top','propharm')  => 'top',
									esc_html__('Side','propharm')  => 'side',
								),
								'dependency' => Array(
									'element' => 'carousel', 'value' => array('true'),
									'element' => 'navigation_type', 'value' => array('arrows','both'),
								)
							),
							array(
								'param_name'=>'autoplay',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Autoplay', 'propharm'),
								'value'     => $logic_values
							),
			    		)
			    	));

			    	vc_map(array(
			    		'name'                    => 'Carousel item',
			    		'description'             => esc_html__('Insert carousel item','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_carousel_item',
			    		'class'                   => 'et_carousel_item',
			    		'icon'                    => 'et_carousel_item',
			    		'show_settings_on_create' => false,
				    	'content_element'         => true,
			    		'as_child'                => array('only' => 'et_carousel'),
			    		"as_parent"               => array('except' => 'vc_row, vc_section'),
						"js_view"                 => 'VcColumnView',
			    		'params'                  => array()
			    	));

			    /* et_pricing_table
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Pricing table','propharm'),
			    		'description'             => esc_html__('Use to display your service/product pricing','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_pricing_table_container',
			    		'class'                   => 'et_pricing_table_container',
			    		'icon'                    => 'et_pricing_table',
			    		'show_settings_on_create' => true,
				    	'content_element'         => true,
						"js_view"                 => 'VcColumnView',
			    		'as_parent'               => array('only' => 'et_pricing_table'),
			    		'params'                  => array(
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
								)
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),
							
			    		)
			    	));

					vc_map(array(
			    		'name'                    => esc_html__('Pricing table','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'description'             => esc_html__('Use to display your service/product pricing','propharm'),
			    		'base'                    => 'et_pricing_table',
			    		'icon'                    => 'et_pricing_table',
			    		'as_child'                => array('only' => 'et_pricing_table_container'),
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-pricing-table.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-pricing-table.js',
			    		'content_element'         => true,
			    		'params'                  => array(
			    			array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Color','propharm'),
								'param_name' => 'color',
								'value'      => $main_color
							),
			    			array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Highlight', 'propharm'),
								'param_name' => 'highlight',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Label','propharm'),
								'param_name' => 'label',
								'value'      => ''
							),
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Currency','propharm'),
								'param_name' => 'currency',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Price','propharm'),
								'param_name' => 'price',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Plan','propharm'),
								'param_name' => 'plan',
								'value'      => ''
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('List items','propharm'),
								'param_name' => 'content',
								'value'      => '',
								'description' => esc_html__('Use line break (press Enter) to separate between items','propharm'),
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button text','propharm'),
								'param_name' => 'button_text',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button link','propharm'),
								'param_name' => 'button_link',
								'value'      => ''
							),
							array(
								'param_name'=>'target',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Target', 'propharm'),
								'value'     => array(
									'_self'  => '_self',
									'_blank' => '_blank'
								)
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

				/* et_testimonial
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Testimonials','propharm'),
			    		'description'             => esc_html__('Add testimonials to carousel container','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_testimonial_container',
			    		'class'                   => 'et_testimonial_container',
			    		'icon'                    => 'et_testimonial_container',
			    		'show_settings_on_create' => true,
				    	'content_element'         => true,
			    		'js_view'                 => 'VcColumnView',
			    		'as_parent'               => array('only' => 'et_testimonial'),
			    		'params'                  => array(
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
								)
							),
							array(
								'param_name'=>'navigation_type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Navigation type', 'propharm'),
								'value'     => array(
									esc_html__('Only arrows','propharm')  => 'arrows',
									esc_html__('Only dottes','propharm')  => 'dottes',
									esc_html__('Both arrows and dottes','propharm')  => 'both'
								)
							),
							array(
								'param_name'=>'autoplay',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Autoplay', 'propharm'),
								'value'     => $logic_values
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							)
			    		)
			    	));

			    	vc_map(array(
			    		'name'                    => esc_html__('Testimonial','propharm'),
			    		'description'             => esc_html__('Add testimonial','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_testimonial',
			    		'class'                   => 'et_testimonial',
			    		'icon'                    => 'et_testimonial',
			    		'as_child'                => array('only' => 'et_testimonial_container'),
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
			    			array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Client image','propharm'),
								'param_name' => 'image',
							),
			    			array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Content','propharm'),
								'param_name' => 'text',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Author','propharm'),
								'param_name' => 'author',
								'value'      => '',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Rating','propharm'),
								'param_name' => 'rating',
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
								),
								'std'=>'5'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),
			    		)
			    	));

			    /* et_client
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Clients','propharm'),
			    		'description'             => esc_html__('Add clients','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_client_container',
			    		'class'                   => 'et_client_container',
			    		'icon'                    => 'et_client_container',
			    		'show_settings_on_create' => true,
			    		'is_container'            => true,
				    	'content_element'         => true,
						"js_view"                 => 'VcColumnView',
			    		'as_parent'               => array('only' => 'et_client'),
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-client.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-client.js',
			    		'params'                  => array(
			    			array(
								'param_name'=>'type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Type', 'propharm'),
								'value'     => array(
									esc_html__('Grid', 'propharm')     => 'grid',
									esc_html__('Carousel', 'propharm') => 'carousel',
								)
							),
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
									'6'  => '6',
								)
							),
							array(
								'param_name'=>'columns_tab',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column tablet', 'propharm'),
								'value'     => array(
									esc_html__('Inherit', 'propharm')  => 'inherit',
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
									'6'  => '6',
								),
								'dependency' => Array('element' => 'type', 'value' => 'grid')
							),
							array(
								'param_name'=>'columns_mob',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Column mobile', 'propharm'),
								'value'     => array(
									esc_html__('Inherit', 'propharm')  => 'inherit',
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
								),
								'dependency' => Array('element' => 'type', 'value' => 'grid')
							),
							array(
								'param_name'=>'navigation_type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Navigation type', 'propharm'),
								'value'     => array(
									esc_html__('Only arrows','propharm')  => 'arrows',
									esc_html__('Only dottes','propharm')  => 'dottes',
									esc_html__('Both arrows and dottes','propharm')  => 'both'
								),
								'dependency' => Array('element' => 'type', 'value' => 'carousel')
							),
							array(
								'param_name'=>'autoplay',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Autoplay', 'propharm'),
								'value'     => $logic_values,
								'dependency' => Array('element' => 'type', 'value' => 'carousel')
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							)
			    		)
			    	));

			    	vc_map(array(
			    		'name'                    => esc_html__('Client','propharm'),
			    		'description'             => esc_html__('Add client','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_client',
			    		'class'                   => 'et_client',
			    		'icon'                    => 'et_client',
			    		'as_child'                => array('only' => 'et_client_container'),
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Link','propharm'),
								'param_name' => 'link',
								'value'      => '',
							),
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Client image','propharm'),
								'param_name' => 'image',
							)

			    		)
			    	));

			    /* et_person
				----*/

			    	foreach ($social_links_array as $social) {
						vc_add_param('et_person', array(
							'type'       => 'textfield',
							'heading'    => ucfirst($social).' link',
							'group'      => esc_html__('Social','propharm'),
							'param_name' => $social,
							'value'      => '',
						));
					}

			    	vc_map(array(
			    		'name'                    => esc_html__('Person','propharm'),
			    		'description'             => esc_html__('Add person','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_person',
			    		'class'                   => 'et_person',
			    		'icon'                    => 'et_person',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-person.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-person.js',
			    		'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Name','propharm'),
								'param_name' => 'name',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => '',
							),
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Image','propharm'),
								'param_name' => 'image',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),
			    		)
			    	));

				/* et_popup_banner
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Popup banner','propharm'),
			    		'description'             => esc_html__('Insert popup banner (if you want to have the popup in entire site, put the banner inside footer)','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_popup_banner',
			    		'class'                   => 'et_popup_banner',
			    		'icon'                    => 'et_popup_banner',
			    		"as_parent"               => array('except' => 'vc_section'),
						"js_view"                 => 'VcColumnView',
			    		"content_element"         => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-popup-banner.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-popup-banner.js',
			    		'params'                  => array(
			    			array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Hide on mobile', 'propharm'),
								'param_name' => 'visible_mob',
								'value'      => '',
								'description'=> esc_html__('Check this option if you want to hide banner on mobile', 'propharm'),
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Hide on desktop', 'propharm'),
								'param_name' => 'visible_desk',
								'value'      => '',
								'description'=> esc_html__('Check this option if you want to hide banner on desktop', 'propharm'),
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Hide on tablet', 'propharm'),
								'param_name' => 'visible_tablet',
								'value'      => '',
								'description'=> esc_html__('Check this option if you want to hide tablet on mobile', 'propharm'),
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Use cookie', 'propharm'),
								'param_name' => 'cookie',
								'value'      => '',
								'description'=> esc_html__('Toggle this option if you want to display your banner onces per visit session', 'propharm'),
							),
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Delay in ms','propharm'),
								'param_name' => 'delay',
								'value'      => '3000',
							),
							array(
								'param_name'=>'effect',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Effect', 'propharm'),
								'value'     => array(
									esc_html__('Fade in and scale', 'propharm') => 'fade-in-scale',
									esc_html__('Slide in right', 'propharm')  	 => 'slide-in-right',
									esc_html__('Slide in bottom', 'propharm')   => 'slide-in-bottom',
									esc_html__('3d flip horizontal', 'propharm')=> 'flip-horizonatal',
									esc_html__('3d flip vertical', 'propharm')  => 'flip-vertical'
								)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Width in px','propharm'),
								'param_name' => 'width',
								'value'      => '720',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Height in px','propharm'),
								'param_name' => 'height',
								'value'      => '400',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Background color','propharm'),
								'param_name' => 'back_color',
								'value'      => '#ffffff'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Border color','propharm'),
								'param_name' => 'border_color',
								'value'      => ''
							),
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Background image','propharm'),
								'param_name' => 'back_img',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Text align','propharm'),
								'param_name' => 'text_align',
								'value'      => $align_values,
							),

							/* padding
							----*/

								array(
									'type'       => 'padding',
									'group'      => esc_html__('Padding','propharm'),
									'heading'    => esc_html__('Padding','propharm'),
									'param_name' => 'padding',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

				/* et_toggle_banner
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Toggle banner','propharm'),
			    		'description'             => esc_html__('Insert toggle banner (if you want to have the toggle banner in entire site, put the banner inside footer)','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_toggle_banner',
			    		'class'                   => 'et_toggle_banner',
			    		'icon'                    => 'et_popup_banner',
			    		"as_parent"               => array('except' => 'vc_section'),
						"js_view"                 => 'VcColumnView',
			    		"content_element"         => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-toggle-banner.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-toggle-banner.js',
			    		'params'                  => array(
			    			array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Hide on mobile', 'propharm'),
								'param_name' => 'visible_mob',
								'value'      => '',
								'description'=> esc_html__('Check this option if you want to hide banner on mobile', 'propharm'),
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Hide on desktop', 'propharm'),
								'param_name' => 'visible_desk',
								'value'      => '',
								'description'=> esc_html__('Check this option if you want to hide banner on desktop', 'propharm'),
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Hide on tablet', 'propharm'),
								'param_name' => 'visible_tablet',
								'value'      => '',
								'description'=> esc_html__('Check this option if you want to hide tablet on mobile', 'propharm'),
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Use cookie', 'propharm'),
								'param_name' => 'cookie',
								'value'      => '',
								'description'=> esc_html__('Toggle this option if you want to display your banner onces per visit session', 'propharm'),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Background color','propharm'),
								'param_name' => 'back_color',
								'value'      => '#ffffff'
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Text align','propharm'),
								'param_name' => 'text_align',
								'value'      => $align_values,
							),

							/* padding
							----*/

								array(
									'type'       => 'padding',
									'group'      => esc_html__('Padding','propharm'),
									'heading'    => esc_html__('Padding','propharm'),
									'param_name' => 'padding',
									'value'      => ''
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

			    /* et_banner
				----*/

			    	vc_map(array(
						'name'                    => esc_html__('Banner','propharm'),
						'description'             => esc_html__('Insert banner with any content','propharm'),
						'category'                => esc_html__('Enovathemes','propharm'),
						'base'                    => 'et_banner',
						'class'                   => 'et_banner',
						'icon'                    => 'et_banner',
						"as_parent"               => array('except' => 'vc_section'),
	    				'content_element'         => true,
						"js_view"                 => 'VcColumnView',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-banner.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-banner.js',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Link','propharm'),
								'description'=> esc_html__('If set, do not use any button inside the banner','propharm'),
								'param_name' => 'link',
								'value'      => ''
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Overflow hidden?','propharm'),
								'param_name' => 'overflow',
								'value'      => ''
							),

							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Overflow hidden mobile?','propharm'),
								'param_name' => 'overflow_mobile',
								'value'      => ''
							),

							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Overflow hidden tablet portrait?','propharm'),
								'param_name' => 'overflow_tab_port',
								'value'      => ''
							),

							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Overflow hidden tablet landscape?','propharm'),
								'param_name' => 'overflow_tab_land',
								'value'      => ''
							),

							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Highlight?','propharm'),
								'param_name' => 'highlight',
								'value'      => ''
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Gradient under content','propharm'),
								'param_name' => 'gradient',
								'value'      => array(
									esc_html__('None','propharm')  => 'none',
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm')  => 'right',
								)
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Content alignment','propharm'),
								'param_name' => 'align',
								'value'      => array(
									esc_html__('None','propharm')  => 'none',
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm')  => 'right',
									esc_html__('Center','propharm')  => 'center',
								)
							),

							/* background options
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Background color','propharm'),
									'group'      => esc_html__('Background', 'propharm'),
									'param_name' => 'back_color',
									'value'      => ''
								),

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Background image','propharm'),
									'group'      => esc_html__('Background','propharm'),
									'param_name' => 'back_image',
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Background repeat','propharm'),
									'group'      => esc_html__('Background','propharm'),
									'param_name' => 'back_repeat',
									'value'      => array(
										esc_html__('No repeat','propharm')  => 'no-repeat',
										esc_html__('Repeat','propharm')     => 'repeat',
									)
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Background size','propharm'),
									'group'      => esc_html__('Background','propharm'),
									'param_name' => 'back_size',
									'value'      => array(
										esc_html__('Auto','propharm')  => 'auto',
										esc_html__('Cover','propharm') => 'cover',
										esc_html__('Contain','propharm') => 'contain',
									)
								),

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Background position','propharm'),
									'group'      => esc_html__('Background','propharm'),
									'description'=> esc_html__('left top, right top, left bottom, right bottom, left center, right center, value value','propharm'),
									'param_name' => 'back_position',
									'value'      => ''
								),

							/* padding
							----*/

								array(
									'type'       => 'padding',
									'group'      => esc_html__('Padding','propharm'),
									'heading'    => esc_html__('Padding','propharm'),
									'param_name' => 'padding',
									'value'      => '32,32,32,32'
								),

								array(
									'type'       => 'crp',
									'group'      => esc_html__('Padding','propharm'),
									'heading'    => esc_html__( 'Responsive padding', 'propharm' ),
									'param_name' => 'crp',
								),
							
							/* parallax
							----*/

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Banner image','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'image',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Offset X coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'parallax_x',
									'value'      => '0',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Offset Y coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'parallax_y',
									'value'      => '0',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Mobile (Max 374 width) offset X coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'm_parallax_x',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Mobile (Max 374 width) offset Y coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'm_parallax_y',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Mobile (Min 375 Max 767 width) offset X coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'mm_parallax_x',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Mobile (Min 375 Max 767 width) offset Y coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'mm_parallax_y',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Tablet portrait offset X coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'tp_parallax_x',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Tablet portrait offset Y coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'tp_parallax_y',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Tablet landscape offset X coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'tl_parallax_x',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Tablet landscape offset Y coordinate','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'tl_parallax_y',
									'value'      => '',
								),
								array(
									'type'       => 'checkbox',
									'heading'    => esc_html__('Parallax','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'parallax',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Parallax speed radtio','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'description'=> esc_html__('The more the value is the slower the parallax effect is','propharm'),
									'param_name' => 'parallax_speed',
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10' => '10',
										'11' => '11',
										'12' => '12',
										'13' => '13',
										'14' => '14',
										'15' => '15',
										'16' => '16',
										'17' => '17',
										'18' => '18',
										'19' => '19',
										'20' => '20'
									),
									'std' => '10',
									'dependency' => Array(
										'element' => 'parallax', 'value' => 'true'
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Parallax limit','propharm'),
									'group'      => esc_html__('Image','propharm'),
									'param_name' => 'parallax_limit',
									'value'      => '0',
									'dependency' => Array(
										'element' => 'parallax', 'value' => 'true'
									)
								),


							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								)
								
						)
					));

			/* MEDIA
			----*/

				/* et_image
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Image','propharm'),
			    		'description'             => esc_html__('Insert/animate single image','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_image',
			    		'class'                   => 'et_image',
			    		'icon'                    => 'et_image',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-image.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-image.js',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
			    			array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Upload image','propharm'),
								'param_name' => 'image',
							),
			    			array(
								'param_name'=>'size',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Size', 'propharm'),
								'value'     => $image_size_values
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Image link','propharm'),
								'param_name' => 'link',
								'value'      => '',
							),
							array(
								'param_name'=>'link_target',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Link target', 'propharm'),
								'value'     => array(
									'_self'  => '_self',
									'_blank' => '_blank'
								),
							),
							array(
								'param_name'=>'alignment',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Alignment', 'propharm'),
								'value'     => $align_values_extended
							),
							array(
								'param_name'=>'border_radius',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Border radius', 'propharm'),
								'value'     => $logic_values,
							),

							/* parallax
							----*/

								array(
									'type'       => 'checkbox',
									'heading'    => esc_html__('Parallax','propharm'),
									'group'      => esc_html__('Parallax','propharm'),
									'param_name' => 'parallax',
									'value'      => ''
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Offset X coordinate','propharm'),
									'group'      => esc_html__('Parallax','propharm'),
									'param_name' => 'parallax_x',
									'value'      => '0',
									'dependency' => Array(
										'element' => 'parallax', 'value' => 'true'
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Offset Y coordinate','propharm'),
									'group'      => esc_html__('Parallax','propharm'),
									'param_name' => 'parallax_y',
									'value'      => '0',
									'dependency' => Array(
										'element' => 'parallax', 'value' => 'true'
									)
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Parallax speed radtio','propharm'),
									'group'      => esc_html__('Parallax','propharm'),
									'description'=> esc_html__('The more the value is the slower the parallax effect is','propharm'),
									'param_name' => 'parallax_speed',
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10' => '10',
										'11' => '11',
										'12' => '12',
										'13' => '13',
										'14' => '14',
										'15' => '15',
										'16' => '16',
										'17' => '17',
										'18' => '18',
										'19' => '19',
										'20' => '20'
									),
									'std' => '10',
									'dependency' => Array(
										'element' => 'parallax', 'value' => 'true'
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Parallax limit','propharm'),
									'group'      => esc_html__('Parallax','propharm'),
									'param_name' => 'parallax_limit',
									'value'      => '0',
									'dependency' => Array(
										'element' => 'parallax', 'value' => 'true'
									)
								),

							/* animation
							----*/

								array(
									'type'       => 'checkbox',
									'heading'    => esc_html__('Animate','propharm'),
									'group'      => 'Animation',
									'param_name' => 'animate',
								),
								array(
									'type'       => 'dropdown',
									'group'      => esc_html__('Animation','propharm'),
									'heading'    => esc_html__('Animation type','propharm'),
									'param_name' => 'animation_type',
									'value'     => array(
										esc_html__('Fade and Blur', 'propharm')      => 'fade-blur',
										esc_html__('Curtain from left', 'propharm')   => 'curtain-left',
										esc_html__('Curtain from right', 'propharm')  => 'curtain-right',
										esc_html__('Curtain from top', 'propharm')    => 'curtain-top',
										esc_html__('Curtain from bottom', 'propharm') => 'curtain-bottom',
										esc_html__('Appear from left', 'propharm')    => 'left',
										esc_html__('Appear from right', 'propharm')   => 'right',
										esc_html__('Appear from top', 'propharm')     => 'top',
										esc_html__('Appear from bottom', 'propharm')  => 'bottom'
									),
									'dependency' => Array('element' => 'animate', 'value' => 'true')
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Curtain Color','propharm'),
									'group'      => esc_html__('Animation','propharm'),
									'param_name' => 'element_color',
									'value'      => $main_color,
									'dependency' => Array(
										'element' => 'animate', 'value' => 'true',
										'element' => 'animation_type', 'value' => array('curtain-left','curtain-right','curtain-top','curtain-bottom')
									)
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Animation','propharm'),
									'heading'    => esc_html__('Start delay in ms (enter only integer number)','propharm'),
									'param_name' => 'delay',
									'value'      => '0',
									'dependency' => Array('element' => 'animate', 'value' => 'true')
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

				/* et_gallery
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Gallery','propharm'),
			    		'description'             => esc_html__('Insert/animate gallery','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_gallery',
			    		'class'                   => 'et_gallery',
			    		'icon'                    => 'et_gallery',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-gallery.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-gallery.js',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
			    			array(
								'type'       => 'attach_images',
								'heading'    => esc_html__('Upload images','propharm'),
								'param_name' => 'images',
							),
			    			array(
								'param_name'=>'size',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Size', 'propharm'),
								'value'     => $image_size_values
							),
							array(
			    				'type'       => 'dropdown',
								'heading'    => esc_html__('Gallery type', 'propharm'),
								'param_name' => 'type',
								'value'      => array(
									esc_html__('Grid','propharm')     => 'grid',
									esc_html__('Carousel','propharm') => 'carousel',
									esc_html__('Slider','propharm')   => 'slider',
								)
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Link to','propharm'),
								'param_name' => 'link',
								'value'      => array(
									esc_html__('None','propharm')       => 'none',
									esc_html__('Attachment','propharm') => 'attach',
									esc_html__('Lightbox','propharm')   => 'lightbox',
								)
							),
							array(
								'param_name'=>'columns',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Columns', 'propharm'),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
									'6'  => '6',
									'7'  => '7',
									'8'  => '8',
									'9'  => '9',
									'10' => '10'
								),
								'dependency' => Array(
									'element' => 'type', 'value' => array('grid','carousel'),
								)
							),
							array(
								'param_name'=>'navigation_type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Navigation type', 'propharm'),
								'value'     => array(
									esc_html__('Only arrows','propharm')  => 'arrows',
									esc_html__('Only dottes','propharm')  => 'dottes',
									esc_html__('Both arrows and dottes','propharm')  => 'both'
								),
								'dependency' => Array(
									'element' => 'type', 'value' => array('carousel'),
								)
							),
							array(
								'param_name'=>'autoplay',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Autoplay', 'propharm'),
								'value'     => $logic_values,
								'dependency' => Array(
									'element' => 'type', 'value' => array('carousel','carousel-thumbs'),
								)
							),
							array(
								'param_name'=>'border_radius',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Border radius', 'propharm'),
								'value'     => $logic_values,
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								)
			    		)
			    	));

				/* et_video
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Video','propharm'),
			    		'description'             => esc_html__('Insert video (selfhosted, youtube, vimeo)','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_video',
			    		'class'                   => 'et_video',
			    		'icon'                    => 'et_video',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-video.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-video.js',
			    		'params'                  => array(
			    			array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Poster','propharm'),
								'param_name' => 'image',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Modal','propharm'),
								'param_name' => 'modal',
								'dependency' => Array('element' => 'image', 'not_empty' => true)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('MP4 video file url','propharm'),
								'param_name' => 'mp4',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Video embed url','propharm'),
								'param_name' => 'embed',
								'value'      => '',
							)
			    		)
			    	));

			    /* et_audio
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Audio','propharm'),
			    		'description'             => esc_html__('Insert audio','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_audio',
			    		'class'                   => 'et_audio',
			    		'icon'                    => 'et_audio',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('MP3 audio file url','propharm'),
								'param_name' => 'mp3',
								'value'      => '',
							),
			    		)
			    	));

			/* INFOGRAPHICS
			----*/

				/* et_counter
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Counter','propharm'),
			    		'description'             => esc_html__('Insert number counter','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_counter',
			    		'class'                   => 'et_counter',
			    		'icon'                    => 'et_counter',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-counter.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-counter.js',
			    		'params'                  => array(
				    		array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Text align','propharm'),
								'param_name' => 'text_align',
								'value'      => $align_values,
							),
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Number','propharm'),
								'param_name' => 'number',
								'value'      => '',
								'description' => esc_html__('Insert an integer value to count to','propharm'),
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Number postfix','propharm'),
								'param_name' => 'number_postfix',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => '',
							),
							array(
								'param_name'=>'type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Title tag', 'propharm'),
								'value'     => array(
									'H1'  => 'h1',
									'H2'  => 'h2',
									'H3'  => 'h3',
									'H4'  => 'h4',
									'H5'  => 'h5',
									'H6'  => 'h6',
									'p'   => 'p',
									'div' => 'div',
								),
								'std' => 'h4'
							),
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon','propharm'),
								'param_name' => 'icon',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'param_name' => 'icon_color',
								'value'      => '#184363'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Value font-size','propharm'),
								'param_name' => 'value_font_size',
								'value'      => '48'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Value color','propharm'),
								'param_name' => 'value_color',
								'value'      => $main_color
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Title color','propharm'),
								'param_name' => 'title_color',
								'value'      => '#184363'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Start delay in ms (enter only integer number)','propharm'),
								'param_name' => 'delay',
								'value'      => '0',
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

				/* et_progress
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Progress','propharm'),
			    		'description'             => esc_html__('Insert progress bar','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_progress',
			    		'class'                   => 'et_progress',
			    		'icon'                    => 'et_progress',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-progress.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-progress.js',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
			    			array(
								'param_name'=>'version',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Type', 'propharm'),
								'value'     => array(
									esc_html__('Default', 'propharm') => 'default',
									esc_html__('Circle', 'propharm')  => 'circle',
								),
								'std' => 'default'
							),
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Title','propharm'),
								'param_name' => 'title',
								'value'      => '',
							),
							array(
								'param_name'=>'type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Title tag', 'propharm'),
								'value'     => array(
									'H1'  => 'h1',
									'H2'  => 'h2',
									'H3'  => 'h3',
									'H4'  => 'h4',
									'H5'  => 'h5',
									'H6'  => 'h6',
									'p'   => 'p',
									'div' => 'div',
								),
								'std' => 'h4'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Percentage','propharm'),
								'param_name' => 'percentage',
								'value'      => '',
								'description' => esc_html__('Only integer value, without any string','propharm'),
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bar Color','propharm'),
								'param_name' => 'bar_color',
								'value'      => $main_color
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Track Color','propharm'),
								'param_name' => 'track_color',
								'value'      => '#f0f0f0'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Text color','propharm'),
								'param_name' => 'text_color',
								'value'      => '#184363'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Start delay in ms (enter only integer number)','propharm'),
								'param_name' => 'delay',
								'value'      => '0',
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

			    /* et_timer
				----*/

					vc_map(array(
			    		'name'                    => esc_html__('Timer','propharm'),
			    		'description'             => esc_html__('Insert timer','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_timer',
			    		'class'                   => 'et_timer',
			    		'icon'                    => 'et_timer',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-timer.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-timer.js',
			    		'show_settings_on_create' => true,
			    		'params'                  => array(
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('End date to count to','propharm'),
								'param_name' => 'enddate',
								'value'      => '',
								'description' => esc_html__('Use format : June 7, 2025 15:03:25','propharm'),
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('GMT offset (like +4)','propharm'),
								'param_name' => 'gmt',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extend by N hours automatically on expire','propharm'),
								'param_name' => 'number',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Days label','propharm'),
								'param_name' => 'days',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Hours label','propharm'),
								'param_name' => 'hours',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Minutes label','propharm'),
								'param_name' => 'minutes',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Seconds label','propharm'),
								'param_name' => 'seconds',
								'value'      => ''
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Value color','propharm'),
								'param_name' => 'value_color',
								'value'      => '#ffffff'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Back color','propharm'),
								'param_name' => 'back_color',
								'value'      => $main_color
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

			/* OTHER
			----*/

				/* et_bullets
				----*/

			    	vc_map(array(
			    		'name'                    => esc_html__('Bulleted navigation','propharm'),
			    		'description'             => esc_html__('Use only with One page layout active','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_bullets',
			    		'class'                   => 'et_bullets hbe',
			    		'icon'                    => 'et_bullets',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-bullets.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-bullets.js',
			    		'params'                  => array(

			    			array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Menu name','propharm'),
								'param_name' => 'menu',
								'value'      => $menu_list,
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Extra class','propharm'),
								'param_name' => 'extra_class',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('One page navigation offset in px (without any string)','propharm'),
								'description'=> esc_html__('If you have sticky header on the page, you can set the offset','propharm'),
								'param_name' => 'offset',
								'value'      => '0',
							),

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Background color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'back_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Color','propharm'),
									'group'      => 'Styling',
									'param_name' => 'color',
									'value'      => '#ffffff',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Color hover','propharm'),
									'group'      => 'Styling',
									'param_name' => 'color_hover',
									'value'      => $main_color,
								),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

			    /* et_gap
				----*/

			    	vc_map(array(
			    		'name'                    => esc_html__('Gap','propharm'),
			    		'description'             => esc_html__('Insert space','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_gap',
			    		'class'                   => 'et_gap',
			    		'icon'                    => 'et_gap',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-gap.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-gap.js',
			    		'params'                  => array(
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Gap size (without any string)','propharm'),
								'param_name' => 'height',
								'value'      => '32'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Custom class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),
							array(
								'type'       => 'rv',
								'heading'    => esc_html__( 'Responsive visibility', 'propharm' ),
								'group'      => esc_html__('Responsive visibility','propharm'),
								'param_name' => 'rv',
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),

			    		)
			    	));

			    	vc_map(array(
			    		'name'                    => esc_html__('Inline gap','propharm'),
			    		'description'             => esc_html__('Insert horizontal space','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_gap_inline',
			    		'class'                   => 'et_gap_inline',
			    		'icon'                    => 'et_gap_inline',
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-gap-inline.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-gap-inline.js',
			    		'params'                  => array(
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Gap size (without any string)','propharm'),
								'param_name' => 'width',
								'value'      => '32'
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Custom class','propharm'),
								'param_name' => 'extra_class',
								'value'      => ''
							),
							array(
								'type'       => 'rv',
								'heading'    => esc_html__( 'Responsive visibility', 'propharm' ),
								'group'      => esc_html__('Responsive visibility','propharm'),
								'param_name' => 'rv',
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),

			    		)
			    	));

			/* WOOCOMMERCE
			----*/

				if (class_exists('Woocommerce')) {

					if(function_exists('wc_get_attribute_taxonomies')){

						$attributes_tax = wc_get_attribute_taxonomies();
						$attributes = array();
						foreach ( $attributes_tax as $attribute ) {
							$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
						}

					}

					/* et_woo_products
					----*/

				    	vc_map(array(
				    		'name'                    => esc_html__('Woocommerce products','propharm'),
				    		'description'             => esc_html__('Use this element to add different types of products','propharm'),
				    		'category'                => array(esc_html__('Enovathemes','propharm'),esc_html__('WooCommerce','propharm')),
				    		'base'                    => 'et_woo_products',
				    		'class'                   => 'et_woo_products',
				    		'icon'                    => 'et_woo_products',
				    		'show_settings_on_create' => true,
				    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo.js',
				    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo.js',
				    		'params'                  => array(
				    			array(
									'param_name'=>'ajax',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Query after page load', 'propharm'),
									'description'   => esc_html__('Speed up the website by querying products after page load', 'propharm'),
									'value'     => $logic_values,
								),
				    			array(
									'param_name'=>'layout',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Layout', 'propharm'),
									'value'     => array(
										esc_html__('Grid', 'propharm')        => 'grid',
										esc_html__('List', 'propharm')        => 'list',
										esc_html__('Simple grid', 'propharm') => 'simple-grid',
										esc_html__('Full', 'propharm')        => 'full',
									)
								),
								array(
									'param_name'=>'carousel',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Carousel', 'propharm'),
									'value'     => $logic_values,
								),
								array(
									'param_name'=>'navigation_type',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Navigation type', 'propharm'),
									'value'     => array(
										esc_html__('Only arrows','propharm')  => 'arrows',
										esc_html__('Only dottes','propharm')  => 'dottes',
										esc_html__('Both arrows and dottes','propharm')  => 'both',
									),
									'dependency' => Array('element' => 'carousel', 'value' => 'true')
								),
								array(
									'param_name'=>'navigation_position',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Navigation position', 'propharm'),
									'value'     => array(
										esc_html__('Top','propharm')  => 'top',
										esc_html__('Side','propharm')  => 'side',
									),
									'dependency' => Array(
										'element' => 'carousel', 'value' => array('true'),
										'element' => 'navigation_type', 'value' => array('arrows','both'),
									)
								),
								array(
									'param_name'=>'autoplay',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Autoplay', 'propharm'),
									'value'     => $logic_values,
									'dependency' => Array('element' => 'carousel', 'value' => 'true')
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6'
									),
									'param_name'  => 'columns_grid',
									'dependency' => Array('element' => 'layout', 'value' => array('grid','simple-grid'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet portrait', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6'
									),
									'param_name'  => 'columns_grid_tab_port',
									'dependency' => Array('element' => 'layout', 'value' => array('grid','simple-grid'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet landscape', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6'
									),
									'param_name'  => 'columns_grid_tab_land',
									'dependency' => Array('element' => 'layout', 'value' => array('grid','simple-grid'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
									),
									'param_name'  => 'columns_list',
									'dependency' => Array('element' => 'layout', 'value' => array('list'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet portrait', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
									),
									'param_name'  => 'columns_list_tab_port',
									'dependency' => Array('element' => 'layout', 'value' => array('list'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet landscape', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
									),
									'param_name'  => 'columns_list_tab_land',
									'dependency' => Array('element' => 'layout', 'value' => array('list'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
									),
									'param_name'  => 'columns_full',
									'dependency' => Array('element' => 'layout', 'value' => 'full')
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Rows', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
									),
									'param_name'  => 'rows',
									'dependency' => Array(
										'element' => 'layout', 'value' => array('grid','list','simple-grid'),
										'element' => 'carousel', 'value' => 'true'
									)
								),
								array(
									'type' => 'textfield',
									'heading' => esc_html__( 'Product title minimum height', 'propharm' ),
									'save_always' => true,
									'param_name' => "min_height",
									'description' => esc_html__( 'Integer value without any string', 'propharm' ),
								),
								array(
									'type' => 'textfield',
									'heading' => esc_html__( 'Product title maximum height', 'propharm' ),
									'save_always' => true,
									'param_name' => "max_height",
									'description' => esc_html__( 'Integer value without any string', 'propharm' ),
								),
								array(
									'type' => 'textfield',
									'heading' => esc_html__( 'Quantity', 'propharm' ),
									'value' => 12,
									'save_always' => true,
									'param_name' => "quantity",
									'description' => esc_html__( 'The "quantity" shortcode determines how many products to show', 'propharm' ),
								),
								array(
									'type' => 'textfield',
									'heading' => esc_html__( 'Category', 'propharm' ),
									'value' => '',
									'param_name' => 'category',
									'save_always' => true,
									'description' => esc_html__( 'Enter comma separated categories slugs if you want to show certain categories', 'propharm' ),
									'dependency' => Array(
										'element' => 'type', 'value' => array('recent','featured','sale','best_selling','top_rated','attribute'),
									)
								),
								array(
									'type' => 'dropdown',
									'heading' => esc_html__( 'Operator', 'propharm' ),
									'param_name' => 'operator',
									'value' => $operator_values,
									'save_always' => true,
									'description' => esc_html__( 'Select filter operator', 'propharm' ),
									'dependency' => Array('element' => 'category', 'not_empty' => true)
								),
								array(
									'type' => 'dropdown',
									'heading' => esc_html__( 'Order by', 'propharm' ),
									'param_name' => 'orderby',
									'value' => $order_by_values,
									'save_always' => true,
									'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'propharm' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
								),
								array(
									'type' => 'dropdown',
									'heading' => esc_html__( 'Sort order', 'propharm' ),
									'param_name' => 'order',
									'value' => $order_way_values,
									'save_always' => true,
									'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'propharm' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
								),
								array(
									'param_name'=>'type',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Type', 'propharm'),
									'value'     => array(
										esc_html__('Recent', 'propharm')       => 'recent',
										esc_html__('Featured', 'propharm')     => 'featured',
										esc_html__('Sale', 'propharm')         => 'sale',
										esc_html__('Best selling', 'propharm') => 'best_selling',
										esc_html__('Top rated', 'propharm')    => 'top_rated',
										esc_html__('Attribute', 'propharm')    => 'attribute',
										esc_html__('Related', 'propharm')      => 'related',
										esc_html__('Viewed', 'propharm')       => 'viewed',
										esc_html__('Custom', 'propharm')       => 'custom',
									)
								),

								/* attribute
								----*/

									array(
										'type' => 'dropdown',
										'heading' => esc_html__( 'Attribute', 'propharm' ),
										'param_name' => 'attribute',
										'value' => $attributes,
										'save_always' => true,
										'description' => esc_html__( 'List of product taxonomy attributes', 'propharm' ),
										'dependency' => Array(
											'element' => 'type', 'value' => array('attribute'),
										)
									),
									array(
										'type' => 'textfield',
										'heading' => esc_html__( 'Filter', 'propharm' ),
										'value' => '',
										'param_name' => 'filter',
										'save_always' => true,
										'description' => esc_html__( 'Taxonomy values', 'propharm' ),
										'dependency' => Array(
											'element' => 'type', 'value' => array('attribute'),
										)
									),

								/* custom
								----*/

									array(
										'type' => 'textfield',
										'heading' => esc_html__( 'Products', 'propharm' ),
										'value' => '',
										'param_name' => 'ids',
										'save_always' => true,
										'description' => esc_html__( 'Enter comma separated products ids', 'propharm' ),
										'dependency' => Array(
											'element' => 'type', 'value' => array('custom'),
										)
									),

								/* element_css
								----*/

									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Element id','propharm'),
										"class"      => "element-attr-hide",
										'param_name' => 'element_id',
										'value'      => '',
									),

									array(
										'type'       => 'textarea',
										'heading'    => esc_html__('Element css','propharm'),
										"class"      => "element-attr-hide",
										'param_name' => 'element_css',
										'value'      => '',
									),
				    		)
				    	));
					

					/* et_woo_categories
					----*/

						vc_map(array(
				    		'name'                    => esc_html__('Woocommerce categories','propharm'),
				    		'description'             => esc_html__('Use this element to add product categories','propharm'),
				    		'category'                => array(esc_html__('Enovathemes','propharm'),esc_html__('WooCommerce','propharm')),
				    		'base'                    => 'et_woo_categories',
				    		'class'                   => 'et_woo_categories',
				    		'icon'                    => 'et_woo_categories',
				    		'show_settings_on_create' => true,
				    		"as_parent"               => array('only' => 'et_woo_category'),
		    				'content_element'         => true,
		    				'is_container'            => true,
							"js_view"                 => 'VcColumnView',
				    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-category.js',
				    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-category.js',
				    		'params'                  => array(
				    			array(
									'param_name'=>'shadow',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Box shadow', 'propharm'),
									'value'     => array(
										esc_html__('False','propharm')    => 'false',
										esc_html__('True','propharm')    => 'true',
									)
								),
								array(
									'param_name'=>'gap',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Extra gap', 'propharm'),
									'value'     => $logic_values,
								),
				    			array(
									'param_name'=>'layout',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Layout', 'propharm'),
									'value'     => array(
										esc_html__('Grid', 'propharm') => 'grid',
										esc_html__('List', 'propharm') => 'list',
									)
								),
								array(
									'param_name'=>'carousel',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Carousel', 'propharm'),
									'value'     => $logic_values,
								),
								array(
									'param_name'=>'overflow',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Carousel overflow visible?', 'propharm'),
									'value'     => $logic_values,
									'dependency' => Array('element' => 'carousel', 'value' => array('true'))
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns mobile', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_mob',
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet portrait', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_tab_port',
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet landscape', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_tab_land',
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns desktop', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_desktop',
								),
								array(
									'param_name'=>'navigation_type',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Navigation type', 'propharm'),
									'value'     => array(
										esc_html__('Only arrows','propharm')  => 'arrows',
										esc_html__('Only dottes','propharm')  => 'dottes',
										esc_html__('Both arrows and dottes','propharm')  => 'both',
									),
									'dependency' => Array('element' => 'carousel', 'value' => array('true'))
								),
								array(
									'param_name'=>'navigation_position',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Navigation position', 'propharm'),
									'value'     => array(
										esc_html__('Top','propharm')  => 'top',
										esc_html__('Side','propharm')  => 'side',
									),
									'dependency' => Array(
										'element' => 'carousel', 'value' => array('true'),
										'element' => 'navigation_type', 'value' => array('arrows','both'),
									)
								),
								array(
									'param_name'=>'autoplay',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Autoplay', 'propharm'),
									'value'     => $logic_values,
									'dependency' => Array('element' => 'carousel', 'value' => array('true'))
								)
				    		)
				    	));

				    	$categories_raw = get_product_categories_raw();

				    	$category_list = array(esc_html__('Choose','propharm')=>'');

				    	if (!is_wp_error($categories_raw)) {

				    		foreach ($categories_raw as $category => $options) {
				    			$category_list[$options['name']] = $category;
				    		}

				    		vc_add_param('et_woo_category', array(
								'type'       => 'dropdown',
								'heading'    => esc_html__( 'Link to cateogry', 'propharm' ),
								'param_name' => 'category',
								'weight'     => 1,
								'value'      => $category_list,
								'std' => '24'
							));
				    	}

				    	vc_map(array(
							'name'                    => esc_html__('Product category','propharm'),
							'description'             => esc_html__('Insert product category','propharm'),
							'category'                => esc_html__('Enovathemes','propharm'),
							'base'                    => 'et_woo_category',
							'class'                   => 'et_woo_category',
							'icon'                    => 'et_woo_categories',
							'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-single.js',
				    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-single.js',
		    				'as_child'               => array('only' => 'et_woo_categories'),
							'show_settings_on_create' => true,
							'params'                  => array(

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Image or icon','propharm'),
									'param_name' => 'image',
									'value'      => '',
				    				'description'=> esc_html__('Leave blank if you want to show the default','propharm'),

								),
				    			array(
									'param_name'=>'icon_size',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Icon size', 'propharm'),
									'value'     => array(
										esc_html__('Extra small','propharm')    => 'small-x',
										esc_html__('Small','propharm')    => 'small',
										esc_html__('Medium','propharm')   => 'medium',
										esc_html__('Large','propharm')    => 'large',
									),
									'std' => 'large'
								),
								array(
									'param_name'=>'title_tag',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Tag', 'propharm'),
									'value'     => array(
										'H1'  => 'h1',
										'H2'  => 'h2',
										'H3'  => 'h3',
										'H4'  => 'h4',
										'H5'  => 'h5',
										'H6'  => 'h6',
										'p'   => 'p',
										'div' => 'div',
									),
									'std' => 'h6'
								),
								array(
									'param_name'=>'children',
									'type'      => 'dropdown',
									'heading'   => esc_html__('List subcategories?', 'propharm'),
									'value'     => $logic_values,
								),

							)
						));

						vc_map(array(
							'name'                    => esc_html__('Product single category','propharm'),
							'description'             => esc_html__('Use only in title section for product category page','propharm'),
							'category'                => esc_html__('Title section','propharm'),
							'base'                    => 'et_woo_category_single',
							'class'                   => 'et_woo_category_single',
							'icon'                    => 'et_woo_categories',
							'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-single-cat.js',
				    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-single-cat.js',
							'show_settings_on_create' => true,
							'params'                  => array(

								array(
									'type'       => 'attach_image',
									'heading'    => esc_html__('Image or icon','propharm'),
									'param_name' => 'image',
									'value'      => '',
				    				'description'=> esc_html__('Leave blank if you want to show the default','propharm'),

								),
				    			array(
									'param_name'=>'icon_size',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Icon size', 'propharm'),
									'value'     => array(
										esc_html__('Extra small','propharm')    => 'small-x',
										esc_html__('Small','propharm')    => 'small',
										esc_html__('Medium','propharm')   => 'medium',
										esc_html__('Large','propharm')    => 'large',
									),
									'std' => 'large'
								),
								array(
									'param_name'=>'title_tag',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Tag', 'propharm'),
									'value'     => array(
										'H1'  => 'h1',
										'H2'  => 'h2',
										'H3'  => 'h3',
										'H4'  => 'h4',
										'H5'  => 'h5',
										'H6'  => 'h6',
										'p'   => 'p',
										'div' => 'div',
									),
									'std' => 'h6'
								)

							)
						));

					/* et_woo_attributes
					----*/

						$woo_attributes       = wc_get_attribute_taxonomies();
						$woo_attributes_array = array(esc_html__('Choose','propharm')=>'');

						if (!empty($woo_attributes) && !is_wp_error($woo_attributes)) {
			                foreach( $woo_attributes as $attribute) {
			                	$woo_attributes_array[$attribute->attribute_label] = $attribute->attribute_name;
			                }
			            }

						vc_map(array(
							'name'                    => esc_html__('Woocommerce attributes','propharm'),
							'description'             => esc_html__('Use this element to add product attributes','propharm'),
							'category'                => array(esc_html__('Enovathemes','propharm'),esc_html__('WooCommerce','propharm')),
							'base'                    => 'et_woo_attributes',
							'class'                   => 'et_woo_attributes',
							'icon'                    => 'et_woo_attributes',
							'show_settings_on_create' => true,
							'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-attribute.js',
							'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-attribute.js',
							'params'                  => array(
								array(
									'type' => 'dropdown',
									'heading'    => esc_html__( 'Choose attribute', 'propharm' ),
									'value'      => $woo_attributes_array,
									'param_name' => 'attribute',
								),
								array(
									'param_name'=>'title_tag',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Tag', 'propharm'),
									'value'     => array(
										'H1'  => 'h1',
										'H2'  => 'h2',
										'H3'  => 'h3',
										'H4'  => 'h4',
										'H5'  => 'h5',
										'H6'  => 'h6',
										'p'   => 'p',
										'div' => 'div',
									),
									'std' => 'h6'
								),
								array(
									'param_name'=>'carousel',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Carousel', 'propharm'),
									'value'     => $logic_values,
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns mobile', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_mob',
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet portrait', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_tab_port',
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns tablet landscape', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_tab_land',
								),
								array(
									'type' => 'dropdown',
									'heading'     => esc_html__( 'Columns desktop', 'propharm' ),
									'value'     => array(
										'1'  => '1',
										'2'  => '2',
										'3'  => '3',
										'4'  => '4',
										'5'  => '5',
										'6'  => '6',
										'7'  => '7',
										'8'  => '8',
										'9'  => '9',
										'10'  => '10',

									),
									'param_name'  => 'columns_desktop',
								),
								array(
									'param_name'=>'navigation_type',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Navigation type', 'propharm'),
									'value'     => array(
										esc_html__('Only arrows','propharm')  => 'arrows',
										esc_html__('Only dottes','propharm')  => 'dottes',
										esc_html__('Both arrows and dottes','propharm')  => 'both',
									),
									'dependency' => Array('element' => 'carousel', 'value' => array('true'))
								),
								array(
									'param_name'=>'navigation_position',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Navigation position', 'propharm'),
									'value'     => array(
										esc_html__('Top','propharm')  => 'top',
										esc_html__('Side','propharm')  => 'side',
									),
									'dependency' => Array(
										'element' => 'carousel', 'value' => array('true'),
										'element' => 'navigation_type', 'value' => array('arrows','both'),
									)
								),
								array(
									'param_name'=>'autoplay',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Autoplay', 'propharm'),
									'value'     => $logic_values,
									'dependency' => Array('element' => 'carousel', 'value' => array('true'))
								)
							)
						));

					/* et_woo_filter
					----*/

						vc_map(array(
				    		'name'                    => esc_html__('Woocommerce product filter','propharm'),
				    		'description'             => esc_html__('Use this element to filter products','propharm'),
				    		'category'                => array(esc_html__('Enovathemes','propharm'),esc_html__('WooCommerce','propharm')),
				    		'base'                    => 'et_woo_filter',
				    		'class'                   => 'et_woo_filter',
				    		'icon'                    => 'et_woo_categories',
				    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-filter.js',
				    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-woo-filter.js',
				    		'show_settings_on_create' => true,
				    		'params'                  => array(
				    			array(
									'type'       => 'atts',
									'heading'    => esc_html__('Attributes','propharm'),
									'param_name' => 'atts',
									"class"      => "element-attr-hide",
									'value'      => '',
								),
								array(
									'param_name'=>'sku',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Search by SKU?', 'propharm'),
									'group'      => esc_html__('Settings','propharm'),
									'value'     => $logic_values,
								),
								array(
									'param_name'=>'orientation',
									'type'      => 'dropdown',
									'heading'   => esc_html__('Orientation', 'propharm'),
									'group'      => esc_html__('Settings','propharm'),
									'value'     => array(
										esc_html__('Horizontal','propharm')  => 'horizontal',
										esc_html__('Vertical','propharm')  => 'vertical',
									)
								),

								array(
									'type'       => 'textarea_html',
									'group'      => esc_html__('Settings','propharm'),
									'heading'    => esc_html__('Title','propharm'),
									'param_name' => 'content',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Background color','propharm'),
									'param_name' => 'background_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Text color','propharm'),
									'param_name' => 'text_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Select background color','propharm'),
									'param_name' => 'select_background_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Select border color','propharm'),
									'param_name' => 'select_border_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Select text color','propharm'),
									'param_name' => 'select_text_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Button background color','propharm'),
									'param_name' => 'button_background_color',
									'value'      => '',
								),
								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Button text color','propharm'),
									'param_name' => 'button_text_color',
									'value'      => '',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Button background color hover','propharm'),
									'param_name' => 'button_background_color_hover',
									'value'      => '',
								),
								array(
									'type'       => 'colorpicker',
									'group'      => esc_html__('Styling','propharm'),
									'heading'    => esc_html__('Button text color hover','propharm'),
									'param_name' => 'button_text_color_hover',
									'value'      => '',
								),

								array(
									'type'       => 'checkbox',
									'heading'    => esc_html__('Box shadow','propharm'),
									'group'      => esc_html__('Styling','propharm'),
									'param_name' => 'box_shadow',
									'value'      => '',
								),

								/* element_css
								----*/

									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Element id','propharm'),
										"class"      => "element-attr-hide",
										'param_name' => 'element_id',
										'value'      => '',
									),

									array(
										'type'       => 'textarea',
										'heading'    => esc_html__('Element css','propharm'),
										"class"      => "element-attr-hide",
										'param_name' => 'element_css',
										'value'      => '',
									),
								
				    		)
				    	));

				    /* et_wishlist
					----*/

				    	vc_map(array(
				    		'name'                    => esc_html__('Woocommerce wishlist','propharm'),
				    		'description'             => esc_html__('Use this element to add table of products in the wishlist','propharm'),
				    		'category'                => array(esc_html__('Enovathemes','propharm'),esc_html__('WooCommerce','propharm')),
				    		'base'                    => 'wishlist',
				    		'class'                   => 'wishlist',
				    		'icon'                    => 'et_wishlist',
				    	));

				    /* et_compare
					----*/

				    	vc_map(array(
				    		'name'                    => esc_html__('Woocommerce compare','propharm'),
				    		'description'             => esc_html__('Use this element to add table of products in the compare','propharm'),
				    		'category'                => array(esc_html__('Enovathemes','propharm'),esc_html__('WooCommerce','propharm')),
				    		'base'                    => 'compare',
				    		'class'                   => 'compare',
				    		'icon'                    => 'et_compare',
				    	));

				}

			/* POSTS
			----*/

				/* et_posts
				----*/

			    	vc_map(array(
			    		'name'                    => esc_html__('Posts','propharm'),
			    		'description'             => esc_html__('Use this element to add posts','propharm'),
			    		'category'                => esc_html__('Enovathemes','propharm'),
			    		'base'                    => 'et_posts',
			    		'class'                   => 'et_posts',
			    		'icon'                    => 'et_posts',
			    		'show_settings_on_create' => true,
			    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-post.js',
			    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-post.js',
			    		'params'                  => array(
			    			array(
								'param_name'=>'ajax',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Query after page load', 'propharm'),
									'description'   => esc_html__('Speed up the website by querying posts after page load', 'propharm'),
								'value'     => $logic_values,
							),
			    			array(
								'param_name'=>'layout',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Layout', 'propharm'),
								'value'     => array(
									esc_html__('Grid', 'propharm')     => 'grid',
									esc_html__('List', 'propharm')     => 'list',
									esc_html__('Carousel', 'propharm') => 'carousel',
								)
							),
							array(
								'type' => 'dropdown',
								'heading'     => esc_html__( 'Columns', 'propharm' ),
								'value'     => array(
									'1'  => '1',
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
								),
								'param_name'  => 'columns_grid',
								'dependency' => Array('element' => 'layout', 'value' => array('grid','carousel'))
							),
							array(
								'param_name'=>'navigation_type',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Navigation type', 'propharm'),
								'value'     => array(
									esc_html__('Only arrows','propharm')  => 'arrows',
									esc_html__('Only dottes','propharm')  => 'dottes',
									esc_html__('Both arrows and dottes','propharm')  => 'both',
								),
								'dependency' => Array('element' => 'layout', 'value' => array('carousel'))
							),
							array(
								'param_name'=>'autoplay',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Autoplay', 'propharm'),
								'value'     => $logic_values,
								'dependency' => Array('element' => 'layout', 'value' => array('carousel','full'))
							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__( 'Quantity', 'propharm' ),
								'value' => 12,
								'save_always' => true,
								'param_name' => "quantity",
								'description' => esc_html__( 'The "quantity" shortcode determines how many posts to show', 'propharm' ),
							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__( 'Category', 'propharm' ),
								'value' => '',
								'param_name' => 'category',
								'save_always' => true,
								'description' => esc_html__( 'Enter comma separated categories slugs if you want to show certain categories', 'propharm' ),
							),
							array(
								'type' => 'dropdown',
								'heading' => esc_html__( 'Operator', 'propharm' ),
								'param_name' => 'operator',
								'value' => $operator_values,
								'save_always' => true,
								'description' => esc_html__( 'Select filter operator', 'propharm' ),
								'dependency' => Array('element' => 'category', 'not_empty' => true)
							),
							array(
								'type' => 'dropdown',
								'heading' => esc_html__( 'Order by', 'propharm' ),
								'param_name' => 'orderby',
								'value' => $order_by_values,
								'save_always' => true,
								'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'propharm' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
							),
							array(
								'type' => 'dropdown',
								'heading' => esc_html__( 'Sort order', 'propharm' ),
								'param_name' => 'order',
								'value' => $order_way_values,
								'save_always' => true,
								'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'propharm' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__( 'Post excerpt length', 'propharm' ),
								'value' => '104',
								'param_name' => 'excerpt',
								'dependency' => Array('element' => 'layout', 'value' => array('grid','carousel','list'))
							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__( 'Post title length', 'propharm' ),
								'value' => '46',
								'param_name' => 'title_length',
							),

							/* element_css
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Element id','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_id',
									'value'      => '',
								),

								array(
									'type'       => 'textarea',
									'heading'    => esc_html__('Element css','propharm'),
									"class"      => "element-attr-hide",
									'param_name' => 'element_css',
									'value'      => '',
								),
			    		)
			    	));

		/* HEADER BUILDER
		----*/

			$vc_menu_categories = array(
				esc_html__('Desktop header','propharm'),
				esc_html__('Mobile header','propharm'),
				esc_html__('Sidebar header','propharm')
			);

			/* et_header_logo
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Header logo','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_header_logo',
		    		'class'                   => 'et_header_logo hbe',
		    		'icon'                    => 'et_header_logo',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-logo.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-logo.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended,
							'default' => 'left'
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* static header
						----*/

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Normal logo','propharm'),
								'group'      => esc_html__('Default logo','propharm'),
								'param_name' => 'logo',
							),

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Retina logo (twice the width and height of normal logo)','propharm'),
								'group'      => esc_html__('Default logo','propharm'),
								'description'=> esc_html__('Ignore if your logo has SVG format','propharm'),
								'param_name' => 'retina_logo',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Width (without any string)','propharm'),
								'group'      => esc_html__('Default logo','propharm'),
								'param_name' => 'width',
								'value'      => '148',
							),

						/* sticky header
						----*/

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Normal logo','propharm'),
								'group'      => esc_html__('Sticky logo','propharm'),
								'param_name' => 'sticky_logo',
							),

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Retina logo (twice the width and height of normal logo)','propharm'),
								'group'      => esc_html__('Sticky logo','propharm'),
								'description'=> esc_html__('Ignore if your logo has SVG format','propharm'),
								'param_name' => 'sticky_retina_logo',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Width (without any string)','propharm'),
								'group'      => esc_html__('Sticky logo','propharm'),
								'param_name' => 'sticky_width',
								'value'      => '',
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    		)
		    	));

			/* et_header_menu
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Header navigation menu','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_header_menu',
		    		'class'                   => 'et_header_menu hbe font',
		    		'icon'                    => 'et_header_menu',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-menu.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-menu.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Menu name','propharm'),
							'param_name' => 'menu',
							'value'      => $menu_list,
							'default'    => 'choose'
						),
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('One page layout navigation','propharm'),
							'description' => esc_html__('If you want yo use this menu as one page layout navigation, check this option.', 'propharm'),
							'param_name' => 'one_page',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('One page navigation offset in px (without any string)','propharm'),
							'param_name' => 'offset',
							'value'      => '0',
							'dependency' => Array('element' => 'one_page', 'value' => "true")
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* top level
						----*/

							/* styling
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Space between menu items in px (without any string)','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_space',
									'value'      => '40',
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Items separator','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_separator',
									'value'      => $logic_values
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Items separator color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_separator_color',
									'value'      => '#e0e0e0',
									'dependency' => Array('element' => 'menu_separator', 'value' => 'true')
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Items separator height (without any string)','propharm'),
									'description'=> esc_html__('Leave blank if you want 100% height','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_separator_height',
									'value'      => '',
									'dependency' => Array('element' => 'menu_separator', 'value' => 'true')
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu indicator','propharm'),
									'group'      => 'Top level',
									'param_name' => 'submenu_indicator',
									'value'      => $logic_values
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color hover','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color_hover',
									'value'      => $main_color,
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Menu hover effect','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_hover',
									'value'      => array(
										esc_html__('None','propharm')      => 'none',
										esc_html__('Underline','propharm') => 'underline',
										esc_html__('Overline','propharm')  => 'overline',
										esc_html__('Outline','propharm')   => 'outline',
										esc_html__('Box','propharm')       => 'box',
										esc_html__('Fill','propharm')      => 'fill',
									),
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu hover effect color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_effect_color',
									'value'      => '',
									'dependency' => Array('element' => 'menu_hover', 'value' => array('underline','overline','outline','box','fill'))
								),

							/* typography
							----*/

								array(
									'param_name'=>'font_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'font_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font weight', 'propharm'),
									'value'     => $font_weight_values,
									'std'       => '700'
								),
								array(
									'param_name'=>'font_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Font size (without any string)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'font_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Top level','propharm'),
									'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
									'param_name' => 'letter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Text transform','propharm'),
									'group'      => 'Top level',
									'param_name' => 'text_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* submenu
						----*/

							/* styling
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Offset','propharm'),
									'description'=> esc_html__('Leave blank to have 100% offset','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenuoffset',
									'value'      => '',
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color hover','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color_hover',
									'value'      => $main_color,
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu background color','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_back_color',
									'value'      => '#ffffff',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu background color hover','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_back_color_hover',
									'value'      => '',
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu shadow','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_shadow',
									'value'      => $logic_values
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu indicator','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_submenu_indicator',
									'value'      => $logic_values
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu items separator','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_separator',
									'value'      => $logic_values
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu appear effect','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_appear',
									'value'      => array(
										esc_html__('Default','propharm')   => 'none',
										esc_html__('Fade','propharm')      => 'fade',
									),
								),


							/* typography
							----*/

								array(
									'param_name'=>'subfont_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'subfont_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font weight', 'propharm'),
									'value'     => $font_weight_values
								),
								array(
									'param_name'=>'subfont_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu font size (without any string)','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'subfont_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Submenu','propharm'),
									'heading'    => esc_html__('Submenu letter spacing (without any string)','propharm'),
									'param_name' => 'subletter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu text transform','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'subtext_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'subelement_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_megamenu
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Megamenu','propharm'),
		    		'description'             => esc_html__('Use only with megamenu builder','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_megamenu',
		    		'class'                   => 'et_megamenu hbe font',
		    		'icon'                    => 'et_megamenu',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-megamenu.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-megamenu.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Menu name','propharm'),
							'param_name' => 'menu',
							'value'      => $menu_list,
						),
						array(
							'param_name'=>'columns',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Column', 'propharm'),
							'value'     => array(
								'1'  => '1',
								'2'  => '2',
								'3'  => '3',
								'4'  => '4',
								'5'  => '5',
								'6'  => '6'
							)
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* top level
						----*/

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color hover','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color_hover',
									'value'      => $main_color,
								),
								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Megamenu top level item border-bottom color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'megamenu_border_color',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Megamenu top level item margin bottom in px (only integer value)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'top_margin_bottom',
									'value'      => '',
								),

							/* typography
							----*/

								array(
									'param_name'=>'font_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'font_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font weight', 'propharm'),
									'value'     => $font_weight_values,
									'std'       => '700'
								),
								array(
									'param_name'=>'font_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Font size (without any string)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'font_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Top level','propharm'),
									'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
									'param_name' => 'letter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Text transform','propharm'),
									'group'      => 'Top level',
									'param_name' => 'text_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* submenu
						----*/

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color hover','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color_hover',
									'value'      => $main_color,
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu background color hover','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_back_color_hover',
									'value'      => '',
								),

								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu items separator','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_separator',
									'value'      => $logic_values
								),

							/* typography
							----*/

								array(
									'param_name'=>'subfont_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'subfont_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font weight', 'propharm'),
									'value'     => $font_weight_values
								),
								array(
									'param_name'=>'subfont_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu font size (without any string)','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'subfont_size',
									'value'      => '',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Submenu','propharm'),
									'heading'    => esc_html__('Submenu letter spacing (without any string)','propharm'),
									'param_name' => 'subletter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Submenu','propharm'),
									'heading'    => esc_html__('Submenu line height (without any string)','propharm'),
									'param_name' => 'subline_height',
									'value'      => '22'
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu text transform','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'subtext_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => '32,0,16,0'
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'subelement_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    		)
		    	));

			/* et_megamenu_tab
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Megamenu tab','propharm'),
		    		'description'             => esc_html__('Use only with megamenu builder','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_megamenu_tab',
		    		'class'                   => 'et_megamenu_tab hbe font',
		    		'icon'                    => 'et_megamenu_tab',
		    		'as_parent'               => array('only' => 'et_megamenu_tab_item'),
		    		'content_element'         => true,
		    		'show_settings_on_create' => true,
		    		'is_container'            => true,
		    		'js_view'                 => 'VcColumnView',
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-megamenu-tab.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-megamenu-tab.js',
		    		'params'                  => array(
						array(
							'param_name'=>'size',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Tabs size', 'propharm'),
							'value'     => $size_values_box
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
						array(
							'param_name'=>'action',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Toggle on', 'propharm'),
							'value'     => array(
								esc_html__('On click','propharm')  => 'click',
								esc_html__('On hover','propharm')  => 'hover',
							)
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tabset background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'tabset_color',
								'value'      => '#f5f5f5',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab content background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'tab_content_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Menu color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'menu_color',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Menu color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'menu_color_hover',
								'value'      => $main_color,
							),

						/* typography
						----*/

							array(
								'param_name'=>'font_family',
								'type'      => 'dropdown',
								'group'     => esc_html__('Typography','propharm'),
								'heading'   => esc_html__('Font family', 'propharm'),
								'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
								'value'     => $google_fonts_family,
							),
							array(
								'param_name'=>'font_weight',
								'type'      => 'dropdown',
								'group'     => esc_html__('Typography','propharm'),
								'heading'   => esc_html__('Font weight', 'propharm'),
								'value'     => $font_weight_values,
								'std'       => '700'
							),
							array(
								'param_name'=>'font_subsets',
								'type'      => 'dropdown',
								'group'     => esc_html__('Typography','propharm'),
								'heading'   => esc_html__('Font subsets', 'propharm'),
								'value'      => array(
									'latin' => 'latin',
								)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Font size (without any string)','propharm'),
								'group'      => esc_html__('Typography','propharm'),
								'param_name' => 'font_size',
								'value'      => '16',
							),
							array(
								'type'       => 'textfield',
								'group'      => esc_html__('Typography','propharm'),
								'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
								'param_name' => 'letter_spacing',
								'value'      => ''
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Text transform','propharm'),
								'group'      => 'Typography',
								'param_name' => 'text_transform',
								'value'      => array(
									esc_html__('None','propharm')       => 'none',
									esc_html__('Uppercase','propharm')  => 'uppercase',
									esc_html__('Lowercase','propharm')  => 'lowercase',
									esc_html__('Capitalize','propharm') => 'capitalize',
								)
							),

						/* padding
						----*/

							array(
								'type'       => 'padding',
								'group'      => esc_html__('Padding','propharm'),
								'heading'    => esc_html__('Padding','propharm'),
								'param_name' => 'padding',
								'value'      => '32,32,32,32'
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

				vc_map(array(
					'name'                    => esc_html__('Megamenu tab item','propharm'),
					'category'                => $vc_menu_categories[0],
					'base'                    => 'et_megamenu_tab_item',
					'class'                   => 'et_megamenu_tab_item hbe',
					'icon'                    => 'et_megamenu_tab_item',
					'as_child'                => array('only' => 'et_megamenu_tab'),
					"as_parent"               => array('except' => 'vc_section'),
					'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-megamenu-tab-item.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-megamenu-tab-item.js',
					'content_element'         => true,
					"js_view"                 => 'VcColumnView',
					'show_settings_on_create' => true,
					'params'                  => array(
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Active','propharm'),
							'param_name' => 'active',
							'value'      => array(
								'false' => 'false',
								'true'  => 'true'
							)
						),
						
						array(
		    				'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => ''
						),

						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__('Icon','propharm'),
							'param_name' => 'icon',
							'value'      => '',
						),

						/* padding
						----*/

							array(
								'type'       => 'padding',
								'group'      => esc_html__('Padding','propharm'),
								'heading'    => esc_html__('Padding','propharm'),
								'param_name' => 'padding',
								'value'      => '0,0,0,0'
							),

						
						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
					)
				));

			/* et_search_toggle
			----*/

			/* et_product_search
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Product AJAX Search','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_product_search',
		    		'class'                   => 'et_product_search hbe',
		    		'icon'                    => 'et_product_search',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-product-search.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-product-search.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Hide category select','propharm'),
							'param_name' => 'hide_category',
							'value'      => '',
						),

						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Search in SKU','propharm'),
							'param_name' => 'sku',
							'value'      => '',
						),

						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Search in description','propharm'),
							'param_name' => 'description',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_text_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button text color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_text_color_hover',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_color',
								'value'      => $main_color,
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button background color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_color_hover',
								'value'      => $main_color,
							),

						/* searchbox
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Search box width in px (without any string)','propharm'),
								'group'      => 'Styling',
								'param_name' => 'search_width',
								'value'      => '560',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Search box color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'search_color',
								'value'      => '#4D4D4D',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Search box background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'search_background_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Search box border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'search_border_color',
								'value'      => '#e0e0e0',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Search box border color focus','propharm'),
								'group'      => 'Styling',
								'param_name' => 'search_border_color_focus',
								'value'      => $main_color,
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_product_search_toggle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Product search toggle','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_product_search_toggle',
		    		'class'                   => 'et_product_search_toggle hbe',
		    		'icon'                    => 'et_search_toggle',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-product-search-toggle.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-product-search-toggle.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Hide category select','propharm'),
							'param_name' => 'hide_category',
							'value'      => '',
						),

						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Search in SKU','propharm'),
							'param_name' => 'sku',
							'value'      => '',
						),

						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Search in description','propharm'),
							'param_name' => 'description',
							'value'      => '',
						),

						/* icon
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_cart_toggle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Cart toggle','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_cart_toggle',
		    		'class'                   => 'et_cart_toggle hbe',
		    		'icon'                    => 'et_cart_toggle',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-cart-toggle.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-cart-toggle.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bubble text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bubble background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'back_color',
								'value'      => $main_color,
							),
							
						/* cartbox
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box product title color','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_title_color',
								'value'      => '#184363',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box text color','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_color',
								'value'      => '#4D4D4D',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box button color','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_button_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box button color hover','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_button_color_hover',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box button background color','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_button_background_color',
								'value'      => $main_color,
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box button background color hover','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_button_background_color_hover',
								'value'      => '#184363',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart box background color','propharm'),
								'group'      => 'Cart box',
								'param_name' => 'cart_background_color',
								'value'      => '#ffffff',
							),
							array(
								'param_name'=>'box_align',
								'type'      => 'dropdown',
								'group'      => 'Cart box',
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => array(
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm') => 'right',
								)
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_wishlist
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Wishlist','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_wishlist',
		    		'class'                   => 'et_wishlist hbe',
		    		'icon'                    => 'et_header_wishlist',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-wishlist.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-wishlist.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Wishlist page link','propharm'),
							'param_name' => 'link',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bubble text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bubble background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'back_color',
								'value'      => $main_color,
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_compare
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Compare','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_compare',
		    		'class'                   => 'et_compare hbe',
		    		'icon'                    => 'et_header_compare',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-compare.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-compare.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Compare page link','propharm'),
							'param_name' => 'link',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bubble text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Bubble background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'back_color',
								'value'      => $main_color,
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_language_switcher
			----*/

				vc_map(array(
		    		'name'                    => esc_html__('Language switcher','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_language_switcher',
		    		'class'                   => 'et_language_switcher hbe',
		    		'icon'                    => 'et_language_switcher',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-language-switcher.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-language-switcher.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),

						/* submenu
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu color','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_color',
								'value'      => '#184363',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu color hover','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_color_hover',
								'value'      => $main_color,
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu background color','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_background_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu background color hover','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_background_color_hover',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Submenu  width in px (without any string)','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_width',
								'value'      => '200',
							),
							array(
								'param_name'=>'box_align',
								'group'      => 'Submenu',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => array(
									esc_html__('Center','propharm')  => 'center',
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm') => 'right',
								)
							),
							array(
								'param_name'=>'position',
								'group'      => 'Submenu',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Position', 'propharm'),
								'value'     => array(
									esc_html__('Bottom','propharm')  => 'bottom',
									esc_html__('Top','propharm')  => 'top',
								)
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_currency_switcher
			---------------*/

				vc_map(array(
		    		'name'                    => esc_html__('Currency switcher','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_currency_switcher',
		    		'class'                   => 'et_currency_switcher hbe',
		    		'icon'                    => 'et_currency_switcher',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-currency-switcher.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-currency-switcher.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						---------------*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#4D4D4D',
							),

						/* submenu
						---------------*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu color','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_color',
								'value'      => '#184363',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu color hover','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_color_hover',
								'value'      => $main_color,
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu background color','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_background_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Submenu background color hover','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_background_color_hover',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Submenu  width in px (without any string)','propharm'),
								'group'      => 'Submenu',
								'param_name' => 'submenu_width',
								'value'      => '200',
							),
							array(
								'param_name'=>'box_align',
								'group'      => 'Submenu',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => array(
									esc_html__('Center','propharm')  => 'center',
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm') => 'right',
								)
							),
							array(
								'param_name'=>'position',
								'group'      => 'Submenu',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Position', 'propharm'),
								'value'     => array(
									esc_html__('Bottom','propharm')  => 'bottom',
									esc_html__('Top','propharm')  => 'top',
								)
							),

						/* margin
						---------------*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						---------------*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						---------------*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_login_toggle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Front-end login','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[0],$vc_menu_categories[1]),
		    		'base'                    => 'et_login_toggle',
		    		'class'                   => 'et_login_toggle hbe',
		    		'icon'                    => 'et_login_toggle',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-login-toggle.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-login-toggle.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('My account link','propharm'),
							'param_name' => 'my_account_link',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Registration page link','propharm'),
							'param_name' => 'registration_link',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Password recovery page','propharm'),
							'param_name' => 'forgot_link',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),

						/* loginbox
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box text color','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_color',
								'value'      => '#4D4D4D',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box background color','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_background_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box input border color','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_border_color',
								'value'      => '#e0e0e0',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box button color','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box button color hover','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_color_hover',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box button background color','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_background_color',
								'value'      => $main_color,
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box button background color hover','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_background_color_hover',
								'value'      => '#184363',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Login box button border width in px (without any string)','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_border_width',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box button border color','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_border_color',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Login box button border color hover','propharm'),
								'group'      => 'Login box',
								'param_name' => 'login_button_border_color_hover',
								'value'      => '',
							),
							array(
								'param_name'=>'box_align',
								'type'      => 'dropdown',
								'group'     => 'Login box',
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => array(
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm') => 'right',
								)
							),
							array(
								'param_name'=>'box_position',
								'type'      => 'dropdown',
								'group'      => 'Login box',
								'heading'   => esc_html__('Position', 'propharm'),
								'value'     => array(
									esc_html__('Top','propharm')  => 'top',
									esc_html__('Bottom','propharm') => 'bottom',
								)
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_header_slogan
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Slogan','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_header_slogan',
		    		'class'                   => 'et_header_slogan hbe',
		    		'icon'                    => 'et_header_slogan',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-slogan.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-slogan.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Max width (without any string)','propharm'),
							'param_name' => 'max_width',
							'value'      => '',
						),
						array(
							'type'       => 'textarea_html',
							'heading'    => esc_html__('Content','propharm'),
							'param_name' => 'content',
							'value'      => '',
						),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_header_social_links
			----*/

				foreach ($social_links_array as $social) {
					vc_add_param('et_header_social_links', array(
						'type'       => 'textfield',
						'heading'    => ucfirst($social).' link',
						'param_name' => $social,
						'value'      => '',
						'weight' => 1
					));
				}

		    	vc_map(array(
					'name'                    => esc_html__('Social links','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_header_social_links',
		    		'class'                   => 'et_header_social_links hbe',
		    		'icon'                    => 'et_header_social_links',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-social-links.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-social-links.js',
					'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
						array(
							'param_name'=>'target',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Target', 'propharm'),
							'value'     => array(
								'_self'  => '_self',
								'_blank' => '_blank'
							)
						),

						/* styling
						----*/

							array(
								'param_name'=>'styling_original',
								'type'      => 'dropdown',
								'group'     => esc_html__('Styling','propharm'),
								'heading'   => esc_html__('Original styling', 'propharm'),
								'value'     => $logic_values
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_color',
								'value'      => '#184363',
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color hover','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_color_hover',
								'value'      => $main_color,
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_background_color',
								'value'      => '',
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color hover','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_background_color_hover',
								'value'      => '',
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_border_color',
								'value'      => '',
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color hover','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_border_color_hover',
								'value'      => '',
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon border width (without any string)','propharm'),
								'group'     => esc_html__('Styling','propharm'),
								'param_name' => 'icon_border_width',
								'dependency' => Array('element' => 'styling_original', 'value' => 'false')
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
									esc_html__('Custom','propharm')  => 'custom',
								),
								'std' => 'medium'
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon size in px (without any string)','propharm'),
								'param_name' => 'icon_size',
								'value'      => '',
								'dependency' => Array('element' => 'size', 'value' => 'custom')
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon box size in px (without any string)','propharm'),
								'param_name' => 'icon_box_size',
								'value'      => '',
								'dependency' => Array('element' => 'size', 'value' => 'custom')
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
					)
				));

			/* et_header_icon
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Header icon','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_header_icon',
		    		'class'                   => 'et_header_icon hbe',
		    		'icon'                    => 'et_header_icon',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-icon.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-icon.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__('Icon','propharm'),
							'param_name' => 'icon',
							'value'      => '',
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Icon link','propharm'),
							'param_name' => 'icon_link',
							'value'      => '',
						),

						array(
							'param_name'=>'target',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Target', 'propharm'),
							'value'     => array(
								'_self'  => '_self',
								'_blank' => '_blank'
							),
							'dependency' => Array('element' => 'icon_link', 'not_empty' => true)
						),

						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Elastic click','propharm'),
							'param_name' => 'click',
							'value'      => $logic_values
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#4D4D4D',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color_hover',
								'value'      => $main_color,
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color_hover',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color',
								'value'      => '',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon border color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_border_color_hover',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon border width in px (without any string)','propharm'),
								'param_name' => 'icon_border_width',
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
									esc_html__('Custom','propharm')  => 'custom',
								),
								'std' => 'medium'
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon size in px (without any string)','propharm'),
								'param_name' => 'icon_size',
								'value'      => '',
								'dependency' => Array('element' => 'size', 'value' => 'custom')
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Icon box size in px (without any string)','propharm'),
								'param_name' => 'icon_box_size',
								'value'      => '',
								'dependency' => Array('element' => 'size', 'value' => 'custom')
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_header_vertical_separator
			----*/

		    	vc_map(array(
					'name'                    => esc_html__('Vertical separator','propharm'),
					'description'             => esc_html__('Use only with header builder','propharm'),
					'category'                => $vc_menu_categories,
					'base'                    => 'et_header_vertical_separator',
		    		'class'                   => 'et_header_vertical_separator hbe',
		    		'icon'                    => 'et_header_vertical_separator',
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-vertical-separator.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-vertical-separator.js',
					'show_settings_on_create' => true,
					'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Type','propharm'),
							'param_name' => 'type',
							'value'      => array(
								esc_html__('solid','propharm')  => 'solid',
								esc_html__('dotted','propharm') => 'dotted',
								esc_html__('dashed','propharm') => 'dashed',
							)
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Color','propharm'),
							'param_name' => 'color',
							'value'      => '#e0e0e0'
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Width (without any string, if you want 100% leave blank)','propharm'),
							'param_name' => 'width',
							'value'      => ''
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Height (without any string, if you want 1px leave blank)','propharm'),
							'param_name' => 'height',
							'value'      => ''
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => ''
						),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
					)
				));

			/* et_header_button
			----*/

				$megamenu_array = array(esc_html__('-- Select -- ','propharm') => '');

				$megamenu = enovathemes_addons_megamenus();
                if (!is_wp_error($megamenu)) {
                    foreach ($megamenu as $megam => $atts) {
                        $megamenu_array[$atts[1]] = $atts[0];
                    }
                }

				vc_map(array(
	    			'name'                    => esc_html__('Header button','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_header_button',
		    		'class'                   => 'et_header_button hbe',
		    		'icon'                    => 'et_header_button',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-button.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-header-button.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
		    			array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Button text','propharm'),
							'param_name' => 'button_text',
							'value'      => '',
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Button link','propharm'),
							'param_name' => 'button_link',
							'value'      => '',
						),
						array(
							'param_name'=>'megamenu',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Show dropdown megamenu', 'propharm'),
							'value'     => $megamenu_array
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Load megamenu asynchronous?','propharm'),
							'param_name' => 'megamenu_ajax',
							'value'      => $logic_values,
							'dependency' => Array('element' => 'megamenu', 'not_empty' => true)
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Offset','propharm'),
							'description'=> esc_html__('Leave blank to have 100% offset','propharm'),
							'param_name' => 'submenuoffset',
							'value'      => '',
							'dependency' => Array('element' => 'megamenu', 'not_empty' => true)
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Megamenu toggle on','propharm'),
							'param_name' => 'submenu_toggle',
							'value'      => array(
								esc_html__('Hover','propharm') => 'hover',
								esc_html__('Click','propharm') => 'click',
							),
							'dependency' => Array('element' => 'megamenu', 'not_empty' => true)
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Megamenu appear effect','propharm'),
							'param_name' => 'submenu_appear',
							'value'      => array(
								esc_html__('Default','propharm') => 'default',
								esc_html__('Fade','propharm')    => 'fade',
							),
							'dependency' => Array(
								array('element' => 'megamenu', 'not_empty' => true),
								array('element' => 'submenu_toggle', 'value' => 'hover')
							)
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Megamenu shadow','propharm'),
							'param_name' => 'submenu_shadow',
							'value'      => $logic_values,
							'dependency' => Array('element' => 'megamenu', 'not_empty' => true)
						),
						array(
							'param_name'=>'target',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Target', 'propharm'),
							'value'     => array(
								'_self'  => '_self',
								'_blank' => '_blank'
							)
						),
						array(
		    				'type'       => 'checkbox',
							'heading'    => esc_html__('Open link in modal window?', 'propharm'),
							'param_name' => 'button_link_modal',
							'value'      => '',
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

		    			/* typography
						----*/

							array(
								'param_name'=>'font_family',
								'type'      => 'dropdown',
								'group'     => esc_html__('Typography', 'propharm'),
								'heading'   => esc_html__('Font family', 'propharm'),
								'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
								'value'     => $google_fonts_family,
							),
							array(
								'param_name'=>'font_weight',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Font weight', 'propharm'),
								'group'     => esc_html__('Typography', 'propharm'),
								'value'     => $font_weight_values,
								'std'       => '400'
							),
							array(
								'param_name'=>'font_subsets',
								'type'      => 'dropdown',
								'heading'   => esc_html__('Font subsets', 'propharm'),
								'group'     => esc_html__('Typography', 'propharm'),
								'value'     => array(
									'latin' => 'latin',
								)
							),
			    			array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button font size (without any string)','propharm'),
								'group'      => esc_html__('Typography','propharm'),
								'param_name' => 'button_font_size',
								'value'      => '16',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button letter spacing (without any string)','propharm'),
								'group'      => esc_html__('Typography','propharm'),
								'param_name' => 'button_letter_spacing',
								'value'      => ''
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Button line height (without any string)','propharm'),
								'group'      => esc_html__('Typography','propharm'),
								'param_name' => 'button_line_height',
								'value'      => '22'
							),
							array(
								'type'       => 'dropdown',
								'group'   	 => esc_html__('Typography', 'propharm'),
								'heading'    => esc_html__('Text transform','propharm'),
								'param_name' => 'button_text_transform',
								'value'      => array(
									esc_html__('None','propharm')       => 'none',
									esc_html__('Uppercase','propharm')  => 'uppercase',
									esc_html__('Lowercase','propharm')  => 'lowercase',
									esc_html__('Capitalize','propharm') => 'capitalize',
								)
							),

						/* styling
						----*/

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Button size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
								),
								'std' => 'medium',
								'dependency' => Array('element' => 'button_size_custom', 'value' => 'false')
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Button custom size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_size_custom',
								'value'      => $logic_values
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Button width in px (without any string)','propharm'),
								'param_name' => 'width',
								'value'      => '',
								'dependency' => Array('element' => 'button_size_custom', 'value' => 'true')
							),

							array(
								'type'       => 'textfield',
								'group'      => 'Styling',
								'heading'    => esc_html__('Button height in px (without any string)','propharm'),
								'param_name' => 'height',
								'value'      => '',
								'dependency' => Array('element' => 'button_size_custom', 'value' => 'true')
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Button style','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_style',
								'value'      => array(
									esc_html__('Normal','propharm')  => 'normal',
									esc_html__('Outline','propharm') => 'outline',
								)
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Button type','propharm'),
								'group'      => 'Styling',
								'param_name' => 'button_type',
								'value'      => array(
									esc_html__('Default','propharm')  => 'default',
									esc_html__('Round','propharm')  => 'round',
									esc_html__('Rounded','propharm') => 'rounded',
								)
							),
							array(
			    				'type'       => 'checkbox',
								'heading'    => esc_html__('Button shadow', 'propharm'),
								'group'      => esc_html__('Styling','propharm'),
								'param_name' => 'button_shadow',
								'value'      => '',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button color','propharm'),
								'group'      => esc_html__('Styling','propharm'),
								'param_name' => 'button_color',
								'value'      => $main_color
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button background color','propharm'),
								'group'      => esc_html__('Styling','propharm'),
								'param_name' => 'button_back_color',
								'value'      => '#ffffff',
								'dependency' => Array('element' => 'button_style', 'value' => 'normal')
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button border color','propharm'),
								'group'      => esc_html__('Styling','propharm'),
								'param_name' => 'button_border_color',
								'value'      => $main_color,
								'dependency' => Array('element' => 'button_style', 'value' => 'outline')
							),

						/* hover
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button color hover','propharm'),
								'group'      => esc_html__('Hover','propharm'),
								'param_name' => 'button_color_hover',
								'value'      => '#ffffff'
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button background color hover','propharm'),
								'group'      => esc_html__('Hover','propharm'),
								'param_name' => 'button_back_color_hover',
								'value'      => '#184363',
								'dependency' => Array('element' => 'button_style', 'value' => 'normal')
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Button border color hover','propharm'),
								'group'      => esc_html__('Hover','propharm'),
								'param_name' => 'button_border_color_hover',
								'value'      => '#184363',
								'dependency' => Array('element' => 'button_style', 'value' => 'outline')
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Hover animation','propharm'),
								'group'      => esc_html__('Hover','propharm'),
								'param_name' => 'animate_hover',
								'value'      => array(
									esc_html__('Normal','propharm')  	  => 'none',
									esc_html__('Fill effect','propharm')   => 'fill',
									esc_html__('Scale effect','propharm')  => 'scale',
									esc_html__('Move effect','propharm')   => 'move',
								),
								'dependency' => Array('element' => 'button_style', 'value' => 'normal')
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Hover animation','propharm'),
								'group'      => esc_html__('Hover','propharm'),
								'param_name' => 'animate_hover_outline',
								'value'      => array(
									esc_html__('Normal','propharm')  	  => 'none',
									esc_html__('Fill effect','propharm')   => 'fill',
									esc_html__('Scale effect','propharm')  => 'scale',
								),
								'dependency' => Array('element' => 'button_style', 'value' => 'outline')
							),

						/* click
						----*/

							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__('Smooth Click animation','propharm'),
								'group'      => esc_html__('Click','propharm'),
								'param_name' => 'click_smooth',
								'value'      => ''
							),

						/* icon
						----*/

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon 1','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon size (without any string)','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon_font_size',
								'value'      => '16',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon margin (without any string)','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon_margin',
								'value'      => '8',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Icon position','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon_position',
								'value'      => array(
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm')  => 'right',
								)
							),

							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__('Icon 2','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon2',
								'value'      => '',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon 2 size (without any string)','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon2_font_size',
								'value'      => '16',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Icon 2 margin (without any string)','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon2_margin',
								'value'      => '8',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Icon 2 position','propharm'),
								'group'      => esc_html__('Icon','propharm'),
								'param_name' => 'icon2_position',
								'value'      => array(
									esc_html__('Left','propharm')  => 'left',
									esc_html__('Right','propharm')  => 'right',
								)
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
	    		));

			/* et_align_container
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Align container','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => array($vc_menu_categories[1]),
		    		'base'                    => 'et_align_container',
		    		'class'                   => 'et_align_container',
		    		'icon'                    => 'et_align_container',
		    		'show_settings_on_create' => true,
		    		"as_parent"               => array('only' => 'et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_header_logo'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Content align', 'propharm'),
							'description' => esc_html__('Align any inside element', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
		    		)
		    	));

		    /* et_vertical_align_top
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Vertical align top','propharm'),
		    		'description'             => esc_html__('Use only with header builder for sidebar and mobile navigation headers','propharm'),
		    		'category'                => array($vc_menu_categories[1]),
		    		'base'                    => 'et_vertical_align_top',
		    		'class'                   => 'et_vertical_align_top',
		    		'icon'                    => 'et_vertical_align_top',
		    		'show_settings_on_create' => true,
		    		"as_parent"               => array('only' => 'et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_header_logo, et_align_container, et_sidebar_menu, et_mobile_menu,et_language_switcher, et_currency_switcher'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
		    		)
		    	));

		    /* et_vertical_align_middle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Vertical align middle','propharm'),
		    		'description'             => esc_html__('Use only with header builder for sidebar and mobile navigation headers','propharm'),
		    		'category'                => array($vc_menu_categories[1]),
		    		'base'                    => 'et_vertical_align_middle',
		    		'class'                   => 'et_vertical_align_middle',
		    		'icon'                    => 'et_vertical_align_middle',
		    		'show_settings_on_create' => true,
		    		"as_parent"               => array('only' => 'et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_header_logo, et_align_container, et_sidebar_menu, et_mobile_menu,et_language_switcher, et_currency_switcher'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
		    		)
		    	));

		    /* et_vertical_align_bottom
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Vertical align bottom','propharm'),
		    		'description'             => esc_html__('Use only with header builder for sidebar and mobile navigation headers','propharm'),
		    		'category'                => array($vc_menu_categories[1]),
		    		'base'                    => 'et_vertical_align_bottom',
		    		'class'                   => 'et_vertical_align_bottom',
		    		'icon'                    => 'et_vertical_align_bottom',
		    		'show_settings_on_create' => true,
		    		"as_parent"               => array('only' => 'et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_header_logo, et_align_container, et_sidebar_menu, et_mobile_menu,et_language_switcher, et_currency_switcher'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
		    		)
		    	));

			/* et_mobile_container
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Mobile container','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories[1],
		    		'base'                    => 'et_header_mobile_container',
		    		'class'                   => 'et_header_mobile_container',
		    		'icon'                    => 'et_header_mobile_container',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-container.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-container.js',
		    		"as_parent"               => array('only' => 'et_mobile_container_tab, et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_mobile_menu, et_header_logo,et_align_container, et_mobile_close,et_vertical_align_top,et_vertical_align_middle,et_vertical_align_bottom,et_language_switcher, et_currency_switcher'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(

		    			array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Load mobile container asynchronous?','propharm'),
							'param_name' => 'async',
							'value'      => $logic_values
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'background_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Default text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#4D4D4D',
							),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab color','propharm'),
								'group'      => 'Tabs styling',
								'param_name' => 'tab_color',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab color active','propharm'),
								'group'      => 'Tabs styling',
								'param_name' => 'tab_color_active',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab background color','propharm'),
								'group'      => 'Tabs styling',
								'param_name' => 'tab_background_color',
								'value'      => '#f5f5f5',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab background color active','propharm'),
								'group'      => 'Tabs styling',
								'param_name' => 'tab_background_color_active',
								'value'      => $main_color,
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Padding','propharm'),
								'heading'    => esc_html__('Padding','propharm'),
								'param_name' => 'margin',
								'value'      => '48,32,48,32'
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

		    /* et_mobile_container_tab
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Mobile container tab','propharm'),
		    		'description'             => esc_html__('Use only with header builder for sidebar and mobile navigation headers','propharm'),
		    		'category'                => array($vc_menu_categories[1]),
		    		'base'                    => 'et_mobile_container_tab',
		    		'class'                   => 'et_mobile_container_tab',
		    		'icon'                    => 'et_mobile_container_tab',
		    		'show_settings_on_create' => true,
		    		"as_child"                => array('only' => 'et_header_mobile_container'),
		    		"as_parent"               => array('only' => 'et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_header_logo, et_align_container, et_sidebar_menu, et_mobile_menu, et_language_switcher, et_currency_switcher'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(
		    			array(
							'type'       => 'attach_image',
							'heading'    => esc_html__('Icon','propharm'),
							'param_name' => 'icon',
							'value'      => '',
						),
						array(
		    				'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => ''
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),
		    		)
		    	));

		    /* et_mobile_toggle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Mobile container toggle','propharm'),
		    		'description'             => esc_html__('Use only with header builder to toggle the mobile container','propharm'),
		    		'category'                => $vc_menu_categories[1],
		    		'base'                    => 'et_mobile_toggle',
		    		'class'                   => 'et_mobile_toggle hbe',
		    		'icon'                    => 'et_mobile_toggle',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-toggle.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-toggle.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color_hover',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
								),
								'std' => 'medium'
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_mobile_close
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Mobile container close','propharm'),
		    		'description'             => esc_html__('Use only with header builder to close the mobile container','propharm'),
		    		'category'                => $vc_menu_categories[1],
		    		'base'                    => 'et_mobile_close',
		    		'class'                   => 'et_mobile_close hbe',
		    		'icon'                    => 'et_mobile_close',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-close.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-close.js',
		    		'params'                  => array(
						
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color_hover',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color_hover',
								'value'      => $main_color,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
								),
								'std' => 'medium'
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    		)
		    	));

			/* et_mobile_menu
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Mobile menu','propharm'),
		    		'description'             => esc_html__('Use only with mobile container','propharm'),
		    		'category'                => $vc_menu_categories[1],
		    		'base'                    => 'et_mobile_menu',
		    		'class'                   => 'et_mobile_menu font',
		    		'icon'                    => 'et_mobile_menu',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-menu.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-menu.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Menu name','propharm'),
							'param_name' => 'menu',
							'value'      => $menu_list,
							'default'    => 'choose'
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Text align','propharm'),
							'param_name' => 'text_align',
							'value'      => $align_values,
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* top level
						----*/

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color parent','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color_hover',
									'value'      => $main_color,
								),

							/* typography
							----*/

								array(
									'param_name'=>'font_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'font_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font weight', 'propharm'),
									'value'     => $font_weight_values,
									'std'       => '700'
								),
								array(
									'param_name'=>'font_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Font size (without any string)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'font_size',
									'value'      => '32',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Menu items line height (without any string)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'line_height',
									'value'      => '32',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Top level','propharm'),
									'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
									'param_name' => 'letter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Text transform','propharm'),
									'group'      => 'Top level',
									'param_name' => 'text_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* submenu
						----*/

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu hover color','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color_hover',
									'value'      => $main_color,
								),

								array(
									'type'       => 'colorpicker',
									'group'      => 'Submenu',
									'heading'    => esc_html__('Submenu separator color','propharm'),
									'param_name' => 'separator_color',
									'value'      => '#eaeaea',
								),

								array(
									'type'       => 'colorpicker',
									'group'      => 'Submenu',
									'heading'    => esc_html__('Submenu background color','propharm'),
									'param_name' => 'submenu_background_color',
									'value'      => '',
								),

							/* typography
							----*/

								array(
									'param_name'=>'subfont_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'subfont_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font weight', 'propharm'),
									'value'     => $font_weight_values
								),
								array(
									'param_name'=>'subfont_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu font size (without any string)','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'subfont_size',
									'value'      => '24',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu items line height (without any string)','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'subline_height',
									'value'      => '24',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Submenu','propharm'),
									'heading'    => esc_html__('Submenu letter spacing (without any string)','propharm'),
									'param_name' => 'subletter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu text transform','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'subtext_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'subelement_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

			/* et_mobile_tabs
			----*/

				vc_map(array(
		    		'name'                    => esc_html__('Dashboard tabs','propharm'),
		    		'description'             => esc_html__('Insert dashboard tabs','propharm'),
		    		'category'                => $vc_menu_categories,
		    		'base'                    => 'et_mobile_tab',
		    		'class'                   => 'et_mobile_tab',
		    		'icon'                    => 'et_mobile_tab',
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-tab.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-mobile-tab.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('My account page link','propharm'),
							'param_name' => 'account',
							'value'      => '',
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Cart page link','propharm'),
							'param_name' => 'cart',
							'value'      => '',
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Wishlist page link','propharm'),
							'param_name' => 'wishlist',
							'value'      => '',
						),

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Compare page link','propharm'),
							'param_name' => 'compare',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart bubble color','propharm'),
								'param_name' => 'bubble_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Cart bubble background color','propharm'),
								'param_name' => 'bubble_back_color',
								'value'      => $main_color,
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab color','propharm'),
								'param_name' => 'color',
								'value'      => '#bdbdbd',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab color active','propharm'),
								'param_name' => 'color_active',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Tab background color','propharm'),
								'param_name' => 'background_color',
								'value'      => '#ffffff',
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

			/* et_modal_container
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Modal container','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_header_modal_container',
		    		'class'                   => 'et_header_modal_container',
		    		'icon'                    => 'et_header_modal_container',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-container.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-container.js',
		    		"as_parent"               => array('only' => 'vc_row, et_modal_close'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'background_color',
								'value'      => '#ffffff',
							),
							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Default text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#4D4D4D',
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

		    /* et_modal_toggle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Modal container toggle','propharm'),
		    		'description'             => esc_html__('Use only with header builder to toggle the modal container','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_modal_toggle',
		    		'class'                   => 'et_modal_toggle hbe',
		    		'icon'                    => 'et_modal_toggle',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-toggle.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-toggle.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#bdbdbd',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color_hover',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color_hover',
								'value'      => '#184363',
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
								),
								'std' => 'small'
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    			/* visibility
						----*/

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from default header version?','propharm'),
								'param_name' => 'hide_default',
								'value'      => '',
							),

							array(
								'type'       => 'checkbox',
								'group'    => esc_html__('Visibility','propharm'),
								'heading'    => esc_html__('Hide from sticky header version?','propharm'),
								'param_name' => 'hide_sticky',
								'value'      => '',
							),
		    		)
		    	));

			/* et_modal_close
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Modal container close','propharm'),
		    		'description'             => esc_html__('Use only with header builder to close the modal container','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_modal_close',
		    		'class'                   => 'et_modal_close hbe',
		    		'icon'                    => 'et_modal_close',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-close.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-close.js',
		    		'params'                  => array(
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'description' => esc_html__('!If you choose Center, do not forget to set the parent element text-align to center', 'propharm'),
							'value'     => $align_values_extended
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_color_hover',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Icon background color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'icon_background_color_hover',
								'value'      => $main_color,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Size','propharm'),
								'group'      => 'Styling',
								'param_name' => 'size',
								'value'      => array(
									esc_html__('Small','propharm')  => 'small',
									esc_html__('Medium','propharm') => 'medium',
									esc_html__('Large','propharm')  => 'large',
								),
								'std' => 'small'
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),

		    		)
		    	));

			/* et_modal_menu
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Modal menu','propharm'),
		    		'description'             => esc_html__('Use only with modal container','propharm'),
		    		'category'                => $vc_menu_categories[0],
		    		'base'                    => 'et_modal_menu',
		    		'class'                   => 'et_modal_menu font',
		    		'icon'                    => 'et_modal_menu',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-menu.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-modal-menu.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Menu name','propharm'),
							'param_name' => 'menu',
							'value'      => $menu_list,
							'default'    => 'choose'
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* top level
						----*/

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Menu color hover','propharm'),
									'group'      => 'Top level',
									'param_name' => 'menu_color_hover',
									'value'      => $main_color,
								),

							/* typography
							----*/

								array(
									'param_name'=>'font_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'font_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font weight', 'propharm'),
									'value'     => $font_weight_values,
									'std'       => '400'
								),
								array(
									'param_name'=>'font_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Top level','propharm'),
									'heading'   => esc_html__('Font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Font size (without any string)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'font_size',
									'value'      => '72',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Menu items line height (without any string)','propharm'),
									'group'      => esc_html__('Top level','propharm'),
									'param_name' => 'line_height',
									'value'      => '96',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Top level','propharm'),
									'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
									'param_name' => 'letter_spacing',
									'value'      => '-2'
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Text transform','propharm'),
									'group'      => 'Top level',
									'param_name' => 'text_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* submenu
						----*/

							/* styling
							----*/

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color',
									'value'      => '#184363',
								),

								array(
									'type'       => 'colorpicker',
									'heading'    => esc_html__('Submenu color hover','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'submenu_color_hover',
									'value'      => $main_color,
								),

							/* typography
							----*/

								array(
									'param_name'=>'subfont_family',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font family', 'propharm'),
									'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
									'value'     => $google_fonts_family,
								),
								array(
									'param_name'=>'subfont_weight',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font weight', 'propharm'),
									'value'     => $font_weight_values
								),
								array(
									'param_name'=>'subfont_subsets',
									'type'      => 'dropdown',
									'group'     => esc_html__('Submenu','propharm'),
									'heading'   => esc_html__('Submenu font subsets', 'propharm'),
									'value'      => array(
										'latin' => 'latin',
									)
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu font size (without any string)','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'subfont_size',
									'value'      => '16',
								),
								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu items line height (without any string)','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'subline_height',
									'value'      => '32',
								),
								array(
									'type'       => 'textfield',
									'group'      => esc_html__('Submenu','propharm'),
									'heading'    => esc_html__('Submenu letter spacing (without any string)','propharm'),
									'param_name' => 'subletter_spacing',
									'value'      => ''
								),
								array(
									'type'       => 'dropdown',
									'heading'    => esc_html__('Submenu text transform','propharm'),
									'group'      => 'Submenu',
									'param_name' => 'subtext_transform',
									'value'      => array(
										esc_html__('None','propharm')       => 'none',
										esc_html__('Uppercase','propharm')  => 'uppercase',
										esc_html__('Lowercase','propharm')  => 'lowercase',
										esc_html__('Capitalize','propharm') => 'capitalize',
									)
								),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Margin','propharm'),
								'heading'    => esc_html__('Margin','propharm'),
								'param_name' => 'margin',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'subelement_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

			/* et_sidebar_container
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Sidebar container','propharm'),
		    		'description'             => esc_html__('Use only with header builder','propharm'),
		    		'category'                => $vc_menu_categories[2],
		    		'base'                    => 'et_header_sidebar_container',
		    		'class'                   => 'et_header_sidebar_container',
		    		'icon'                    => 'et_header_sidebar_container',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-sidebar-container.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-sidebar-container.js',
		    		"as_parent"               => array('only' => 'et_sidebar_toggle, et_gap, et_separator, et_header_button, et_header_icon, et_header_social_links, et_header_slogan, et_search_form, et_sidebar_menu, et_header_logo,et_align_container,et_vertical_align_top,et_vertical_align_middle,et_vertical_align_bottom'),
					"js_view"                 => 'VcColumnView',
		    		"content_element"         => true,
		    		'params'                  => array(

						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Background color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'background_color',
								'value'      => '#ffffff',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Default text color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'text_color',
								'value'      => '#4D4D4D',
							),

						/* margin
						----*/

							array(
								'type'       => 'margin',
								'group'      => esc_html__('Padding','propharm'),
								'heading'    => esc_html__('Padding','propharm'),
								'param_name' => 'margin',
								'value'      => '48,32,48,32'
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

		    /* et_sidebar_menu
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Sidebar navigation menu','propharm'),
		    		'description'             => esc_html__('Use only with sidebar builder','propharm'),
		    		'category'                => $vc_menu_categories[2],
		    		'base'                    => 'et_sidebar_menu',
		    		'class'                   => 'et_sidebar_menu hbe font',
		    		'icon'                    => 'et_sidebar_menu',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-sidebar-menu.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-sidebar-menu.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Menu name','propharm'),
							'param_name' => 'menu',
							'value'      => $menu_list,
							'default'    => 'choose'
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						/* styling
						----*/

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Menu color','propharm'),
								'group'      => 'Styling',
								'param_name' => 'menu_color',
								'value'      => '#184363',
							),

							array(
								'type'       => 'colorpicker',
								'heading'    => esc_html__('Menu color hover','propharm'),
								'group'      => 'Styling',
								'param_name' => 'menu_color_hover',
								'value'      => $main_color,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Submenu indicator','propharm'),
								'group'      => 'Styling',
								'param_name' => 'submenu_indicator',
								'value'      => $logic_values
							),

						/* typography
						----*/

							array(
								'param_name'=>'font_family',
								'type'      => 'dropdown',
								'group'     => esc_html__('Top level','propharm'),
								'heading'   => esc_html__('Font family', 'propharm'),
								'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
								'value'     => $google_fonts_family,
							),
							array(
								'param_name'=>'font_weight',
								'type'      => 'dropdown',
								'group'     => esc_html__('Top level','propharm'),
								'heading'   => esc_html__('Font weight', 'propharm'),
								'value'     => $font_weight_values,
								'std'       => 'uppercase'
							),
							array(
								'param_name'=>'font_subsets',
								'type'      => 'dropdown',
								'group'     => esc_html__('Top level','propharm'),
								'heading'   => esc_html__('Font subsets', 'propharm'),
								'value'      => array(
									'latin' => 'latin',
								)
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Font size (without any string)','propharm'),
								'group'      => esc_html__('Top level','propharm'),
								'param_name' => 'font_size',
								'value'      => '32',
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Line height (without any string)','propharm'),
								'group'      => esc_html__('Top level','propharm'),
								'param_name' => 'line_height',
								'value'      => '32',
							),
							array(
								'type'       => 'textfield',
								'group'      => esc_html__('Top level','propharm'),
								'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
								'param_name' => 'letter_spacing',
								'value'      => ''
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Text transform','propharm'),
								'group'      => 'Top level',
								'param_name' => 'text_transform',
								'value'      => array(
									esc_html__('None','propharm')       => 'none',
									esc_html__('Uppercase','propharm')  => 'uppercase',
									esc_html__('Lowercase','propharm')  => 'lowercase',
									esc_html__('Capitalize','propharm') => 'capitalize',
								)
							),

							/* submenu
							----*/

								array(
									'type'       => 'textfield',
									'heading'    => esc_html__('Submenu offset','propharm'),
									'group'      => esc_html__('Submenu','propharm'),
									'param_name' => 'suboffset',
									'value'      => '',
								),

								/* typography
								----*/

									array(
										'param_name'=>'subfont_family',
										'type'      => 'dropdown',
										'group'     => esc_html__('Submenu','propharm'),
										'heading'   => esc_html__('Submenu font family', 'propharm'),
										'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
										'value'     => $google_fonts_family,
									),
									array(
										'param_name'=>'subfont_weight',
										'type'      => 'dropdown',
										'group'     => esc_html__('Submenu','propharm'),
										'heading'   => esc_html__('Submenu font weight', 'propharm'),
										'value'     => $font_weight_values
									),
									array(
										'param_name'=>'subfont_subsets',
										'type'      => 'dropdown',
										'group'     => esc_html__('Submenu','propharm'),
										'heading'   => esc_html__('Submenu font subsets', 'propharm'),
										'value'      => array(
											'latin' => 'latin',
										)
									),
									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Submenu font size (without any string)','propharm'),
										'group'      => esc_html__('Submenu','propharm'),
										'param_name' => 'subfont_size',
										'value'      => '16',
									),
									array(
										'type'       => 'textfield',
										'heading'    => esc_html__('Submenu line height (without any string)','propharm'),
										'group'      => esc_html__('Submenu','propharm'),
										'param_name' => 'subline_height',
										'value'      => '20',
									),
									array(
										'type'       => 'textfield',
										'group'      => esc_html__('Submenu','propharm'),
										'heading'    => esc_html__('Submenu letter spacing (without any string)','propharm'),
										'param_name' => 'subletter_spacing',
										'value'      => ''
									),
									array(
										'type'       => 'dropdown',
										'heading'    => esc_html__('Submenu text transform','propharm'),
										'group'      => 'Submenu',
										'param_name' => 'subtext_transform',
										'value'      => array(
											esc_html__('None','propharm')       => 'none',
											esc_html__('Uppercase','propharm')  => 'uppercase',
											esc_html__('Lowercase','propharm')  => 'lowercase',
											esc_html__('Capitalize','propharm') => 'capitalize',
										)
									),

						/* padding
						----*/

							array(
								'type'       => 'padding',
								'group'      => esc_html__('Padding','propharm'),
								'heading'    => esc_html__('Padding','propharm'),
								'param_name' => 'padding',
								'value'      => ''
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

		/* TITLE SECTION
		----*/

			/* et_title_section_title
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Page title','propharm'),
		    		'description'             => esc_html__('Use only with title section','propharm'),
		    		'category'                => esc_html__('Title section','propharm'),
		    		'base'                    => 'et_title_section_title',
		    		'class'                   => 'et_title_section_title font',
		    		'icon'                    => 'et_title_section_title',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-title-section-title.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-title-section-title.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'textfield',
							"class"      => "element-attr-hide",
							'heading'    => esc_html__('Etp title','propharm'),
							'param_name' => 'etp_title',
							'value'      => '',
						),
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'value'     => $align_values
						),
						array(
							'param_name'=>'text_align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Text align', 'propharm'),
							'value'     => $align_values
						),
						array(
							'param_name'=>'type',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Tag', 'propharm'),
							'value'     => array(
								'H1'  => 'h1',
								'H2'  => 'h2',
								'H3'  => 'h3',
								'H4'  => 'h4',
								'H5'  => 'h5',
								'H6'  => 'h6',
								'p'   => 'p',
								'div' => 'div',
							),
							'std' => 'h1'
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Text color','propharm'),
							'param_name' => 'text_color',
							'value'      => '#184363',
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Background color','propharm'),
							'param_name' => 'background_color',
							'value'      => '',
						),

						array(
							'param_name'=>'font_family',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font family', 'propharm'),
							'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
							'value'     => $google_fonts_family,
						),
						array(
							'param_name'=>'font_weight',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font weight', 'propharm'),
							'value'     => $font_weight_values,
						),
						array(
							'param_name'=>'font_subsets',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font subsets', 'propharm'),
							'value'      => array(
								'latin' => 'latin',
							)
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Font size (without any string)','propharm'),
							'param_name' => 'font_size',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
							'param_name' => 'letter_spacing',
							'value'      => ''
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Line height (without any string)','propharm'),
							'param_name' => 'line_height',
							'value'      => ''
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Text transform','propharm'),
							'param_name' => 'text_transform',
							'value'      => array(
								esc_html__('None','propharm')       => 'none',
								esc_html__('Uppercase','propharm')  => 'uppercase',
								esc_html__('Lowercase','propharm')  => 'lowercase',
								esc_html__('Capitalize','propharm') => 'capitalize',
							)
						),

						/* tablet
						----*/

							array(
								'param_name'=>'tablet_align',
								'type'      => 'dropdown',
								'group'      => esc_html__('Tablet','propharm'),
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => $align_values
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet landscape font size (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tlf',
								'value'      => $typography_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet landscape line height (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tll',
								'value'      => $typography_values,
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet portrait font size (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tpf',
								'value'      => $typography_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet portrait line height (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tpl',
								'value'      => $typography_values,
							),

						/* mobile
						----*/

							array(
								'param_name'=>'mobile_align',
								'type'      => 'dropdown',
								'group'      => esc_html__('Mobile','propharm'),
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => $align_values
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Font size (without any string)','propharm'),
								'group'      => esc_html__('Mobile','propharm'),
								'param_name' => 'mf',
								'value'      => $typography_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Line height (without any string)','propharm'),
								'group'      => esc_html__('Mobile','propharm'),
								'param_name' => 'ml',
								'value'      => $typography_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Font size (max-width 374px)','propharm'),
								'group'      => esc_html__('Mobile','propharm'),
								'param_name' => 'mfs',
								'value'      => $typography_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Line height (max-width 374px)','propharm'),
								'group'      => esc_html__('Mobile','propharm'),
								'param_name' => 'mls',
								'value'      => $typography_values,
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

			/* et_title_section_subtitle
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Page subtitle','propharm'),
		    		'description'             => esc_html__('Use only with title section','propharm'),
		    		'category'                => esc_html__('Title section','propharm'),
		    		'base'                    => 'et_title_section_subtitle',
		    		'class'                   => 'et_title_section_subtitle font',
		    		'icon'                    => 'et_title_section_subtitle',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-title-section-subtitle.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-title-section-subtitle.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'textfield',
							"class"      => "element-attr-hide",
							'heading'    => esc_html__('Etp title','propharm'),
							'param_name' => 'etp_subtitle',
							'value'      => '',
						),
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'value'     => $align_values
						),
						array(
							'param_name'=>'text_align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Text align', 'propharm'),
							'value'     => $align_values
						),
						array(
							'param_name'=>'type',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Tag', 'propharm'),
							'value'     => array(
								'H1'  => 'h1',
								'H2'  => 'h2',
								'H3'  => 'h3',
								'H4'  => 'h4',
								'H5'  => 'h5',
								'H6'  => 'h6',
								'p'   => 'p',
								'div' => 'div',
							),
							'std' => 'p'
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Text color','propharm'),
							'param_name' => 'text_color',
							'value'      => '#184363',
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Background color','propharm'),
							'param_name' => 'background_color',
							'value'      => '',
						),

						array(
							'param_name'=>'font_family',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font family', 'propharm'),
							'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
							'value'     => $google_fonts_family,
						),
						array(
							'param_name'=>'font_weight',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font weight', 'propharm'),
							'value'     => $font_weight_values
						),
						array(
							'param_name'=>'font_subsets',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font subsets', 'propharm'),
							'value'      => array(
								'latin' => 'latin',
							)
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Font size (without any string)','propharm'),
							'param_name' => 'font_size',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
							'param_name' => 'letter_spacing',
							'value'      => ''
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Line height (without any string)','propharm'),
							'param_name' => 'line_height',
							'value'      => ''
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Text transform','propharm'),
							'param_name' => 'text_transform',
							'value'      => array(
								esc_html__('None','propharm')       => 'none',
								esc_html__('Uppercase','propharm')  => 'uppercase',
								esc_html__('Lowercase','propharm')  => 'lowercase',
								esc_html__('Capitalize','propharm') => 'capitalize',
							)
						),

						/* tablet
						----*/

							array(
								'param_name'=>'tablet_align',
								'type'      => 'dropdown',
								'group'      => esc_html__('Tablet','propharm'),
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => $align_values
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet landscape font size (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tlf',
								'value'      => $font_size_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet landscape line height (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tll',
								'value'      => $line_height_values,
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet portrait font size (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tpf',
								'value'      => $font_size_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Tablet portrait line height (without any string)','propharm'),
								'group'      => esc_html__('Tablet','propharm'),
								'param_name' => 'tpl',
								'value'      => $line_height_values,
							),

						/* mobile
						----*/

							array(
								'param_name'=>'mobile_align',
								'type'      => 'dropdown',
								'group'      => esc_html__('Mobile','propharm'),
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => $align_values
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Font size (without any string)','propharm'),
								'group'      => esc_html__('Mobile','propharm'),
								'param_name' => 'mf',
								'value'      => $font_size_values,
							),

							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__('Line height (without any string)','propharm'),
								'group'      => esc_html__('Mobile','propharm'),
								'param_name' => 'ml',
								'value'      => $line_height_values,
							),

						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

			/* et_breadcrumbs
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Breadcrumbs','propharm'),
		    		'description'             => esc_html__('Use only with title section','propharm'),
		    		'category'                => esc_html__('Title section','propharm'),
		    		'base'                    => 'et_breadcrumbs',
		    		'class'                   => 'et_breadcrumbs font',
		    		'icon'                    => 'et_breadcrumbs',
		    		'show_settings_on_create' => true,
		    		'admin_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-breadcrumbs.js',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/et-breadcrumbs.js',
		    		'params'                  => array(
		    			array(
							'type'       => 'textfield',
							"class"      => "element-attr-hide",
							'heading'    => esc_html__('Etp breadcrumbs','propharm'),
							'param_name' => 'etp_breadcrumbs',
							'value'      => '',
						),
						array(
							'param_name'=>'align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Align', 'propharm'),
							'value'     => $align_values
						),
						array(
							'param_name'=>'text_align',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Text align', 'propharm'),
							'value'     => $align_values
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Extra class','propharm'),
							'param_name' => 'extra_class',
							'value'      => '',
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Text color','propharm'),
							'param_name' => 'text_color',
							'value'      => '#9a9a9a',
						),

						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__('Text color hover','propharm'),
							'param_name' => 'text_color_hover',
							'value'      => '#184363',
						),

						array(
							'param_name'=>'font_family',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font family', 'propharm'),
							'description' => esc_html__('800+ google fonts included. For preview click', 'propharm').' <a href="//fonts.google.com/" target="_blank">'.esc_html__('here', 'propharm').'</a>',
							'value'     => $google_fonts_family,
						),
						array(
							'param_name'=>'font_weight',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font weight', 'propharm'),
							'value'     => $font_weight_values
						),
						array(
							'param_name'=>'font_subsets',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Font subsets', 'propharm'),
							'value'      => array(
								'latin' => 'latin',
							)
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Font size (without any string)','propharm'),
							'param_name' => 'font_size',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Letter spacing (without any string)','propharm'),
							'param_name' => 'letter_spacing',
							'value'      => ''
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Line height (without any string)','propharm'),
							'param_name' => 'line_height',
							'value'      => ''
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Text transform','propharm'),
							'param_name' => 'text_transform',
							'value'      => array(
								esc_html__('None','propharm')       => 'none',
								esc_html__('Uppercase','propharm')  => 'uppercase',
								esc_html__('Lowercase','propharm')  => 'lowercase',
								esc_html__('Capitalize','propharm') => 'capitalize',
							)
						),

						/* tablet
						----*/

							array(
								'param_name'=>'tablet_align',
								'type'      => 'dropdown',
								'group'      => esc_html__('Tablet','propharm'),
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => $align_values
							),


						/* mobile
						----*/

							array(
								'param_name'=>'mobile_align',
								'type'      => 'dropdown',
								'group'      => esc_html__('Mobile','propharm'),
								'heading'   => esc_html__('Align', 'propharm'),
								'value'     => $align_values
							),


						/* element_css
						----*/

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element id','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_id',
								'value'      => '',
							),

							array(
								'type'       => 'textfield',
								'heading'    => esc_html__('Element font','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_font',
								'value'      => '',
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__('Element css','propharm'),
								"class"      => "element-attr-hide",
								'param_name' => 'element_css',
								'value'      => '',
							),
		    		)
		    	));

		/* WIDGETS
		----*/

			/* widget_contact_form
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Fast contact form','propharm'),
		    		'description'             => esc_html__('Use to add AJAX contact form','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_contact_form',
		    		'class'                   => 'widget_contact_form',
		    		'icon'                    => 'widget_contact_form',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Submit button text','propharm'),
							'param_name' => 'submit_text',
							'value'      => 'Send',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Recipient','propharm'),
							'param_name' => 'recipient',
							'value'      => get_option('admin_email'),
						),
		    		)
		    	));

		    /* widget_facebook
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Facebook like box','propharm'),
		    		'description'             => esc_html__('Add facebook likebox','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_facebook',
		    		'class'                   => 'widget_facebook',
		    		'icon'                    => 'widget_facebook',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('App ID from the app dashboard','propharm'),
							'param_name' => 'app_id',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('The URL of the Facebook Page','propharm'),
							'param_name' => 'href',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Replace en_US with your locale, e.g., ru_RU for Russian (Russia)','propharm'),
							'description' => esc_html__('You can change the language of the Page plugin by loading a localized version of the Facebook JavaScript SDK.','propharm'),
							'param_name' => 'language_code',
							'value'      => 'en_US',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('The pixel width of the plugin. Min. is 180 & Max. is 500','propharm'),
							'param_name' => 'width',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('The pixel height of the plugin. Min. is 70','propharm'),
							'param_name' => 'height',
							'value'      => '',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show timeline','propharm'),
							'param_name' => 'timeline',
							'value'      => 'true',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show events','propharm'),
							'param_name' => 'events',
							'value'      => 'true',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show messages','propharm'),
							'param_name' => 'messages',
							'value'      => 'true',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Hide cover photo in the header','propharm'),
							'param_name' => 'hide_cover',
							'value'      => 'false',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show profile photos when friends like this','propharm'),
							'param_name' => 'show_facepile',
							'value'      => 'false',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Use the small header instead','propharm'),
							'param_name' => 'small_header',
							'value'      => 'false',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Try to fit inside the container width','propharm'),
							'param_name' => 'adapt_container_width',
							'value'      => 'true',
						),

		    		)
		    	));

		    /* widget_flickr
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Flickr images','propharm'),
		    		'description'             => esc_html__('Use to add images from flickr','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_flickr',
		    		'class'                   => 'widget_flickr',
		    		'icon'                    => 'widget_flickr',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-flickr.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Number of photos to show','propharm'),
							'param_name' => 'photos_number',
							'value'      => '6',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Flickr id','propharm'),
							'description'=> esc_html__('For more infomration go:','propharm').' '.'<a target="_blank" href="http://idgettr.com/">'.esc_html__( 'here', 'propharm' ).'</a>',
							'param_name' => 'flickr_id',
							'value'      => '',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Image size','propharm'),
							'param_name' => 'image_size',
							'value'      => array(
								esc_html__('Small','propharm')      => 'square',
								esc_html__('Thumbnails','propharm') => 'thumb',
								esc_html__('Medium','propharm')     => 'mid',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Display','propharm'),
							'param_name' => 'image_size',
							'value'      => array(
								esc_html__('Latest','propharm') => 'latest',
								esc_html__('Random','propharm') => 'random',
							),
						),
						array(
							'param_name'=>'columns_mob',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Columns mobile', 'propharm'),
							'value'     => array(
								'1'  => '1',
								'2'  => '2',
								'3'  => '3',
								'4'  => '4',
								'5'  => '5',
								'6'  => '6',
								'7'  => '7',
								'8'  => '8',
								'9'  => '9',
								'10'  => '10'
							)
						),
						array(
							'param_name'=>'columns_tablet',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Columns tablet', 'propharm'),
							'value'     => array(
								'1'  => '1',
								'2'  => '2',
								'3'  => '3',
								'4'  => '4',
								'5'  => '5',
								'6'  => '6',
								'7'  => '7',
								'8'  => '8',
								'9'  => '9',
								'10'  => '10'
							)
						),
						array(
							'param_name'=>'columns_desk',
							'type'      => 'dropdown',
							'heading'   => esc_html__('Columns desktop', 'propharm'),
							'value'     => array(
								'1'  => '1',
								'2'  => '2',
								'3'  => '3',
								'4'  => '4',
								'5'  => '5',
								'6'  => '6',
								'7'  => '7',
								'8'  => '8',
								'9'  => '9',
								'10'  => '10'
							)
						),
		    		)

		    	));

		    /* widget_mailchimp
			----*/

 				$list_array = enovathemes_addons_mailchimp_list();

 				$list_values = array(''=>esc_html__('Choose','propharm'));

 				if ( !is_wp_error( $list_array ) && is_array($list_array)){

 					foreach ( $list_array as $list){
 						$list_values[$list['id']] = $list['name'];
 					}
 				}

				$list_values = array_flip($list_values);

				if (empty($list_values)) {
					array_push($list_values, esc_html__('Mailchimp did not return any list','propharm'));
				}

		    	vc_map(array(
		    		'name'                    => esc_html__('Mailchimp','propharm'),
		    		'description'             => esc_html__('Use to add AJAX mailchimp subscribe','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_mailchimp',
		    		'class'                   => 'widget_mailchimp',
		    		'icon'                    => 'widget_mailchimp',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__('Description','propharm'),
							'param_name' => 'description',
							'value'      => '',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('List','propharm'),
							'description'=> esc_html__('Make sure you have the Mailchimp API key and at least one list in your Mailchimp dashboard. Go to theme options >> general >> Mailchimp API key','propharm'),
							'param_name' => 'list',
							'value'      => $list_values,
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show First Name field','propharm'),
							'param_name' => 'first_name',
							'value'      => 'false',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Required?','propharm'),
							'param_name' => 'required_first_name',
							'value'      => 'false',
							'dependency' => Array('element' => 'first_name', 'value' => 'true')
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show Last Name field','propharm'),
							'param_name' => 'last_name',
							'value'      => 'false',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Required?','propharm'),
							'param_name' => 'required_last_name',
							'value'      => 'false',
							'dependency' => Array('element' => 'last_name', 'value' => 'true')
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show phone field','propharm'),
							'param_name' => 'phone',
							'value'      => 'false',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Required?','propharm'),
							'param_name' => 'required_phone',
							'value'      => 'false',
							'dependency' => Array('element' => 'phone', 'value' => 'true')
						),
		    		)
		    	));

			/* widget_posts
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Recent posts','propharm'),
		    		'description'             => esc_html__('Use to add recent posts with image','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_posts',
		    		'class'                   => 'widget_posts',
		    		'icon'                    => 'widget_posts',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-posts.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Number of posts','propharm'),
							'param_name' => 'number',
							'value'      => '',
						)
		    		)
		    	));

		    /* widget_login
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Login form','propharm'),
		    		'description'             => esc_html__('Use to add front-end login form','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_login',
		    		'class'                   => 'widget_login',
		    		'icon'                    => 'widget_login',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Registration page link','propharm'),
							'param_name' => 'registration_link',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Password recovery page','propharm'),
							'param_name' => 'forgot_link',
							'value'      => '',
						)
		    		)
		    	));

			/* widget_product_categories
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Product categories','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_product_categories',
		    		'class'                   => 'widget_product_categories',
		    		'icon'                    => 'widget_product_categories',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-product-categories.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Order by','propharm'),
							'param_name' => 'orderby',
							'value'      => array(
								esc_html__('Category order','propharm') => 'order',
								esc_html__('Name','propharm') => 'name',
							),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show as dropdown','propharm'),
							'param_name' => 'dropdown',
							'value'      => '1',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show product counts','propharm'),
							'param_name' => 'count',
							'value'      => '1',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show hierarchy','propharm'),
							'param_name' => 'hierarchical',
							'value'      => '1',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Only show children of the current category','propharm'),
							'param_name' => 'show_children_only',
							'value'      => '1',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Hide empty categories','propharm'),
							'param_name' => 'hide_empty',
							'value'      => '1',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Maximum depth','propharm'),
							'param_name' => 'max_depth',
							'value'      => '',
						),
		    		)

		    	));

		    /* widget_products_by_rating
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Product by rating','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_products_by_rating',
		    		'class'                   => 'widget_products_by_rating',
		    		'icon'                    => 'widget_products_by_rating',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-products-by-rating.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Number of products to show','propharm'),
							'param_name' => 'number',
							'value'      => '',
						),
		    		)

		    	));

		    /* widget_products
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Products','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_products',
		    		'class'                   => 'widget_products',
		    		'icon'                    => 'widget_products',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-products.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Number of products to show','propharm'),
							'param_name' => 'number',
							'value'      => '',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Show','propharm'),
							'param_name' => 'show',
							'value'      => array(
								esc_html__('All products','propharm') => '',
								esc_html__('Featured','propharm') => 'featured',
								esc_html__('On-sale products','propharm') => 'onsale',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Order by','propharm'),
							'param_name' => 'orderby',
							'value'      => array(
								esc_html__('Date','propharm') => 'date',
								esc_html__('Price','propharm') => 'price',
								esc_html__('Random','propharm') => 'random',
								esc_html__('Sales','propharm') => 'sales',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__('Order','propharm'),
							'param_name' => 'order',
							'value'      => array(
								esc_html__('ASC','propharm') => 'asc',
								esc_html__('DESC','propharm') => 'desc',
							),
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Hide free products','propharm'),
							'param_name' => 'hide_free',
							'value'      => '1',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__('Show hidden products','propharm'),
							'param_name' => 'show_hidden',
							'value'      => '1',
						),
		    		)

		    	));

		    /* widget_recent_product_reviews
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Recent product reviews','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_recent_product_reviews',
		    		'class'                   => 'widget_recent_product_reviews',
		    		'icon'                    => 'widget_recent_product_reviews',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-products-reviews.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Number of products to show','propharm'),
							'param_name' => 'number',
							'value'      => '',
						),
		    		)

		    	));

		    /* widget_recent_viewed_products
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Recent viewed products','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_recent_viewed_products',
		    		'class'                   => 'widget_recent_viewed_products',
		    		'icon'                    => 'widget_recent_viewed_products',
		    		'front_enqueue_js'        => PROPHARM_ENOVATHEMES_TEMPPATH .'/js/vc_elements/widget-products-viewed.js',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Number of products to show','propharm'),
							'param_name' => 'number',
							'value'      => '',
						),
		    		)

		    	));

		    /* widget_product_tag_cloud
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Product tag cloud','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_product_tag_cloud',
		    		'class'                   => 'widget_product_tag_cloud',
		    		'icon'                    => 'widget_product_tag_cloud',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						)
		    		)

		    	));

		    /* widget_cart
			----*/

		    	vc_map(array(
		    		'name'                    => esc_html__('Cart','propharm'),
		    		'description'             => esc_html__('Woocommerce','propharm'),
		    		'category'                => esc_html__('WordPress Widgets','propharm'),
		    		'base'                    => 'widget_cart',
		    		'class'                   => 'widget_cart',
		    		'icon'                    => 'widget_cart',
		    		'show_settings_on_create' => true,
		    		'params'                  => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__('Title','propharm'),
							'param_name' => 'title',
							'value'      => '',
						),
		    		)
		    	));

	}

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

		class WPBakeryShortCode_et_Carousel extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Carousel_Item extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Pricing_Table_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Pricing_Table extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Accordion extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Accordion_Item extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Tab extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Tab_Item extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Testimonial_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Testimonial extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Client_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Client extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Banner extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Popup_Banner extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Toggle_Banner extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Stagger_Box extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Icon_Box_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Step_Box_Container extends WPBakeryShortCodesContainer {}
		
		class WPBakeryShortCode_et_Header_Sidebar_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Header_Mobile_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Mobile_Container_Tab extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Header_Modal_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Header_Modal_Container_Column extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Align_Container extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Vertical_Align_Top extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Vertical_Align_Middle extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Vertical_Align_Bottom extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Megamenu_Tab extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_et_Megamenu_Tab_Item extends WPBakeryShortCodesContainer {}

		class WPBakeryShortCode_et_Woo_Categories extends WPBakeryShortCodesContainer {}

	}

}

?>
