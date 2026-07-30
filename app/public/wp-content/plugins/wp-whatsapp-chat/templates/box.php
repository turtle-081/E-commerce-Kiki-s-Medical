<?php
// Self-hydration: external consumers of this template (notably QLWAPP_PRO)
// were not updated when the contact-data cluster moved from Button to
// Contact[0]. If the caller did not pass $primary_contact the toggle would
// silently disappear — fall back to the model so the template stays usable.
if ( ! isset( $primary_contact ) && class_exists( '\\QuadLayers\\QLWAPP\\Models\\Contacts' ) ) {
	$primary_contact = \QuadLayers\QLWAPP\Models\Contacts::instance()->get_primary();
}
?>
<div id="qlwapp" class="qlwapp qlwapp-free <?php printf( 'qlwapp-%s qlwapp-%s qlwapp-%s qlwapp-%s', esc_attr( $button['layout'] ), esc_attr( $button['position'] ), esc_attr( $display['devices'] ), esc_attr( $button['rounded'] === 'yes' ? 'rounded' : 'square' ) ); ?>">
	<div class="qlwapp-container">
		<?php if ( $button['box'] === 'yes' ) : ?>
			<div class="qlwapp-box">
					<div class="qlwapp-header">
						<i class="qlwapp-close" data-action="close">&times;</i>
							<div class="qlwapp-description">
							<?php if ( ! empty( $box['header'] ) ) : ?>
								<div class="qlwapp-description-container">
									<?php echo wpautop( wp_kses_post( wpautop( $box['header'] ) ) ); ?>
								</div>
								<?php endif; ?>
							</div>
					</div>
				<div class="qlwapp-body">
					<?php if ( isset( $primary_contact ) ) : ?>
						<a class="qlwapp-account" data-action="open" data-phone="<?php echo qlwapp_format_phone( $primary_contact['phone'] ); ?>" data-message="<?php echo esc_html( $primary_contact['message'] ?? '' ); ?>" data-whatsapp-link-type="<?php echo esc_attr( $primary_contact['whatsapp_link_type'] ?? 'web' ); ?>" role="button" tabindex="0" target="_blank">
							<?php if ( ! empty( $primary_contact['avatar'] ) ) : ?>
								<div class="qlwapp-avatar">
									<div class="qlwapp-avatar-container">
										<img alt="<?php printf( '%s %s', esc_html( $primary_contact['firstname'] ?? '' ), esc_html( $primary_contact['lastname'] ?? '' ) ); ?>" src="<?php echo esc_url( $primary_contact['avatar'] ); ?>" <?php echo ( $box['lazy_load'] === 'yes' ) ? 'loading="lazy"' : ''; ?>>
									</div>
								</div>
							<?php endif; ?>
							<div class="qlwapp-info">
								<span class="qlwapp-label"><?php echo esc_html( $primary_contact['label'] ?? '' ); ?></span>
								<span class="qlwapp-name"><?php printf( '%s %s', esc_html( $primary_contact['firstname'] ?? '' ), esc_html( $primary_contact['lastname'] ?? '' ) ); ?></span>
							</div>
						</a>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $box['footer'] ) ) : ?>
					<div class="qlwapp-footer">
						<?php echo wpautop( wp_kses_post( $box['footer'] ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $primary_contact ) ) : ?>
		<a class="qlwapp-toggle" data-action="<?php echo ( $button['box'] === 'yes' ? 'box' : 'open' ); ?>" data-phone="<?php echo qlwapp_format_phone( $primary_contact['phone'] ); ?>" data-message="<?php echo esc_html( $primary_contact['message'] ?? '' ); ?>" data-whatsapp-link-type="<?php echo esc_attr( $primary_contact['whatsapp_link_type'] ?? 'web' ); ?>" role="button" tabindex="0" target="_blank">
			<?php if ( $button['icon'] ) : ?>
				<i class="qlwapp-icon <?php echo esc_attr( $button['icon'] ); ?>"></i>
			<?php endif; ?>
			<i class="qlwapp-close" data-action="close">&times;</i>
			<?php if ( $button['text'] ) : ?>
				<span class="qlwapp-text"><?php echo esc_html( $button['text'] ); ?></span>
			<?php endif; ?>
		</a>
		<?php endif; ?>
	</div>
</div>
