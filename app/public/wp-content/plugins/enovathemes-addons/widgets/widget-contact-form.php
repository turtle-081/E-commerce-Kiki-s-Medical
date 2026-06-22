<?php 
	
	add_action('widgets_init', 'enovathemes_addons_register_fast_contact_widget');
	function enovathemes_addons_register_fast_contact_widget(){
		register_widget( 'Enovathemes_Addons_WP_Widget_Contact_Form' );
	} 

	class Enovathemes_Addons_WP_Widget_Contact_Form extends WP_Widget {

		public function __construct() {
			parent::__construct(
				'fast_contact_widget',
				esc_html__('* Fast Contact Form', 'enovathemes-addons'),
				array( 'description' => esc_html__('Fast Contact Form widget', 'enovathemes-addons'))
			);
		}

		public function widget( $args, $instance ) {

			wp_enqueue_script('widget-contact-form');

			extract($args);

			$title 	      = apply_filters( 'widget_title', $instance['title'] );
			$submit_text  = isset($instance['submit_text']) ? esc_attr($instance['submit_text']) : esc_html__('Send', 'enovathemes-addons');
			$recipient    = isset($instance['recipient']) ? esc_attr($instance['recipient']) : get_option('admin_email');

			echo $before_widget;

				if ( ! empty( $title ) ){echo $before_title . $title . $after_title;}

				// wp_enqueue_script( 'widget-contact-form');

	            ?>

				<div class="contact-form">
					<form name="et-contact-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="et-contact-form" method="POST">
						<div>
							<input type="text" name="name" placeholder="<?php echo esc_html__('Name', 'enovathemes-addons'); ?>" value="" />
							<span class="alert warning"><?php echo esc_html__('Please enter your name', 'enovathemes-addons'); ?></span>
						</div>

						<div>
							<input type="text" name="email" placeholder="<?php echo esc_html__('Email', 'enovathemes-addons'); ?>" value="" />
							<span class="alert warning"><?php echo esc_html__('Invalid or empty email', 'enovathemes-addons'); ?></span>
						</div>

						<div class="message-div">
							<textarea name="message" placeholder="<?php echo esc_html__('Write your message', 'enovathemes-addons'); ?>"></textarea>
							<span class="alert warning"><?php echo esc_html__('Please enter your message', 'enovathemes-addons'); ?></span>
						</div>

						<div class="send-div">
							<input type="submit" value="<?php echo $submit_text; ?>" name="submit">
							<div class="sending"></div>
						</div>

		            	<div class="alert success final"><?php echo esc_html__('Your message successfully sent', 'enovathemes-addons'); ?></div>
		            	<div class="alert error final"><?php echo esc_html__('Something went wrong. Your message was not send', 'enovathemes-addons'); ?></div>

						<input type="hidden" name="action" value="et_contact_form">
						<input type="hidden" name="recipient" value="<?php echo $recipient; ?>">
		            	<?php wp_nonce_field( "et_contact_form_action", "et_contact_form_nonce", false, true ) ?>
		            </form>
        		</div>

			<?php echo $after_widget;
		}

	 	public function form( $instance ) {

	 		$defaults = array(
	 			'title'       => esc_html__('Fast contact', 'enovathemes-addons'),
	 			'submit_text' => esc_html__('Contact us', 'enovathemes-addons'),
	 			'recipient'   => get_option('admin_email'),
	 		);

	 		$instance = wp_parse_args((array) $instance, $defaults);

			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'enovathemes-addons' ); ?></label> 
				<input class="widefat" class="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('recipient'); ?>"><?php echo esc_html__( 'Recipient email:', 'enovathemes-addons' ); ?></label>
				<input class="widefat" class="<?php echo $this->get_field_id('recipient'); ?>" name="<?php echo $this->get_field_name('recipient'); ?>" type="text" value="<?php echo $instance['recipient']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('submit_text'); ?>"><?php echo esc_html__( 'Submit button text:', 'enovathemes-addons' ); ?></label>
				<input class="widefat" class="<?php echo $this->get_field_id('submit_text'); ?>" name="<?php echo $this->get_field_name('submit_text'); ?>" type="text" value="<?php echo $instance['submit_text']; ?>" />
			</p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']       = strip_tags( $new_instance['title'] );
			$instance['submit_text'] = strip_tags($new_instance['submit_text']);
			$instance['recipient'] = strip_tags($new_instance['recipient']);
			return $instance;
		}
	}
?>