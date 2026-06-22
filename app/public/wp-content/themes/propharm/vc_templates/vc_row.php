<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$row_background = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	'vc_row',
	'wpb_row',
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $video_bg || $parallax
) {
	$css_classes[] = 'vc_row-has-fill';
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if ( ! empty( $full_width ) ) {

	if ('stretch_row_content' == $full_width) {
		$css_classes[] = 'stretch_row_content';
	}
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && (!empty( $video_bg_mp4 ) || !empty( $video_bg_webm ) || !empty($video_bg_ogv)));

$parallax_speed = $parallax_speed_bg;
$parallax_duration = $parallax_duration_bg;

if ( $has_video_bg ) {

	$css_classes[] = 'vc-video-bg';

	$parallax       = $video_bg_parallax;
	$parallax_speed = $video_bg_parallax_speed;
	$parallax_duration = $video_bg_parallax_duration;

	$row_background = '<video class="video-container" autoplay preload="auto" loop="loop" muted="muted" poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png">';
		if ($video_bg_mp4){
	    	$row_background .= '<source type="video/mp4" src="'.esc_url($video_bg_mp4).'"/>';
		}
	    if ($video_bg_webm){
	    	$row_background .= '<source type="video/webm" src="'.esc_url($video_bg_webm).'"/>';
	    }
	    if ($video_bg_ogv){
	    	$row_background .= '<source type="video/ogg" src="'.esc_url($video_bg_ogv).'"/>';
	    }
	$row_background .= '</video>';

	if ( ! empty( $video_bg_overlay ) ) {
		$row_background .= '<div class="video-container-overlay"></div>';
	}

	if ( ! empty( $video_bg_placeholder ) ) {
		$row_background .= '<div class="video-container-placeholder"></div>';
	}

}

if ( $fixed_bg == "true" ) {
	$css_classes[] = 'vc-fixed-bg';
	$row_background = '<div class="fixed-container"></div>';
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
		$row_background = '<div class="animated-container" data-img-width="'.$animated_bg_image_src[1].'" data-img-height="'.$animated_bg_image_src[2].'"></div>';
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

		$row_background = '<video class="video-container" autoplay preload="auto" loop="loop" muted="muted" poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png">';
			if ($video_bg_mp4){
		    	$row_background .= '<source type="video/mp4" src="'.esc_url($video_bg_mp4).'"/>';
			}
		    if ($video_bg_webm){
		    	$row_background .= '<source type="video/webm" src="'.esc_url($video_bg_webm).'"/>';
		    }
		    if ($video_bg_ogv){
		    	$row_background .= '<source type="video/ogg" src="'.esc_url($video_bg_ogv).'"/>';
		    }
		$row_background .= '</video>';

		if ( ! empty( $video_bg_overlay ) ) {
			$row_background .= '<div class="video-container-overlay"></div>';
		}

		if ( ! empty( $video_bg_placeholder ) ) {
			$row_background .= '<div class="video-container-placeholder"></div>';
		}

	}else{

		$css_classes[] = 'vc-parallax';

		$row_background = '<div class="parallax-container"></div>';
	}


}

if ($hide_row_sticky == "true") {
	$css_classes[] = 'hide-sticky';
}

if ($hide_row_default == "true") {
	$css_classes[] = 'hide-default';
}

if (!empty($element_id)) {
	$css_classes[] = 'vc-row-'.$element_id;
}

// color and curtain

$color = '';
if (strrpos($css,'background: #') || strrpos($css,'background-color: #')) {
	$hex_start = strrpos($css,'#');
	$hex_end   = $hex_start + 7;
	$color = substr($css, $hex_start, $hex_end - $hex_start);
}
if ($curtain != "none") {
	$row_background .= '<div class="vc-curtain" data-curtain="'.esc_attr($curtain).'"></div>';
	if ($curtain_color) {$color = $curtain_color;}
}
if (!empty($color)) {
	$wrapper_attributes[] = 'data-color="' . esc_attr( $color ) . '"';
}

// propharm
$row_overlay = '';

if ($top_gradient == "true") {
	$wrapper_attributes[] = 'data-top-gradient="' . esc_attr( $top_gradient ) . '"';
	$row_overlay .= '<div class="top-gradient gradient"></div>';
}

if ($bottom_gradient == "true") {
	$wrapper_attributes[] = 'data-bottom-gradient="' . esc_attr( $bottom_gradient ) . '"';
	$row_overlay .= '<div class="bottom-gradient gradient"></div>';
}

if ($grid_overlay == "black" || $grid_overlay == "white") {
	$css_classes[] = 'grid-overlay-active';
	$wrapper_attributes[] = 'data-grid-overlay="' . esc_attr( $grid_overlay ) . '"';
	$row_overlay .= '<div class="grid-overlay"></div>';
}
if ($reverse_mob == "true") {
	$css_classes[] = 'reverse-mob-'.$reverse_mob;
}
if ($reverse_tab == "true") {
	$css_classes[] = 'reverse-tab-'.$reverse_tab;
}
if ($reverse_tab_land == "true") {
	$css_classes[] = 'reverse-tab-land-'.$reverse_tab_land;
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ). '"';
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?> >
	<div class="container et-clearfix">
		<?php echo wpb_js_remove_wpautop( $content ); ?>
	</div>
	<div class="row-back">
		<?php echo html_entity_decode($row_overlay); ?>
		<?php echo html_entity_decode($row_background); ?>
	</div>
</div>