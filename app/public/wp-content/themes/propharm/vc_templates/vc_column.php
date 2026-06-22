<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$column_background  = '';
$responsive_padding = array();

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

if (!empty($crp)) {
	$crp = explode(',', $crp);

	$query_array = array();

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
}

$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation ),
	'wpb_column',
	'vc_column_container',
	$width,
);

if ( $shadow == "true" ) {
	$css_classes[] = 'shadow';
}

$css_classes[] = 'text-align-'.$text_align;

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $parallax
) {
	$css_classes[] = 'vc_col-has-fill';
}

$has_video_bg = ( ! empty( $video_bg ) && (!empty( $video_bg_mp4 ) || !empty( $video_bg_webm ) || !empty($video_bg_ogv)));


$parallax_speed = $parallax_speed_bg;
$parallax_duration = $parallax_duration_bg;

if ( $has_video_bg ) {

	$css_classes[] = 'vc-video-bg';

	$parallax       = $video_bg_parallax;
	$parallax_speed = $video_bg_parallax_speed;
	$parallax_duration = $video_bg_parallax_duration;

	$column_background = '<video class="video-container" autoplay preload="auto" loop="loop" muted="muted" poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png">';
		if ($video_bg_mp4){
	    	$column_background .= '<source type="video/mp4" src="'.esc_url($video_bg_mp4).'"/>';
		}
	    if ($video_bg_webm){
	    	$column_background .= '<source type="video/webm" src="'.esc_url($video_bg_webm).'"/>';
	    }
	    if ($video_bg_ogv){
	    	$column_background .= '<source type="video/ogg" src="'.esc_url($video_bg_ogv).'"/>';
	    }
	$column_background .= '</video>';

	if ( ! empty( $video_bg_overlay ) ) {
		$column_background .= '<div class="video-container-overlay"></div>';
	}

	if ( ! empty( $video_bg_placeholder ) ) {
		$column_background .= '<div class="video-container-placeholder"></div>';
	}

}

if ( $fixed_bg == "true" ) {
	$css_classes[] = 'vc-fixed-bg';
	$column_background = '<div class="fixed-container"></div>';
}

if ( $animated_bg == "true" ) {

	if (empty($animated_bg_speed)) {
		$animated_bg_speed = 35000;
	}

	if (empty($animated_bg_dir)) {
		$animated_bg_dir = 'horizontal';
	}

	$css_classes[] = 'vc-animated-bg';

	$wrapper_attributes[] = 'data-animatedbg-speed="' . esc_attr( $animated_bg_speed ) . '"';
	$wrapper_attributes[] = 'data-animatedbg-dir="' . esc_attr( $animated_bg_dir ) . '"';

	if ( ! empty( $animated_bg_image ) ) {
		$animated_bg_image_id = preg_replace( '/[^\d]/', '', $animated_bg_image );
		$animated_bg_image_src = wp_get_attachment_image_src( $animated_bg_image, 'full' );
		$column_background = '<div class="animated-container" data-img-width="'.$animated_bg_image_src[1].'" data-img-height="'.$animated_bg_image_src[2].'"></div>';
	}

}

if ($parallax == "true") {

	if (empty($parallax_speed)) {
		$parallax_speed = 1.5;
	}

	$wrapper_attributes[] = 'data-parallax-speed="' . esc_attr( $parallax_speed ) . '"';
	$wrapper_attributes[] = 'data-parallax-duration="' . esc_attr( $parallax_duration ) . '"';

	if ( $has_video_bg ) {

		$css_classes[] = 'vc-parallax vc-video-parallax';

		$column_background = '<video class="video-container" autoplay preload="auto" loop="loop" muted="muted" poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png">';
			if ($video_bg_mp4){
		    	$column_background .= '<source type="video/mp4" src="'.esc_url($video_bg_mp4).'"/>';
			}
		    if ($video_bg_webm){
		    	$column_background .= '<source type="video/webm" src="'.esc_url($video_bg_webm).'"/>';
		    }
		    if ($video_bg_ogv){
		    	$column_background .= '<source type="video/ogg" src="'.esc_url($video_bg_ogv).'"/>';
		    }
		$column_background .= '</video>';

		if ( ! empty( $video_bg_overlay ) ) {
			$column_background .= '<div class="video-container-overlay"></div>';
		}

		if ( ! empty( $video_bg_placeholder ) ) {
			$column_background .= '<div class="video-container-placeholder"></div>';
		}

	}else{

		$css_classes[] = 'vc-parallax';

		$column_background = '<div class="parallax-container"></div>';
	}


}

if (!empty($element_id)) {
	$css_classes[] = 'vc-column-'.$element_id;
}

// color and curtain

$color = '';
if (strrpos($css,'background: #') || strrpos($css,'background-color: #')) {
	$hex_start = strrpos($css,'#');
	$hex_end   = $hex_start + 7;
	$color = substr($css, $hex_start, $hex_end - $hex_start);
}
if ($curtain != "none") {
	$column_background .= '<div class="vc-curtain" data-curtain="'.esc_attr($curtain).'"></div>';
	if ($curtain_color) {$color = $curtain_color;}
}

if (!empty($color)) {
	$wrapper_attributes[] = 'data-color="' . esc_attr( $color ) . '"';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );

$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if (isset($animation_delay) && !empty($css_animation)) {
	$wrapper_attributes[] = 'data-del="'.esc_attr($animation_delay).'"';
}

?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?> >
	<div class="vc_column-inner vci <?php echo esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) )?>" <?php echo implode( ' ', $responsive_padding ); ?>>
		<div class="wpb_wrapper">
			<?php echo wpb_js_remove_wpautop( $content ); ?>
		</div>
		<div class="col-back">
			<?php echo propharm_enovathemes_output_html($column_background); ?>
		</div>
	</div>
</div>