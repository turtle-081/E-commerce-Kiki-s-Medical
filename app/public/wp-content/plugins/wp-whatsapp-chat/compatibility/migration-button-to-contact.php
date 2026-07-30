<?php
/**
 * Migration: Button "contact data" -> Contacts.
 *
 * Until v8.5.x the `qlwapp_button` option carried the contact-related fields
 * (phone, type, group, message, whatsapp_link_type) that the `qlwapp_contacts`
 * option also stores per contact. Which source the frontend displayed depended
 * on `button.box`:
 *
 *   - box=no  : the toggle dialled the BUTTON's contact data; contacts[] was
 *               never shown to visitors.
 *   - box=yes : the chat box showed the contacts; the button's contact fields
 *               were inert.
 *
 * This file makes the consolidated Contact-only model preserve what each site
 * actually displayed, with one rule:
 *
 *   - box=yes : no changes. The contacts already are the source of truth.
 *   - box=no  : prepend a NEW primary contact built from the button's contact
 *               fields, so the toggle keeps showing the same number/message.
 *               Existing contacts are kept (they become secondaries, visible
 *               only if the box is later enabled) — nothing is overwritten.
 *
 * `whatsapp_link_type` is intentionally NOT handled here: it was a global
 * button setting applied to the toggle in BOTH box states, so it is migrated
 * box-independently by `qlwapp_migrate_button_link_type_to_contact` (v2) below.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! function_exists( 'qlwapp_migration_build_button_contact' ) ) {

	/**
	 * Build the contact to seed from the legacy button option, or null when
	 * nothing should be migrated. Pure (no WordPress / ORM) so the box rule can
	 * be unit-tested in isolation.
	 *
	 *   - box=yes -> null (contacts already are the source of truth);
	 *   - otherwise copy the button's contact-data fields verbatim. No
	 *     "skip if equal to default" check: under box=no the button value is
	 *     exactly what the visitor saw, default or not, and it must survive;
	 *   - phone is normalised so the contact never inherits a raw legacy value;
	 *   - returns null when the button carries none of the migratable fields.
	 *
	 * `whatsapp_link_type` is deliberately excluded — see file header / v2.
	 *
	 * @param array $button The legacy `qlwapp_button` option value.
	 * @return array|null The contact fields to prepend, or null to skip.
	 */
	function qlwapp_migration_build_button_contact( array $button ) {
		// box=yes -> contacts were the displayed source; nothing to migrate.
		// Missing box key means the entity default 'no'.
		if ( isset( $button['box'] ) && 'yes' === $button['box'] ) {
			return null;
		}

		$contact = array();

		foreach ( array( 'type', 'phone', 'group', 'message' ) as $field ) {
			if ( array_key_exists( $field, $button ) ) {
				$contact[ $field ] = $button[ $field ];
			}
		}

		// Button carried no contact data at all -> nothing to seed.
		if ( empty( $contact ) ) {
			return null;
		}

		if ( isset( $contact['phone'] ) && function_exists( 'qlwapp_format_phone' ) ) {
			$contact['phone'] = qlwapp_format_phone( $contact['phone'] );
		}

		return $contact;
	}
}

if ( ! function_exists( 'qlwapp_migration_prepend_primary_contact' ) ) {

	/**
	 * Insert $new_contact as the primary contact at the head of $contacts.
	 * Pure so the primacy guarantees (id, order, position) are unit-testable.
	 *
	 *   - id    = max existing id + 1, so the new row never collides with an
	 *             existing contact id (update/delete address rows by id);
	 *   - order = 0, the lowest, so Models_Contacts::get_primary() — which picks
	 *             the lowest `order`, first position winning ties — resolves to
	 *             this contact;
	 *   - array_unshift keeps it first, so it also wins the position tie-break.
	 *
	 * @param array $contacts    The `qlwapp_contacts` option value.
	 * @param array $new_contact Fields produced by qlwapp_migration_build_button_contact().
	 * @return array The contacts array with the new primary prepended.
	 */
	function qlwapp_migration_prepend_primary_contact( array $contacts, array $new_contact ): array {
		$max_id = 0;

		foreach ( $contacts as $contact ) {
			if ( isset( $contact['id'] ) && is_numeric( $contact['id'] ) && (int) $contact['id'] > $max_id ) {
				$max_id = (int) $contact['id'];
			}
		}

		$new_contact['id']    = $max_id + 1;
		$new_contact['order'] = 0;

		array_unshift( $contacts, $new_contact );

		return $contacts;
	}
}

if ( ! function_exists( 'qlwapp_migrate_button_phone_to_contact' ) ) {

	/**
	 * One-shot migration. Guarded by an option flag so it only runs once.
	 */
	function qlwapp_migrate_button_phone_to_contact() {

		$flag_option = 'qlwapp_migration_button_to_contact_done';

		if ( get_option( $flag_option ) ) {
			return;
		}

		// Atomic claim across concurrent requests: add_option returns false when
		// the flag already exists, so only one of N parallel boots runs the body.
		if ( ! add_option( $flag_option, '1', '', false ) ) {
			return;
		}

		$button = get_option( 'qlwapp_button', null );

		if ( ! is_array( $button ) ) {
			return;
		}

		$new_contact = qlwapp_migration_build_button_contact( $button );

		// box=yes, or button carried no contact data -> nothing to do.
		if ( null === $new_contact ) {
			return;
		}

		$contacts = get_option( 'qlwapp_contacts', array() );

		if ( ! is_array( $contacts ) ) {
			$contacts = array();
		}

		$contacts = qlwapp_migration_prepend_primary_contact( $contacts, $new_contact );

		update_option( 'qlwapp_contacts', $contacts );
	}

	add_action( 'plugins_loaded', 'qlwapp_migrate_button_phone_to_contact', 20 );
}

if ( ! function_exists( 'qlwapp_migration_apply_link_type' ) ) {

	/**
	 * Pure decision for the v2 (`whatsapp_link_type`) migration. Extracted so
	 * the seed-vs-propagate rules can be unit-tested without WordPress or the
	 * ORM.
	 *
	 * Rules:
	 *   - a default/empty button value propagates nothing;
	 *   - with no contacts row yet, seed contacts[0] with the value — otherwise
	 *     an api-only customisation (no other field diverged from default, so
	 *     v1 wrote no row) would be lost when get_all() later returns the
	 *     entity default 'web';
	 *   - with contacts present, copy onto every contact whose field is
	 *     genuinely absent (null/''); an explicit stored value (including 'web')
	 *     is user-curated and left alone.
	 *
	 * @param mixed $button_value The button's stored `whatsapp_link_type`.
	 * @param mixed $contacts     The `qlwapp_contacts` option value.
	 * @return array|null The contacts array to persist, or null when nothing
	 *                    should change.
	 */
	function qlwapp_migration_apply_link_type( $button_value, $contacts ) {
		// Default — nothing the user customised, nothing to propagate.
		if ( $button_value === 'web' || $button_value === '' || $button_value === null ) {
			return null;
		}

		// No contacts row yet: v1 left it empty because only the link type was
		// customised. Seed contacts[0] here so the value survives.
		if ( ! is_array( $contacts ) || empty( $contacts ) ) {
			return array(
				array(
					'id'                 => 1,
					'whatsapp_link_type' => $button_value,
				),
			);
		}

		$changed = false;

		foreach ( $contacts as $index => $contact ) {
			$current = isset( $contact['whatsapp_link_type'] ) ? $contact['whatsapp_link_type'] : null;

			// Only overwrite when the field is genuinely absent. Treating 'web'
			// as "untouched default" would clobber a user that explicitly chose
			// 'web' per-contact while keeping Button on 'api'.
			if ( $current === null || $current === '' ) {
				$contacts[ $index ]['whatsapp_link_type'] = $button_value;
				$changed                                  = true;
			}
		}

		return $changed ? $contacts : null;
	}
}

if ( ! function_exists( 'qlwapp_migrate_button_link_type_to_contact' ) ) {

	/**
	 * v2 migration: propagate `whatsapp_link_type` from Button to every Contact.
	 *
	 * Runs under its own guard so sites that already completed the v1 migration
	 * (which did not include this field) still receive the new value. The field
	 * was global on Button — applied to the toggle in both box states — so it is
	 * migrated box-independently: copied to every contact that has no stored
	 * value yet (including the primary v1 may have just prepended). Contacts that
	 * already store a value (including an explicit 'web') are treated as
	 * user-curated and left alone. When no contacts row exists at all, contacts[0]
	 * is seeded so the value is not lost.
	 *
	 * The seed-vs-propagate decision lives in qlwapp_migration_apply_link_type.
	 */
	function qlwapp_migrate_button_link_type_to_contact() {

		$flag_option = 'qlwapp_migration_link_type_done';

		if ( get_option( $flag_option ) ) {
			return;
		}

		if ( ! add_option( $flag_option, '1', '', false ) ) {
			return;
		}

		$button = get_option( 'qlwapp_button', null );

		if ( ! is_array( $button ) || ! array_key_exists( 'whatsapp_link_type', $button ) ) {
			return;
		}

		$contacts = get_option( 'qlwapp_contacts', null );
		$result   = qlwapp_migration_apply_link_type( $button['whatsapp_link_type'], $contacts );

		if ( null !== $result ) {
			update_option( 'qlwapp_contacts', $result );
		}
	}

	add_action( 'plugins_loaded', 'qlwapp_migrate_button_link_type_to_contact', 20 );
}
