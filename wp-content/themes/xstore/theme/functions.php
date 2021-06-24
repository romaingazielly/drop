<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Script, styles, fonts
// **********************************************************************//  
if(!function_exists('etheme_theme_styles')) {
    function etheme_theme_styles() {
        if ( !is_admin() ) {
        	$theme = wp_get_theme();
        	$options_parent = array("parent-style");

	        $generated_css_js = get_option('etheme_generated_css_js');
	        $generated_css = false;

	        if ( isset($generated_css_js['css']) ){
		        if ( isset($generated_css_js['css']['is_enabled']) && $generated_css_js['css']['is_enabled'] ){
			        if ( file_exists ($generated_css_js['css']['path']) ){
				        $generated_css = true;
			        }
		        }
	        }

        	if ( etheme_get_option('fa_icons', 0) ) {
            	wp_enqueue_style("fa",get_template_directory_uri().'/css/font-awesome.min.css', array(), $theme->version);
        	}

        	if ($generated_css){
		        wp_enqueue_style("et-generated-css",$generated_css_js['css']['url'], array(), $theme->version);
            } else {


		        if ( etheme_get_option( 'et_optimize_css', 0 ) ) {
			        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/xstore.css', array(), $theme->version );
		        } else {
			        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), $theme->version );
			        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array( 'bootstrap' ), $theme->version );
		        }

		        if ( class_exists( 'WPBMap' ) || defined( 'ELEMENTOR_VERSION' ) ) {
			        wp_enqueue_style( "et-builders-global-style", get_template_directory_uri() . '/css/builders-global.css', array( "parent-style" ), $theme->version );
		        }

		        if ( class_exists( 'WPBMap' ) ) {
			        wp_enqueue_style( "et-wpb-style", get_template_directory_uri() . '/css/wpb.css', array(
				        "parent-style",
				        "et-builders-global-style"
			        ), $theme->version );
		        }

		        if ( defined( 'ELEMENTOR_VERSION' ) ) {
			        wp_enqueue_style( "et-elementor-style", get_template_directory_uri() . '/css/elementor.css', array(
				        "parent-style",
				        "et-builders-global-style"
			        ), $theme->version );
		        }

		        if ( etheme_get_option( 'portfolio_projects', 1 ) ) {
			        wp_enqueue_style( "et-portfolio-style", get_template_directory_uri() . '/css/portfolio.css', array( "parent-style" ), $theme->version );
		        }
		        
                wp_enqueue_style( "secondary-style", get_template_directory_uri() . '/css/secondary-menu.css', array( "parent-style" ), $theme->version );

		        if ( etheme_get_option( 'enable_swatch', 1 ) ) {
			        wp_enqueue_style( "et-swatches-style", get_template_directory_uri() . '/css/swatches.css', array( "parent-style" ), $theme->version );
		        }

		        if ( class_exists( 'bbPress' ) && is_bbpress() ) {
			        wp_enqueue_style( "forum-style", get_template_directory_uri() . '/css/forum.css', array( "parent-style" ), $theme->version );
		        }

		        if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'Dokan_Pro' ) ) {
			        wp_enqueue_style( "et-dokan-style", get_template_directory_uri() . '/css/dokan.css', array( "parent-style" ), $theme->version );
		        }

		        if ( class_exists( 'WCFMmp' ) ) {
			        wp_enqueue_style( "et-wcfmmp-style", get_template_directory_uri() . '/css/wcfmmp.css', array( "parent-style" ), $theme->version );
		        }

		        if ( class_exists( 'WCMp' ) ) {
			        wp_enqueue_style( "et-wcmp-style", get_template_directory_uri() . '/css/wcmp.css', array(
				        "parent-style",
			        ), $theme->version );
		        }
	        }

            wp_enqueue_style( 'js_composer_front');
	
	        wp_register_style( 'xstore-inline-css', false );
	        wp_register_style( 'xstore-inline-desktop-css', false );
	        wp_register_style( 'xstore-inline-tablet-css', false );
	        wp_register_style( 'xstore-inline-mobile-css', false );

            if ( get_query_var('et_is-woocommerce', false) ) {
                if ( is_product() ) {
                    $product_id = get_the_ID();
                    $slider_vertical = ( etheme_get_option('thumbs_slider_vertical', 'horizontal') || etheme_get_custom_field('slider_direction', $product_id) == 'vertical') || ( get_query_var('etheme_single_product_builder') && etheme_get_option('product_gallery_type_et-desktop', 'thumbnails_bottom') == 'thumbnails_left' );
                    if ( $slider_vertical ) {
                        wp_enqueue_style("etheme-slick",get_template_directory_uri().'/css/slick.css', array(), $theme->version);
                    }
                }
                elseif ( etheme_get_option('show_plus_filters',0) && ( is_shop() || is_product_category() || is_product_tag() || is_tax('brand') ) ) {
                    $limit_items = etheme_get_option('show_plus_filter_after',3);
	                wp_add_inline_style( 'xstore-inline-css',
		                '.sidebar .sidebar-widget:not(.etheme_swatches_filter):not(.null-instagram-feed) ul > li:nth-child('.$limit_items.')
							~ li:not(.et_widget-open):not(.et_widget-show-more):not(.current-cat):not(.current-item):not(.selected),
							 .sidebar-widget ul.menu > li:nth-child('.$limit_items.')
							~ li:not(.et_widget-open):not(.et_widget-show-more){
								   display: none;
							}');
                }
            }
            
        	if ( is_rtl() ) {
		    	wp_enqueue_style( 'rtl-style', get_template_directory_uri() . '/rtl.css', array(), $theme->version);
		    	$options_parent[] = 'rtl-style';
		    }

		    if ( ! defined( 'ET_CORE_VERSION' ) ) {
		    	wp_enqueue_style( 'plugin-off', get_template_directory_uri() . '/css/plugin-off.css', array(), $theme->version);
		    }

        	$icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';

        	wp_register_style( 'xstore-icons-font', false );
            wp_enqueue_style( 'xstore-icons-font' );
            wp_add_inline_style( 'xstore-icons-font', 
	            "@font-face {
				  font-family: 'xstore-icons';
				  src:
				    url('".get_template_directory_uri()."/fonts/xstore-icons-".$icons_type.".ttf') format('truetype'),
				    url('".get_template_directory_uri()."/fonts/xstore-icons-".$icons_type.".woff2') format('woff2'),
				    url('".get_template_directory_uri()."/fonts/xstore-icons-".$icons_type.".woff') format('woff'),
				    url('".get_template_directory_uri()."/fonts/xstore-icons-".$icons_type.".svg#xstore-icons') format('svg');
				  font-weight: normal;
				  font-style: normal;
				  font-display: swap;
				}"
			);

			if( etheme_get_option('dark_styles', 0) ) {
            	wp_enqueue_style("dark-style",get_template_directory_uri().'/css/dark.css', array(), $theme->version);
            	$options_parent[] = 'dark-style';
        	}
		    
        	$upload_dir = wp_upload_dir();
        	if ( !is_xstore_migrated() && is_file($upload_dir['basedir'].'/xstore/options-style.min.css') && filesize($upload_dir['basedir'].'/xstore/options-style.min.css') > 0 && !is_customize_preview() ) {
        		$custom_css = $upload_dir['baseurl'] . '/xstore/options-style.min.css';
        		$custom_css = str_replace( array( 'https://', 'http://',), '//', $custom_css );
            	wp_enqueue_style("options-style",$custom_css, $options_parent, $theme->version);
            }

        	do_action('etheme_last_style');
	
	        // tweak for media queries (start)
	        wp_add_inline_style( 'xstore-inline-tablet-css', '@media only screen and (max-width: 992px) {' );
         
	        // tweak for media queries (start)
	        wp_add_inline_style( 'xstore-inline-mobile-css', '@media only screen and (max-width: 767px) {' );

        }
    }
}

// to remove default enqueue of rtl style from wp
remove_action( 'wp_head', 'locale_stylesheet' );

add_action( 'wp_enqueue_scripts', 'etheme_theme_styles', 30);

 if ( ! function_exists( 'etheme_theme_styles_after' ) ) {
	function etheme_theme_styles_after() {
		wp_enqueue_style( 'xstore-inline-css' );
		// tweak for media queries (end)
		wp_add_inline_style( 'xstore-inline-tablet-css', '}' );
		wp_enqueue_style( 'xstore-inline-tablet-css' );
		// tweak for media queries (end)
		wp_add_inline_style( 'xstore-inline-mobile-css', '}' );
		wp_enqueue_style( 'xstore-inline-mobile-css' );

		if ( function_exists('vc_build_safe_css_class') ) {

		    $et_fonts = get_option( 'etheme-fonts', false );

		    // remove custom fonts from vc_google_fonts wp_enqueue_style to prevent site speed falling  
			if ( $et_fonts ) {
			    foreach ( $et_fonts as $value ) {
			    	wp_dequeue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $value['name'] ) );
			    }
			}
		}
	}
}

add_action( 'wp_footer', 'etheme_theme_styles_after', 10 );

// **********************************************************************// 
// ! Plugins activation
// **********************************************************************// 
if(!function_exists('etheme_register_required_plugins')) {
    global $pagenow;

    if ($pagenow!='plugins.php'){
	    add_action('tgmpa_register', 'etheme_register_required_plugins');
    }

	function etheme_register_required_plugins() {
		if( ! etheme_is_activated() ) return;

		$activated_data = get_option( 'etheme_activated_data' );

		$key = $activated_data['api_key'];

		if( ! $key || empty( $key ) ) return;

		$plugins = get_transient( 'etheme_plugins_info' );
		if (! $plugins || empty( $plugins ) || isset($_GET['et_clear_plugins_transient'])){
			$plugins_dir = ETHEME_API . 'files/get/';
			$token = '?token=' . $key;
			$url = 'https://8theme.com/import/xstore-demos/1/plugins/?plugins_dir=' . $plugins_dir . '&token=' .$token;
			$response = wp_remote_get( $url );
			$response = json_decode(wp_remote_retrieve_body( $response ), true);
			$plugins = $response;
			set_transient( 'etheme_plugins_info', $plugins, 24 * HOUR_IN_SECONDS );
		}

        if ( ! $plugins || ! is_array($plugins) || ! count($plugins) ){
	        $plugins = array();
        }
		// Change this to your theme text domain, used for internationalising strings

		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'domain'       		=> 'xstore',         	// Text domain - likely want to be the same as your theme.
			'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
			'menu'         		=> 'install-required-plugins', 	// Menu slug
			'has_notices'      	=> false,                       	// Show admin notices or not
			'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
			'message' 			=> '',							// Message to output right before the plugins table
			'strings'      		=> array(
				'page_title'                       			=> esc_html__( 'Install Required Plugins', 'xstore'),
				'menu_title'                       			=> esc_html__( 'Install Plugins', 'xstore' ),
				'installing'                       			=> esc_html__( 'Installing Plugin: %s', 'xstore' ), // %1$s = plugin name
				'downloading_package'                       => esc_html__( 'Downloading the install package&#8230;', 'xstore' ), // %1$s = plugin name
				'oops'                             			=> esc_html__( 'Something went wrong with the plugin API.', 'xstore' ),
				'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'xstore' ), // %1$s = plugin name(s)
				'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'xstore' ), // %1$s = plugin name(s)
				'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'xstore' ), // %1$s = plugin name(s)
				'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'xstore' ), // %1$s = plugin name(s)
				'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'xstore' ), // %1$s = plugin name(s)
				'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'xstore' ), // %1$s = plugin name(s)
				'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'xstore' ), // %1$s = plugin name(s)
				'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'xstore' ), // %1$s = plugin name(s)
				'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'xstore' ),
				'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'xstore' ),
				'return'                           			=> esc_html__( 'Return to Required Plugins Installer', 'xstore' ),
				'plugin_activated'                 			=> esc_html__( 'Plugin activated successfully.', 'xstore' ),
				'complete' 									=> esc_html__( 'All plugins installed and activated successfully. %s', 'xstore' ), // %1$s = dashboard link
				'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
			)
		);

		tgmpa($plugins, $config);
	}
}

// **********************************************************************// 
// ! Footer Demo Widgets
// **********************************************************************// 

if(!function_exists('etheme_footer_demo')) {
    function etheme_footer_demo($position){
        switch ($position) {
            case 'footer-copyrights':
                ?>
					© Created by <a href="#"><i class="fa fa-heart"></i> &nbsp;<strong>8theme</strong></a> - Power Elite ThemeForest Author.
                <?php
            break;
        }
    }
}