<?php

if ( ! class_exists( 'Redux' ) ) {
    return;
}

$opt_name = "propharm_enovathemes";
$theme    = wp_get_theme();

$args = array(
    'opt_name'             => $opt_name,
    'display_name'         => $theme->get( 'Name' ),
    'display_version'      => $theme->get( 'Version' ),
    'menu_type'            => 'submenu',
    'allow_sub_menu'       => true,
    'menu_title'           => esc_html__('Theme settings', 'enovathemes-addons'),
    'page_title'           => esc_html__('Theme settings', 'enovathemes-addons'),
    'google_api_key'       => 'AIzaSyD4_siUiwNbGDKcVNPQjCl-6eyzhctrPsM',
    'google_update_weekly' => true,
    'async_typography'     => true,
    'admin_bar'            => true,
    'admin_bar_icon'       => '',
    'admin_bar_priority'   => 50,
    'global_variable'      => 'propharm_enovathemes',
    'dev_mode'             => false,
    'update_notice'        => false,
    'customizer'           => false,
    'page_priority'        => null,
    'page_parent'          => 'themes.php',
    'page_permissions'     => 'manage_options',
    'menu_icon'            => '',
    'last_tab'             => '',
    'page_icon'            => 'icon-themes',
    'page_slug'            => 'enovathemes',
    'save_defaults'        => true,
    'default_show'         => false,
    'default_mark'         => '',
    'show_import_export'   => false
);

Redux::setArgs( $opt_name, $args );

if ( ! function_exists( 'remove_demo' ) ) {
    function remove_demo() {
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
}

$inc = 123;


/* General
---------------*/

    Redux::setSection( $opt_name, array(
		'title'      => esc_html__('General', 'enovathemes-addons'),
		'id'         => esc_html__('sec_general', 'enovathemes-addons'),
		'icon_class' => 'icon-small',
	    'icon'       => 'el-icon-wrench',
	    'fields' => array(
	    	array(
				'id'       =>'disable-gutenberg',
				'type'     => 'switch',
				'title'    => esc_html__('Disable gutenberg', 'enovathemes-addons'),
				'subtitle' => esc_html__('By default WordPress comes with new block editor "Gutenberg". If you want classic editor or Visual Composer, make sure this option is active', 'enovathemes-addons'),
				"default"  => 1
			),
			array(
				'id'       =>'disable-gutenberg-type',
				'type'     => 'checkbox',
				'title'    => esc_html__('Choose post types to disable Gutenberg', 'enovathemes-addons'),
				'options'  => array(
			        'post' => esc_html__('Posts', 'enovathemes-addons'),
			        'page' => esc_html__('Pages', 'enovathemes-addons'),
			        'product' => esc_html__('Products', 'enovathemes-addons'),
			        'widgets' => esc_html__('Widgets', 'enovathemes-addons'),
			    ),
			    'default' => array(
			        'post' => '1',
			        'page' => '1',
			        'widgets' => '1',
			        'product' => '1'
    			),
			    'required' => array('disable-gutenberg','equals',1)
			),
	    	array(
				'id'       =>'disable-defaults',
				'type'     => 'switch',
				'class'    => 'hidden-field',
				'title'    => esc_html__('Turn off default styling', 'enovathemes-addons'),
				"default"  => 1
			),
			array(
				'id'       =>'plugins-combined',
				'type'     => 'switch',
				'title'    => esc_html__('Load combined js plugins file', 'enovathemes-addons'),
				"default"  => 1
			),
			array(
				'id'       =>'lazy',
				'type'     => 'switch',
				'title'    => esc_html__('Load images lazy?', 'enovathemes-addons'),
				"default"  => 1
			),
			array(
				'id'          =>'cache-queries',
				'type'        => 'switch',
				'title'       => esc_html__('Cache custom queries?', 'enovathemes-addons'),
				'description' => esc_html__('Enable this option to speed up ajax requests and if you use caching plugins, such as WP Total Cache', 'enovathemes-addons'),
				"default"     => 0
			),
	    	array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('Colors', 'enovathemes-addons')
			),
			array(
				'id'       =>'main-color',
				'type'     => 'color',
				'title'    => esc_html__('Main color', 'enovathemes-addons'),
				'default'  => '#15a9e3',
                'transparent' => false
			),
			array(
				'id'       =>'area-color',
				'type'     => 'color',
				'title'    => esc_html__('Area color', 'enovathemes-addons'),
				'default'  => '#edf4f6',
                'transparent' => false
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('Layout settings', 'enovathemes-addons')
			),
	    	array(
				'id'        =>'layout',
				'type'      => 'radio',
				'title'     => esc_html__('Layout', 'enovathemes-addons'),
				'subtitle'  => esc_html__('Boxed layout allows you to display the whole website in the box. (works on screens larger than 1200px wide). Make sure your navigation is not sidebar', 'enovathemes-addons'),
				'options'   => array(
					'wide'  => esc_html__('Wide', 'enovathemes-addons'),
					'boxed' => esc_html__('Boxed', 'enovathemes-addons'),
				),
				'default' => 'wide',
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('Site background settings', 'enovathemes-addons'),
			    'required' => array('layout','equals','boxed')
			),
	    	array(
				'id'       =>'site-background',
				'type'     => 'background',
				'title'    => esc_html__('Site background options', 'enovathemes-addons'),
				'required' => array('layout','equals','boxed')
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('Footer settings', 'enovathemes-addons')
			),
			array(
				'id'   => 'warning-info-'.$inc++,
				'class'=> 'warning-info',
				'type' => 'info',
				'style' => 'warning',
				'desc' => esc_html__('Important! If you do not see any option, first you must', 'enovathemes-addons').' <a href="'.esc_url(home_url('/')).'wp-admin/post-new.php?post_type=footer">'.esc_html__("create a footer", "enovathemes-addons").'</a>'
			),
			array(
				'id'=>'footer-id',
				'type' => 'select',
				'data' => 'posts',
				'args' => array('post_type' => 'footer', 'posts_per_page' => -1),
				'title'    => esc_html__('Footer', 'enovathemes-addons'),
			),
			array(
				'id'=>'footer-id-wpml',
				'type' => 'text',
				'class'    => 'wpml-on',
				'title'    => esc_html__('WPML footer per language', 'enovathemes-addons'),
				'description'    => esc_html__('Specify footer for each language with the following format language code:footer id, separate multiple by | (example: en:7846|de:54568)', 'enovathemes-addons'),
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('Header settings', 'enovathemes-addons')
			),
			array(
				'id'   => 'warning-info-'.$inc++,
				'class'=> 'warning-info',
				'type' => 'info',
				'style' => 'warning',
				'desc' => esc_html__('Important! If you do not see any option, first you must', 'enovathemes-addons').' <a href="'.esc_url(home_url('/')).'wp-admin/post-new.php?post_type=header">'.esc_html__("create a header", "enovathemes-addons").'</a>'
			),
			array(
				'id'=>'header-desktop-id',
				'type' => 'select',
				'data' => 'posts',
				'args' => array('post_type' => 'header', 'posts_per_page' => -1),
				'title'    => esc_html__('Desktop header', 'enovathemes-addons'),
			),
			array(
				'id'=>'header-desktop-id-wpml',
				'type' => 'text',
				'class'    => 'wpml-on',
				'title'    => esc_html__('WPML desktop header per language', 'enovathemes-addons'),
				'description'    => esc_html__('Specify desktop header for each language with the following format language code:header id, separate multiple by | (example: en:7846|de:54568)', 'enovathemes-addons'),
			),
			array(
				'id'=>'header-mobile-id',
				'type' => 'select',
				'data' => 'posts',
				'args' => array('post_type' => 'header', 'posts_per_page' => -1),
				'title'    => esc_html__('Mobile header', 'enovathemes-addons'),
			),
			array(
				'id'=>'header-mobile-id-wpml',
				'type' => 'text',
				'class'    => 'wpml-on',
				'title'    => esc_html__('WPML mobile header per language', 'enovathemes-addons'),
				'description'    => esc_html__('Specify mobile header for each language with the following format language code:header id, separate multiple by | (example: en:7846|de:54568)', 'enovathemes-addons'),
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('Page title section settings', 'enovathemes-addons')
			),
			array(
				'id'   => 'warning-info-'.$inc++,
				'class'=> 'warning-info',
				'type' => 'info',
				'style' => 'warning',
				'desc' => esc_html__('Important! If you do not see any option, first you must', 'enovathemes-addons').' <a href="'.esc_url(home_url('/')).'wp-admin/post-new.php?post_type=title_section">'.esc_html__("create a title section", "enovathemes-addons").'</a>'
			),
			array(
				'id'=>'title-section-id',
				'type' => 'select',
				'data' => 'posts',
				'args' => array('post_type' => 'title_section', 'posts_per_page' => -1),
				'title'    => esc_html__('Page title section', 'enovathemes-addons'),
			),
			array(
				'id'=>'title-section-id-wpml',
				'type' => 'text',
				'class'    => 'wpml-on',
				'title'    => esc_html__('WPML title section per language', 'enovathemes-addons'),
				'description'    => esc_html__('Specify title section for each language with the following format language code:title section id, separate multiple by | (example: en:7846|de:54568)', 'enovathemes-addons'),
			),
			array(
				'id'=>'error-id',
				'type' => 'select',
				'data' => 'posts',
				'args' => array('post_type' => 'page', 'posts_per_page' => -1),
				'title'    => esc_html__('404 error page custom content', 'enovathemes-addons'),
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('One page navigation settings', 'enovathemes-addons')
			),
			array(
				'id'       =>'one-page-filter',
				'type'     => 'text',
				'title'    => esc_html__('One page menu filter', 'enovathemes-addons'),
				'subtitle'=> esc_html__("Exclude links from one page menu by entering comma-separated menu items' ids", 'enovathemes-addons'),
			),
			array(
			    'id'   => 'info_normal_'.$inc++,
				'class'=> 'info-normal',
			    'type' => 'info',
			    'desc' => esc_html__('API settings', 'enovathemes-addons')
			),
			array(
				'id'      =>'mailchimp-api-key',
				'type'     => 'text',
				'title'    => esc_html__('Mailchimp API key', 'enovathemes-addons'),
				'subtitle' => esc_html__("If you are not sure how to retrieve this, follow this link from MailChimp Knowledge Base to retrieve your account's API key", 'enovathemes-addons').' <a href="http://kb.mailchimp.com/accounts/management/about-api-keys#Finding-or-generating-your-API-key" target="_blank">'.esc_html__("Retrieve your account's API key", 'enovathemes-addons').'</a>',
			),
			array(
				'id'       =>'flickr-api',
				'type'     => 'text',
				'title'    => esc_html__("Flickr API Key", 'enovathemes-addons'),
				'subtitle' => esc_html__("If you are not sure how to retrieve this, follow this link from Flickr Knowledge Base to retrieve your account's API key", 'enovathemes-addons').' <a href="https://www.flickr.com/services/developer/api/" target="_blank">'.esc_html__("Retrieve your account's API key", 'enovathemes-addons').'</a>',
			),
			array(
				'id'       =>'instagram',
				'type'     => 'switch',
				'title'    => esc_html__('Disable theme styles for Smash Balloon Instagram Feed plugin', 'enovathemes-addons'),
				'description' => esc_html__('If you want to use pro version features of Smash Balloon Instagram Feed plugin, activate this setting, it will disable theme styles.', 'enovathemes-addons'),
				"default"  => 1,
			),
			array(
				'id'       =>'mtt',
				'type'     => 'switch',
				'title'    => esc_html__('Move to top arrow', 'enovathemes-addons'),
				'subtitle' => esc_html__('Toggle this option if you want to have move to top arrow', 'enovathemes-addons'),
				"default"  => 1
			)
	    )
	));

/* CSS
---------------*/

	Redux::setSection( $opt_name, array(
		'title'      => esc_html__('CSS', 'enovathemes-addons'),
		'icon_class' => 'icon-small',
	    'icon'       => 'el-icon-star',
	    'fields'     => array(
	    	array(
	            'id'       => 'custom-css',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				// 'class'    => 'hidden-field',
				'theme'    => 'monokai',
	            'title'    => esc_html__('Custom CSS Styles', 'enovathemes-addons'),
	            'subtitle' => esc_html__('Enter custom css code here.', 'enovathemes-addons')
	        ),
	        array(
	            'id'       => 'custom-css-max-374',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(max-width: 374px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-375',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 375px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-375-max-767',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 375px) and (max-width: 767px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-max-767',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(max-width: 767px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-768',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 768px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-768-max-1023',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 768px) and (max-width: 1023px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-max-1023',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(max-width: 1023px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-1024',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 1024px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-1024-max-1279',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 1024px) and (max-width: 1279px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-max-1279',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(max-width: 1279px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-1280',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 1280px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-1280-max-1599',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 1280px) and (max-width: 1599px)', 'enovathemes-addons'),
	        ),
	        array(
	            'id'       => 'custom-css-min-1600-max-1919',
	            'type'     => 'ace_editor',
				'mode'     => 'css',
				'theme'    => 'monokai',
	            'title'    => esc_html__('(min-width: 1600px) and (max-width: 1919px)', 'enovathemes-addons'),
	        ),
	    )
	));

/* Typography
---------------*/

	Redux::setSection( $opt_name, array(
		'title'      => esc_html__('Typography', 'enovathemes-addons'),
		'icon_class' => 'icon-small',
	    'icon'       => 'el-icon-font',
	    'fields'     => array(
	    	array(
				'id'       =>'main-typo',
				'type'     => 'typography',
				'title'    => esc_html__('Main typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => true,
				'font-style'     => false,
				'font-weight'    => true,
				'color'          => true,
				'text-align'     => false,
				'font-family'    => true,
				'default'     => array(
					'font-family'    => 'PT Sans',
			        'font-size'      => '14px',
			        'font-weight'    => '400',
			        'line-height'    => '24px',
			        'letter-spacing' => '0',
			        'color'          => '#56778f',
			    )
			),

			array(
				'id'       =>'headings-typo',
				'type'     => 'typography',
				'title'    => esc_html__('Headings typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => true,
				'letter-spacing' => true,
				'line-height'    => false,
				'font-style'     => false,
				'font-size'      => false,
				'font-weight'    => true,
				'color'          => true,
				'text-align'     => false,
				'font-family'    => true,
				'default'     => array(
					'font-family'    => 'PT Sans',
			        'font-weight'    => '700',
			        'letter-spacing' => '0',
			        'color'          => '#184363'
			    )
			),

			array(
				'id'       =>'h1-typo',
				'type'     => 'typography',
				'title'    => esc_html__('H1 typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => false,
				'line-height'    => true,
				'font-style'     => false,
				'font-size'      => true,
				'font-weight'    => false,
				'color'          => false,
				'text-align'     => false,
				'font-family'    => false,
				'default'     => array(
			        'font-size'   => '48px',
			        'line-height' => '52px'
			    )
			),

			array(
				'id'       =>'h2-typo',
				'type'     => 'typography',
				'title'    => esc_html__('H2 typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => false,
				'line-height'    => true,
				'font-style'     => false,
				'font-size'      => true,
				'font-weight'    => false,
				'color'          => false,
				'text-align'     => false,
				'font-family'    => false,
				'default'     => array(
			        'font-size'   => '40px',
			        'line-height' => '46px'
			    )
			),

			array(
				'id'       =>'h3-typo',
				'type'     => 'typography',
				'title'    => esc_html__('H3 typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => false,
				'line-height'    => true,
				'font-style'     => false,
				'font-size'      => true,
				'font-weight'    => false,
				'color'          => false,
				'text-align'     => false,
				'font-family'    => false,
				'default'     => array(
			        'font-size'   => '32px',
			        'line-height' => '36px'
			    )
			),

			array(
				'id'       =>'h4-typo',
				'type'     => 'typography',
				'title'    => esc_html__('H4 typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => false,
				'line-height'    => true,
				'font-style'     => false,
				'font-size'      => true,
				'font-weight'    => false,
				'color'          => false,
				'text-align'     => false,
				'font-family'    => false,
				'default'     => array(
			        'font-size'   => '24px',
			        'line-height' => '32px'
			    )
			),

			array(
				'id'       =>'h5-typo',
				'type'     => 'typography',
				'title'    => esc_html__('H5 typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => false,
				'line-height'    => true,
				'font-style'     => false,
				'font-size'      => true,
				'font-weight'    => false,
				'color'          => false,
				'text-align'     => false,
				'font-family'    => false,
				'default'     => array(
			        'font-size'   => '20px',
			        'line-height' => '28px'
			    )
			),

			array(
				'id'       =>'h6-typo',
				'type'     => 'typography',
				'title'    => esc_html__('H6 typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'text-transform' => false,
				'letter-spacing' => false,
				'line-height'    => true,
				'font-style'     => false,
				'font-weight'    => false,
				'font-size'      => true,
				'color'          => false,
				'text-align'     => false,
				'font-family'    => false,
				'default'     => array(
			        'font-size'   => '18px',
			        'line-height' => '26px'
			    )
			),
        )
	));

/* Forms
---------------*/

	Redux::setSection( $opt_name, array(
		'title'      => esc_html__('Forms', 'enovathemes-addons'),
		'icon_class' => 'icon-small',
	    'icon'       => 'el-icon-tasks',
	    'fields'     => array(
			array(
				'id'       =>'form-text-color',
				'type'     => 'link_color',
				'title'    => esc_html__('Forms fields text colors', 'enovathemes-addons'),
				'visited'  => false,
				'active'    => false,
				'default'  => array(
			        'regular' => '#184363',
			        'hover'   => '#184363',
			    )
			),
			array(
				'id'       =>'form-back-color',
				'type'     => 'link_color',
				'title'    => esc_html__('Forms fields background colors', 'enovathemes-addons'),
				'visited'  => false,
				'active'    => false,
				'default'   => array(
			        'regular' => '#edf4f6',
			        'hover'   => '#ffffff',
			    )
			),
			array(
				'id'       =>'form-border-color',
				'type'     => 'link_color',
				'title'    => esc_html__('Forms fields border colors', 'enovathemes-addons'),
				'visited'  => false,
				'active'    => false,
				'default'   => array(
			        'regular' => '#edf4f6',
			        'hover'   => '#15a9e3',
			    )
			),
			array(
				'id'       =>'form-button-typo',
				'type'     => 'typography',
				'title'    => esc_html__('Button typography', 'enovathemes-addons'),
				'units'          => 'px',
				'google'         => true,
				'subsets'        => true,
				'all_styles'     => true,
				'font-weight'    => true,
				'font-size'      => false,
				'font-family'    => true,
				'letter-spacing' => true,
				'text-transform' => true,
				'line-height'    => false,
				'font-style'     => false,
				'color'          => false,
				'text-align'     => false,
				'text-transform' => false,
				'word-spacing'   => false,
				'default'     => array(
					'font-weight'    => '700',
					'font-family'    => 'PT Sans',
					'letter-spacing' => '0',
			    )
			),
			array(
				'id'       => 'form-button-back',
				'type'     => 'link_color',
				'active'   => false,
				'visited'  => false,
				'title'    => esc_html__('Button background colors', 'enovathemes-addons'),
				'default'  => array(
					'regular'  => '#f2971f',
					'hover'    => '#184363'
				)
			),
			array(
				'id'       =>'form-button-color',
				'type'     => 'link_color',
				'active'   => false,
				'visited'  => false,
				'title'    => esc_html__('Button text colors', 'enovathemes-addons'),
				'default'  => array(
					'regular'  => '#ffffff',
					'hover'    => '#ffffff'
				)
			),
		)
	));

/* Blog
---------------*/

	global $wpdb;

	$querystr = "
	    SELECT $wpdb->posts.*
	    FROM $wpdb->posts, $wpdb->postmeta
	    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
	    AND $wpdb->posts.post_status = 'publish'
	    AND $wpdb->posts.post_type = 'title_section'
	    ORDER BY $wpdb->posts.post_title ASC
	";

	$title_sections = $wpdb->get_results($querystr, OBJECT);

	$title_sections_array = array(
		'none'    => esc_html__( 'None', 'enovathemes-addons' ),
		'default' => esc_html__( 'Default', 'enovathemes-addons' ),
		'inherit' => esc_html__( 'Inherit', 'enovathemes-addons' ),
	);

    if($title_sections){

    	foreach ($title_sections as $title_section) {
    		$title_section_id    = $title_section->ID;
    		$title_section_title = $title_section->post_title;
    		$title_sections_array[$title_section_id] = $title_section_title;
    	}

    }

	Redux::setSection( $opt_name, array(
		'title'      => esc_html__('Blog', 'enovathemes-addons'),
		'icon_class' => 'icon-small',
	    'icon'       => 'el-icon-pencil',
	    'fields' => array(
			array(
				'id'=>'blog-title',
				'type' => 'select',
				'title' => esc_html__('Choose title section', 'enovathemes-addons'),
				'options' => $title_sections_array,
				'default' => 'inherit',
			),
			array(
				'id'=>'blog-title-single',
				'type' => 'select',
				'title' => esc_html__('Choose title section for single post pages', 'enovathemes-addons'),
				'options' => $title_sections_array,
				'default' => 'inherit',
			),
			array(
				'id'      =>'blog-title-text',
				'type'    => 'text',
				'title'   => esc_html__('Blog title', 'enovathemes-addons'),
				'default' => 'Blog',
			),
			array(
				'id'      =>'blog-subtitle-text',
				'type'    => 'text',
				'title'   => esc_html__('Blog subtitle', 'enovathemes-addons'),
				'default' => '',
			),
			array(
				'id'        =>'blog-sidebar',
				'type'      => 'select',
				'title'     => esc_html__('Blog sidebar position', 'enovathemes-addons'),
				'options'   => array(
					'none'  => esc_html__('None', 'enovathemes-addons'),
					'left'  => esc_html__('Left', 'enovathemes-addons'),
					'right' => esc_html__('Right', 'enovathemes-addons'),
				),
				'default' => 'none',
			),
			array(
				'id'        =>'blog-navigation',
				'type'      => 'select',
				'title'     => esc_html__('Blog navigation', 'enovathemes-addons'), 
				'options'   => array(
					'pagination' => esc_html__('Pagination', 'enovathemes-addons'), 
					'loadmore'   => esc_html__('Load more', 'enovathemes-addons'), 
					'infinite'   => esc_html__('Infinite load', 'enovathemes-addons'),
				),
				'default' => 'pagination',
			),
			array(
				'id'       => 'blog-post-layout',
				'type'     => 'image_select',
				'title'    => esc_html__('Blog post layout', 'enovathemes-addons'),
				'width'    => '140',
				'height'   => '140',
				'options'  => array(
					'grid' => array(
						'alt'   => esc_html__('Grid', 'enovathemes-addons'),
						'title' => esc_html__('Grid', 'enovathemes-addons'),
						'img'   => THEME_IMG.'grid.png'
					),
					'masonry' => array(
						'alt'   => esc_html__('Masonry', 'enovathemes-addons'),
						'title' => esc_html__('Masonry', 'enovathemes-addons'),
						'img'   => THEME_IMG.'masonry.png'
					),
					'list' => array(
						'alt'   => esc_html__('List', 'enovathemes-addons'),
						'title' => esc_html__('List', 'enovathemes-addons'),
						'img'   => THEME_IMG.'list.png'
					),
					'full' => array(
						'alt'   => esc_html__('Full', 'enovathemes-addons'),
						'title' => esc_html__('Full', 'enovathemes-addons'),
						'img'   => THEME_IMG.'full.png'
					),
				),
				'default' => 'masonry'
			),
			array(
				'id'       =>'blog-image-full',
				'type'     => 'switch',
				'title'    => esc_html__('Use original image size (no cropping)', 'enovathemes-addons'),
				"default"  => 0,
				'required' => array('blog-post-layout','equals',array('grid','masonry'))
			),
			array(
				'id'       =>'blog-post-title-excerpt',
				'type'     => 'slider',
				'title'    => esc_html__('Blog post title excerpt length', 'enovathemes-addons'),
				'min'      =>'0',
				'max'      =>'500',
				'step'     =>'1',
				'default'  => '52',
			),
			array(
				'id'       =>'blog-post-excerpt',
				'type'     => 'slider',
				'title'    => esc_html__('Blog post excerpt length', 'enovathemes-addons'),
				'min'      =>'0',
				'max'      =>'500',
				'step'     =>'1',
				'default'  => '0',
			),
			array(
				'id'        =>'blog-single-sidebar',
				'type'      => 'select',
				'title'     => esc_html__('Blog single post sidebar position', 'enovathemes-addons'),
				'options'   => array(
					'none'  => esc_html__('None', 'enovathemes-addons'),
					'left'  => esc_html__('Left', 'enovathemes-addons'),
					'right' => esc_html__('Right', 'enovathemes-addons'),
				),
				'default' => 'none',
			),
			array(
				'id'       =>'blog-single-social',
				'type'     => 'switch',
				'title'    => esc_html__('Blog single post social share', 'enovathemes-addons'),
				"default"  => 0
			),
			array(
				'id'       =>'blog-related-posts',
				'type'     => 'switch',
				'title'    => esc_html__('Blog single post related posts', 'enovathemes-addons'),
				"default"  => 1
			),
			array(
				'id'        =>'blog-related-posts-by',
				'type'      => 'select',
				'title'     => esc_html__('Related posts by', 'enovathemes-addons'),
				'options'   => array(
					'categories'  => esc_html__('Categories', 'enovathemes-addons'),
					'tags'  => esc_html__('Tags', 'enovathemes-addons'),
				),
				'default' => 'categories',
				'required' => array('blog-related-posts','equals',1)
			)
		)
	));

/* Woo Commerce
---------------*/

	Redux::setSection( $opt_name, array(
		'title'      => esc_html__('Shop', 'enovathemes-addons'),
		'icon_class' => 'icon-small',
	    'icon'       => 'el-icon-shopping-cart',
	    'fields' => array(
			array(
				'id'=>'product-title',
				'type' => 'select',
				'title'    => esc_html__('Choose title section', 'enovathemes-addons'),
				'options' => $title_sections_array,
				'default' => 'inherit',
			),
			array(
				'id'=>'product-title-single',
				'type' => 'select',
				'title' => esc_html__('Choose title section for single product pages', 'enovathemes-addons'),
				'options' => $title_sections_array,
				'default' => 'inherit',
			),
			array(
				'id'      =>'product-title-text',
				'type'     => 'text',
				'title'    => esc_html__('Shop title', 'enovathemes-addons'),
				'default'  => 'Shop',
			),
			array(
				'id'      =>'product-subtitle-text',
				'type'     => 'text',
				'title'    => esc_html__('Shop subtitle', 'enovathemes-addons'),
				'default'  => '',
			),
			array(
				'id'        =>'product-sidebar',
				'type'      => 'select',
				'title'     => esc_html__('Shop sidebar position', 'enovathemes-addons'),
				'options'   => array(
					'none'  => esc_html__('None', 'enovathemes-addons'),
					'left'  => esc_html__('Left', 'enovathemes-addons'),
					'right' => esc_html__('Right', 'enovathemes-addons'),
				),
				'default' => 'none',
			),
			array(
				'id'       =>'product-sidebar-toggle',
				'type'     => 'switch',
				'title'    => esc_html__('Activate sidebar on toggle', 'enovathemes-addons'),
				"default"  => 0,
			),
			array(
				'id'       =>'product-per-page',
				'type'     => 'slider',
				'title'    => esc_html__('Products per page', 'enovathemes-addons'),
				'min'      =>'0',
				'max'      =>'999',
				'step'     =>'1',
				'default'  => '9'
			),
			array(
				'id'       =>'product-navigation',
				'type'     => 'select',
				'title'    => esc_html__('Shop navigation', 'enovathemes-addons'),
				'subtitle' => esc_html__('Shop navigation', 'enovathemes-addons'),
				'options'  => array(
					'pagination' => esc_html__('Pagination', 'enovathemes-addons'), 
					'loadmore'   => esc_html__('Load more', 'enovathemes-addons'), 
					'infinite'   => esc_html__('Infinite load', 'enovathemes-addons'),
				),
				'default'  => 'pagination'
			),
			array(
				'id'        =>'product-post-layout',
				'type'      => 'select',
				'title'     => esc_html__('Product layout', 'enovathemes-addons'),
				'options'   => array(
					'comp'  => esc_html__('List', 'enovathemes-addons'),
					'grid'  => esc_html__('Grid', 'enovathemes-addons'),
				),
				'default' => 'grid',
			),
			array(
				'id'       =>'product-title-excerpt',
				'type'     => 'slider',
				'title'    => esc_html__('Product title excerpt length', 'enovathemes-addons'),
				'min'      =>'0', 
				'max'      =>'500', 
				'step'     =>'1',
				'default'  => '500'
			),
			array(
				'id'       =>'product-title-min-height',
				'type'     => 'slider',
				'title'    => esc_html__('Product title minimum height', 'enovathemes-addons'),
				'min'      =>'0', 
				'max'      =>'500', 
				'step'     =>'1',
				'default'  => '22'
			),
			array(
				'id'       =>'product-title-max-height',
				'type'     => 'slider',
				'title'    => esc_html__('Product title maximum height', 'enovathemes-addons'),
				'min'      =>'0', 
				'max'      =>'500', 
				'step'     =>'1',
				'default'  => '22'
			),
			array(
				'id'       =>'product-image-full',
				'type'     => 'switch',
				'title'    => esc_html__('Use original image size (no cropping)', 'enovathemes-addons'),
				"default"  => 0,
			),
			array(
				'id'       =>'sale-color',
				'type'     => 'color',
				'title'    => esc_html__('Sale color', 'enovathemes-addons'),
				'default'  => '#39cb74',
                'transparent' => false
			),
			array(
				'id'       =>'discount-color',
				'type'     => 'color',
				'title'    => esc_html__('Discount color', 'enovathemes-addons'),
				'default'  => '#39cb74',
                'transparent' => false
			),
			array(
				'id'       =>'quickview',
				'type'     => 'switch',
				'title'    => esc_html__('Product quick view', 'enovathemes-addons'),
				"default"  => 0
			),
			array(
				'id'       =>'wishlist',
				'type'     => 'switch',
				'title'    => esc_html__('Product wishlist', 'enovathemes-addons'),
				"default"  => 0
			),
			array(
				'id'       =>'compare',
				'type'     => 'switch',
				'title'    => esc_html__('Product compare', 'enovathemes-addons'),
				"default"  => 0
			),
			array(
				'id'       =>'ask',
				'type'     => 'switch',
				'title'    => esc_html__('Product quick question form', 'enovathemes-addons'),
				"default"  => 0
			),
			array(
				'id'      =>'product-ask-form',
				'type'     => 'text',
				'title'    => esc_html__('Product quick question form ID', 'enovathemes-addons'),
				'required' => array('ask','equals',1)
			),
			array(
				'id'      =>'product-wishlist-page',
				'type'     => 'text',
				'title'    => esc_html__('Wishlist page url', 'enovathemes-addons'),
				'required' => array('wishlist','equals',1)
			),
			array(
				'id'      =>'product-compare-page',
				'type'     => 'text',
				'title'    => esc_html__('Compare page url', 'enovathemes-addons'),
				'required' => array('compare','equals',1)
			),
			array(
				'id'        =>'product-single-sidebar',
				'type'      => 'select',
				'title'     => esc_html__('Single product sidebar position', 'enovathemes-addons'),
				'options'   => array(
					'none'  => esc_html__('None', 'enovathemes-addons'),
					'left'  => esc_html__('Left', 'enovathemes-addons'),
					'right' => esc_html__('Right', 'enovathemes-addons'),
				),
				'default' => 'none',
			),
			array(
				'id'       => 'product-single-post-layout',
				'type'     => 'select',
				'title'    => esc_html__('Single product thumbnails', 'enovathemes-addons'),
				'options'  => array(
					'single-product-thumbnails-down' => esc_html__('Horizonal thumbnails', 'enovathemes-addons'),
					'single-product-thumbnails-left' => esc_html__('Vertical thumbnails', 'enovathemes-addons'),
				),
				'default' => 'single-product-thumbnails-down'
			),
			array(
				'id'        =>'product-single-details-layout',
				'type'      => 'select',
				'title'     => esc_html__('Single product details layout', 'enovathemes-addons'),
				'options'   => array(
					'tabs'   => esc_html__('Tabs', 'enovathemes-addons'),
					'blocks' => esc_html__('Blocks', 'enovathemes-addons'),
				),
				'default' => 'tabs',
			),
			array(
				'id'       =>'product-single-social',
				'type'     => 'switch',
				'title'    => esc_html__('Social share', 'enovathemes-addons'),
				"default"  => 1
			),
			array(
				'id'       =>'product-quick-buy',
				'type'     => 'switch',
				'title'    => esc_html__('Buy now button', 'enovathemes-addons'),
				"default"  => 0
			)
		)
	));

?>
