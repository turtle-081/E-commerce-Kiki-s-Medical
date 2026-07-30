<?php

namespace QuadLayers\QLWAPP\Controllers;

use QuadLayers\QLWAPP\Models\Box as Models_Box;
use QuadLayers\QLWAPP\Models\Button as Models_Button;
use QuadLayers\QLWAPP\Models\Display as Models_Display;
use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Models\Scheme as Models_Scheme;
use QuadLayers\QLWAPP\Services\Entity_Visibility;

class Frontend {

	protected static $instance;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'wp', array( $this, 'display' ) );
		add_action(
			'qlwapp_load',
			function () {
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend' ), 20 );
				add_action( 'wp_footer', array( __CLASS__, 'add_app' ) );
				add_shortcode( 'whatsapp', array( __CLASS__, 'do_shortcode' ) );
			},
			10
		);
		add_filter( 'litespeed_optm_js_defer_exc', array( __CLASS__, 'litespeed_exclude' ) );
		add_filter( 'litespeed_optm_js_exc', array( __CLASS__, 'litespeed_exclude' ) );
		add_filter( 'litespeed_optm_css_exc', array( __CLASS__, 'litespeed_exclude' ) );
	}

	public static function litespeed_exclude( $excludes ) {
		$excludes[] = 'wp-whatsapp-chat/build/frontend';
		return $excludes;
	}

	public function display() {

		$is_elementor_library = isset( $_GET['post_type'] ) && 'elementor_library' === $_GET['post_type'] && isset( $_GET['render_mode'] ) && 'template-preview' === $_GET['render_mode'];

		if ( $is_elementor_library ) {
			return;
		}

		if ( is_admin() ) {
			return;
		}

		do_action( 'qlwapp_load' );
	}

	public static function register_scripts() {

		$frontend = include QLWAPP_PLUGIN_DIR . 'build/frontend/js/index.asset.php';

		wp_register_script(
			'qlwapp-frontend',
			plugins_url( '/build/frontend/js/index.js', QLWAPP_PLUGIN_FILE ),
			$frontend['dependencies'],
			$frontend['version'],
			true
		);

		wp_register_style(
			'qlwapp-frontend',
			plugins_url( '/build/frontend/css/style.css', QLWAPP_PLUGIN_FILE ),
			null,
			QLWAPP_PLUGIN_VERSION
		);
	}

	public static function enqueue_frontend() {
		$display    = Models_Display::instance()->get();
		$is_visible = Entity_Visibility::instance()->is_show_view( $display );

		if ( ! $is_visible ) {
			return;
		}

		wp_enqueue_script( 'qlwapp-frontend' );
		wp_enqueue_style( 'qlwapp-frontend' );
	}

	public static function add_app() {

		$button  = Models_Button::instance()->get();
		$display = Models_Display::instance()->get();
		$box     = Models_Box::instance()->get();
		$scheme  = Models_Scheme::instance()->get();

		$is_visible = Entity_Visibility::instance()->is_show_view( $display );

		if ( ! $is_visible ) {
			return;
		}

		// Intentional: this enqueue is necessary for shortcode rendering and as a fallback
		// when enqueue_frontend() (hooked on wp_enqueue_scripts) hasn't run yet.
		// WordPress deduplicates assets, so there is no double-loading.
		wp_enqueue_script( 'qlwapp-frontend' );
		wp_enqueue_style( 'qlwapp-frontend' );

		// Primary contact = contacts[0] before display filtering. The toggle
		// must always target it, even when display rules would hide it on the
		// current device — otherwise the array_values(array_filter(...)) below
		// would silently reindex contacts[0] to a different contact.
		$primary_contact = Models_Contacts::instance()->get_primary();

		// Filter the contacts based on the display settings (modal/contact-list only).
		$contacts = array_values(
			array_filter(
				Models_Contacts::instance()->get_all(),
				function ( $contact ) {
					if ( ! isset( $contact['display'] ) ) {
						return true;
					}
					$is_visible = Entity_Visibility::instance()->is_show_view( $contact['display'] );
					return $is_visible;
				}
			)
		);

		$style  = self::get_scheme_css_properties( $scheme );
		$style .= self::get_button_css_properties( $button );

		$contacts_json        = wp_json_encode( $contacts );
		$display_json         = wp_json_encode( $display );
		$button_json          = wp_json_encode( $button );
		$box_json             = wp_json_encode( $box );
		$scheme_json          = wp_json_encode( $scheme );
		$primary_contact_json = wp_json_encode( $primary_contact );

		?>
		<div
			class="qlwapp"
			style="<?php echo esc_attr( $style ); ?>"
			data-contacts="<?php echo esc_attr( $contacts_json ); ?>"
			data-primary-contact="<?php echo esc_attr( $primary_contact_json ); ?>"
			data-display="<?php echo esc_attr( $display_json ); ?>"
			data-button="<?php echo esc_attr( $button_json ); ?>"
			data-box="<?php echo esc_attr( $box_json ); ?>"
			data-scheme="<?php echo esc_attr( $scheme_json ); ?>"
		>
			<?php if ( isset( $button['box'], $box['footer'] ) && 'yes' === $button['box'] && ! empty( $box['footer'] ) ) : ?>
				<div class="qlwapp-footer">
					<?php echo wpautop( wp_kses_post( $box['footer'] ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	public static function get_button_css_properties( $button ) {
		$style = '';
		foreach ( $button as $key => $value ) {
			if ( '' !== $value ) {
				if ( ! str_contains( $key, 'animation' ) ) {
					continue;
				}
				if ( str_contains( $key, 'animation_delay' ) ) {
					$value = "{$value}s";
				}
				$style .= sprintf( '--%s-button-%s:%s;', QLWAPP_DOMAIN, esc_attr( str_replace( '_', '-', $key ) ), esc_attr( $value ) );
			}
		}
		return $style;
	}

	public static function get_scheme_css_properties( $scheme ) {
		$style = '';
		foreach ( $scheme as $key => $value ) {
			if ( is_numeric( $value ) ) {
				$value = "{$value}px";
			}
			if ( '' !== $value ) {
				$style .= sprintf( '--%s-scheme-%s:%s;', QLWAPP_DOMAIN, esc_attr( str_replace( '_', '-', $key ) ), esc_attr( $value ) );
			}
		}
		return $style;
	}

	public static function do_shortcode( $atts, $content = null ) {

		wp_enqueue_script( 'qlwapp-frontend' );
		wp_enqueue_style( 'qlwapp-frontend' );

		$button             = Models_Button::instance()->get();
		$button['text']     = $content;
		$button['position'] = '';
		$button['box']      = 'no';
		$button             = htmlentities( wp_json_encode( wp_parse_args( $atts, $button ) ), ENT_QUOTES, 'UTF-8' );

		// Primary contact mirrors add_app(): pre-display-filter target for the
		// inline toggle. Contacts list mirrors the same display filter applied
		// to the global app so behavior stays consistent.
		$primary_contact = Models_Contacts::instance()->get_primary();

		// Preserve the legacy `[whatsapp phone="..." message="..."]` overrides.
		// Before the Button/Contact split these were merged into the button
		// payload that the frontend read; now they belong on the primary
		// contact (the toggle target) so the override actually takes effect.
		if ( is_array( $primary_contact ) && is_array( $atts ) ) {
			$contact_overrides = array_intersect_key(
				$atts,
				array_flip( array( 'phone', 'message', 'type', 'group', 'whatsapp_link_type' ) )
			);
			if ( ! empty( $contact_overrides ) ) {
				// Normalise the override phone like the model/migration do, so
				// the persisted `data-primary-contact` never carries a raw
				// value for consumers that read it without re-formatting.
				if ( isset( $contact_overrides['phone'] ) ) {
					$contact_overrides['phone'] = qlwapp_format_phone( $contact_overrides['phone'] );
				}
				$primary_contact = array_merge( $primary_contact, $contact_overrides );
			}
		}

		$contacts = array_values(
			array_filter(
				Models_Contacts::instance()->get_all(),
				function ( $contact ) {
					if ( ! isset( $contact['display'] ) ) {
						return true;
					}
					return Entity_Visibility::instance()->is_show_view( $contact['display'] );
				}
			)
		);

		$primary_contact_json = htmlentities( wp_json_encode( $primary_contact ), ENT_QUOTES, 'UTF-8' );
		$contacts_json        = htmlentities( wp_json_encode( $contacts ), ENT_QUOTES, 'UTF-8' );

		$scheme = Models_Scheme::instance()->get();
		$style  = self::get_scheme_css_properties( $scheme );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return '<div style="' . $style . '" class="qlwapp qlwapp--shortcode" data-button="' . $button . '" data-contacts="' . $contacts_json . '" data-primary-contact="' . $primary_contact_json . '"></div>';
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
