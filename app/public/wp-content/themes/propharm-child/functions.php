<?php

function propharm_enovathemes_child_scripts() {
    wp_enqueue_style( 'propharm_enovathemes-parent-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'propharm_enovathemes_child_scripts' );

?>