<?php  if ( ! defined('ETHEME_FW')) {exit('No direct script access allowed');}
// **********************************************************************//
// ! Add select2 styles and scripts admin widgets page
// **********************************************************************//
add_action( 'widgets_admin_page', 'etheme_load_selec2' );
function etheme_load_selec2(){
	wp_register_style( 'select2css', ETHEME_CODE_CSS . 'select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', ETHEME_CODE_JS . 'select2.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}

// **********************************************************************//
// ! Add admin styles and scripts
// **********************************************************************//
if(!function_exists('etheme_load_admin_styles')) {
	add_action( 'admin_enqueue_scripts', 'etheme_load_admin_styles', 150 );
	function etheme_load_admin_styles() {
		global $pagenow;

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

		if ( strpos($screen_id, 'et-panel') ) {
		    wp_enqueue_style('etheme_admin_panel_css', ETHEME_CODE_CSS.'et_admin-panel.css');
		    if ( strpos($screen_id, 'et-panel-sales-booster') || strpos($screen_id, 'et-panel-white-label-branding') ) {
		        wp_enqueue_style('etheme_admin_panel_options_css', ETHEME_CODE_CSS.'et_admin-panel-options.css');
		    }
		    if ( count($xstore_branding_settings) ) {
		        if ( isset($xstore_branding_settings['control_panel']) ) {
			        $colors = array();
			        $colors_output = array();
			        foreach ($xstore_branding_settings['control_panel'] as $color => $color_val) {
			            if ( strpos($color, '_color') !== false && $color_val ) {
			                $colors['--et_admin_' . str_replace('_', '-', $color)] = $color_val;
			            }
			        }
			        foreach ($colors as $color_key => $color_val) {
			            $colors_output[] = $color_key . ':' . $color_val . ' !important';
			        }
			        if ( count($colors_output)) {
	                    wp_add_inline_style('etheme_admin_panel_css', '
	                        :root {
	                            '.implode(';', $colors_output) . '
	                        }
	                    ' );
			        }
		        }
		    }
		}
		
	    wp_enqueue_style('farbtastic');
	    wp_enqueue_style('etheme_admin_css', ETHEME_CODE_CSS.'etheme_admin_backend.css');
	    if ( is_rtl() ) {
	    	wp_enqueue_style('etheme_admin_rtl_css', ETHEME_CODE_CSS.'etheme_admin_backend-rtl.css');
	    }
	    wp_enqueue_style('xstore-icons', ETHEME_CODE_CSS.'xstore-admin-icons.css');
	    wp_enqueue_style("font-awesome", get_template_directory_uri().'/css/font-awesome.min.css');
	    
        if ( count($xstore_branding_settings) ) {
		    
            if ( isset($xstore_branding_settings['advanced']) ) {
	            wp_add_inline_style('etheme_admin_css', $xstore_branding_settings['advanced']['admin_css']);
	        }
		}

	    // Variations Gallery Images script 
		if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) && etheme_get_option('enable_variation_gallery', 0) ) {
			wp_enqueue_script('etheme_admin_product_variations_js', ETHEME_CODE_JS.'product-variations.js', array('etheme_admin_js', 'wc-admin-product-meta-boxes', 'wc-admin-variation-meta-boxes'), false,true);
		}
	}
}

if(!function_exists('etheme_add_admin_script')) {
	add_action('admin_init','etheme_add_admin_script', 1130);
	function etheme_add_admin_script(){
	 
		global $pagenow;

	    add_thickbox();

	    wp_enqueue_script('theme-preview');
	    wp_enqueue_script('common');
	    wp_enqueue_script('wp-lists');
	    // wp_enqueue_script('postbox');
	    wp_enqueue_script('farbtastic');
//	    wp_enqueue_script('et_masonry', get_template_directory_uri().'/js/jquery.masonry.min.js',array(),false,true);
     
	    wp_enqueue_script('etheme_admin_js', ETHEME_CODE_JS.'admin-scripts.js', array(), false,true);

    	wp_localize_script( 'etheme_admin_js', 'et_variation_gallery_admin', array(
			'choose_image' => esc_html__( 'Choose Image', 'xstore' ),
			'add_image'    => esc_html__( 'Add Images', 'xstore' ),
			'menu_enabled' => etheme_get_option('et_menu_options', 1),
		) );
    	
    	$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
    	
    	if ( count($xstore_branding_settings) && isset($xstore_branding_settings['advanced']) &&
    	        $xstore_branding_settings['advanced']['screenshot'] ) {

    		$theme = wp_get_theme();

    		$xstore_branding_settings['theme_template'] = $theme->template;
	        
	         add_filter( 'wp_prepare_themes_for_js', function($themes) use ($xstore_branding_settings){
                $themes[$xstore_branding_settings['theme_template']]['screenshot'][0] = $xstore_branding_settings['advanced']['screenshot'];
                return $themes;
            } );
     
    	}

	}
}

// **********************************************************************// 
// ! Notice "extra notice" dismiss
// **********************************************************************// 
//add_action( 'wp_ajax_et_close_extra_notice', 'et_close_extra_notice' ); 
function et_close_extra_notice(){
	update_option( 'etheme_extra_notice_show', false );
}

// **********************************************************************// 
// ! Notice "extra notice from remote"
// **********************************************************************// 
//add_action( 'admin_notices', 'etheme_extra_notice', 50 );
function etheme_extra_notice(){
	$show = get_option( 'etheme_extra_notice_show', false );

	if ( get_transient( 'etheme_extra_notice' ) ) {
		if ( $show ) {
			echo wp_specialchars_decode(get_transient( 'etheme_extra_notice' ));
		}
		return;
	}

	$headers    = array( 'type'=>'Type', 'notice' => 'Notice' );
	$notice     = wp_remote_get( 'https://xstore.8theme.com/et-notice.txt' );
	$notice     = wp_remote_retrieve_body( $notice );
	$old_notice = get_option( 'etheme_extra_notice_data', false );

	if ( ! $show && $old_notice == $notice ) {return;}

	$file_data = str_replace( "\r", "\n", $notice );

	foreach ( $headers as $field => $regex ) {
		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ){
			$headers[ $field ] = _cleanup_header_comment( $match[1] );
		}
	}

	if ( ! in_array( $headers['type'] , array( 'success', 'info', 'error' ) ) ) {
		return;
	}

	if ( ! isset( $headers['notice'] ) || empty( $headers['notice'] ) ) {return;}

	$out = '
		<div class="et-extra-message et-message et-' . $headers['type'] . '">
			' . $headers['notice'] . '
			<button type="button" class="notice-dismiss close-btn"></button>
		</div>
	';

	update_option( 'etheme_extra_notice_show', true );
	update_option( 'etheme_extra_notice_data', $notice );
	set_transient( 'etheme_extra_notice', $out, DAY_IN_SECONDS*2 );

	echo wp_specialchars_decode($out);
}

add_action('wp_ajax_etheme_deactivate_theme', 'etheme_deactivate_theme');
if( ! function_exists( 'etheme_deactivate_theme' ) ) {
	function etheme_deactivate_theme() {
		// $activated_data = get_option( 'etheme_activated_data' );
		// $theme_id = 15780546;
		// $api_url = ETHEME_API;
		// $status = '';
		// $errors = array();
		// $api = ( ! empty( $activated_data['api_key'] ) ) ? $activated_data['api_key'] : false;

		// $domain = get_option( 'siteurl' );
	 //    $domain = str_replace( 'http://', '', $domain );
	 //    $domain = str_replace( 'https://', '', $domain );
	 //    $domain = str_replace( 'www', '', $domain );
	 //    $domain = urlencode( $domain );

		// $response = wp_remote_get( $api_url . 'deactivate/' . $api . '?envato_id='. $theme_id .'&domain=' . $domain );
		// $response_code = wp_remote_retrieve_response_code( $response );

  //       if( $response_code != '200' ) {
  //           $errors[] = 'API error (5)';
  //           echo json_encode( $errors );
  //           die();
  //       }

  //       $data = json_decode( wp_remote_retrieve_body( $response ), true );

  //       if( isset( $data['error'] ) ) {
  //           $errors[] = $data['error'];
  //           echo json_encode( $errors );
  //           die();
  //       }

		// if ( isset( $data['status'] ) ) {
			// $status = $data['status'];
		 	$status = 'deleted';
			$data = array(
				'api_key' => 0,
				'theme' => 0,
				'purchase' => 0,
	      	);
			update_option( 'etheme_activated_data', maybe_unserialize( $data ) );
			update_option( 'envato_purchase_code_15780546', '' );

			echo json_encode( $status );
			die();
		// }
	}
}

add_action( 'wp_ajax_et_update_menu_ajax', 'et_update_menu_ajax' ); 
if ( ! function_exists('et_update_menu_ajax')) {

	function et_update_menu_ajax () {

		$post = $_POST['item_menu'];

		// update_post_meta( $post['db_id'], '_menu-item-disable_titles', $post['dis_titles']);
		update_post_meta( $post['db_id'], '_menu-item-anchor', sanitize_post($post['anchor']));
		update_post_meta( $post['db_id'], '_menu-item-design', sanitize_post($post['design']));
		update_post_meta( $post['db_id'], '_menu-item-design2', sanitize_post($post['design2']));
		update_post_meta( $post['db_id'], '_menu-item-column_width', $post['column_width']);
		update_post_meta( $post['db_id'], '_menu-item-column_height', $post['column_height']);

		update_post_meta( $post['db_id'], '_menu-item-sublist_width', $post['sublist_width']);

		update_post_meta( $post['db_id'], '_menu-item-columns', $post['columns']);
		update_post_meta( $post['db_id'], '_menu-item-icon_type', sanitize_post($post['icon_type']));
		update_post_meta( $post['db_id'], '_menu-item-icon', $post['icon']);
		update_post_meta( $post['db_id'], '_menu-item-label', sanitize_post($post['item_label']));
		update_post_meta( $post['db_id'], '_menu-item-background_repeat', sanitize_post($post['background_repeat']));
		update_post_meta( $post['db_id'], '_menu-item-background_position', $post['background_position']);
		update_post_meta( $post['db_id'], '_menu-item-use_img', sanitize_post($post['use_img']));
		update_post_meta( $post['db_id'], '_menu-item-widget_area', sanitize_post($post['widget_area']));
		update_post_meta( $post['db_id'], '_menu-item-static_block', sanitize_post($post['static_block']));

		echo json_encode($post);
		die();
	}
}

add_action( 'admin_footer', 'admin_template_js' );
function admin_template_js() {
	if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}
	ob_start();
	?>
		<script type="text/html" id="tmpl-et-variation-gallery-image">
		    <li class="image">
		        <input type="hidden" name="et_variation_gallery[{{data.product_variation_id}}][]" value="{{data.id}}">
		        <img src="{{data.url}}">
		        <a href="#" class="delete remove-et-variation-gallery-image"></a>
		    </li>
		</script>
	<?php 
	$data = ob_get_clean();
	echo apply_filters( 'et_variation_gallery_admin_template_js', $data );
}

add_action( 'woocommerce_save_product_variation', 'et_save_variation_gallery', 10, 2 );

add_action( 'woocommerce_product_after_variable_attributes', 'et_gallery_admin_html', 10, 3 );

if ( ! function_exists( 'et_gallery_admin_html' ) ):
		function et_gallery_admin_html( $loop, $variation_data, $variation ) {
			if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}
			$variation_id   = absint( $variation->ID );
			$gallery_images = get_post_meta( $variation_id, 'et_variation_gallery_images', true );
			?>
            <div class="form-row form-row-full et-variation-gallery-wrapper">
                <h4><?php esc_html_e( 'Variation Image Gallery', 'xstore' ) ?></h4>
                <div class="et-variation-gallery-image-container">
                    <ul class="et-variation-gallery-images">
						<?php
							if ( is_array( $gallery_images ) && ! empty( $gallery_images ) ) {

								foreach ( $gallery_images as $image_id ):
									
									$image = wp_get_attachment_image_src( $image_id );
									
									?>
							        <li class="image">
							            <input type="hidden" name="et_variation_gallery[<?php echo esc_attr( $variation_id ); ?>][]" value="<?php echo esc_attr( $image_id ); ?>">
							            <img src="<?php echo esc_url( $image[ 0 ] ) ?>">
							            <a href="#" class="delete remove-et-variation-gallery-image"></a>
							        </li>
								
								<?php endforeach;
							}
						?>
                    </ul>
                </div>
                <p class="add-et-variation-gallery-image-wrapper hide-if-no-js">
                    <a href="#" data-product_variation_loop="<?php echo esc_attr($loop); ?>" data-product_variation_id="<?php echo absint( $variation->ID ) ?>" class="button add-et-variation-gallery-image"><?php esc_html_e( 'Add Gallery Images', 'xstore' ) ?></a>
                </p>
            </div>
			<?php
		}
	endif;
	
	//-------------------------------------------------------------------------------
	// Save Gallery
	//-------------------------------------------------------------------------------
	if ( ! function_exists( 'et_save_variation_gallery' ) ):
		function et_save_variation_gallery( $variation_id, $loop ) {
			if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}

			if ( isset( $_POST[ 'et_variation_gallery' ] ) ) {
				if ( isset( $_POST[ 'et_variation_gallery' ][ $variation_id ] ) ) {

					$gallery_image_ids = (array) array_map( 'absint', $_POST[ 'et_variation_gallery' ][ $variation_id ] );
					update_post_meta( $variation_id, 'et_variation_gallery_images', $gallery_image_ids );
				} else {
					delete_post_meta( $variation_id, 'et_variation_gallery_images' );
				}
			} else {
				delete_post_meta( $variation_id, 'et_variation_gallery_images' );
			}
		}
	endif;
	
//add_action( 'woocommerce_product_after_variable_attributes', 'et_extra_variation_options', 10, 3 );
if ( !function_exists('et_extra_variation_options')) {
    function et_extra_variation_options($loop, $variation_data, $variation) {
        if ( !etheme_get_option('variable_products_detach', false) ) {return;}
        ?>
        <div>
            <?php
                woocommerce_wp_text_input(
						array(
							'id'            => "_et_product_variation_title{$loop}",
							'name'          => "_et_product_variation_title[{$loop}]",
							'value'         => get_post_meta( $variation->ID, '_et_product_variation_title', true ),
							'placeholder'   => esc_html__('Custom variation title', 'xstore'),
							'type'          => 'text',
						)
					);
            ?>
        </div>
        <?php
    }
}

add_action( 'woocommerce_save_product_variation', 'et_save_extra_variation_options', 10, 2 );
function et_save_extra_variation_options($variation_id, $i) {
//    $custom_title = $_POST['_et_product_variation_title'][$i];
//    if ( ! empty( $custom_title ) ) {
//        update_post_meta( $variation_id, '_et_product_variation_title', esc_attr( $custom_title ) );
//    } else {
//    	delete_post_meta( $variation_id, '_et_product_variation_title' );
//    }
    
    // sale price time start/end
    $_sale_price_time_start = $_POST['_sale_price_time_start'][$i];
    if ( ! empty( $_sale_price_time_start ) ) {
        update_post_meta( $variation_id, '_sale_price_time_start', esc_attr( $_sale_price_time_start ) );
    } else {
    	delete_post_meta( $variation_id, '_sale_price_time_start' );
    }

    $_sale_price_time_end = $_POST['_sale_price_time_end'][$i];
    if ( ! empty( $_sale_price_time_end ) ) {
        update_post_meta( $variation_id, '_sale_price_time_end', esc_attr( $_sale_price_time_end ) );
    } else {
    	delete_post_meta( $variation_id, '_sale_price_time_end' );
    }
}

add_action( 'woocommerce_product_options_pricing', 'et_general_product_data_time_fields' );
function et_general_product_data_time_fields() { 
	
	?>
	</div> 
	<div class="options_group pricing show_if_simple show_if_external hidden">
	<?php

	woocommerce_wp_text_input( array( 'id' => '_sale_price_time_start', 'label' => esc_html('Sale price time start', 'xstore'), 'placeholder' => esc_html( 'From&hellip; 12:00', 'xstore'), 'desc_tip' => 'true', 'description' => __( 'Only when sale price schedule is enabled', 'xstore' ) ) );
	woocommerce_wp_text_input( array( 'id' => '_sale_price_time_end', 'label' => esc_html('Sale price time end', 'xstore'), 'placeholder' => esc_html( 'To&hellip; 12:00', 'xstore' ), 'desc_tip' => 'true', 'description' => __( 'Only when sale price schedule is enabled', 'xstore' ) ) );

}
// -----------------------------------------
// 1. Add custom field input @ Product Data > Variations > Single Variation
  
add_action( 'woocommerce_variation_options_pricing', 'et_add_custom_field_to_variations', 10, 3 ); 
 
function et_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	?>

	<div class="form-field sale_price_time_fields">
	
		<?php
			woocommerce_wp_text_input( 
				array( 
					'id' => '_sale_price_time_start[' . $loop . ']', 
					'wrapper_class' => 'form-row form-row-first', 
					'label' => __( 'Sale price time start', 'xstore' ),
					'placeholder' => esc_html__( 'From&hellip; 12:00', 'xstore'),
					'value' => get_post_meta( $variation->ID, '_sale_price_time_start', true )
				)
			);
			woocommerce_wp_text_input( 
				array( 
					'id' => '_sale_price_time_end[' . $loop . ']', 
					'wrapper_class' => 'form-row form-row-last', 
					'label' => __( 'Sale price time end', 'xstore' ),
					'placeholder' => esc_html__( 'To&hellip; 12:00', 'xstore' ),
					'value' => get_post_meta( $variation->ID, '_sale_price_time_end', true )
				) 
			); 
		?> 

	</div>

<?php }
  
// -----------------------------------------
// 3. Store custom field value into variation data
  
add_filter( 'woocommerce_available_variation', 'et_add_custom_field_variation_data' );
 
function et_add_custom_field_variation_data( $variations ) {
    $variations['_sale_price_time_start'] = '<p class="form-row form-row-first">'.esc_html__('Sale price time start:', 'xstore') . '<span>' . get_post_meta( $variations[ 'variation_id' ], '_sale_price_time_start', true ) . '</span></p>';
    $variations['_sale_price_time_start'] = '<p class="form-row form-row-last">'.esc_html__('Sale price time end:', 'xstore') . '<span>' . get_post_meta( $variations[ 'variation_id' ], '_sale_price_time_start', true ) . '</span></p>';
    return $variations;
}

// Hook to save the data value from the custom fields 
add_action( 'woocommerce_process_product_meta', 'et_save_general_product_data_time_fields' );
function et_save_general_product_data_time_fields( $post_id ) { 
	$_sale_price_time_start = $_POST['_sale_price_time_start']; 
	update_post_meta( $post_id, '_sale_price_time_start', esc_attr( $_sale_price_time_start ) ); 
	$_sale_price_time_end = $_POST['_sale_price_time_end']; 
	update_post_meta( $post_id, '_sale_price_time_end', esc_attr( $_sale_price_time_end ) ); 
}


// WooCommerce settings
add_filter('woocommerce_account_settings', function($settings) {
    $updated_settings = array();

      foreach ( $settings as $section ) {
          
          $updated_settings[] = $section;
    
        // at the bottom of the General Options section
        if ( isset( $section['id'] ) && 'account_registration_options' == $section['id'] &&
           isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
            
            $updated_settings[] = array(
                'type'  => 'et_custom_section_start',
            );
            
            $updated_settings[] = array(
                'title' => __( 'XStore "My account" page settings', 'xstore' ),
                'type'  => 'title',
                'id'    => 'et_wc_account_options',
            );
            
              $updated_settings[] = array(
                'title'    => __( 'Account page type', 'xstore' ),
                'id'       => 'et_wc_account_page_type',
                'default'  => 'new',
                'type'     => 'select',
                'options'  => array(
                        'default'     => esc_html__( 'Default', 'xstore' ),
                        'new'         => esc_html__( 'New', 'xstore' ),
                    ),
                );
    
              $updated_settings[] = array(
                'name'     => __( 'Account banner', 'xstore' ),
                'id'       => 'et_wc_account_banner',
                'type'     => 'textarea',
                'css'      => 'min-width:300px;',
                'desc_tip'     => __( 'You can add simple html or staticblock shortcode', 'xstore' ),
              );
              
              $updated_settings[] = array(
                'title'    => __( 'Products type', 'xstore' ),
                'id'       => 'et_wc_account_products_type',
                'default'  => 'random',
                'type'     => 'select',
                'options'  => array(
                        'featured'     => esc_html__( 'Featured', 'xstore' ),
                        'sale'         => esc_html__( 'On sale', 'xstore' ),
                        'bestsellings' => esc_html__( 'Bestsellings', 'xstore' ),
                        'none' => esc_html__( 'None', 'xstore' ),
                        'random'       => esc_html__( 'Random', 'xstore' ),
                    ),
                );
                $updated_settings[] = array(
                    'title'    => __( 'Navigation icons', 'xstore' ),
                    'desc'          => __( 'Show icons on the "My account" page for the account navigation', 'xstore' ),
                    'id'            => 'et_wc_account_nav_icons',
                    'default'       => 'yes',
                    'type'          => 'checkbox',
                    'autoload'      => false
                );
              
              $updated_settings[] = array(
                    'type' => 'sectionend',
                    'id'   => 'et_wc_account_options',
                );
              
              $updated_settings[] = array(
                'type'  => 'et_custom_section_end',
            );
        }
        
      }
    
      return $updated_settings;
});

add_action('woocommerce_admin_field_et_custom_section_start', function() {
    echo '<div class="et-wc-section-wrapper">';
});
add_action('woocommerce_admin_field_et_custom_section_end', function() {
    echo '</div>';
});

// WooCommerce status
add_filter('woocommerce_debug_tools', function($settings) {
   $settings['clear_et_brands_transients'] = array(
        'name'   => __( 'Brands transients', 'xstore' ),
        'button' => __( 'Clear transients', 'xstore' ),
        'desc'   => __( 'This tool will clear the brands transients cache.', 'xstore' ),
        'callback' => 'etheme_clear_brands_transients'
    );
    return $settings;
});

function etheme_clear_brands_transients() {
    delete_transient('wc_layered_nav_counts_brand');
}