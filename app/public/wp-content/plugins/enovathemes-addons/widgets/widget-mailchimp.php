<?php

add_action('widgets_init', 'enovathemes_addons_register_mailchimp_widget');
function enovathemes_addons_register_mailchimp_widget(){
	register_widget( 'Enovathemes_Addons_WP_Widget_Mailchimp' );
}

class  Enovathemes_Addons_WP_Widget_Mailchimp extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'mailchimp',
			esc_html__('* Mailchimp', 'enovathemes-addons'),
			array( 'description' => esc_html__('Mailchimp form', 'enovathemes-addons'))
		);
	}

	public function widget( $args, $instance ) {

		extract($args);

		$output = "";

		$title         = apply_filters( 'widget_title', $instance['title'] );
		$terms         = $instance['terms'] ? $instance['terms'] : '';
		$description   = $instance['description'] ? $instance['description'] : '';
		$first_name    = $instance['first_name'] ? $instance['first_name'] : 'false';
		$last_name     = $instance['last_name'] ? $instance['last_name'] : 'false';
		$phone         = $instance['phone'] ? $instance['phone'] : 'false';

		$required_first_name = $instance['required_first_name'] ? $instance['required_first_name'] : 'false';
		$required_last_name  = $instance['required_last_name'] ? $instance['required_last_name'] : 'false';
		$required_phone      = $instance['required_phone'] ? $instance['required_phone'] : 'false';

		$required_first_name = ($required_first_name == "true") ? 'data-required="true"' :  'data-required="false"'; 
		$required_last_name = ($required_last_name == "true") ? 'data-required="true"' :  'data-required="false"'; 
		$required_phone = ($required_phone == "true") ? 'data-required="true"' :  'data-required="false"'; 


		$list = $instance['list'] ? $instance['list'] : '';

		$output .= $before_widget;

		if ( ! empty( $title ) ){$output .= $before_title . $title . $after_title;}
		$output .='<div class="mailchimp-form">';
			if (!empty($description)) {
				$output .= '<p class="mailchimp-description">'.$description.'</p>';
			}
			$output .='<form class="et-mailchimp-form" name="et-mailchimp-form" action="'.esc_url( admin_url('admin-post.php') ).'" method="POST">';

				if ($first_name == "true") {
					$output .='<div>';
						$output .='<input '.$required_first_name.' class="field" type="text" value="" name="fname" placeholder="'.esc_html__("First name", 'enovathemes-addons').'">';
						$output .='<span class="alert warning">'.esc_html__('Please enter your First name', 'enovathemes-addons').'</span>';
					$output .='</div>';
				}

				if ($last_name == "true") {
					$output .='<div>';
						$output .='<input '.$required_last_name.' class="field" type="text" value="" name="lname" placeholder="'.esc_html__("Last name", 'enovathemes-addons').'">';
						$output .='<span class="alert warning">'.esc_html__('Please enter your Last name', 'enovathemes-addons').'</span>';
					$output .='</div>';
				}

				if ($phone == "true") {
					$output .='<div>';
						$output .='<input '.$required_phone.' class="field" type="text" value="" name="phone" placeholder="'.esc_html__("Phone number", 'enovathemes-addons').'">';
						$output .='<span class="alert warning">'.esc_html__('Please enter your Phone number', 'enovathemes-addons').'</span>';
					$output .= '</div>';
				}

				$output .='<div class="field-wrap">';
					$output .='<input type="text" value="" class="field" name="email" placeholder="'.esc_html__("Email address", 'enovathemes-addons').'">';
					$output .='<span class="alert warning">'.esc_html__('Invalid or empty email', 'enovathemes-addons').'</span>';
				$output .= '</div>';
				
				$output .='<input type="hidden" value="'.$list.'" name="list">';
				$output .='<input type="hidden" name="action" value="et_mailchimp" />';
				$output .='<div class="send-div">';
			    	$output .='<button type="submit" class="button" name="subscribe">'.esc_html__('Subscribe', 'enovathemes-addons').'</button>';
				$output .='</div>';
			    $output .='<div class="et-mailchimp-success alert final success">'.esc_html__('You have successfully subscribed to the newsletter.', 'enovathemes-addons').'</div>';
		        $output .='<div class="et-mailchimp-error alert final error">'.esc_html__('Something went wrong. Your subscription failed.', 'enovathemes-addons').'</div>';
		        $output .='<div class="et-mailchimp-terms-error alert final error">'.esc_html__('Please accept terms and conditions & privacy policy.', 'enovathemes-addons').'</div>';
		        
		        $element_id = rand(1,1000000);

				$output .='<input type="hidden" name="id" value="'.$element_id.'" />';
		        $output .= wp_nonce_field( "et_mailchimp_action", "et_mailchimp_nonce_".$element_id, false, false );
				$output .='<div class="sending"></div>';
			$output .='</form>';
			if (isset($terms) && !empty($terms)) {
				$output .='<div class="terms">'.sprintf('%s <a href="%s">%s</a>',esc_html__('I accept','enovathemes-addons'),esc_url( $terms ),esc_html__('terms and conditions & privacy policy','enovathemes-addons')).'</div>';
			}
		$output .='</div>';
		$output .= $after_widget;
		echo $output;
	}

 	public function form( $instance ) {

		$defaults = array(
 			'title'       => esc_html__('Subscribe', 'enovathemes-addons'),
 			'description' => '',
 			'terms'       => '',
 			'list'       => '',
 			'first_name'  => 'false',
 			'last_name'   => 'false',
 			'phone'       => 'false',
 			'required_first_name'  => 'false',
 			'required_last_name'   => 'false',
 			'required_phone'       => 'false',
 		);

 		$instance = wp_parse_args((array) $instance, $defaults);

 		$list_array = enovathemes_addons_mailchimp_list();

 		?>


			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'enovathemes-addons' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>

			<?php if ( is_wp_error( $list_array ) ): ?>
				<p><?php echo wp_kses_post( $list_array->get_error_message() ); ?></p>
			<?php else: ?>
				<p>
					<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php echo esc_html__( 'List:', 'enovathemes-addons' ); ?></label> 
					<select class="widefat" id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" >
					<?php foreach ( $list_array as $list ) { ?>
						<option value="<?php echo $list['id']; ?>" <?php selected( $instance['list'], $list['id'] ); ?>><?php echo $list['name']; ?></option>
					<?php } ?>
					</select>
				</p>
			<?php endif; ?>

			<p>
				<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php echo esc_html__( 'Description:', 'enovathemes-addons' ); ?></label> 
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text"><?php echo $instance['description']; ?></textarea>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'terms' ); ?>"><?php echo esc_html__( 'Terms &  conditions page link:', 'enovathemes-addons' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'terms' ); ?>" name="<?php echo $this->get_field_name( 'terms' ); ?>" type="url" value="<?php echo $instance['terms']; ?>" />
			</p>

			<p class="et-clearfix label-right">
				<label for="<?php echo $this->get_field_id('first_name'); ?>"><?php echo esc_html__( 'Show first name field', 'enovathemes-addons' ); ?>
					<input class="checkbox" type="checkbox" <?php checked($instance['first_name'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('first_name'); ?>" name="<?php echo $this->get_field_name('first_name'); ?>" /> 
				</label>
				<label for="<?php echo $this->get_field_id('required_first_name'); ?>"><?php echo esc_html__( 'Required?', 'enovathemes-addons' ); ?>
					<input class="checkbox" type="checkbox" <?php checked($instance['required_first_name'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('required_first_name'); ?>" name="<?php echo $this->get_field_name('required_first_name'); ?>" /> 
				</label>
			</p>

			<p class="et-clearfix label-right">
				<label for="<?php echo $this->get_field_id('last_name'); ?>"><?php echo esc_html__( 'Show last name field', 'enovathemes-addons' ); ?>
					<input class="checkbox" type="checkbox" <?php checked($instance['last_name'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('last_name'); ?>" name="<?php echo $this->get_field_name('last_name'); ?>" /> 
				</label>
				<label for="<?php echo $this->get_field_id('required_last_name'); ?>"><?php echo esc_html__( 'Required?', 'enovathemes-addons' ); ?>
					<input class="checkbox" type="checkbox" <?php checked($instance['required_last_name'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('required_last_name'); ?>" name="<?php echo $this->get_field_name('required_last_name'); ?>" /> 
				</label>
			</p>

			<p class="et-clearfix label-right">
				<label for="<?php echo $this->get_field_id('phone'); ?>"><?php echo esc_html__( 'Show phone field', 'enovathemes-addons' ); ?>
					<input class="checkbox" type="checkbox" <?php checked($instance['phone'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" /> 
				</label>
				<label for="<?php echo $this->get_field_id('required_phone'); ?>"><?php echo esc_html__( 'Required?', 'enovathemes-addons' ); ?>
					<input class="checkbox" type="checkbox" <?php checked($instance['required_phone'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('required_phone'); ?>" name="<?php echo $this->get_field_name('required_phone'); ?>" /> 
				</label>
			</p>

		
	<?php }

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['first_name']  = strip_tags( $new_instance['first_name'] );
		$instance['last_name']   = strip_tags( $new_instance['last_name'] );
		$instance['description'] = strip_tags( $new_instance['description']);
		$instance['phone']       = strip_tags( $new_instance['phone']);
		$instance['list']        = strip_tags( $new_instance['list']);
		$instance['terms']       = strip_tags( $new_instance['terms']);
		$instance['required_first_name']  = strip_tags( $new_instance['required_first_name'] );
		$instance['required_last_name']   = strip_tags( $new_instance['required_last_name'] );
		$instance['required_phone']       = strip_tags( $new_instance['required_phone']);
		return $instance;
	}
}

?>