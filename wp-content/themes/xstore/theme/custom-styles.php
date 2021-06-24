<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');
// **********************************************************************//
// ! Ititialize theme configuration and variables
// **********************************************************************//

// priority of 999 is in kirki 
add_action('wp_head', 'etheme_assets', 1000);
if(!function_exists('etheme_assets')) {
function etheme_assets() { 
	$post_id = (array)get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
	$css = '';
	$dir = wp_upload_dir();
	$css = et_custom_styles();
	$css .= et_custom_styles_responsive();

	$header_bg_page = etheme_get_custom_field('header_bg', $post_id['id']);
	$header_bg_transparent = etheme_get_custom_field('header_transparent', $post_id['id']);

	$bg_image = etheme_get_custom_field('bg_image', $post_id['id']);
	$bg_color = etheme_get_custom_field('bg_color', $post_id['id']);

	if( ! empty( $bg_image ) || ! empty( $bg_color ) ) {
	   $css .= 'body {';
			if( ! empty( $bg_color ) ) $css .= 'background-color: ' . $bg_color . '!important;';
			if( ! empty( $bg_image ) ) $css .= 'background-image: url(' . $bg_image .')!important;';
		$css .= '}'; 
	}

	if ( $header_bg_transparent ) {
		$header_bg_page = 'transparent';
	}

	// ! Breadcrumb background image for single pages
	global $post;
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
	$post_id = etheme_get_page_id( true );

	if ( $post_id['id'] == NULL ) {
		$post_id['id'] = is_object($post) ? $post->ID : 0;
	}

	$bc_type = etheme_get_option( 'breadcrumb_type', 'left2' );
	$page_breadcrumb = etheme_get_custom_field( 'breadcrumb_type', $post_id['id'] );
	if( ! empty( $page_breadcrumb ) && $page_breadcrumb != 'inherit') {
		$bc_type = $page_breadcrumb;
	}

	if( in_array($post_id['type'], array('page', 'shop', 'blog', 'portfolio')) && $post_id['id'] > 0 && has_post_thumbnail($post_id['id']) && ! is_tax( 'portfolio_category' ) ) {
		if ( $post_id['type'] == 'shop' && get_option( 'woocommerce_shop_page_id', '' ) == '' ) {
			$bread_bg['background-image'] = '';
		}
		else {
			$bread_bg['background-image'] = wp_get_attachment_url( get_post_thumbnail_id($post_id['id']), 'large');
		}
	}

//	if( is_category() || is_tax('product_cat') ) {
//		$term_id = get_queried_object()->term_id;
//
//		if( $term_id && $image = get_term_meta( $term_id, '_et_page_heading', true ) ) {
//			$bread_bg['background-image'] = $image;
//		}
//	}

	if ( is_tax( 'portfolio_category' ) ) {
		$portfolio_page = etheme_get_option('portfolio_page', '');
		if ( ! empty( $portfolio_page ) && has_post_thumbnail( $portfolio_page ) ) {
		   $bread_bg['background-image'] = get_the_post_thumbnail_url( $portfolio_page, 'large' );
		}
	}
	if( ! empty( $bread_bg['background-image'] ) || ! empty( $bread_bg['background-color'] ) ){
		$css .= '.page-heading {';
			if ( ! empty( $bread_bg['background-image'] ) ) {
				$css .= 'background-image: url(' . $bread_bg['background-image'] . ');';
			}
		   $css .= 'margin-bottom: 25px;';
		$css .= '}';
	}

	// ! End of "Breadcrumb background image for single pages"

	if( ! empty( $header_bg_page) ) {
		$css .='.main-header {
			background:' . $header_bg_page . '!important;
		}';
	} 
	 if ( etheme_get_option('slider_arrows_colors', 'transparent') == 'transparent') {
		$css .= '.swiper-custom-right:not(.et-swiper-elementor-nav), .swiper-custom-left:not(.et-swiper-elementor-nav){
			background: transparent !important;
		}';
	}
	 
	 if ( get_query_var( 'etheme_single_product_variation_gallery', false ) ) {
		$css .= '.swiper-control-top, .swiper-control-bottom { transition: min-height .3s ease-in-out; }';
	 }
		echo '<style type="text/css" class="et_custom-css">' . $css. '</style>';
	}
}