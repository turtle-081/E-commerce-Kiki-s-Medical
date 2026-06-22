<?php

	defined( 'ABSPATH' ) || exit;

	add_action('widgets_init', 'enovathemes_addons_register_reglog_widget');
	function enovathemes_addons_register_reglog_widget(){
		register_widget( 'Enovathemes_Addons_WP_Widget_Login' );
	}

	class  Enovathemes_Addons_WP_Widget_Login extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'reglog',
				esc_html__('* Login form', 'enovathemes-addons'),
				array( 'description' => esc_html__('Front-end login form', 'enovathemes-addons'))
			);
		}

		public function widget( $args, $instance ) {

			extract($args);

			$title  = apply_filters( 'widget_title', $instance['title'] );
			$registration_link = $instance['registration_link'] ? esc_url($instance['registration_link']) : '';
			$forgot_link       = $instance['forgot_link'] ? esc_url($instance['forgot_link']) : '';
			$my_account_link   = $instance['my_account_link'] ? esc_url($instance['my_account_link']) : '';

			$output = "";
			echo $before_widget;
			if ( ! empty( $title ) ){echo $before_title . $title . $after_title;}

			$args = array(
		        'echo'           => true,
		        'form_id'        => 'loginform',
		        'redirect'       => (!empty($my_account_link)) ? $my_account_link : '',
		        'label_username' => esc_html__('Username', 'enovathemes-addons'),
		        'label_password' => esc_html__('Password', 'enovathemes-addons'),
		        'label_remember' => esc_html__( 'Remember Me', 'enovathemes-addons'),
		        'label_log_in'   => esc_html__( 'Log In', 'enovathemes-addons'),
		        'id_username'    => 'user_login',
		        'id_password'    => 'user_pass',
		        'id_remember'    => 'rememberme',
		        'id_submit'      => 'wp-submit',
		        'remember'       => false,
		        'value_username' => '',
		        'value_remember' => false
			);

			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$user = ($current_user->user_firstname) ? $current_user->user_firstname : $current_user->display_name;
			?>
			<span><?php echo esc_html__('Hello, ', 'enovathemes-addons').$user; ?></span>
			<div class="my-account-buttons">
				<?php if (!empty($my_account_link)): ?>
					<a class="button et-button small dashboard" href="<?php echo esc_url($my_account_link); ?>" title="<?php echo esc_html__('Dashboard', 'enovathemes-addons'); ?>"><?php echo esc_html__('Dashboard', 'enovathemes-addons'); ?></a>
				<?php endif ?>
				<a class="button et-button small logout" href="<?php echo wp_logout_url( home_url() ); ?>" title="<?php echo esc_html__('Logout', 'enovathemes-addons'); ?>"><?php echo esc_html__('Logout', 'enovathemes-addons'); ?></a>
			</div>
			<?php } else {
				wp_login_form( $args );
				if (!empty($forgot_link)) {
					echo '<a href="'.esc_url($forgot_link).'">'.esc_html__("Forgot password?", 'enovathemes-addons').'</a>';
				}
				if (!empty($registration_link)) {
					echo '<p>'.esc_html__("Don't have account yet?", 'enovathemes-addons').' <a href="'.esc_url($registration_link).'">'.esc_html__("Sign up", 'enovathemes-addons').'</a></p>';
				}
			}
			echo $after_widget;
		}

	 	public function form( $instance ) {

			$defaults = array(
	 			'title'  => esc_html__('Login', 'enovathemes-addons'),
	 			'registration_link'  => '',
	 			'forgot_link'  => '',
	 			'my_account_link'  => '',
	 		);

	 		$instance = wp_parse_args((array) $instance, $defaults);
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'enovathemes-addons' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('registration_link'); ?>"><?php echo esc_html__( 'Registration page link:', 'enovathemes-addons' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('registration_link'); ?>" name="<?php echo $this->get_field_name('registration_link'); ?>" type="text" value="<?php echo $instance['registration_link']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('forgot_link'); ?>"><?php echo esc_html__( 'Password recovery page:', 'enovathemes-addons' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('forgot_link'); ?>" name="<?php echo $this->get_field_name('forgot_link'); ?>" type="text" value="<?php echo $instance['forgot_link']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('my_account_link'); ?>"><?php echo esc_html__( 'My account link:', 'enovathemes-addons' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('my_account_link'); ?>" name="<?php echo $this->get_field_name('my_account_link'); ?>" type="text" value="<?php echo $instance['my_account_link']; ?>" />
			</p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']  = strip_tags( $new_instance['title'] );
			$instance['registration_link']  = strip_tags( $new_instance['registration_link'] );
			$instance['forgot_link']  = strip_tags( $new_instance['forgot_link'] );
			$instance['my_account_link']  = strip_tags( $new_instance['my_account_link'] );
			return $instance;
		}
	}

?>