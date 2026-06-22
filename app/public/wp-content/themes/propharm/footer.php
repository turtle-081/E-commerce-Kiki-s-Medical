			<?php do_action('propharm_enovathemes_footer'); ?>
		</div>
		<!-- page-content-wrap end -->

		<?php if (is_page()){

			$page_back_video = get_post_meta( get_the_ID(), 'enovathemes_addons_page_back_video', true );

			if (!empty($page_back_video)) {
				$output ='<video poster="'.PROPHARM_ENOVATHEMES_IMAGES.'/transparent.png'.'" class="page-background-video" autoplay preload="auto" loop="loop" muted="muted">';
                    $output .='<source src="'.$page_back_video.'" type="video/mp4">';
                $output .='</video>';

                echo propharm_enovathemes_output_html($output);
            }

		} ?>


	</div>
	<!-- wrap end -->
</div>
<!-- general wrap end -->
<?php do_action('propharm_enovathemes_move_top'); ?>
<?php do_action('propharm_enovathemes_custom_cursor'); ?>
<?php wp_footer(); ?>
</body>
</html>