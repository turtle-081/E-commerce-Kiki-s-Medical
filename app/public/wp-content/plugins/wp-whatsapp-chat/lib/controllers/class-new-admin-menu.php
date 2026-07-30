<?php

namespace QuadLayers\QLWAPP\Controllers;

use QuadLayers\QLWAPP\Api\Admin_Menu_Routes_Library;
use QuadLayers\QLWAPP\Services\Entity_Options;


class New_Admin_Menu {

	protected static $instance;
	protected static $menu_slug = 'wp-whatsapp-chat';

	private function __construct() {
		/**
		 * Admin Menu
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'remove_notices' ) );
		add_action( 'admin_footer', array( $this, 'render_support_bot' ) );
	}

	public function register_scripts() {

		global $wp_version;

		$menu           = include QLWAPP_PLUGIN_DIR . 'build/new-admin-menu/js/index.asset.php';
		$entity_options = Entity_Options::instance();

		wp_register_script(
			'qlwapp-new-admin-menu',
			plugins_url( '/build/new-admin-menu/js/index.js', QLWAPP_PLUGIN_FILE ),
			$menu['dependencies'],
			$menu['version'],
			true
		);

		wp_localize_script(
			'qlwapp-new-admin-menu',
			'qlwappApiAdminMenu',
			array(
				'QLWAPP_API_REST_ROUTES'    => $this->get_endpoints(),
				'QLWAPP_DISPLAY_POST_TYPES' => $entity_options->get_entries(),
				'QLWAPP_DISPLAY_TAXONOMIES' => $entity_options->get_taxonomies(),
				'QUICK_BOT_URL'             => 'https://app.quick.bot',
				'QUICK_BOT_VIEWER_URL'      => 'https://viewer.quick.bot',
				'WP_VERSION'                => $wp_version,
			)
		);

		wp_register_style(
			'qlwapp-new-admin-menu',
			plugins_url( '/build/new-admin-menu/css/style.css', QLWAPP_PLUGIN_FILE ),
			array(
				'qlwapp-components',
				'qlwapp-frontend',
				'media-views',
				'wp-components',
			),
			QLWAPP_PLUGIN_VERSION
		);

		wp_enqueue_editor();
	}

	private function get_endpoints() {
		$route_library   = Admin_Menu_Routes_Library::instance();
		$endpoints       = $route_library->get_routes();
		$endpoints_array = array();

		foreach ( $endpoints as $endpoint ) {

			$endpoint_key = str_replace( '/', '_', $endpoint::get_rest_route() );

			if ( ! isset( $endpoints_array[ $endpoint_key ] ) ) {

				$endpoints_array[ $endpoint_key ] = $endpoint::get_rest_path();

			}
		}

		return $endpoints_array;
	}

	public function enqueue_scripts() {

		if ( ! isset( $_GET['page'] ) || self::get_menu_slug() !== $_GET['page'] ) {
			return;
		}

		wp_deregister_style( 'colors' );
		wp_deregister_style( 'wp-admin' );
		wp_enqueue_style( 'admin-bar' );
		wp_enqueue_style( 'admin-menu' );
		wp_enqueue_style( 'common' );

		wp_enqueue_media();
		wp_enqueue_script( 'qlwapp-new-admin-menu' );
		wp_enqueue_style( 'qlwapp-new-admin-menu' );
	}

	public function add_menu() {

		$menu_slug = self::get_menu_slug();

		add_menu_page(
			QLWAPP_PLUGIN_NAME,
			QLWAPP_PLUGIN_NAME,
			'edit_posts',
			$menu_slug,
			'__return_null',
			'dashicons-whatsapp'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Welcome', 'wp-whatsapp-chat' ),
			esc_html__( 'Welcome', 'wp-whatsapp-chat' ),
			'edit_posts',
			$menu_slug,
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Premium', 'wp-whatsapp-chat' ),
			esc_html__( 'Premium', 'wp-whatsapp-chat' ),
			'edit_posts',
			"$menu_slug&tab=premium",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Button', 'wp-whatsapp-chat' ),
			esc_html__( 'Button', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=button",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Box', 'wp-whatsapp-chat' ),
			esc_html__( 'Box', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=box",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Contacts', 'wp-whatsapp-chat' ),
			esc_html__( 'Contacts', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=contacts",
			'__return_null'
		);
		if ( ! class_exists( 'QuadLayers\\QLWAPP_PRO\\Controllers\\New_Admin_Menu' ) ) {
			add_submenu_page(
				$menu_slug,
				esc_html__( 'Bots', 'wp-whatsapp-chat' ),
				esc_html__( 'Bots', 'wp-whatsapp-chat' ) . ' <span class="qlwapp__badge">Beta</span>',
				'manage_options',
				"{$menu_slug}&tab=bots",
				'__return_null',
				5
			);
		}
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Customize', 'wp-whatsapp-chat' ),
			esc_html__( 'Customize', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=customize",
			'__return_null'
		);
		add_submenu_page(
			$menu_slug,
			esc_html__( 'Analytics', 'wp-whatsapp-chat' ),
			esc_html__( 'Analytics', 'wp-whatsapp-chat' ),
			'manage_options',
			"{$menu_slug}&tab=analytics",
			'__return_null'
		);
	}

	/**
	 * Render the quick.bot support assistant bubble in the admin footer.
	 *
	 * Replaces the former React-based <SupportChatButton /> iframe. Uses the
	 * quick.bot embed SDK (QuickBot.initBubble) so we can pass contextual
	 * prefilled variables (user, plan, screen, versions, UTM attribution) to
	 * the support bot.
	 *
	 * Only loads on the plugin admin page, matching the previous behaviour.
	 */
	public function render_support_bot() {

		if ( ! isset( $_GET['page'] ) || self::get_menu_slug() !== $_GET['page'] ) {
			return;
		}

		// quick.bot public id (the slug from viewer.quick.bot/<publicId>).
		$public_id = 'quadlayers-plugin-n0lchjj';

		// URL of the quick.bot embed SDK that exposes the default `QuickBot` export.
		$sdk_url = 'https://cdn.jsdelivr.net/npm/@quick.bot/js@latest/dist/web.js';

		global $wp_version;

		$current_user = wp_get_current_user();
		$screen       = get_current_screen();
		$is_premium   = class_exists( 'QuadLayers\\QLWAPP_PRO\\Controllers\\New_Admin_Menu' );
		$screen_id    = $screen ? $screen->id : 'unknown';

		$vars = array(
			// Context.
			'user_name'      => $current_user->display_name,
			'user_email'     => $current_user->user_email,
			'plan'           => $is_premium ? 'premium' : 'free',
			'current_screen' => $screen_id,
			'wp_version'     => $wp_version,
			'php_version'    => phpversion(),
			'plugin_version' => defined( 'QLWAPP_PLUGIN_VERSION' ) ? QLWAPP_PLUGIN_VERSION : 'unknown',

			// UTM attribution to mark the origin of the conversation.
			'utm_source'     => 'qlwapp_plugin',
			'utm_medium'     => 'admin',
			'utm_campaign'   => 'support_bot',
			'utm_content'    => $screen_id,
		);
		?>
		<script type="module">
			import QuickBot from <?php echo wp_json_encode( $sdk_url ); ?>;
			QuickBot.initBubble( {
				publicId: <?php echo wp_json_encode( $public_id ); ?>,
				prefilledVariables: <?php echo wp_json_encode( $vars ); ?>,
				previewMessage: {
					message: <?php echo wp_json_encode( __( 'Hi! Need help?', 'wp-whatsapp-chat' ) ); ?>,
						subtext: <?php echo wp_json_encode( __( 'We\'re here to assist you 💚', 'wp-whatsapp-chat' ) ); ?>,
					avatarUrl: 'https://2.gravatar.com/avatar/8addf317f80adb7b1e3219184d051e86273d5291bd6a3ab303671c3d0c04a835?size=100&d=initials',
				},
				style: {
					button: {
						backgroundColor: '#01a952',
						iconColor: '#ffffff',
						layout: 'bubble',
						animation: 'pulse',
						notificationBubble: true,
						notificationBubbleAnimation: 'shakeY',
					},
				},
			} );
		</script>
		<?php
	}

	public function remove_notices() {

		if ( ! isset( $_GET['page'] ) || self::get_menu_slug() !== $_GET['page'] ) {
			return;
		}

		remove_all_actions( 'admin_notices' );
	}

	public static function get_menu_slug() {
		return self::$menu_slug;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
