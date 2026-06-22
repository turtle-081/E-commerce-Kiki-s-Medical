<?php

    add_action('widgets_init', 'register_product_search_widget');
    function register_product_search_widget(){
    	register_widget( 'Enovathemes_Addons_WP_Product_Search' );
    }

    class Enovathemes_Addons_WP_Product_Search extends WP_Widget {

    	public function __construct() {
    		parent::__construct(
    			'product_search_widget',
    			esc_html__('* Product ajax search', 'enovathemes-addons'),
    			array( 'description' => esc_html__('Product ajax search', 'enovathemes-addons'))
    		);
    	}

    	public function widget( $args, $instance) {

    		wp_enqueue_script('widget-product-search');

    		extract($args);

    		$title          = apply_filters( 'widget_title', $instance['title'] );
            $category       = $instance['category'] ? $instance['category'] : 'false';
            $in_category    = $instance['in_category'] ? $instance['in_category'] : 'false';
            $attribute      = $instance['attribute'] ? $instance['attribute'] : 'false';
            $attribute_term = $instance['attribute_term'] ? $instance['attribute_term'] : '';
            $SKU            = $instance['SKU'] ? $instance['SKU'] : 'false';
            $description    = $instance['description'] ? $instance['description'] : 'false';
            $cache          = (class_exists('SitePress') || function_exists('pll_the_languages')) ? false : true;

            $shop_link = (function_exists('wc_get_page_id')) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';
            if ('' === get_option( 'permalink_structure' )) {
                $shop_link = get_home_url().'?post_type=product';
            }
           
    		echo $before_widget;

    			if ( ! empty( $title ) ){echo $before_title . $title . $after_title;}

                $current_cat_id   = '';
                $current_cat_name = '';

                if ($in_category == "true") {
                    $current_cat = get_queried_object();
                    if (!is_wp_error($current_cat) && is_object($current_cat) && property_exists($current_cat,'taxonomy')) {
                        if ($current_cat->taxonomy == 'product_cat') {
                            $current_cat_id = $current_cat->term_id;
                            $current_cat_name = $current_cat->name;
                        }
                    }
                }

                $placeholder = esc_html__( 'What are you looking for?', 'enovathemes-addons' );


                $current_attribute = '';

                if (!empty($attribute) && !empty($attribute_term)) {
                    if (term_exists($attribute_term,'pa_'.$attribute)) {
                        $current_attribute = array($attribute_term,'pa_'.$attribute);
                        $current_attribute = 'data-in-term="'.implode(',',$current_attribute).'"';
                        $label = get_term_by('slug',$attribute_term,'pa_'.$attribute);
                        if (is_object($label)) {
                            $placeholder = esc_html__( 'Search in', 'enovathemes-addons' ).' '.$label->name;
                        }
                    }
                }

                if (!empty($current_cat_name)) {
                    $placeholder = esc_html__( 'Search in', 'enovathemes-addons' ).' '.$current_cat_name;
                }


                ?>

    			<div class="product-search hide-category-<?php echo esc_attr($category); ?>">
    				<form name="product-search" method="POST" data-in-category="<?php echo esc_attr($current_cat_id); ?>" <?php echo $current_attribute; ?> data-sku="<?php echo esc_attr($SKU); ?>" data-description="<?php echo esc_attr($description); ?>">
                        <?php $categories = get_product_categories_hierarchy($cache); ?>
                        <?php if ($categories): ?>
                            <select name="category" class="category">
                                <option class="default" value=""><?php echo esc_html__( 'Category', 'enovathemes-addons' ); ?></option>
                                <?php echo list_taxonomy_hierarchy_no_instance( $categories); ?>
                            </select>
                        <?php endif ?>
                        <div class="search-wrapper">
                            <input type="search" name="search" class="search" placeholder="<?php echo $placeholder; ?>" value="">
                            <?php if (et_get_theme_icon() && isset(et_get_theme_icon()['loading'])): ?>
                                <?php echo et_get_theme_icon()['loading']; ?>
                            <?php endif ?>
                        </div>
                        <input data-shop="<?php echo $shop_link; ?>" type="submit" value="<?php echo esc_html__( 'Search', 'enovathemes-addons' ); ?>" class="small et-button" />
                        <div class="input-after"></div>
                        <div class="search-results"></div>
    	            </form>
        		</div>

    		<?php echo $after_widget;
    	}

     	public function form( $instance ) {

     		$defaults = array(
                'title'       => esc_html__('Product search', 'enovathemes-addons'),
                'in_category' => 'false',
                'category'    => 'false',
                'attribute'   => 'false',
                'attribute_term'   => '',
                'SKU'         => 'false',
     			'description' => 'false',
     		);

     		$instance = wp_parse_args((array) $instance, $defaults);

    		?>

    		<div id="<?php echo esc_attr($this->get_field_id( 'widget_id' )); ?>">

    			<p>
    				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'enovathemes-addons' ); ?></label>
    				<input class="widefat <?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
    			</p>

                <p class="et-clearfix label-right">
                    <label for="<?php echo $this->get_field_id('in_category'); ?>"><?php echo esc_html__( 'Search within category by default, if in current category page', 'enovathemes-addons' ); ?>
                        <input class="checkbox" type="checkbox" <?php checked($instance['in_category'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('in_category'); ?>" name="<?php echo $this->get_field_name('in_category'); ?>" /> 
                    </label>
                </p>
                

                <p class="et-clearfix label-right">
                    <label for="<?php echo $this->get_field_id('category'); ?>"><?php echo esc_html__( 'Hide category select?', 'enovathemes-addons' ); ?>
                        <input class="checkbox" type="checkbox" <?php checked($instance['category'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" /> 
                    </label>
                </p>

                <p class="et-clearfix label-right">
                    <label for="<?php echo $this->get_field_id('SKU'); ?>"><?php echo esc_html__( 'Search in SKU?', 'enovathemes-addons' ); ?>
                        <input class="checkbox" type="checkbox" <?php checked($instance['SKU'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('SKU'); ?>" name="<?php echo $this->get_field_name('SKU'); ?>" /> 
                    </label>
                </p>

                <p class="et-clearfix label-right">
                    <label for="<?php echo $this->get_field_id('description'); ?>"><?php echo esc_html__( 'Search in product description', 'enovathemes-addons' ); ?>
                        <input class="checkbox" type="checkbox" <?php checked($instance['description'], 'true'); ?> value="true" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" /> 
                    </label>
                </p>

    		</div>

    		<?php
    	}

    	public function update( $new_instance, $old_instance ) {
    		$instance = $old_instance;
            $instance['title']       = strip_tags( $new_instance['title'] );
            $instance['in_category'] = strip_tags( $new_instance['in_category'] );
            $instance['category']    = strip_tags( $new_instance['category'] );
            $instance['attribute']   = strip_tags( $new_instance['attribute'] );
            $instance['attribute_term']   = strip_tags( $new_instance['attribute_term'] );
            $instance['SKU']         = strip_tags( $new_instance['SKU'] );
    		$instance['description'] = strip_tags( $new_instance['description'] );
    		return $instance;
    	}

    }

?>