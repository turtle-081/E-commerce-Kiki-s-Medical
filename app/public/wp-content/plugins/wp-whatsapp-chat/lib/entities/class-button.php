<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Button extends SingleEntity {
	public $layout                        = 'button';
	public $box                           = 'no';
	public $position                      = 'bottom-right';
	public $text                          = '';
	public $icon                          = 'qlwapp-whatsapp-icon';
	public $developer                     = 'no';
	public $rounded                       = 'yes';
	public $animation_name                = '';
	public $animation_delay               = '';
	public $notification_bubble           = 'none';
	public $notification_bubble_animation = 'none';
	// Availability lives on Button (global "business hours" gate for the
	// toggle) AND on Contact (per-row gate inside the chat box). Different
	// layers, not duplicated data: the contact-data refactor that moved
	// phone/message/type/group/whatsapp_link_type to Contact did not apply
	// to availability — those answer different questions.
	public $timefrom = '00:00';
	public $timeto   = '00:00';
	public $timedays = array();
	public $timezone = '';
	// Whitelist constrained to hidden|readonly in Models_Button — with_status
	// is meaningless at the button level (it's a per-contact status bubble).
	public $visibility = 'readonly';

	public function __construct() {
		$this->text     = esc_html__( 'How can I help you?', 'wp-whatsapp-chat' );
		$this->timezone = qlwapp_get_timezone_current();
	}
}
