<?php if(is_active_sidebar('shop-single-widgets')): ?>
	<aside class='shop-single-widgets widget-area'>  
		<?php if ( function_exists( 'dynamic_sidebar' )){dynamic_sidebar('shop-single-widgets');} ?>
	</aside>
<?php endif ?>