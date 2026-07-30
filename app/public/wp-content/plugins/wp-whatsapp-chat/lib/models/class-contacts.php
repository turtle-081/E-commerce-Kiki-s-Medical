<?php

namespace QuadLayers\QLWAPP\Models;

use QuadLayers\QLWAPP\Entities\Contact as Contact_Entity;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Contacts Class
 */
class Contacts {

	protected static $instance;
	protected $repository;

	/**
	 * Per-request memo for get_all(). The ORM caches the option lookup, but
	 * the model still iterates entities and runs qlwapp_replacements_vars on
	 * each message — repeated for every caller (add_app + get_primary + the
	 * shortcode all hit this). Invalidate on every write below.
	 */
	protected $cached_all = null;

	public function __construct() {
		add_filter( 'sanitize_option_qlwapp_contacts', 'wp_unslash' );
		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'qlwapp_contacts' )
		->setEntity( Contact_Entity::class )
		->setDefaultEntities( array( array( 'id' => 1 ) ) )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get_args() {
		$entity   = new Contact_Entity();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	public function get( int $id ) {
		$entity = $this->repository->find( $id );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function delete( int $id ) {
		$all_contacts = $this->get_all();

		// Prevent deletion when only one contact remains
		if ( count( $all_contacts ) <= 1 ) {
			return false;
		}

		$this->cached_all = null;
		return $this->repository->delete( $id );
	}

	public function update_all( array $contacts ) {
		foreach ( $contacts as $contact ) {
			if ( isset( $contact['id'] ) ) {
				$this->update( $contact['id'], $contact );
			}
		}
		return true;
	}

	public function update( int $id, array $contact ) {
		$entity           = $this->repository->update( $id, $this->sanitize_value_data( $contact ) );
		$this->cached_all = null;
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function create( array $contact ) {
		if ( isset( $contact['id'] ) ) {
			unset( $contact['id'] );
		}

		$entity           = $this->repository->create( $this->sanitize_value_data( $contact ) );
		$this->cached_all = null;

		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function get_contacts_reorder() {
		return $this->get_all();
	}

	public function get_contacts() {
		return $this->get_all();
	}

	public function get_all() {
		if ( null !== $this->cached_all ) {
			return $this->cached_all;
		}

		$entities = $this->repository->findAll();

		// The ORM injects its default entity only when the stored option is
		// strictly null; a persisted empty array ([]) slips through findAll()
		// as "no contacts", which would leave the frontend with no primary
		// contact and hide the button entirely. Fall back to the same entity
		// default the ORM would seed (id => 1) so get_all()/get_primary() never
		// depend on that null-vs-[] distinction at the storage layer.
		if ( ! $entities ) {
			$default_entity     = new Contact_Entity();
			$default_entity->id = 1;
			$entities           = array( $default_entity );
		}

		// Only replace variables on frontend (not in admin or REST API admin requests).
		$is_rest_admin = defined( 'REST_REQUEST' ) && REST_REQUEST && is_user_logged_in();
		$is_frontend   = ! is_admin() && ! $is_rest_admin;

		$contacts = array();

		foreach ( $entities as $entity ) {
			$contact = $entity->getProperties();

			if ( $is_frontend ) {
				$contact['message'] = qlwapp_replacements_vars( $contact['message'] );
			}

			$contacts[] = $contact;
		}

		$this->cached_all = $contacts;
		return $this->cached_all;
	}

	/**
	 * Invariant: the primary contact (toggle target) is the contact with the
	 * lowest `order`, resolved before any display filtering. Do not move this
	 * responsibility to controllers — the frontend filters by device and would
	 * reindex the array, silently moving the toggle target.
	 *
	 * We sort by `order` rather than trusting the stored array position: a
	 * drag-and-drop reorder only rewrites the per-contact `order` field (via
	 * update_all -> in-place ORM updates), it never reorders the physical
	 * array. Reading position[0] would therefore return a stale contact that
	 * disagrees with both the admin editor and the frontend list, which both
	 * order by `order` (see filterVisibleContacts.js for the matching JS rule:
	 * missing/null order sorts last, ties keep their relative position).
	 *
	 * Returns null when no contacts exist (template guards).
	 */
	public function get_primary() {
		$contacts = $this->get_all();

		if ( empty( $contacts ) ) {
			return null;
		}

		$primary       = $contacts[0];
		$primary_order = isset( $primary['order'] ) && null !== $primary['order'] ? $primary['order'] : PHP_INT_MAX;

		foreach ( $contacts as $contact ) {
			$order = isset( $contact['order'] ) && null !== $contact['order'] ? $contact['order'] : PHP_INT_MAX;

			if ( $order < $primary_order ) {
				$primary       = $contact;
				$primary_order = $order;
			}
		}

		return $primary;
	}

	public function delete_all() {
		$this->cached_all = null;
		return $this->repository->deleteAll();
	}

	public function sanitize_value_data( $value_data ) {
		$args = $this->get_args();

		foreach ( $value_data as $key => $value ) {
			if ( array_key_exists( $key, $args ) ) {
				$type = $args[ $key ];

				if ( is_null( $type ) && ! is_numeric( $value ) ) {
					$value_data[ $key ] = intval( $value );
				} elseif ( is_bool( $type ) && ! is_bool( $value ) ) {
					$value_data[ $key ] = ( $value === 'true' || $value === '1' || $value === 1 );
				} elseif ( is_string( $type ) && ! is_string( $value ) ) {
					$value_data[ $key ] = strval( $value );
				} elseif ( is_array( $type ) && ! is_array( $value ) ) {
					$value_data[ $key ] = (array) $type;
				}
			} else {
				unset( $value_data[ $key ] );
			}
		}

		if ( isset( $value_data['whatsapp_link_type'] ) && ! in_array( $value_data['whatsapp_link_type'], array( 'api', 'web' ), true ) ) {
			$value_data['whatsapp_link_type'] = 'web';
		}

		return $value_data;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
