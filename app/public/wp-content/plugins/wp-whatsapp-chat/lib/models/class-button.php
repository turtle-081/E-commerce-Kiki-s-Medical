<?php
namespace QuadLayers\QLWAPP\Models;

use QuadLayers\QLWAPP\Entities\Button as Button_Entity;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;

class Button {

	protected static $instance;
	protected $repository;

	public function __construct() {
		add_filter( 'sanitize_option_qlwapp_button', 'wp_unslash' );
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'qlwapp_button' )
		->setEntity( Button_Entity::class );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get() {
		$entity = $this->repository->find();
		$result = null;

		if ( $entity ) {
			$result = $entity->getProperties();
		} else {
			$admin  = new Button_Entity();
			$result = $admin->getProperties();
		}

		// Only replace variables on frontend (not in admin or REST API admin requests).
		$is_rest_admin = defined( 'REST_REQUEST' ) && REST_REQUEST && is_user_logged_in();
		if ( ! is_admin() && ! $is_rest_admin ) {
			$result['text'] = qlwapp_replacements_vars( $result['text'] );
		}

		return $result;
	}

	public function delete_all() {
		return $this->repository->delete();
	}

	public function save( $data ) {
		$entity = $this->repository->create( $this->sanitize( $data ) );

		if ( $entity ) {
			return true;
		}
	}

	public function sanitize( $settings ) {
		if ( isset( $settings['layout'] ) ) {
			$settings['layout'] = sanitize_html_class( $settings['layout'] );
		}
		if ( isset( $settings['position'] ) ) {
			$settings['position'] = sanitize_html_class( $settings['position'] );
		}
		if ( isset( $settings['text'] ) ) {
			$settings['text'] = sanitize_text_field( $settings['text'] );
		}
		if ( isset( $settings['icon'] ) ) {
			// Check if it's a URL (for custom images) or a CSS class (for font icons)
			if ( filter_var( $settings['icon'], FILTER_VALIDATE_URL ) ||
				( strpos( $settings['icon'], 'http' ) === 0 ) ||
				( strpos( $settings['icon'], '.' ) !== false && preg_match( '/\.(jpg|jpeg|png|gif|svg|webp)$/i', $settings['icon'] ) ) ) {
				// It's an image URL, sanitize as URL
				$settings['icon'] = sanitize_url( $settings['icon'] );
			} else {
				// It's a CSS class, sanitize as HTML class
				$settings['icon'] = sanitize_html_class( $settings['icon'] );
			}
		}
		if ( isset( $settings['timefrom'] ) ) {
			$settings['timefrom'] = preg_match( '/^\d{2}:\d{2}$/', $settings['timefrom'] )
				? $settings['timefrom']
				: '00:00';
		}
		if ( isset( $settings['timeto'] ) ) {
			$settings['timeto'] = preg_match( '/^\d{2}:\d{2}$/', $settings['timeto'] )
				? $settings['timeto']
				: '00:00';
		}
		if ( isset( $settings['timedays'] ) && is_array( $settings['timedays'] ) ) {
			$settings['timedays'] = array_values(
				array_filter(
					array_map( 'sanitize_text_field', $settings['timedays'] ),
					function ( $day ) {
						return in_array( $day, array( '0', '1', '2', '3', '4', '5', '6' ), true );
					}
				)
			);
		}
		if ( isset( $settings['timezone'] ) ) {
			$settings['timezone'] = sanitize_text_field( $settings['timezone'] );
		}
		if ( isset( $settings['visibility'] ) ) {
			// hidden|readonly only — with_status is a per-contact status bubble,
			// not a button-level concept.
			$settings['visibility'] = in_array( $settings['visibility'], array( 'hidden', 'readonly' ), true )
				? $settings['visibility']
				: 'readonly';
		}
		return $settings;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
