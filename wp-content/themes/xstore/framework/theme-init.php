<?php if ( ! defined( 'ETHEME_FW' ) ) {
	exit( 'No direct script access allowed' );
}

// **********************************************************************//
// ! Set Content Width
// **********************************************************************//
if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

// **********************************************************************//
// ! Include CSS and JS
// **********************************************************************//
if ( ! function_exists( 'etheme_enqueue_scripts' ) ) {
	function etheme_enqueue_scripts() {
		if ( ! is_admin() ) {

			$theme = wp_get_theme();

			$etheme_scripts = array( 'jquery' );

			$load_wc_cart_fragments = etheme_get_option( 'load_wc_cart_fragments', 0 );
			
			$is_woocommerce = class_exists('WooCommerce');
			$is_product = $is_woocommerce && is_product();

			if ( is_singular() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if ( etheme_get_option( 'et_optimize_js' ) ) {
				wp_enqueue_script( 'etheme_optimize', get_template_directory_uri() . '/js/etheme.optimize.min.js', array(), false, true );
			}

			wp_enqueue_script( 'et_imagesLoaded', get_template_directory_uri() . '/js/libs/imagesLoaded.js', array(), '4.1.4', true );

			if ( etheme_masonry() ) {
				wp_enqueue_script( 'et_masonry', get_template_directory_uri() . '/js/libs/isotope.js', array(), '3.0.6', true );
			}

			if ( $is_product ) {
				wp_enqueue_script( 'photoswipe_optimize', get_template_directory_uri() . '/js/photoswipe-optimize.min.js', array(), $theme->version, true );
			}

			$single_template = get_query_var( 'et_post-template', 'default' );

			if ( in_array( $single_template, array(
					'large',
					'large2'
				) ) && has_post_thumbnail() && is_singular( apply_filters( 'etheme_backstretch_enqueue', array( 'post', 'wpsl_stores' ) ) ) ) {
				wp_enqueue_script( 'backstretch_single', get_template_directory_uri() . '/js/libs/jquery.backstretch.min.js', array(), '2.1.18', true );
				wp_enqueue_script( 'backstretch_single_postImg', get_template_directory_uri() . '/js/postBackstretchImg.min.js', array('backstretch_single'), $theme->version, true );
			}

			if ( get_query_var( 'etheme_single_product_variation_gallery', false ) ) {
				$etheme_scripts[] = 'wp-util';
			}

			if ( $is_woocommerce && ! $load_wc_cart_fragments && ! WC()->cart->cart_contents_count){
				wp_enqueue_script( 'etheme-mini-cart', get_template_directory_uri() . '/js/mini-cart.min.js', $etheme_scripts, $theme->version, true );
			}
			if ( class_exists( 'Woocommerce' ) && is_product() ) {
				$product = wc_get_product ( get_the_ID() );
				if( $product->is_type( 'variable' ) && etheme_get_option( 'ajax_add_to_cart', 1 ) ) {
					wp_enqueue_script( 'etheme-spv-ajax', get_template_directory_uri() . '/js/ajax-spv-add-to-cart.min.js', $etheme_scripts, $theme->version, true );
				}
			}

			//wp_enqueue_script( 'etheme_p', get_template_directory_uri() . '/js/etheme.plugins.min.js', $etheme_scripts, $theme->version, true );
			//wp_enqueue_script( 'etheme', get_template_directory_uri() . '/js/etheme_neo.min.js', $etheme_scripts, $theme->version, true );
			wp_enqueue_script( 'etheme', get_template_directory_uri() . '/js/etheme.min.js', $etheme_scripts, $theme->version, true );

			if ( etheme_get_option('portfolio_projects') ) {
				wp_enqueue_script( 'portfolio', get_template_directory_uri() . '/js/portfolio.min.js', array_merge($etheme_scripts, array('etheme')), $theme->version, true );
			}

			if ( $is_product ) {
				$product_id      = get_the_ID();
				$slider_vertical = ( etheme_get_option( 'thumbs_slider_vertical' ) || etheme_get_custom_field( 'slider_direction', $product_id ) == 'vertical' ) || ( get_query_var( 'etheme_single_product_builder' ) && etheme_get_option( 'product_gallery_type_et-desktop' ) == 'thumbnails_left' );
				if ( $slider_vertical ) {
					wp_enqueue_script( 'stick', get_template_directory_uri() . '/js/libs/slick.min.js', array(), '1.8.1' );
				}
			}

			$etConf  = array();
			$cartUrl = '#';

			if ( $is_woocommerce ) {
				$cartUrl               = esc_url( wc_get_cart_url() );

				// dequeue woocommerce zoom scripts
				if ( ( ! get_query_var( 'etheme_single_product_builder' ) && ! etheme_get_option( 'product_zoom' ) ) || ( get_query_var( 'etheme_single_product_builder' ) && ! etheme_get_option( 'product_gallery_zoom_et-desktop' ) ) || get_query_var( 'is_mobile' ) ) {
					wp_deregister_script( 'zoom' );
					wp_dequeue_script( 'zoom' );
				}

				if ( ('yes' === get_option( 'woocommerce_enable_myaccount_registration' )) && ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'Dokan_Pro' )) ) {

					wp_enqueue_script( 'dokan-form-validate' );
					wp_enqueue_script( 'speaking-url' );
					wp_enqueue_script( 'dokan-vendor-registration' );

					wp_enqueue_script( 'wc-password-strength-meter' );

					$form_validate_messages = array(
						'required'        => __( "This field is required", 'xstore' ),
						'remote'          => __( "Please fix this field.", 'xstore' ),
						'email'           => __( "Please enter a valid email address.", 'xstore' ),
						'url'             => __( "Please enter a valid URL.", 'xstore' ),
						'date'            => __( "Please enter a valid date.", 'xstore' ),
						'dateISO'         => __( "Please enter a valid date (ISO).", 'xstore' ),
						'number'          => __( "Please enter a valid number.", 'xstore' ),
						'digits'          => __( "Please enter only digits.", 'xstore' ),
						'creditcard'      => __( "Please enter a valid credit card number.", 'xstore' ),
						'equalTo'         => __( "Please enter the same value again.", 'xstore' ),
						'maxlength_msg'   => __( "Please enter no more than {0} characters.", 'xstore' ),
						'minlength_msg'   => __( "Please enter at least {0} characters.", 'xstore' ),
						'rangelength_msg' => __( "Please enter a value between {0} and {1} characters long.", 'xstore' ),
						'range_msg'       => __( "Please enter a value between {0} and {1}.", 'xstore' ),
						'max_msg'         => __( "Please enter a value less than or equal to {0}.", 'xstore' ),
						'min_msg'         => __( "Please enter a value greater than or equal to {0}.", 'xstore' ),
					);

					wp_localize_script( 'dokan-form-validate', 'DokanValidateMsg', apply_filters( 'DokanValidateMsg_args', $form_validate_messages ) );
				}

			}

			$etGlobalConf = array(
				'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
				'woocommerceSettings'     => array(
					'is_woocommerce'  => false,
					'is_swatches'     => false,
					'ajax_filters'    => false,
					'ajax_pagination' => false,
					'mini_cart_progress' => false,
					'is_single_product_builder' => get_query_var( 'etheme_single_product_builder', false ),
					'mini_cart_content_quantity_input' => (etheme_get_option('cart_content_quantity_input_et-desktop', false) && !get_query_var('is_mobile', false)) || (etheme_get_option('cart_content_quantity_input_et-mobile', false) && get_query_var('is_mobile', false)),
					'sidebar_widgets_dropdown_limited' => false,
					'widget_show_more_text' => esc_html__('more', 'xstore'),
					'sidebar_off_canvas_icon' => '<svg version="1.1" width="1em" height="1em" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M94.8,0H5.6C4,0,2.6,0.9,1.9,2.3C1.1,3.7,1.3,5.4,2.2,6.7l32.7,46c0,0,0,0,0,0c1.2,1.6,1.8,3.5,1.8,5.5v37.5c0,1.1,0.4,2.2,1.2,3c0.8,0.8,1.8,1.2,3,1.2c0.6,0,1.1-0.1,1.6-0.3l18.4-7c1.6-0.5,2.7-2.1,2.7-3.9V58.3c0-2,0.6-3.9,1.8-5.5c0,0,0,0,0,0l32.7-46c0.9-1.3,1.1-3,0.3-4.4C97.8,0.9,96.3,0,94.8,0z M61.4,49.7c-1.8,2.5-2.8,5.5-2.8,8.5v29.8l-16.8,6.4V58.3c0-3.1-1-6.1-2.8-8.5L7.3,5.1h85.8L61.4,49.7z"></path></svg>',
					'ajax_add_to_cart_archives' => get_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' ) == 'yes',
					'cart_url'                => false,
					'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes',
				),
				'notices'                 => array(
					'ajax-filters'         => esc_html__( 'Ajax error: cannot get filters result', 'xstore' ),
					'post-product'         => esc_html__( 'Ajax error: cannot get post/product result', 'xstore' ),
					'products'             => esc_html__( 'Ajax error: cannot get products result', 'xstore' ),
					'posts'                => esc_html__( 'Ajax error: cannot get posts result', 'xstore' ),
					'element'              => esc_html__( 'Ajax error: cannot get element result', 'xstore' ),
					'portfolio'            => esc_html__( 'Ajax error: problem with ajax et_portfolio_ajax action', 'xstore' ),
					'portfolio-pagination' => esc_html__( 'Ajax error: problem with ajax et_portfolio_ajax_pagination action', 'xstore' ),
					'menu'                 => esc_html__( 'Ajax error: problem with ajax menu_posts action', 'xstore' ),
					'noMatchFound'         => esc_html__( 'No matches found', 'xstore' ),
					'variationGalleryNotAvailable' => esc_html__('Variation Gallery not available on variation id', 'xstore'),
				),
				'layoutSettings'          => array(
					'layout'            => etheme_get_option( 'main_layout' ),
					'is_rtl'            => is_rtl()
				),
				'sidebar' => array(
					'closed_pc_by_default' => etheme_get_option('first_catItem_opened'),
				),
				'et_global' => array(
					'classes' => array(
						'skeleton' => 'skeleton-body',
						'mfp' => 'et-mfp-opened'
					),
					'mobHeaderStart' => (int)get_theme_mod('mobile_header_start_from', 992)
				)
			);

			$etPortfolioConf = array(
				'ajaxurl' => $etGlobalConf['ajaxurl'],
				'layoutSettings'          => array(
					'is_rtl'            => $etGlobalConf['layoutSettings']['is_rtl'],
				),
			);

			$etAjaxFiltersConfig = array(
				'scroll_top_after_ajax' => etheme_get_option('ajax_product_filter_scroll_top', 1),
				'ajax_categories' => etheme_get_option('ajax_categories', 1),
				'product_page_banner_pos' => etheme_get_option( 'product_bage_banner_pos', 1 ),
				'loop_prop_columns' => etheme_get_option('woocommerce_catalog_columns', 4),
				'loop_prop_columns_category' => etheme_get_option('category_page_columns', 'inherit'),
				'builder' => '',
			);

			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				$etAjaxFiltersConfig['builder'] .= '/elementor';
			}

			if ( defined( 'WPB_VC_VERSION' ) ) {
				$etAjaxFiltersConfig['builder'] .= '/wpb';
			}

			if ( get_query_var('view_mode_smart', false) ) {
				$etAjaxFiltersConfig['loop_prop_columns'] = etheme_get_option('view_mode_smart_active', 4);
				$etAjaxFiltersConfig['loop_prop_columns_category'] = etheme_get_option('categories_view_mode_smart_active', 4);
			}

			$etConf = array(
				'noresults'               => esc_html__( 'No results were found!', 'xstore' ),
				'successfullyAdded'       => esc_html__( 'Product added.', 'xstore' ),
				'successfullyCopied' => esc_html__('Copied to clipboard', 'xstore'),
				'checkCart'               => esc_html__( 'Please check your ', 'xstore' ) . "<a href='" . $cartUrl . "'>" . esc_html__( 'cart.', 'xstore' ) . "</a>",
				'catsAccordion'           => etheme_get_option( 'cats_accordion', 1 ),
				'contBtn'                 => esc_html__( 'Continue shopping', 'xstore' ),
				'checkBtn'                => esc_html__( 'Checkout', 'xstore' ),
				'ajaxProductNotify'       => etheme_get_option( 'ajax_added_product_notify', 1 ),
				'variationGallery'        => get_query_var( 'etheme_single_product_variation_gallery', false ),
				'quickView'               => array(
					'type'     => etheme_get_option( 'quick_view_content_type', 'popup' ),
					'position' => etheme_get_option( 'quick_view_content_position', 'right' ),
					'layout'   => etheme_get_option( 'quick_view_layout', 'default' ),
					'variationGallery' => etheme_get_option('enable_variation_gallery', 0)
				),
				'builders' => array(
					'is_wpbakery' => ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ),
				),
			);

			if ( $is_woocommerce && current_theme_supports( 'woocommerce' ) ) {
				$etGlobalConf['woocommerceSettings']['is_woocommerce'] = true;
				$etGlobalConf['woocommerceSettings']['is_swatches']    = etheme_get_option( 'enable_swatch', 1 ) && class_exists( 'St_Woo_Swatches_Base' );
				if ( is_shop() || is_product_category() || is_product_tag() || is_tax('brand') || is_post_type_archive( 'product' ) ) {
					$etGlobalConf['woocommerceSettings']['ajax_filters']    = etheme_get_option( 'ajax_product_filter', 0 );
					$etGlobalConf['woocommerceSettings']['ajax_pagination'] = etheme_get_option( 'ajax_product_pagination', 0 );
					$etGlobalConf['woocommerceSettings']['sidebar_widgets_dropdown_limited'] = etheme_get_option('show_plus_filters',0);
					$etGlobalConf['woocommerceSettings']['sidebar_widgets_dropdown_limit'] = etheme_get_option('show_plus_filter_after',3);
					$etGlobalConf['woocommerceSettings']['wishlist_for_variations'] = etheme_get_option('wishlist_for_variations_new',1);
				}

				$etGlobalConf['woocommerceSettings']['cart_progress_currency_pos'] = get_option('woocommerce_currency_pos');
				$etGlobalConf['woocommerceSettings']['cart_progress_thousand_sep'] = get_option('woocommerce_price_thousand_sep');
				$etGlobalConf['woocommerceSettings']['cart_progress_decimal_sep']  = get_option('woocommerce_price_decimal_sep');
				$etGlobalConf['woocommerceSettings']['cart_progress_num_decimals'] = get_option('woocommerce_price_num_decimals');
				$etGlobalConf['woocommerceSettings']['is_smart_addtocart'] = etheme_get_option('product_page_smart_addtocart', 0);

				$etGlobalConf['woocommerceSettings']['mini_cart_progress'] = get_option('xstore_sales_booster_settings_progress_bar', get_theme_mod('booster_progress_bar_et-desktop', false));
				$etGlobalConf['woocommerceSettings']['buy_now_btn'] = etheme_get_option('buy_now_btn',0);
				$etGlobalConf['woocommerceSettings']['cart_url'] = apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null );
				if ( etheme_get_option('sidebar_for_mobile', 'off_canvas') == 'off_canvas' ) {
					$sidebar_off_canvas_icon = etheme_get_option('sidebar_for_mobile_icon', '');
					$sidebar_off_canvas_icon = isset($sidebar_off_canvas_icon['id']) ? $sidebar_off_canvas_icon['id'] : '';
					if ( function_exists('etheme_fgcontent') && $sidebar_off_canvas_icon != '' ) {
						$type      = get_post_mime_type( $sidebar_off_canvas_icon );
						$mime_type = explode( '/', $type );
						if ( $mime_type['1'] == 'svg+xml' ) {
							$svg = get_post_meta( $sidebar_off_canvas_icon, '_xstore_inline_svg', true );

							if ( ! empty( $svg ) ) {
								$etGlobalConf['woocommerceSettings']['sidebar_off_canvas_icon'] = $svg;
							} else {

								$attachment_file = get_attached_file( $sidebar_off_canvas_icon );

								if ( $attachment_file ) {

									$svg = etheme_fgcontent( $attachment_file , false, null);

									if ( ! empty( $svg ) ) {
										update_post_meta( $sidebar_off_canvas_icon, '_xstore_inline_svg', $svg );
									}

									$etGlobalConf['woocommerceSettings']['sidebar_off_canvas_icon'] = $svg;

								}

							}
						}
						else {
							$etGlobalConf['woocommerceSettings']['sidebar_off_canvas_icon'] = etheme_get_image($sidebar_off_canvas_icon, 'thumbnail' );
						}
					}
				}
			}

			if ( $etGlobalConf['woocommerceSettings']['ajax_filters'] || $etGlobalConf['woocommerceSettings']['ajax_pagination'] ) {
				wp_enqueue_script( 'ajaxFilters', get_template_directory_uri() . '/js/ajax-filters.min.js',array('etheme'), $theme->version, true );
			}


			$etConf = array_merge($etConf, $etGlobalConf);

			$etAjaxFiltersConfig = array_merge($etGlobalConf, $etAjaxFiltersConfig);

			wp_localize_script( 'etheme', 'etConfig', $etConf );
			wp_localize_script( 'portfolio', 'etPortfolioConfig', $etPortfolioConf );
			wp_localize_script( 'ajaxFilters', 'etAjaxFiltersConfig', $etAjaxFiltersConfig );
			// wp_dequeue_script('prettyPhoto');
			wp_dequeue_script( 'prettyPhoto-init' );

			if ( class_exists( 'Vc_Manager' ) ) {
				// fix to scripts in static blocks
				wp_enqueue_script( 'wpb_composer_front_js' );
			}

			if (etheme_get_option( 'disable_wp_block_css', 0 )){
				wp_dequeue_style( 'wp-block-library' );
				wp_dequeue_style( 'wp-block-library-theme' );
				wp_dequeue_style( 'wc-block-style' );
			}

			if ($is_woocommerce && ! $load_wc_cart_fragments && ! WC()->cart->cart_contents_count){
				wp_dequeue_script( 'wc-cart-fragments' );
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'etheme_enqueue_scripts', 30 );

// **********************************************************************//
// ! Add new images size
// **********************************************************************//

if ( ! function_exists( 'etheme_image_sizes' ) ) {
	function etheme_image_sizes() {
		add_image_size( 'shop_catalog_alt', 600, 600, true );
	}
}
add_action( 'after_setup_theme', 'etheme_image_sizes' );

// **********************************************************************//
// ! Theme 3d plugins
// **********************************************************************//
add_action( 'init', 'etheme_3d_plugins' );
if ( ! function_exists( 'etheme_3d_plugins' ) ) {
	function etheme_3d_plugins() {
		if ( function_exists( 'set_revslider_as_theme' ) ) {
			set_revslider_as_theme();
		}
		if ( function_exists( 'set_ess_grid_as_theme' ) ) {
			set_ess_grid_as_theme();
		}
	}
}

add_action( 'vc_before_init', 'etheme_vcSetAsTheme' );
if ( ! function_exists( 'etheme_vcSetAsTheme' ) ) {
	function etheme_vcSetAsTheme() {
		if ( function_exists( 'vc_set_as_theme' ) ) {
			vc_set_as_theme();
		}
	}
}

// ! REFER for woo premium plugins
if ( ! defined( 'YITH_REFER_ID' ) ) {
	define( 'YITH_REFER_ID', '1028760' );
}

// REFER for yellow pencil
if ( ! defined( 'YP_THEME_MODE' ) ) {
	define( 'YP_THEME_MODE', "true" );
}

// **********************************************************************//
// ! Load theme translations
// **********************************************************************//
if ( ! function_exists( 'etheme_load_textdomain' ) ) {
	add_action( 'after_setup_theme', 'etheme_load_textdomain' );

	function etheme_load_textdomain() {
		load_theme_textdomain( 'xstore', get_template_directory() . '/languages' );

		$locale      = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable( $locale_file ) ) {
			require_once( $locale_file );
		}
	}
}