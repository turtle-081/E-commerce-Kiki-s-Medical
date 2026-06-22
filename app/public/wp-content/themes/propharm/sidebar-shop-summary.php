<?php if(is_active_sidebar('shop-summary-widgets')): ?>
	<aside class='shop-summary-widgets widget-area'>  
		<?php if ( function_exists( 'dynamic_sidebar' )){dynamic_sidebar('shop-summary-widgets');} ?>
	</aside>
<?php endif ?>	