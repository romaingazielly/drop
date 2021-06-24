<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************//
// ! Add custom query data
// **********************************************************************//
add_action('wp', 'et_custom_query');
if ( ! function_exists( 'et_custom_query' ) ) {
	function et_custom_query(){
		global $post;
		
		$old_options = get_option('et_options', array());
		set_query_var( 'et_redux_options', $old_options );
		
		$is_woocommerce = class_exists('WooCommerce');
		set_query_var('et_is-woocommerce', $is_woocommerce);

		$post_id = etheme_get_page_id();
		$id = $post_id['id'];
		$is_mobile_device = wp_is_mobile();
		$top_bar_color = '';
		$header_color = '';
		$etheme_single_product_builder = get_option( 'etheme_single_product_builder', false );

		$custom = etheme_get_custom_field('top_bar_color', $id);

		if( ! empty( $custom ) && $custom != 'inherit' ) {
			$top_bar_color = $custom;
		}

		if ( $is_mobile_device ) {
			$header_color = etheme_get_option('mobile_header_color', 'dark');
		}

		$custom = etheme_get_custom_field('header_color', $id);

		if( ! empty( $custom ) && $custom != 'inherit' ) {
			$header_color = $custom;
		}

		$template = etheme_get_option('post_template', 'default');

		$custom = etheme_get_custom_field('post_template', $id);

		if( ! empty( $custom ) ) {
			$template = $custom;
		}

		if ( $is_woocommerce ) {
			$grid_sidebar = etheme_get_option('grid_sidebar', 'left');
			set_query_var('et_grid-sidebar', $grid_sidebar);
			if  (is_shop() || is_product_category() || is_product_tag() || is_tax('brand') || is_post_type_archive( 'product' ) ) {
				$view_mode = etheme_get_view_mode();
				set_query_var( 'et_view-mode', $view_mode );

				// set shop products custom template
				$grid_custom_template = etheme_get_option('custom_product_template', 'default');
				$list_custom_template = etheme_get_option('custom_product_template_list', 'default');
				$list_custom_template = ( $list_custom_template != '-1' ) ? $list_custom_template : $grid_custom_template;

				set_query_var('et_custom_product_template', ( $view_mode == 'grid' ? (int)$grid_custom_template : (int)$list_custom_template ) );
				
				$view_mode_smart = etheme_get_option('view_mode', 'grid_list') == 'smart';
				set_query_var('view_mode_smart', $view_mode_smart);
				$view_mode_smart_active = etheme_get_option('view_mode_smart_active', 4);
				set_query_var('view_mode_smart_active', $view_mode_smart_active);
			}

			if ( is_product_category() ) {
				$categories_sidebar = etheme_get_option('category_sidebar', 'left');
				set_query_var('et_cat-sidebar', $categories_sidebar);
				if ( $view_mode_smart ) {
					$view_mode_smart_active = etheme_get_option('categories_view_mode_smart_active', 4);
					set_query_var('view_mode_smart_active', $view_mode_smart_active);
				}
				$category_cols = etheme_get_option('category_page_columns', 'inherit');
				if ( $category_cols >= 1 ) {
					set_query_var('et_cat-cols', $category_cols);
				}
			}
			elseif ( is_tax('brand') ) {
				$brand_sidebar = etheme_get_option('brand_sidebar', 'left');
				set_query_var('et_cat-sidebar', $brand_sidebar);
				if ( $view_mode_smart ) {
					$view_mode_smart_active = etheme_get_option('brands_view_mode_smart_active', 4);
					set_query_var('view_mode_smart_active', $view_mode_smart_active);
				}
				$brand_cols = etheme_get_option('brand_page_columns', 'inherit');
				if ( $brand_cols >= 1 ) {
					set_query_var('et_cat-cols', $brand_cols);
				}
			}

//             if ( is_product() ) {

				if ( !$etheme_single_product_builder ) {

					$l = etheme_page_config();
					$layout = $l['product_layout'];

					$thumbs_slider_mode = etheme_get_option('thumbs_slider_mode', 'enable');

					if ( $thumbs_slider_mode == 'enable' || ( $thumbs_slider_mode == 'enable_mob' && $is_mobile_device ) ) {
						$gallery_slider = true;
					}
					else {
						$gallery_slider = false;
					}

					$thumbs_slider = etheme_get_option('thumbs_slider_vertical', 'horizontal');

					$enable_slider = etheme_get_custom_field('product_slider', $id);

					$stretch_slider = etheme_get_option('stretch_product_slider', 1);

					$slider_direction = etheme_get_custom_field('slider_direction', $id);

					$vertical_slider = ($thumbs_slider == 'vertical') ? true : false;

					if ( $slider_direction == 'vertical' ) {
						$vertical_slider = true;
					}
					elseif($slider_direction == 'horizontal') {
						$vertical_slider = false;
					}

					$show_thumbs = ($thumbs_slider != 'disable' ) ? true : false;

					if ( $layout == 'large' && $stretch_slider ) {
						$show_thumbs = false;
					}
					if ( $slider_direction == 'disable' ) {
						$show_thumbs = false;
					}
					elseif ( in_array($slider_direction, array('vertical', 'horizontal') ) ) {
						$show_thumbs = true;
					}
					if ( $enable_slider == 'on' || ($enable_slider == 'on_mobile' && $is_mobile_device ) ) {
						$gallery_slider = true;
					}
					elseif ( $enable_slider == 'off' || ($enable_slider == 'on_mobile' && !$is_mobile_device ) ) {
						$gallery_slider = false;
						$show_thumbs = false;
					}

//                    $etheme_single_product_variation_gallery = $gallery_slider && $show_thumbs && etheme_get_option('enable_variation_gallery');

				}
				else {

					$gallery_type = etheme_get_option('product_gallery_type_et-desktop', 'thumbnails_bottom');
					$vertical_slider = $gallery_type == 'thumbnails_left';

					$gallery_slider = ( !in_array($gallery_type, array('one_image', 'double_image')) );
					$show_thumbs = ( in_array($gallery_type, array('thumbnails_bottom', 'thumbnails_bottom_inside', 'thumbnails_left')));
					$thumbs_slider = etheme_get_option('product_gallery_thumbnails_et-desktop', 1);

					if( defined('DOING_AJAX') && DOING_AJAX ) {
						$gallery_slider = true;
					}

//                    $etheme_single_product_variation_gallery = etheme_get_option('enable_variation_gallery');
				
				}

				set_query_var( 'etheme_single_product_gallery_type', $gallery_slider );
				set_query_var( 'etheme_single_product_vertical_slider', $vertical_slider );
				set_query_var( 'etheme_single_product_show_thumbs', $show_thumbs );
				
				if ( is_product() ) {
					set_query_var( 'etheme_single_product_variation_gallery', apply_filters('etheme_single_product_variation_gallery', etheme_get_option('enable_variation_gallery', 0) ) );
					set_query_var('is_single_product', true);
				}
				
				set_query_var('etheme_variable_products_detach', etheme_get_option('variable_products_detach', false));
			    set_query_var('etheme_variation_product_parent_hidden', etheme_get_option('variation_product_parent_hidden', true));
			    set_query_var('etheme_variation_product_name_attributes', etheme_get_option('variation_product_name_attributes', true));
				
			// }
		}
		
		// ! set-query-var
		set_query_var( 'is_yp', (isset($_GET['yp_page_type']) ? true : false)); // yellow pencil
		set_query_var( 'et_post-template', $template );
		set_query_var( 'is_mobile', $is_mobile_device );
		set_query_var( 'et_header-color', $header_color );
		set_query_var( 'et_top-bar-color', $top_bar_color );
		set_query_var( 'et_page-id', $post_id );
		set_query_var( 'etheme_single_product_builder', $etheme_single_product_builder );
	}
}

function etheme_child_styles() {
	$theme = wp_get_theme();
	$depends = array();
	
	$generated_css_js = get_option('etheme_generated_css_js');
	$generated_css = false;
	
	if ( isset($generated_css_js['css']) ){
		if ( isset($generated_css_js['css']['is_enabled']) && $generated_css_js['css']['is_enabled'] ){
			if ( file_exists ($generated_css_js['css']['path']) ){
				$generated_css = true;
			}
		}
	}
	
	if ($generated_css){
		$depends[] = 'et-generated-css';
	}
	else {
		$depends[] = 'parent-style';
	}
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		$depends,
		$theme->version
	);
}

add_action('wp_head', function() {
	global $post;
	if ( !is_object($post) ) return;
	if ( $post->post_type == 'staticblocks' && function_exists('wp_no_robots') ) {
		wp_no_robots();
	}
});

// **********************************************************************//
// ! Add classes to body
// **********************************************************************//
add_filter('body_class', 'etheme_add_body_classes');
if(!function_exists('etheme_add_body_classes')) {
	function etheme_add_body_classes($classes) {
		
		$l = etheme_page_config();

		$post_id = (array)get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$post_template  = get_query_var('et_post-template', 'default');

		$id = $post_id['id'];
		$etheme_single_product_builder = get_query_var('etheme_single_product_builder', false);

		// portfolio page asap fix
		$portfolio_page_id = etheme_get_option( 'portfolio_page', '' );

		if ( etheme_get_option('portfolio_projects', '') && $portfolio_page_id ) {

			if ( function_exists('icl_object_id') ) {
				global $sitepress;
				if ( ! empty( $sitepress )  ) {
					$multy_id = icl_object_id ( $id, "page", false, $sitepress->get_default_language() );
				} elseif( function_exists( 'pll_current_language' ) ) {
					$multy_id = icl_object_id ( $id, "page", false, pll_current_language() );
				} else {
					$multy_id = false;
				}

				if (  $id == $portfolio_page_id || $portfolio_page_id == $multy_id ) {
					foreach ( $classes as $key => $value ) {
						if ( in_array($value, array('page-template-default', 'page-template-portfolio') ) ) unset( $classes[ $key ] );
					}
					$classes[] = 'page-template-portfolio';
				}
			} else {
				if (  $id == $portfolio_page_id ) {
					foreach ( $classes as $key => $value ) {
						if ( in_array($value, array('page-template-default', 'page-template-portfolio') ) ) unset( $classes[ $key ] );
					}
					$classes[] = 'page-template-portfolio';
				}
			}
		}


        $cart = etheme_get_option('cart_icon_et-desktop', 'type1');
        switch ($cart) {
            case 'type1':
                $classes[] = 'et_cart-type-1';
                break;
            case 'type2':
                $classes[] = 'et_cart-type-4';
                break;
            case 'type4':
                $classes[] = 'et_cart-type-3';
                break;
            default:
                $classes[] = 'et_cart-type-2';
                break;
        }
        $classes[] = 'et-fixed-disable';
        $classes[] = 'et-secondary-menu-on';
        $classes[] = (etheme_get_option('header_overlap_et-desktop', 0)) ? 'et_b_dt_header-overlap' : 'et_b_dt_header-not-overlap';
        $classes[] = (etheme_get_option('header_overlap_et-mobile', 0)) ? 'et_b_mob_header-overlap' : 'et_b_mob_header-not-overlap';


		$classes[] = 'breadcrumbs-type-'.$l['breadcrumb'];
		$classes[] = etheme_get_option('main_layout', 'wide');
		$classes[] = (etheme_get_option('cart_special_breadcrumbs', 1)) ? 'special-cart-breadcrumbs' : '';
		$classes[] = (etheme_get_option('site_preloader', 0)) ? 'et-preloader-on' : 'et-preloader-off';
		$classes[] = (function_exists('etheme_is_catalog') && etheme_is_catalog()) ? 'et-catalog-on' : 'et-catalog-off';
		$classes[] = ( ( etheme_get_option('footer_fixed', 0) || etheme_get_custom_field('footer_fixed', $id) == 'yes' ) && etheme_get_custom_field('footer_fixed', $id) != 'no' ) ? 'et-footer-fixed-on' : 'et-footer-fixed-off';
		$classes[] = ( get_query_var('is_mobile') ) ? 'mobile-device' : ''; // new
		if ( get_query_var('is_mobile') && etheme_get_option('footer_widgets_open_close', 1) ) {
			$classes[] = 'f_widgets-open-close';
			$classes[] = (etheme_get_option('footer_widgets_open_close_type', 'closed_mobile') == 'closed_mobile') ? 'fwc-default' : '';
		}
		$classes[] = etheme_masonry() ? 'etheme_masonry_on' : '';


		if ( etheme_woocommerce_installed() ) {
			if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_tax('brand') || is_post_type_archive( 'product' ) ) {
				$classes[] = (etheme_get_option('sidebar_widgets_scroll', 0)) ? 's_widgets-with-scroll' : ''; // sidebar widgets with scroll
				$classes[] = (etheme_get_option('sidebar_widgets_open_close', 0)) ? 's_widgets-open-close' : '';
				$sidebar_widgets_open_close_type = etheme_get_option('sidebar_widgets_open_close_type', 'open');
				$classes[] = ($sidebar_widgets_open_close_type == 'closed' || (($sidebar_widgets_open_close_type == 'closed_mobile') && get_query_var('is_mobile')) ) ? 'swc-default' : '';
				$classes[] = (etheme_get_option('filters_area_widgets_open_close', 0)) ? 'fa_widgets-open-close' : '';
				$filters_area_widget_open_close_type = etheme_get_option('filters_area_widgets_open_close_type', 'open');
				$classes[] = ($filters_area_widget_open_close_type == 'closed' || (($filters_area_widget_open_close_type == 'closed_mobile') && get_query_var('is_mobile')) ) ? 'fawc-default' : '';
				if ( etheme_get_option( 'ajax_product_filter', 0 ) ) {
					$classes[] = 'et-ajax-product-filter';
				}
				if ( etheme_get_option( 'ajax_product_pagination', 0 ) ) {
					$classes[] = 'et-ajax-product-pagination';
				}
			}
			elseif ( is_product() ) {
				$classes[] = 'sticky-message-'.(etheme_get_option('sticky_added_to_cart_message', 1) ? 'on' : 'off');
				if ( $etheme_single_product_builder ) {
					$classes[] = (etheme_get_option('single_product_widget_area_1_widget_scroll_et-desktop', 0)) ? 's_widgets-with-scroll' : ''; // sidebar widgets with scroll
					$classes[] = (etheme_get_option('single_product_widget_area_1_widget_toggle_et-desktop', 0)) ? 's_widgets-open-close' : '';
					$single_product_widget_area_1_widget_toggle_actions = etheme_get_option('single_product_widget_area_1_widget_toggle_actions_et-desktop', 'opened');
					$classes[] = ($single_product_widget_area_1_widget_toggle_actions == 'closed' || (($single_product_widget_area_1_widget_toggle_actions == 'closed_mobile') && get_query_var('is_mobile')) ) ? 'swc-default' : '';
				}
			}
		}

		// secondary
		$classes[] = 'et-secondary-visibility-' . etheme_get_option('secondary_menu_visibility', 'on_hover');
		if( etheme_get_option('secondary_menu_visibility', 'on_hover') == 'opened' ) {
			$classes[] = (etheme_get_option('secondary_menu_home', '1')) ? 'et-secondary-on-home' : '';
			$classes[] = (etheme_get_option('secondary_menu_subpages')) ? 'et-secondary-on-subpages' : '';
		}
		if ( $post_template == 'large2' ) {
			$post_template = 'large global-post-template-large2';
		}
		$classes[] = 'global-post-template-' . $post_template;

		// $header_bg = etheme_get_option('header_bg_color');

		// if( !empty($header_bg['background-color']) && $header_bg['background-color'] == 'transparent' ) {
		//     $classes[] = "body-header-transparent";
		// }

		if(!$etheme_single_product_builder && etheme_get_option('product_name_signle', 0)) {
			$classes[] = 'global-product-name-off';
		} else {
			$classes[] = 'global-product-name-on';
		}

		if ( class_exists( 'WooCommerce_Quantity_Increment' ) ) $classes[] = 'et_quantity-off';

		if ( etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
		   $classes[] = 'et-enable-swatch';
		}

		if ( etheme_get_option( 'et_optimize_js', 0 ) ) {
			$classes[] = 'et-old-browser';
		}

		return $classes;
	}
}

// **********************************************************************//
// ! Render custom styles
// **********************************************************************//
if ( !function_exists('et_custom_styles') ) {
	function et_custom_styles () {

//		$preloader = etheme_get_option( 'preloader_img', '' );
//
//		$et_selectors = array();

//		$activeColor = (etheme_get_option('activecol', '#c62828')) ? etheme_get_option('activecol', '#c62828') : '#8a8a8a';
		
		$css = '';

			$fonts = get_option( 'etheme-fonts', false );
			if ( $fonts ) {
				foreach ( $fonts as $value ) {
					// ! Validate format
					switch ( $value['file']['extension'] ) {
						case 'ttf':
							$format = 'truetype';
							break;
						case 'otf':
							$format = 'opentype';
							break;
//						case 'eot':
//							$format = false;
//							break;
						case 'eot?#iefix':
							$format = 'embedded-opentype';
							break;
						case 'woff2':
							$format = 'woff2';
							break;
						case 'woff':
							$format = 'woff';
							break;
						default:
							$format = false;
							break;
					}

					$format = ( $format ) ? 'format("' . $format . '")' : '';

					$font_url = ( is_ssl() && (strpos($value['file']['url'], 'https') === false) ) ? str_replace('http', 'https', $value['file']['url']) : $value['file']['url'];

					// ! Set fonts
					$css .= '
						@font-face {
							font-family: "' . $value['name'] . '";
							src: url(' . $font_url . ') ' . $format . ';
						}
					';
				}
			}

			$sale_size = etheme_get_option('sale_icon_size', '');
			$sale_size = explode( 'x', $sale_size );

			if ( ! isset( $sale_size[0] ) ) $sale_size[0] = 3.75;
			if ( ! isset( $sale_size[1] ) ) $sale_size[1] = $sale_size[0];

//			$sale_color = etheme_get_option('sale_icon_color', '#ffffff');
//			$sale_bg = etheme_get_option('sale_icon_bg_color', '#c62828');
//			$sale_br_radius = etheme_get_option('sale_br_radius', 0);
			$sale_width = $sale_size[0];
			$sale_height = $sale_size[1];

			$css .= '.onsale{';
				$css .= (!empty($sale_width)) ? 'width:'.$sale_width.'em;' : '';
				$css .= (!empty($sale_height)) ? 'height:'.$sale_height.'em; line-height: 1.2;' : '';
			$css .= '}';

//			$site_width = etheme_get_option('site_width', 1170);

			$css .= '@media (min-width: 1200px){
				.container, div.container, .et-container {
					width: 100%;
				}
				.footer:after,
				.boxed .template-container, .framed .template-container,
				.boxed .header-wrapper, .framed .header-wrapper {
					max-width: 100%;
				}
			}';

		// Secondary links
		$secondary_links_border_style = etheme_get_option('secondary-links-border-style', 'solid');
		$secondary_links_border_style = (!empty($secondary_links_border_style['border-style']) && $secondary_links_border_style['border-style'] != 'solid' ) ? $secondary_links_border_style['border-style'] : '';

		if ( trim($secondary_links_border_style) != '' ) {
			$css .= '.secondary-menu-wrapper .menu > li:not(:last-child) > a {
				border-bottom-style: ' . $secondary_links_border_style . '!important;
			}
			.menu-wrapper .secondary-menu-wrapper .menu > li {
				border: none;
				border-color: transparent;
			}';
		}

		$active_buttons_bg = etheme_get_option('active_buttons_bg',
			array(
				'regular'    => '',
				'hover'   => '',
			)
		);
		
		if ( is_array($active_buttons_bg) && isset($active_buttons_bg['hover']) && $active_buttons_bg['hover'] != '' ) {
			$css .= '.btn-checkout:hover, .btn-view-wishlist:hover {
				opacity: 1 !important;
			}';
		}

		$q_dimentions = etheme_get_option('quick_dimentions', 
			array(
				'width'  => '',
				'height' => '',
			)
		);

		if ( etheme_get_option('quick_view', 1) && ( !empty($q_dimentions['width']) || !empty($q_dimentions['height']) ) ) {
			$css .= '@media (min-width: 768px) {';
				$css .= '.quick-view-popup {';
				if ( !empty($q_dimentions['width']) ) {
					$css .= 'width: '.$q_dimentions['width'].';';
				}
				if ( !empty($q_dimentions['height']) ) {
					$css .= 'height: '.$q_dimentions['height'].';';
				}

				$css .= '}';

				if ( !empty($q_dimentions['height']) ) {
					$css .= '.quick-view-popup .product-content {';
					$css .= 'max-height:'.$q_dimentions['height'].';';
					$css .= '}';
					$css .= '.quick-view-layout-default img, .quick-view-layout-default iframe {';
					$css .= 'max-height:'.$q_dimentions['height'].';';
					$css .= 'margin: 0 auto !important;';
					$css .= '}';
				}
			$css .= '}';
		}

		// ! breadcrumb background
		$bread_bg = etheme_get_option( 'breadcrumb_bg',
			array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => '',
				'background-position'   => '',
				'background-size'       => '',
				'background-attachment' => '',
			)
		);

		$css .= '.page-heading {';
			if( ! empty( $bread_bg['background-image'] ) || ! empty( $bread_bg['background-color'] ) ){
				$css .= 'margin-bottom: 25px;';
			}
		$css .= '}';

		$css = et_minify_css($css);
		return $css;
	}
}

if ( !function_exists('et_custom_styles_responsive') ) {
	function et_custom_styles_responsive () {
		$css = '';
		$custom_css = etheme_get_option('custom_css_global', '');
		$custom_css_desktop = etheme_get_option('custom_css_desktop', '');
		$custom_css_tablet = etheme_get_option('custom_css_tablet', '');
		$custom_css_wide_mobile = etheme_get_option('custom_css_wide_mobile', '');
		$custom_css_mobile = etheme_get_option('custom_css_mobile', '');
		if($custom_css != '') {
			$css .= $custom_css;
		}
		if($custom_css_desktop != '') {
			$css .= '@media (min-width: 993px) { ' . $custom_css_desktop . ' }';
		}
		if($custom_css_tablet != '') {
			$css .= '@media (min-width: 768px) and (max-width: 992px) {' . $custom_css_tablet . ' }';
		}
		if($custom_css_wide_mobile != '') {
			$css .= '@media (min-width: 481px) and (max-width: 767px) { ' . $custom_css_wide_mobile . ' }';
		}
		if($custom_css_mobile != '') {
			$css .= '@media (max-width: 480px) { ' . $custom_css_mobile . ' }';
		}
		$css = et_minify_css($css);
		return $css;
	}
}

if ( !function_exists('et_minify_css') ) {
	function et_minify_css ($css) {
		// Normalize whitespace
		$css = preg_replace( '/\s+/', ' ', $css );
		
		// Remove spaces before and after comment
		$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
		// Remove comment blocks, everything between /* and */, unless
		// preserved with /*! ... */ or /** ... */
		$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
		// Remove ; before }
		$css = preg_replace( '/;(?=\s*})/', '', $css );
		// Remove space after , : ; { } */ >
		$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
		// Remove space before , ; { } ( ) >
		$css = preg_replace( '/ (,|;|\{|}|>)/', '$1', $css );
		// Strips leading 0 on decimal values (converts 0.5px into .5px)
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
		// Strips units if value is 0 (converts 0px to 0)
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
		// Converts all zeros value into short-hand
		$css = preg_replace( '/0 0 0 0/', '0', $css );
		// Shortern 6-character hex color codes to 3-character where possible
		$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
		return trim( $css );
	
	}
}

add_action('wp', 'is_xstore_migrated');
// **********************************************************************//
// ! Check xstore migrated
// **********************************************************************//
function is_xstore_migrated(){
	if ( get_option( 'et_options' ) ) {
		return get_option( 'xstore_theme_migrated', false );
	} else{
		return true;
	}
}

// **********************************************************************//
// ! Check woocommerce installed
// **********************************************************************//
if( ! function_exists('etheme_woocommerce_installed') ) {
	function etheme_woocommerce_installed() {
		return class_exists('WooCommerce');
	}
}

// **********************************************************************//
// ! WooCommerce active notice
// **********************************************************************//
if( ! function_exists('etheme_woocommerce_notice') ) {
	function etheme_woocommerce_notice($notice = '') {
		if ( ! etheme_woocommerce_installed() ) {
			if ( $notice == '' ) $notice = esc_html__( 'To use this element install or activate WooCommerce plugin', 'xstore' );
			echo '<p class="woocommerce-warning">' . $notice . '</p>';
			return true;
		} else {
			return false;
		}
	}
}

// **********************************************************************//
// ! core plugin active notice
// **********************************************************************//
if( ! function_exists('etheme_xstore_plugin_notice') ) {
	function etheme_xstore_plugin_notice($notice = '') {
		if ( ! defined( 'ET_CORE_DIR' ) ) {
			if ( $notice == '' ) $notice = esc_html__( 'To use this element install or activate XStore Core plugin', 'xstore' );
			echo '<p class="woocommerce-warning">' . $notice . '</p>';
			return true;
		} else {
			return false;
		}
	}
}

// **********************************************************************//
// ! Wp title
// **********************************************************************//
if(!function_exists('etheme_wp_title')) {
	function etheme_wp_title($title, $sep ) {
		global $paged, $page;

		if ( is_feed() ) {
			return $title;
		}

		// Add the site name.
		$title .= get_bloginfo( 'name', 'display' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}

		// Add a page number if necessary.
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'xstore' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'etheme_wp_title', 10, 2 );
}


// **********************************************************************//
// ! Get gallery from content
// **********************************************************************//
if(!function_exists('etheme_gallery_from_content')) {
	function etheme_gallery_from_content($content) {

		$result = array(
			'ids' => array(),
			'filtered_content' => ''
		);

		preg_match('/\[gallery.*ids=.(.*).\]/', $content, $ids);
		if(!empty($ids)) {
			$result['ids'] = explode(",", $ids[1]);
			$content =  str_replace($ids[0], "", $content);
			$result['filtered_content'] = apply_filters( 'the_content', $content);
		}

		return $result;

	}
}

// **********************************************************************//
// ! Get post classes
// **********************************************************************//
if(!function_exists('etheme_post_class')) {
	function etheme_post_class( $layout = false ) {
		global $et_loop;

		$classes = array();
		$classes[] = 'blog-post';

		if ( ! empty( $et_loop['columns'] ) ) {
			if( $et_loop['columns'] < 1 ) $et_loop['columns'] = 1;
			$cols = 12/$et_loop['columns'];
			$classes[] = 'post-grid';
			$classes[] = 'isotope-item';
			$classes[] = 'col-md-' . $cols;
		}

		if(etheme_get_option('blog_byline', 1)) {
			$classes[] = ' byline-on';
		} else {
			$classes[] = ' byline-off';
		}

		if( ! $layout ) {
			$classes[] = ' content-'.etheme_get_option('blog_layout', 'default');
		} else {
			$classes[] = ' content-'.$layout;
		}

		if( ! empty( $et_loop['slide_view'] ) ) {
			$classes[] = 'slide-view-' . $et_loop['slide_view'];
		}

		if( ! empty( $et_loop['blog_align'] ) ) {
			$classes[] = ' blog-align-' . $et_loop['blog_align'];
		}
		return $classes;
	}
}

// **********************************************************************//
// ! Get column class bootstrap
// **********************************************************************//
if(!function_exists('etheme_get_product_class')) {
	function etheme_get_product_class($colums = 3 ) {
		$colums = intval($colums);

		if (! $colums ) {
			$colums = 3;
		}
		$cols = 12 / $colums;

		$small = 6;
		$extra_small = 6;

		$class = 'col-md-' . $cols;
		$class .= ' col-sm-' . $small;
		$class .= ' col-xs-' . $extra_small;

		return $class;
	}
}

// **********************************************************************//
// ! Get read more button text
// **********************************************************************//
if(!function_exists('etheme_read_more')) {
	function etheme_read_more( $link = false, $echo = false ) {
		$btn = etheme_get_option( 'read_more', 'link' );

		if ( $btn == 'off' || ! $link ) return;

		if ( $echo ) {
			printf(
				'<a href="%s" class="more-button"><span class="read-more%s">%s</span></a>',
				esc_url( $link ),
				( $btn == 'btn' ) ? ' btn medium active' : '',
				esc_html__( 'Continue reading', 'xstore' )
			);
		} else {
			return sprintf(
				'<a href="%s" class="more-button"><span class="read-more%s">%s</span></a>',
				esc_url( $link ),
				( $btn == 'btn' ) ? ' btn medium active' : '',
				esc_html__( 'Continue reading', 'xstore' )
			);
		}
	}
}

// **********************************************************************//
// ! Views coutner
// **********************************************************************//
if(!function_exists('etheme_get_views')) {
	function etheme_get_views($id = false, $echo = false) {
		if( ! $id ) $id = get_the_ID();
		$number = get_post_meta( $id, '_et_views_count', true );
		if( empty($number) ) $number = 0;

		if ( $echo ) {
			echo '<span class="views-count">' . $number . '</span>';
		} else {
			return $number;
		}
	}
}

add_action( 'wp', 'etheme_update_views');

if(!function_exists('etheme_update_views')) {
	function etheme_update_views() {
		if( ! is_single() || ! is_singular( 'post' ) ) return;

		$id = get_the_ID();

		$number = etheme_get_views( $id );
		if( empty($number) ) {
			$number = 1;
			add_post_meta( $id, '_et_views_count', $number );
		} else {
			$number++;
			update_post_meta( $id, '_et_views_count', $number );
		}
	}
}

// **********************************************************************//
// ! Custom Comment Form
// **********************************************************************//

if(!function_exists('etheme_custom_comment_form')) {
	function etheme_custom_comment_form($defaults) {
		$defaults['comment_notes_before'] = '
			<p class="comment-notes">
				<span id="email-notes">
				' . esc_html__( 'Your email address will not be published. Required fields are marked', 'xstore' ) . '
				</span>
			</p>
		';
		$defaults['comment_notes_after'] = '';
		$dafaults['id_form'] = 'comments_form';

		$defaults['comment_field'] = '
			<div class="form-group">
				<label for="comment" class="control-label">'.esc_html__('Your Comment', 'xstore').'</label>
				<textarea placeholder="' . esc_html__('Comment', 'xstore') . '" class="form-control required-field"  id="comment" name="comment" cols="45" rows="12" aria-required="true"></textarea>
			</div>
		';

		return $defaults;
	}
}

add_filter('comment_form_defaults', 'etheme_custom_comment_form');

if(!function_exists('etheme_custom_comment_form_fields')) {
	function etheme_custom_comment_form_fields() {
		$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$reqT = '<span class="required">*</span>';
		$aria_req = ($req ? " aria-required='true'" : ' ');
		$consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields = array(
			'author' => '
				<div class="form-group comment-form-author">'.
					'<label for="author" class="control-label">'.esc_html__('Name', 'xstore').' '.($req ? $reqT : '').'</label>'.
					'<input id="author" name="author" placeholder="' . esc_html__('Your name (required)', 'xstore') . '" type="text" class="form-control ' . ($req ? ' required-field' : '') . '" value="' . esc_attr($commenter['comment_author']) . '" size="30" ' . $aria_req . '>'.
				'</div>
			',
			'email' => '
				<div class="form-group comment-form-email">'.
					'<label for="email" class="control-label">'.esc_html__('Email', 'xstore').' '.($req ? $reqT : '').'</label>'.
					'<input id="email" name="email" placeholder="' . esc_html__('Your email (required)', 'xstore') . '" type="text" class="form-control ' . ($req ? ' required-field' : '') . '" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" ' . $aria_req . '>'.
				'</div>
			',
			'url' => '
				<div class="form-group comment-form-url">'.
					'<label for="url" class="control-label">'.esc_html__('Website', 'xstore').'</label>'.
					'<input id="url" name="url" placeholder="' . esc_html__('Your website', 'xstore') . '" type="text" class="form-control" value="' . esc_attr($commenter['comment_author_url']) . '" size="30">'.
				'</div>
			',
			'cookies' => '
				<p class="comment-form-cookies-consent">
					<label for="wp-comment-cookies-consent">
						<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' . '
						<span>' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'xstore' ) . '</span>
					</label>
				</p>'
		);

		return $fields;
	}
}

add_filter('comment_form_default_fields', 'etheme_custom_comment_form_fields');

if ( ! function_exists( 'filter_login_form_middle' ) ) {
	function filter_login_form_middle( $content, $args ){
		$content .= '<a href="'.wp_lostpassword_url().'" class="lost-password">'.esc_html__('Lost Password ?', 'xstore').'</a>';
		return $content;
	}
}
add_filter( 'login_form_middle', 'filter_login_form_middle', 10, 2 );

// **********************************************************************//
// ! Set exerpt
// **********************************************************************//
if(!function_exists('etheme_excerpt_length')) {
	function etheme_excerpt_length( $length ) {
		return (int)etheme_get_option('excerpt_length', 25);
	}
}

if(!function_exists('etheme_excerpt_length_sliders')) {
	function etheme_excerpt_length_sliders( $length ) {
		return (int)etheme_get_option('excerpt_length_sliders', 25);
	}
}

add_filter( 'excerpt_length', 'etheme_excerpt_length', 999 );

if( ! function_exists( 'etheme_excerpt_more' ) ) {
	function etheme_excerpt_more( $more ) {
		return etheme_get_option( 'excerpt_words', '...' );
	}
}

add_filter( 'excerpt_more', 'etheme_excerpt_more', 9999 );


// **********************************************************************//
// ! Enable shortcodes in text widgets
// **********************************************************************//
add_filter('widget_text', 'do_shortcode');


// **********************************************************************//
// ! Add Facebook Open Graph Meta Data
// **********************************************************************//

//Adding the Open Graph in the Language Attributes
if( ! function_exists( 'etheme_add_opengraph_doctype' ) ) {
	function etheme_add_opengraph_doctype( $output ) {
		$share_facebook = etheme_get_option('socials',array( 'share_twitter', 'share_facebook', 'share_vk', 'share_pinterest', 'share_mail', 'share_linkedin', 'share_whatsapp', 'share_skype'
			));
		if ( is_array($share_facebook) && in_array( 'share_facebook', $share_facebook ) ) {
			return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
		} else {
			return $output;
		}
	}
}
add_filter('language_attributes', 'etheme_add_opengraph_doctype');

// **********************************************************************//
// ! Search, search SKU
// **********************************************************************/

add_action('pre_get_posts', 'etheme_search_all_sku_query');
if (! function_exists('etheme_search_all_sku_query')) {
	function etheme_search_all_sku_query($query){
		//add_filter('posts_join', 'etheme_search_post_join');
		add_filter('posts_where', 'etheme_search_post_excerpt');
	}
}

function etheme_search_post_join($join = ''){

   global $wp_the_query, $wpdb;

   // default
   $prefix = 'wp_';
   if ( $wpdb->prefix ) {
	   // current site prefix
	   $prefix = $wpdb->prefix;
   } elseif ( $wpdb->base_prefix ) {
	   // wp-config.php defined prefix
	   $prefix = $wpdb->base_prefix;
   }

   // escape if not woocommerce search query
   
   if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) )
	   return $join;

   $join .= 'INNER JOIN '.$prefix.'postmeta AS jcmt1 ON ('.$prefix.'posts.ID = jcmt1.post_id)';

   return $join;
}

if ( ! function_exists( 'etheme_search_post_excerpt' ) ) :

	function etheme_search_post_excerpt($where = ''){

		global $wp_the_query;
		global $wpdb;

		$prefix = 'wp_';
		if ( $wpdb->prefix ) {
		   // current site prefix
		   $prefix = $wpdb->prefix;
		} elseif ( $wpdb->base_prefix ) {
		   // wp-config.php defined prefix
		   $prefix = $wpdb->base_prefix;
		}

		// ! Filter by brands
		if ( isset( $_GET['filter_brand'] ) && ! empty($_GET['filter_brand']) ) {

			$brands = explode(',', $_GET['filter_brand']);
			$ids    = array();

			foreach ($brands as $key => $value) {
				$term = get_term_by('slug', $value, 'brand');
				if ( ! isset( $term->term_taxonomy_id ) || empty( $term->term_taxonomy_id ) ) // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
				{
				} else {
					$ids[] = $term->term_taxonomy_id;
				}
			}

			if ( ! implode( ',', $ids ) ) {
				$ids = 0;
			} else {
				$ids = implode( ',', $ids );
			}

			$where .= " AND " . $prefix . "posts.ID IN ( SELECT " . $prefix . "term_relationships.object_id  FROM " . $prefix . "term_relationships WHERE term_taxonomy_id  IN (" . $ids . ") )";

//			return $where;
		}
		
		$variable_products_detach = etheme_get_option('variable_products_detach', false);
//		$variable_products_no_parent = etheme_get_option('variation_product_parent_hidden', true);

		// ! WooCommerce search query
		if (is_search()){
			if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ) return $where;

			$s = $wp_the_query->query_vars['s'];

			// ! Search by sku
			if (etheme_get_option('search_by_sku_et-desktop', 1)){
				if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) ){
					$where .= " OR ( " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )
					AND " . $prefix . "posts.ID IN (
						SELECT ID FROM {$wpdb->prefix}posts
						LEFT JOIN {$wpdb->prefix}icl_translations ON {$wpdb->prefix}icl_translations.element_id = {$wpdb->prefix}posts.ID
						WHERE post_type = 'product'
						AND post_status = 'publish'
						AND {$wpdb->prefix}icl_translations.language_code = '". ICL_LANGUAGE_CODE ."'
					) )";
				} else {
					$where .= " OR " . $prefix . "posts.ID IN ( SELECT " . $prefix . "postmeta.post_id  FROM " . $prefix . "postmeta WHERE meta_key = '_sku' AND meta_value LIKE '%$s%' )";
					$where .= " AND post_type = 'product'";
				}
            }

			// ! Add product_variation to search result
			if ( etheme_get_option('search_product_variation_et-desktop', 0) || $variable_products_detach ){
//				if ( $variable_products_detach && $variable_products_no_parent ) {
//					$where .= "AND " . $prefix . "posts.ID NOT IN (SELECT posts.ID  FROM ".$prefix."posts AS posts
//                        INNER JOIN ".$prefix."term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//                        INNER JOIN ".$prefix."term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//                        INNER JOIN ".$prefix."terms AS terms ON term_taxonomy.term_id = terms.term_id
//                        WHERE
//                            term_taxonomy.taxonomy = 'product_type'
//                        AND terms.slug = 'variable')";
//                }
				$where .= " OR post_type = 'product_variation' AND post_status = 'publish' AND ( post_title LIKE '%$s%' OR post_excerpt LIKE '%$s%' OR post_content LIKE '%$s%' ) ";
			}
		}
//		elseif ( $variable_products_detach ) {
//			if ( empty( $wp_the_query->query_vars['wc_query'] ) ) return $where;
//
//			$visibility    = wc_get_product_visibility_term_ids();
//			if ( $variable_products_no_parent ) {
//				$where .= "AND " . $prefix . "posts.ID NOT IN (SELECT posts.ID  FROM " . $prefix . "posts AS posts
//                    INNER JOIN " . $prefix . "term_relationships AS term_relationships ON posts.ID = term_relationships.object_id
//                    INNER JOIN " . $prefix . "term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
//                    INNER JOIN " . $prefix . "terms AS terms ON term_taxonomy.term_id = terms.term_id
//                    WHERE
//                        term_taxonomy.taxonomy = 'product_type'
//                    AND terms.slug = 'variable')";
//			}
////			$where .= " OR (post_type = 'product_variation' AND post_status = 'publish')";
//			$product_visibility_terms  = wc_get_product_visibility_term_ids();
//			$where .= " OR (post_type = 'product_variation' AND post_status = 'publish' AND post_parent NOT IN (
//			    SELECT object_id FROM ".$prefix."term_relationships AS term
//    WHERE term_taxonomy_id IN (".implode(',',(array)$product_visibility_terms['exclude-from-catalog']).")))";
//
//
//        }

	   return $where;
	}
endif;

// **********************************************************************//
// ! Footer Type
// **********************************************************************//
if(!function_exists('etheme_footer_type')) {
	function etheme_footer_type() {
		return etheme_get_option('footer_type');
	}

	add_filter('custom_footer_filter', 'etheme_footer_type',10);
}

// **********************************************************************//
// ! Footer widgets class
// **********************************************************************//
if(!function_exists('etheme_get_footer_widget_class')) {
	function etheme_get_footer_widget_class($n) {
		$class = 'col-md-';
		switch ($n) {
			case 1:
				$class .= 12;
				break;
			case 2:
				$class .= 6;
				break;
			case 3:
				$class .= 4;
				break;
			case 4:
				$class .= 3;
				break;

			default:
				$class .= 3;
				break;
		}
		if( $n == 4 ) $class .= ' col-sm-6';
		return $class;
	}
}


// **********************************************************************//
// ! Get activated theme
// **********************************************************************//
if( ! function_exists( 'etheme_activated_theme' ) ) {
	function etheme_activated_theme() {
		$activated_data = get_option( 'etheme_activated_data' );

		// auto update option for old users
		
		update_option( 'envato_purchase_code_15780546', '3e1fffff58adaaaa3d0ceea2zbaaccg4' );
		
		$theme = true;
		return $theme;
	}

}

// **********************************************************************//
// ! Is theme activatd
// **********************************************************************//
if(!function_exists('etheme_is_activated')) {
	function etheme_is_activated() {
		if ( etheme_activated_theme() != ETHEME_PREFIX ) return false;
		if ( ! get_option( 'etheme_is_activated' ) ) update_option( 'etheme_is_activated', true );
		return get_option( 'etheme_is_activated', false );
	}
}


// **********************************************************************//
// ! http://codex.wordpress.org/Function_Reference/wp_nav_menu#How_to_add_a_parent_class_for_menu_item
// **********************************************************************//
add_filter( 'wp_nav_menu_objects', 'etheme_add_menu_parent_class');
function etheme_add_menu_parent_class( $items ) {
	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}
	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'menu-parent-item';
		}
	}
	return $items;
}

// **********************************************************************//
// ! Twitter API functions
// **********************************************************************//
if(!function_exists('etheme_capture_tweets')) {
	function etheme_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count) {

		$connection = etheme_connection_with_access_token($consumer_key,$consumer_secret,$user_token, $user_secret);
		$params = array(
			'screen_name' => $user,
			'count' => $count
		);

		$content = $connection->get("statuses/user_timeline",$params);

		return json_encode($content);
	}
}

if(!function_exists('etheme_connection_with_access_token')) {
	function etheme_connection_with_access_token($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret) {
		$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
		return $connection;
	}
}


if(!function_exists('etheme_tweet_linkify')) {
	function etheme_tweet_linkify($tweet) {
		$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet);
		$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet);
		$tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet);
		$tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet);
		return $tweet;
	}
}
if(!function_exists('etheme_store_tweets')) {
	function etheme_store_tweets($file, $tweets) {
		ob_start(); // turn on the output buffering
		$fo = etheme_fo($file, 'w'); // opens for writing only or will create if it's not there
		if (!$fo) return etheme_print_tweet_error(error_get_last());
		$fr = etheme_fw($fo, $tweets); // writes to the file what was grabbed from the previous function
		if (!$fr) return etheme_print_tweet_error(error_get_last());
		etheme_fc($fo); // closes
		ob_end_flush(); // finishes and flushes the output buffer;
	}
}

if(!function_exists('etheme_pick_tweets')) {
	function etheme_pick_tweets($file) {
		ob_start(); // turn on the output buffering
		$fo = etheme_fo($file, 'r'); // opens for reading only
		if (!$fo) return etheme_print_tweet_error(error_get_last());
		$fr = etheme_fr($fo, filesize($file));
		if (!$fr) return etheme_print_tweet_error(error_get_last());
		etheme_fc($fo);
		ob_end_flush();
		return $fr;
	}
}

if(!function_exists('etheme_print_tweet_error')) {
	function etheme_print_tweet_error($errorsArray) {
		$html = '';
		if( count($errorsArray) > 0 ){
			foreach ($errorsArray as $key => $error) {
				$html .= '<p class="warning">Error: ' . $error['message']  . '</p>';
			}
		}
		return $html;
	}
}

if(!function_exists('etheme_twitter_cache_enabled')) {
	function etheme_twitter_cache_enabled(){
		return apply_filters('etheme_twitter_cache_enabled', true);
	}
}

if(!function_exists('etheme_get_tweets')) {
	function etheme_get_tweets($consumer_key, $consumer_secret, $user_token, $user_secret, $user, $count, $cachetime=50, $key = 'widget') {
		if(etheme_twitter_cache_enabled()){
			//setting the location to cache file
			$cachefile = ETHEME_CODE_DIR . 'cache/cache-twitter-' . $key . '.json';

			// the file exitsts but is outdated, update the cache file
			if (file_exists($cachefile) && ( time() - $cachetime > filemtime($cachefile)) && filesize($cachefile) > 0) {
				//capturing fresh tweets
				$tweets = etheme_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count);
				$tweets_decoded = json_decode($tweets, true);
				//if get error while loading fresh tweets - load outdated file
				if(isset($tweets_decoded['errors'])) {
					$tweets = etheme_pick_tweets($cachefile);
				}
				//else store fresh tweets to cache
				else
					etheme_store_tweets($cachefile, $tweets);
			}
			//file doesn't exist or is empty, create new cache file
			elseif (!file_exists($cachefile) || filesize($cachefile) == 0) {
				$tweets = etheme_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count);
				$tweets_decoded = json_decode($tweets, true);
				//if request fails, and there is no old cache file - print error
				if(isset($tweets_decoded['errors'])) {
					echo etheme_print_tweet_error($tweets_decoded['errors']);
					return array();
				}
				//make new cache file with request results
				else
					etheme_store_tweets($cachefile, $tweets);
			}
			//file exists and is fresh
			//load the cache file
			else {
			   $tweets = etheme_pick_tweets($cachefile);
			}
		} else{
		   $tweets = etheme_capture_tweets($consumer_key,$consumer_secret,$user_token,$user_secret,$user, $count);
		}

		$tweets = json_decode($tweets, true);

		if(isset($tweets['errors'])) {
			echo etheme_print_tweet_error($tweets_decoded['errors']);
			return array();
		}

		return $tweets;
	}
}

// **********************************************************************//
// ! Related posts
// **********************************************************************//
if(!function_exists('etheme_get_related_posts')) {
	function etheme_get_related_posts($postId = false, $limit = 5){
		global $post;
		if(!$postId) {
			$postId = $post->ID;
		}

		$query_type = etheme_get_option('related_query', 'categories');
		$atts = array(
			'title' => esc_html__( 'Related posts', 'xstore' ),
			'echo' => true,
			'large' => 3,
			'notebook' => 3,
			'tablet_land' => 2,
			'tablet_portrait' => 2,
			'mobile' => 1,
			'size' => etheme_get_option('blog_related_images_size', 'medium'),
			'autoheight' => false,
			'slider_autoplay' => false,
			'slider_speed' => false,
		);
		$args = array();
		if($query_type == 'tags') {
			$tags = get_the_tags($postId);
			if ($tags) {
				$tags_ids = array();
				foreach($tags as $tag) $tags_ids[] = $tag->term_id;

				$args = array(
					'tag__in' => $tags_ids,
					'post__not_in' => array($postId),
					'showposts'=>$limit, // Number of related posts that will be shown.
				);
			}
		} else {
			$categories = get_the_category($postId);
			if ($categories) {
				$category_ids = array();
				foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

				$args = array(
					'category__in' => $category_ids,
					'post__not_in' => array($postId),
					'showposts'=>$limit, // Number of related posts that will be shown.
				);
			}
		}
		etheme_slider( $args, 'post' , $atts );
	}
}



if(!function_exists('etheme_get_menus_options')) {
	function etheme_get_menus_options() {
		$menus = array();
		$menus = array(""=>"Default");
		$nav_terms = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		foreach ( $nav_terms as $obj ) {
			$menus[$obj->slug] = $obj->name;
		}
		return $menus;
	}
}


// **********************************************************************//
// ! Get image by size function
// **********************************************************************//
if( ! function_exists('etheme_get_image') ) {
	function etheme_get_image($attach_id, $size, $location = '') {
		
		$type   = '';
		if ( !isset($_GET['vc_editable']) ) {
			$type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
		}
		
		$class = ( $location == 'slider' && $type !='default' && $type !='lqip' ) ? 'swiper-lazy' : '';
		
		if ($type == 'lqip') {
			if ( $size == 'woocommerce_thumbnail' ) {
				$placeholder = wp_get_attachment_image_src( $attach_id, 'etheme-woocommerce-nimi' );
			} else {
				$placeholder = wp_get_attachment_image_src( $attach_id, 'etheme-nimi' );
				
			}
			$class .= ' lazyload lazyload-lqip';
		} elseif($type == 'lazy'){
			$class .= ' lazyload lazyload-simple';
		}
		
		if (function_exists('wpb_getImageBySize')) {
			$image = wpb_getImageBySize( array(
				'attach_id' => $attach_id,
				'thumb_size' => $size,
				'class' => $class
			) );
			$image = (isset($image['thumbnail'])) ? $image['thumbnail'] : false;
		} elseif (!empty($size) && ( ( !is_array($size) && strpos($size, 'x') !== false ) || is_array($size) ) && defined('ELEMENTOR_PATH') ) {
			$size = is_array($size) ? $size : explode('x', $size);
			if ( ! class_exists( 'Group_Control_Image_Size' ) ) {
				require_once ELEMENTOR_PATH . '/includes/controls/groups/image-size.php';
			}
			$image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
				array(
					'image' => array(
						'id' => $attach_id,
					),
					'image_custom_dimension' => array('width' => $size[0], 'height' => $size[1]),
					'image_size' => 'custom',
					'hover_animation' => ' ' . $class
				)
			);
		}
		else {
			$image = wp_get_attachment_image( $attach_id, $size, false, array('class' => $class) );
		}
		if (isset($placeholder) && isset($placeholder[0]) && $type == 'lqip') {
			$new_attr = 'src="' . $placeholder[0] . '" data-src';
			$image = str_replace( 'src', $new_attr, $image );
		}
		
		if ( $type != 'default' ) {
			if ( $location == 'slider' ) {
				
				if ( $type != 'lqip') {
					$image = str_replace( 'src', 'data-src', $image );
				}
				// $image = str_replace( 'src', 'data-src', $image );
				//$image = str_replace( 'srcset', 'data-srcset', $image );
				$image = str_replace( 'sizes', 'data-sizes', $image );
			}
			
		}
		
		return $image;
	}
}

if ( ! function_exists( 'etheme_stock_taxonomy' ) ) :
function etheme_stock_taxonomy( $term_id = false, $taxonomy = 'product_cat', $category = false, $stock = true ) {
	if ( $term_id === false ) return false;
	$args = array(
		'post_type'         => 'product',
		'posts_per_page'    => -1,
		'tax_query'         => array(
			array(
				'taxonomy'  => $taxonomy,
				'field'     => 'term_id',
				'terms'     => $term_id
			),
		),
	);

	if ( $category ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'product_cat',
			'field'            => 'slug',
			'terms'            => $category,
			'include_children' => true,
			'operator'         => 'IN'
		);
	}

	$cat_prods = get_posts( $args );
	$i = 0;

	foreach ( $cat_prods as $single_prod ) {
		$product = wc_get_product( $single_prod->ID );

		if ( ! $stock ) {
			$i++;
		} elseif( $product->is_in_stock() === true ){
			$i++;
		}
	}

	return $i;
}
endif;

// **********************************************************************//
// ! Check file exists by url
// **********************************************************************//
if ( ! function_exists( 'etheme_custom_font_exists' ) ) :
	function etheme_custom_font_exists( $url ) {
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'] . '/custom-fonts';
		$url = explode( '/custom-fonts', $url );

		return file_exists( $upload_dir . $url[1] );
	}
endif;

// **********************************************************************//
// ! Force name sorting
// **********************************************************************//
if ( ! function_exists( 'et_force_name_sort' ) ) :
	function et_force_name_sort( $array, $order ){

		if ( is_wp_error( $array ) || count( $array ) <= 0 ) return;

		// ! Set values
		$to_sort = array();
		$sorted = array();

		// ! Set names array
		foreach ( $array as $key => $value ) {
			$to_sort[] = strtolower( $value->name );
		}

		// ! Sort names array
		sort( $to_sort );

		// ! Change order if need it
		if ( $order == 'DESC' ){
		   $to_sort = array_reverse( $to_sort );
		}

		// ! Set new sorted array
		foreach ( $to_sort as $key => $value ) {
			foreach ( $array as $k => $v ) {
				if ( $value == strtolower( $v->name ) ) {
					$sorted[] = $v;
				}
			}
		}
		return $sorted;
	}
endif;

function unicode_chars($source, $iconv_to = 'UTF-8') {
	$decodedStr = '';
	$pos = 0;
	$len = strlen ($source);
	while ($pos < $len) {
		$charAt = substr ($source, $pos, 1);
			$decodedStr .= $charAt;
			$pos++;
	}

	if ($iconv_to != "UTF-8") {
		$decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
	}
	
	return $decodedStr;
}

// For wpml test
apply_filters( 'wpml_current_language', NULL );

// rewrite default bbp separator with theme icon and removed wrapped p

function et_bbp_breadcrumb_sep() {
	$args['sep'] = '<i class="et-icon et-right-arrow"></i>';
	$args['before'] = '<div class="bbp-breadcrumb">';
	$args['after'] = '</div>';
	return $args;
}

add_filter('bbp_before_get_breadcrumb_parse_args', 'et_bbp_breadcrumb_sep' );

// **********************************************************************//
// ! Masonry
// **********************************************************************/
if ( ! function_exists( 'etheme_masonry' ) ) :
function etheme_masonry(){
   $masonry = false;

	if ( etheme_get_option( 'global_masonry', 0 ) ) {
		$masonry = true;
	} elseif ( class_exists( 'WooCommerce' ) && etheme_get_option( 'products_masonry', 0 ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$masonry = true;
	} elseif ( ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && 'post' == get_post_type() && etheme_get_option( 'blog_masonry', 1 ) ) {
		$masonry = true;
	} elseif ( ( etheme_get_option( 'portfolio_page', '') == get_the_id() || is_tax( 'portfolio_category' ) ) && etheme_get_option('portfolio_masonry', 1) ) {
		$masonry = true;
	}
	return $masonry;
}
endif;

// **********************************************************************//
// ! Add activation redirect
// **********************************************************************//
add_action( 'after_switch_theme', 'et_activation_redirect' );
if ( ! function_exists( 'et_activation_redirect' ) ) :
	function et_activation_redirect() {
		if ( isset($_GET['page']) && $_GET['page'] == '_options' ) {
			if ( !class_exists( 'Kirki' ) || !etheme_is_activated() ) {
				header( 'Location:' . admin_url( 'admin.php?page=et-panel-welcome' ) );
			}
			else {
				header( 'Location:' . wp_customize_url() );
			}
		}
	}
endif;

// **********************************************************************//
// ! Add custom fonts to customizer typography
// **********************************************************************//
function et_kirki_custom_fonts( $standard_fonts ){
	$etheme_fonts = get_option( 'etheme-fonts', false );
	if ( ! is_array($etheme_fonts) || count( $etheme_fonts ) < 1 ) {
		return $standard_fonts;
	}
	$custom_fonts = array();

	foreach ( $etheme_fonts as $value ) {
		$custom_fonts[$value['name']] = array(
			'label' => $value['name'],
			'variant' => '400',
			'stack' => '"'.$value['name'].'"'
		);
	}

	$std_fonts = array(
			"Arial, Helvetica, sans-serif",
			"Courier, monospace",
			"Garamond, serif",
			"Georgia, serif",
			"Impact, Charcoal, sans-serif",
			"Tahoma,Geneva, sans-serif",
			"Verdana, Geneva, sans-serif",
		);

	foreach ( $std_fonts as $value) {
		$custom_fonts[$value] = array(
			'label' => $value,
			'variant' => '400',
			'stack' => $value
		);
	}

	return array_merge_recursive( $custom_fonts, $standard_fonts );
}
add_filter( 'kirki/fonts/standard_fonts', 'et_kirki_custom_fonts', 20 );

// **********************************************************************//
// ! Hex to rgba
// **********************************************************************//
if ( ! function_exists( 'et_hex_to_rgba' ) ) {
	function et_hex_to_rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		if ( empty( $color ) ) return $default;
		if ( $color[0] == '#' ) $color = substr( $color, 1 );

		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		$rgb =  array_map( 'hexdec', $hex );

		if( $opacity !== false ){
			if( abs( $opacity ) > 1 ) $opacity = 1.0;
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		return $output;
	}
}

if( ! function_exists( 'etheme_blog_header' ) ){
	function etheme_blog_header() {
		global $wp_query;
		$cat = $wp_query->get_queried_object();

		if( etheme_get_option( 'blog_page_banner', '' ) != '' ){
			echo '<div class="category-description">';
			echo do_shortcode( etheme_get_option( 'blog_page_banner', '' ) );
			echo '</div>';
		} else {
			return;
		}
	}
}

add_action( 'wp_ajax_et_ajax_blog_element', 'et_ajax_blog_element');
add_action( 'wp_ajax_nopriv_et_ajax_blog_element', 'et_ajax_blog_element');
if ( ! function_exists( 'et_ajax_blog_element' ) ) {
	function et_ajax_blog_element(){

		$atts = '';
		$args = ( isset( $_POST['args'] ) ) ? $_POST['args'] : array() ;
		$args['ajax'] = false;
		foreach ( $args as $key => $value ) {
			// ! Do it because js change data type
			if ( $value === 'false' ) $value = false;

			$atts .= $key . '="' . $value . '" ';
		}
		echo do_shortcode( '[' . $_POST[ 'element' ] . ' ' . $atts . ' paged="' . $_POST[ 'paged' ] . '" html_type="true" ]' );
		die();
	}
}


// **********************************************************************//
// ! Ajax response for shortcodes/VC elements loading
// **********************************************************************//
add_action( 'wp_ajax_et_ajax_element', 'et_ajax_element');
add_action( 'wp_ajax_nopriv_et_ajax_element', 'et_ajax_element');
if ( ! function_exists( 'et_ajax_element' ) ) {
	function et_ajax_element(){
		if ( ! isset( $_POST[ 'element' ] ) ) die();

		$atts = '';
		$args = ( isset( $_POST['args'] ) ) ? $_POST['args'] : array() ;
		$args['ajax'] = false;

		if (isset($_POST[ 'type' ]) && $_POST[ 'type' ] == 'widget'){
		    $widget_args = (isset($_POST[ 'widget_args' ])) ? et_normalize_widget_args($_POST[ 'widget_args' ]) : array();
			the_widget( 'ETC\App\Models\Widgets\\' . $_POST[ 'element' ],$args, $widget_args );
			die();
        }

		foreach ( $args as $key => $value ) {
			// ! Do it because js change data type
			if ( $value === 'false' ) $value = false;

			$atts .= $key . '="' . $value . '" ';
		}

		add_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		add_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
		add_filter( 'etheme_output_shortcodes_inline_css', function() { return true; } );

		// this add variation gallery filters at loop start and remove it after loop end
//        if ( !$_POST['archiveVariationGallery'] ) {
			add_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
			add_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
//        }

		add_filter('woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2);

		if ( isset( $_POST[ 'content' ] ) && ! empty( $_POST[ 'content' ] ) ) {
			// ! Do it because js add backslash
			$content = stripslashes( $_POST[ 'content' ] );
			$content = do_shortcode( $content ) . '[/' . $_POST[ 'element' ] . ']';
		} else {
			$content = '';
		}
		echo do_shortcode( '[' . $_POST[ 'element' ] . ' ' . $atts . ' ]' . $content );
		die();
	}
}

function et_normalize_widget_args($args){
	$args = stripslashes($args);
	$args = json_decode( $args, true);
	$args = str_replace('u0022', '"',$args );
	if (isset($args['before_title'])){
		$args['before_title'] = html_entity_decode($args['before_title']);
	}
	if (isset($args['after_title'])){
		$args['after_title'] = html_entity_decode($args['after_title']);
    }
	if (isset($args['before_widget'])){
		$args['before_widget'] = html_entity_decode($args['before_widget']);
	}
	if (isset($args['after_widget'])){
		$args['after_widget'] = html_entity_decode($args['after_widget']);
	}
	return $args;
}

// **********************************************************************//
// ! Ajax holder for shortcodes/VC elements loading
// **********************************************************************//
if ( ! function_exists( 'et_ajax_element_holder' ) ) {
	function et_ajax_element_holder($element = false, $atts = array(), $extra = '', $content = false, $type = 'element', $widget = array()){
		if ( ! $element ) return;

		if ( $content ) {
			$content = '<span class="hidden et-element-content" type="text/template">' . $content . '</span>';
		}

		if (count($widget)){
			$content .= '<span class="hidden et-element-args_widget" type="text/template">' . esc_js(json_encode($widget, JSON_HEX_QUOT) ). '</span>';
        }

		$output = '
			<div class="et-load-block lazy-loading et-ajax-element type-'.$type.'" type="'.$type.'" extra="' . $extra . '" element="' . $element . '">
				' . etheme_loader(false, 'no-lqip') . '
				<span class="hidden et-element-args" type="text/template">' . json_encode( $atts ) . '</span>
				' . $content . '
			</div>
		';
		return $output;
	}
}

// **********************************************************************//
// Let's search for variations also in visual composer our elements
// **********************************************************************//

function et_vc_include_field_search ( $search_string ) {

	$query = $search_string;
	$data = array();
	$args = array(
		's' => $query,
		'post_type' => 'any',
	);
	$args['vc_search_by_title_only'] = true;
	$args['numberposts'] = - 1;
	if ( 0 === strlen( $args['s'] ) ) {
		unset( $args['s'] );
	}
	add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
	$posts = get_posts( $args );
	if ( is_array( $posts ) && ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			if ( $post->post_type == 'product' ) {
				$title = $post->post_title;
				$data[] = array (
					'value' => $post->ID,
					'label' => $title,
					'group' => $post->post_type,
				);
				$_product = wc_get_product($post->ID);
				if ( $_product->is_type('variable') ) {
					$attributes = $_product->get_available_variations();
					foreach ($attributes as $key) {
						$variation_group = $key['attributes'];
						$variation_attributes = '';
						$_i = 0;
						$delimiter = ' ';
						foreach ($variation_group as $key2 => $value ) {
							if ( $_i > 0 ) $delimiter = ', ';
							$variation_attributes .= $delimiter . str_replace(array('attribute_', 'pa_'), '', $key2).':'.$value;
							$_i++;
						}
						$data[] = array (
							'value' => $key['variation_id'],
							'label' => $title . ' ( ' . $variation_attributes . ' )',
							'group' => 'product_variation'
						);
					}
				}
			}
			else {
				$data[] = array(
					'value' => $post->ID,
					'label' => $post->post_title,
					'group' => $post->post_type,
				);
			}
		}
	}

	return $data;

}

function et_vc_include_field_render( $value ) {

	$val = $value['value'];

	$post_type = get_post_type( $val );

	if ( $post_type == 'product_variation' ) {
		$post = wc_get_product($val);
		$attributes = $post->get_attributes();
		$variation_attributes = '';
		$_i = 0;
		$delimiter = ' ';
		foreach ($attributes as $key => $value) {
			if ( $_i > 0 ) $delimiter = ', ';
			$variation_attributes .= $delimiter . str_replace(array('attribute_', 'pa_'), '', $key).':'.$value;
			$_i++;
		}
		return array (
			'value' => $post->get_ID(),
			'label' => $post->get_ID() . ' - ' . $post->get_title() . ' ( ' . $variation_attributes . ' )',
			'group' => $post->post_type
		);
	}
	else {

		$post = get_post( $val );

		return array (
			'value' => $post->ID,
			'label' => $post->ID . ' - ' . $post->post_title,
			'group' => $post->post_type,
		);

	}
}

// **********************************************************************//
// ! Visibility of next/prev pruduct
// **********************************************************************//

if ( ! function_exists('et_visible_pruduct') ) :
	function et_visible_pruduct( $id, $valid ){
		$product = wc_get_product( $id );

		// updated for woocommerce v3.0
		$visibility = $product->get_catalog_visibility();
		$stock = $product->is_in_stock();

		if (  $visibility  != 'hidden' &&  $visibility  != 'search' && $stock ) {
			return get_post( $id );
		}

		$the_query = new WP_Query( array( 'post_type' => 'product', 'p' => $id ) );

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$valid_post = ( $valid == 'next' ) ? get_adjacent_post( 1, '', 0, 'product_cat' ) : get_adjacent_post( 1, '', 1, 'product_cat' );
				if ( empty( $valid_post ) ) return;
				$next_post_id = $valid_post->ID;
				$visibility = wc_get_product( $next_post_id );
				$stock = $visibility->is_in_stock();
				$visibility = $visibility->get_catalog_visibility();

			}
			// Restore original Post Data
			wp_reset_postdata();
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $stock ) {
			return et_visible_pruduct( $next_post_id, $valid );
		}

		if ( $visibility == 'visible' || $visibility == 'catalog' && $stock ) {
			return $valid_post;
		} else {
			return et_visible_pruduct( $next_post_id, $valid );
		}
		
	}
endif;

// **********************************************************************//
// ! Project links
// **********************************************************************//

if ( ! function_exists('etheme_project_links') ) :
	function etheme_project_links( $atts, $content = null ) {

		global $post;
		$is_product = false;

		if ( $post->post_type == 'product' ) {
			$is_product = true;

			$next_post = get_adjacent_post( 1, '', 0, 'product_cat' );
			$prev_post = get_adjacent_post( 1, '', 1, 'product_cat' );

			if ( ! empty( $next_post ) && $next_post->post_type == 'product' ) {
				$next_post = et_visible_pruduct( $next_post->ID, 'next' );
			}

			if ( ! empty( $prev_post ) && $prev_post->post_type == 'product' ) {
				$prev_post = et_visible_pruduct( $prev_post->ID, 'prev' );
			}

			if (is_null($prev_post)){
				$prev_post_id = '';
			} else {
				if ( empty($prev_post) && !is_object(get_previous_post())) {
					$prev_post_id = '';
				}
				else {
					$prev_post_id = empty( $prev_post ) ? get_previous_post()->ID : $prev_post->ID;
				}
			}
			if (is_null($next_post)){
				$next_post_id = '';
			} else {
				if ( empty($next_post) && !is_object(get_next_post())) {
					$next_post_id = '';
				}
				else {
					$next_post_id = empty($next_post) ? get_next_post()->ID : $next_post->ID;
				}
			}

			if ( empty($next_post) || empty($prev_post)) {
				$post_id        = $post->ID; // current post ID
				$product = wc_get_product($post_id);
				$args = array(
					'post_type'             => 'product',
					'post_status'           => 'publish',
					'ignore_sticky_posts'   => 1,
					'posts_per_page'        => '12',
					'orderby'    => 'date',
					'tax_query'             => array(
						array(
							'taxonomy'      => 'product_cat',
							'field' => 'term_id', //This is optional, as it defaults to 'term_id'
							'terms'         => $product->get_category_ids(),
							'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
						),
						array(
							'taxonomy'      => 'product_visibility',
							'field'         => 'slug',
							'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
							'operator'      => 'NOT IN'
						),
					)
				);
				$args['order'] = 'ASC';
				$products = new WP_Query($args);

				// get IDs of posts retrieved from get_posts
				$ids = array();
				while ( $products->have_posts() ) : $products->the_post(); global $product;
					$ids[] = $product->get_ID();
				endwhile;

				// get and echo previous and next post in the same category
				$index    = array_search( $post_id, $ids );

				if ( ( count($ids) - 1 ) > 2 ) {
					if ( empty($prev_post) ) {
						// $prev_post = $prev_post_id = isset( $ids[ $index - 1 ] ) ? $ids[ $index - 1 ] : 0;
						$prev_post = $prev_post_id = end($ids);

						if ( !empty($prev_post)) {
							$prev_post    = et_visible_pruduct( $prev_post, 'prev' );
							$prev_post_id = $prev_post->ID;
						}
					}
					if ( empty($next_post)) {
						//$next_post = $next_post_id = isset( $ids[ $index + 1 ] ) ? $ids[ $index + 1 ] : 0;
						$next_post = $next_post_id = $ids[0];
						if ( !empty($next_post)) {
							$next_post    = et_visible_pruduct( $next_post, 'next' );
							$next_post_id = $next_post->ID;
						}
					}
				}
			}

		} else {
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ($next_post){
				$next_post_id = $next_post->ID;
			}

			if ($prev_post){
				$prev_post_id = $prev_post->ID;
			}
		}
		?>
        <div class="posts-navigation">
			<?php if(!empty($prev_post)) :
				if ( function_exists('mb_strlen') ) {
					$prev_symbols = (mb_strlen(get_the_title($prev_post_id)) > 30) ? '...' : '';
					$title = mb_substr(get_the_title($prev_post_id),0,30) . $prev_symbols;
				}
				else {
					$prev_symbols = (strlen(get_the_title($prev_post_id)) > 30) ? '...' : '';
					$title = substr(get_the_title($prev_post_id),0,30) . $prev_symbols;
				}?>
                <div class="posts-nav-btn prev-post">
                    <div class="post-info">
                        <div class="post-details">
                            <a href="<?php echo get_permalink($prev_post_id); ?>" class="post-title">
								<?php echo wp_specialchars_decode($title); ?>
                            </a>
							<?php if ( $is_product ) {
								$p = wc_get_product($prev_post);
								echo '<p class="price">'.$p->get_price_html().'</p>';
							} ?>
                        </div>
                        <a href="<?php echo get_permalink($prev_post_id); ?>">
							<?php $img = get_the_post_thumbnail( $prev_post_id, array(90, 90));
							echo (!empty($img) ) ? $img : '<img src="'.ETHEME_BASE_URI.'images/placeholder.jpg">';  ?>
                        </a>
                    </div>
                    <span class="post-nav-arrow">
                        <i class="et-icon et-left-arrow"></i>
                    </span>
                </div>
			<?php endif; ?>

			<?php if(!empty($next_post)) :
				if ( function_exists('mb_strlen') ) {
					$next_symbols = (mb_strlen(get_the_title($next_post_id)) > 30) ? '...' : '';
					$title = mb_substr(get_the_title($next_post_id),0,30) . $next_symbols;
				}
				else {
					$next_symbols = (strlen(get_the_title($next_post_id)) > 30) ? '...' : '';
					$title = substr(get_the_title($next_post_id),0,30) . $next_symbols;
				} ?>
                <div class="posts-nav-btn next-post">
					<span class="post-nav-arrow">
                        <i class="et-icon et-right-arrow"></i>
                    </span>
                    <div class="post-info">
                        <a href="<?php echo get_permalink($next_post_id); ?>">
							<?php $img = get_the_post_thumbnail( $next_post_id, array(90, 90));
							echo (!empty($img) ) ? $img : '<img src="'.ETHEME_BASE_URI.'images/placeholder.jpg">';  ?>
                        </a>
                        <div class="post-details">
                            <a href="<?php echo get_permalink($next_post_id); ?>" class="post-title">
								<?php echo wp_specialchars_decode($title); ?>
                            </a>
							<?php if ( $is_product ) {
								$p = wc_get_product($next_post);
								echo '<p class="price">'.$p->get_price_html().'</p>';
							} ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
        </div>
		<?php wp_reset_query();
	}
endif;

// dokan compatibles
$dokan_compatibles_actions = array(
	'start_wrap' => array(
		'dokan_edit_product_wrap_before',
		'dokan_dashboard_wrap_before',
	),
	'end_wrap' => array(
		'dokan_edit_product_wrap_after',
		'dokan_dashboard_wrap_after'
	),
);

foreach ($dokan_compatibles_actions['start_wrap'] as $key => $value) {
	add_action($value, function(){ ?>
	<div class="container content-page sidebar-mobile-bottom">
		<div class="sidebar-position-without">
			<div class="row">
				<div class="content col-md-12">
	<?php });
}

foreach ($dokan_compatibles_actions['end_wrap'] as $key => $value) {
		add_action($value, function(){ ?>
				</div>
			</div>
		</div>
	</div>
	<?php });
}


function et_time_2_remove_header(){
	$today     = time();
	$event     = mktime( 0,0,0,3,17,2021 );
	return '<strong>' . round( ( $event - $today ) / 86400 ) . ' ' . esc_html__('days', 'xstore') . '</strong>';
}


// **********************************************************************//
// ! Notice "Plugin version"
// **********************************************************************//
add_action( 'admin_notices', 'etheme_required_core_notice', 50 );
add_action( 'wp_body_open', 'etheme_required_plugin_notice_frontend', 50 );

function etheme_required_core_notice(){

	$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

	if (
		count($xstore_branding_settings)
		&& isset($xstore_branding_settings['control_panel'])
		&& isset($xstore_branding_settings['control_panel']['hide_updates'])
		&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
	){
		return;
	}


    $file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';

    if ( ! file_exists($file) ) return;

    $plugin = get_plugin_data( $file, false, false );

    if ( version_compare( ETHEME_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
        $video = '<a class="et-button" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank" style="color: white!important; text-decoration: none"><span class="dashicons dashicons-video-alt3" style="color: var(--et_admin_red-color, #c62828);git "></span> Video tutorial</a>';

        echo '
        <div class="et-message et-warning">
            This theme version requires the following plugin <strong>XStore Core</strong> to be updated up to <strong>' . ETHEME_CORE_MIN_VERSION . ' version. </strong>You can install the updated version of XStore core plugin: <ul>
                <li>1) via <a href="'.admin_url('update-core.php').'">Dashboard</a> > Updates > click Check again button > update plugin</li>
                <li>2) via FTP using archive from <a href="https://www.8theme.com/downloads" target="_blank">Downloads</a></li>
                <li>3) via FTP using archive from the full theme package downloaded from <a href="https://themeforest.net/" target="_blank">ThemeForest</a></li>
                <li>4) via <a href="https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/" target="_blank">Easy Theme and Plugin Upgrades</a> WordPress Plugin</li>
                <li>5) Don\'t Forget To Clear <strong style="color:#c62828;"> Cache! </strong></li>
                </ul>
                <br>
                ' . $video . '
                <br><br>
        </div>
    ';
    }
}

function etheme_required_plugin_notice_frontend(){
	if ( is_user_logged_in() && current_user_can('administrator') ) {

		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

		if (
			count($xstore_branding_settings)
			&& isset($xstore_branding_settings['control_panel'])
			&& isset($xstore_branding_settings['control_panel']['hide_updates'])
			&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
		){
			return;
		}

		if( !function_exists('get_plugin_data') ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$file = ABSPATH . 'wp-content/plugins/et-core-plugin/et-core-plugin.php';

		if ( ! file_exists($file) ) return;

		$plugin = get_plugin_data( $file, false, false );

		if ( version_compare( ETHEME_CORE_MIN_VERSION, $plugin['Version'], '>' ) ) {
			$video = '<a class="et-button et-button-active" href="https://www.youtube.com/watch?v=xMEoi3rKoHk" target="_blank"> Video tutorial</a>';
			echo '
				</br>
				<div class="woocommerce-massege woocommerce-info error">
					XStore theme requires the following plugin: <strong>Core plugin v.' . ETHEME_CORE_MIN_VERSION . '.</strong>
					'.$video.'. This warning is visible for <strong>administrator only</strong>.
				</div>
			';
		}
	}
}


// **********************************************************************//
// ! add sizes for LQIP
// **********************************************************************//
$cropping = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

if ( 'uncropped' === $cropping ) {
	add_image_size( 'etheme-woocommerce-nimi', 10, 10 );
} elseif ( 'custom' === $cropping ) {
	$width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
	$height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
	add_image_size( 'etheme-woocommerce-nimi', $width, $height );
} else {
	$cropping_split = explode( ':', $cropping );
	$width          = max( 1, current( $cropping_split ) );
	$height         = max( 1, end( $cropping_split ) );
	add_image_size( 'etheme-woocommerce-nimi', $width, $height );
}

add_image_size( 'etheme-nimi', 10, 10 );
add_action( 'wp', 'etheme_lazy_attachment' );

// remove lazy on email sent
add_action('woocommerce_email_before_order_table', function(){
	remove_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
});
add_action('woocommerce_email_after_order_table', function() {
	add_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
});

// **********************************************************************//
// ! add LQIP
// **********************************************************************//
function etheme_lazy_attachment(){
	// Remove it after global lazy finish
	if ( ! is_admin() && defined('ET_CORE_VERSION') ) {

		// tweak for dokan img attributes with lazy load
		add_filter( 'dokan_product_image_attributes', function( $attr ) {
			$attr['img'] = array_merge( $attr['img'], array(
				'data-src' => array(),
				'data-l-src' => array(),
				'data-sizes' => array(),
				'data-srcset' => array(),
				'srcset' => array(),
			));
			return $attr;
		});

		add_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
	}
}


// **********************************************************************//
// ! add LQIP attr
// **********************************************************************//
function etheme_lazy_attachment_attrs($attr, $attachment, $size){

	// Remove it after global lazy finish
	if ( strpos( $attr['class'], 'swiper-lazy' ) !== false || strpos( $attr['class'], 'lazyload' ) !== false || isset($_GET['vc_editable']) ) {
		return $attr;
	}

	$type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
	$srcset = get_theme_mod( 'images_srcset_type_et-desktop' );

	switch ($type) {
		case 'lqip':
			// Set LQIP
			if ( $size == 'woocommerce_thumbnail' ) {
				$placeholder = wp_get_attachment_image_src( $attachment->ID, 'etheme-woocommerce-nimi' );
			}
			else {
				$placeholder = wp_get_attachment_image_src( $attachment->ID, 'etheme-nimi' );
			}

			$placeholder = $placeholder[0];
			if ( strpos($attr['class'], 'attachment-shop_single') === false  ) {
				$attr['data-src']      = $attr['src'];
			}
			$attr['src']    = $placeholder;
			$attr['class'] .= ' lazyload lazyload-lqip';
			break;
		case 'lazy':
		// return $attr;
			$attr['class']        .= ' lazyload lazyload-simple';
			if ( isset( $attr['data-src']) ) {
				// only for single product image zoom
				$attr['data-l-src'] = $attr['src'];
				if ( isset($attr['data-etheme-single-main']) ){
					return $attr;
				}
			} else {
				$attr['data-src']  = $attr['src'];
			}
			unset( $attr['src'] );
			break;
		default:
			return $attr;
			break;
	}

	$attr['data-sizes']    = 'auto';

	// Set srcset
	if ( isset( $attr['srcset'] ) ) {
		$attr['data-srcset'] = $attr['srcset'];
			// $attr['srcset'] = $srcset;
		unset( $attr['srcset'] );
	}
	return $attr;
}


// **********************************************************************//
// ! add action for etheme_prefooter
// **********************************************************************//
add_action( 'etheme_prefooter', 'etheme_prefooter_content', 10 );

function etheme_prefooter_content(){
	get_template_part( 'templates/footer/prefooter');

}


// **********************************************************************//
// ! add actions for etheme_footer
// **********************************************************************//
add_action( 'etheme_footer', 'etheme_footer_content', 10 );

function etheme_footer_content(){
	get_template_part( 'templates/footer/footer');
}

add_action( 'etheme_footer', 'etheme_copyrights_content', 20 );
function etheme_copyrights_content(){
	get_template_part( 'templates/footer/copyrights');
}

// compatibility with elementor header/footer builders
// rewritten due to a single post template error
function etheme_register_elementor_locations( $elementor_theme_manager ) {

    // the default locations
	$core_locations = $elementor_theme_manager->get_core_locations();

	// do not rewrite this locations
	unset($core_locations['archive']);
	unset($core_locations['single']);

	foreach ( $core_locations as $location => $settings ) {
	    // rewrite locations to default
		$elementor_theme_manager->register_location( $location, $settings );
	}

	// previse rewritten all locations
    //$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'etheme_register_elementor_locations' );

add_action( "elementor/theme/before_do_header", function() {
	ob_start();
	
	do_action( 'et_after_body', true )
	
	?>
	<div class="template-container">
	
	<?php
	/**
	 * Hook: etheme_header_before_template_content.
	 *
	 * @hooked etheme_top_panel_content - 10
	 * @hooked etheme_mobile_menu_content - 20
	 *
	 * @version 6.0.0 +
	 * @since 6.0.0 +
	 *
	 */
	do_action( 'etheme_header_before_template_content' );
	?>
	<div class="template-content">
	<div class="page-wrapper">
	<?php
	echo ob_get_clean();
} );

add_action( "elementor/theme/before_do_footer", function() {
	ob_start(); ?>
	</div> <!-- page wrapper -->

	</div> <!-- template-content -->
	
	<?php do_action('after_page_wrapper'); ?>
	</div> <!-- template-container -->
	<?php echo ob_get_clean();
});


// **********************************************************************//
// ! Get query custom field
// **********************************************************************//
function etheme_get_query_custom_field($field){
	$page    = get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
	$page_id =  ( isset( $page['id'] ) ) ? $page['id'] : false;

	if ( $page_id ) {
		$field = etheme_get_custom_field($field, $page_id);
	}else{
		$field = false;
	}

	return $field;
}

function etheme_get_image_sizes( $size = '' ) {
	$wp_additional_image_sizes = wp_get_additional_image_sizes();
	
	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();
	
	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
		} elseif ( isset( $wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width' => $wp_additional_image_sizes[ $_size ]['width'],
				'height' => $wp_additional_image_sizes[ $_size ]['height'],
				'crop' =>  $wp_additional_image_sizes[ $_size ]['crop']
			);
		}
	}
	
	// Get only 1 size if found
	if ( $size ) {
		if( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}
	return $sizes;
}

function etheme_get_demo_versions(){
	$versions   = get_transient( 'etheme_demo_versions_info' );
	$url        = 'http://8theme.com/import/xstore-demos/1/versions';
	$url        = apply_filters( 'etheme_demos_url', $url);

	if ( ! $versions || empty( $versions ) || isset($_GET['etheme_demo_versions_info']) ) {
		$api_response = wp_remote_get( $url );
		$code         = wp_remote_retrieve_response_code( $api_response );

		if ( $code == 200 ) {
			$api_response = wp_remote_retrieve_body( $api_response );
			$api_response = json_decode( $api_response, true );
			$versions = $api_response;
			set_transient( 'etheme_demo_versions_info', $versions, 48 * HOUR_IN_SECONDS );
		} else {
			$versions = array();
		}
	}
	return $versions;
}

add_filter( 'woocommerce_create_pages', 'etheme_do_not_setup_demo_pages', 10 );
function etheme_do_not_setup_demo_pages($args){
    if (
        isset($_REQUEST['action'])
        && $_REQUEST['action'] == 'install_pages'
        && isset($_REQUEST['page'])
        && $_REQUEST['page'] == 'wc-status'
    ){
	    return $args;
    }
    return array();
}

function etheme_get_current_page_url() {
	if ( defined('SHOP_IS_ON_FRONT') ) {
		$link = home_url();
	} elseif ( is_shop() ) {
		$link = get_permalink( wc_get_page_id( 'shop' ) );
	} elseif ( is_product_category() ) {
		$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
	} elseif ( is_product_tag() ) {
		$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
	} else {
		$queried_object = get_queried_object();
		if ( is_object($queried_object)) {
			$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
		}
		else {
			$link = home_url();
		}
	}

	// Min/Max.
	if ( isset( $_GET['min_price'] ) ) {
		$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
	}

	if ( isset( $_GET['max_price'] ) ) {
		$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
	}

	// Order by.
	if ( isset( $_GET['orderby'] ) ) {
		$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
	}

	// Brand
	if ( isset( $_GET['filter_brand'] ) ) {
		$link = add_query_arg( 'filter_brand', wc_clean( wp_unslash( $_GET['filter_brand'] ) ), $link );
	}
	
	// Stock Status
	if ( isset( $_GET['stock_status'] ) ) {
		$link = add_query_arg( 'stock_status', wc_clean( wp_unslash( $_GET['stock_status'] ) ), $link );
	}

	/**
	 * Search Arg.
	 * To support quote characters, first they are decoded from " entities, then URL encoded.
	 */
	if ( get_search_query() ) {
		$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
	}

	// Post Type Arg.
	if ( isset( $_GET['post_type'] ) ) {
		$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

		// Prevent post type and page id when pretty permalinks are disabled.
		if ( is_shop() ) {
			$link = remove_query_arg( 'page_id', $link );
		}
	}

	// Min Rating Arg.
	if ( isset( $_GET['rating_filter'] ) ) {
		$link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
	}

	// All current filters.
	if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
		foreach ( $_chosen_attributes as $name => $data ) {
			$filter_name = wc_attribute_taxonomy_slug( $name );
			if ( ! empty( $data['terms'] ) ) {
				$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
			}
			if ( 'or' === $data['query_type'] ) {
				$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
			}
		}
	}

	if ( ! $link){
	    global $wp;
		$link = home_url( $wp->request );
	}

	return $link;
}

add_filter('et_ajax_widgets', 'et_ajax_widgets');
if (! function_exists('et_ajax_widgets')){
	function et_ajax_widgets($ajax){
		if (isset($_GET['et_ajax'])){
			return false;
		}
		if ( wp_is_mobile() && etheme_get_option('sidebar_for_mobile', 'off_canvas') == 'off_canvas' ) {
			return false;
		}
		return $ajax;
	}
}

if( ! function_exists('etheme_is_catalog') ){
	function etheme_is_catalog(){
		if ( etheme_get_option( 'just_catalog', 0 ) ){
			if ( etheme_get_option( 'just_catalog_type', 'all' ) == 'unregistered' && is_user_logged_in()){
				return false;
			} else {
				return true;
			}
		}
		return false;
	}
}