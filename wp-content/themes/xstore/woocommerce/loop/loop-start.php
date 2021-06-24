<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
global $woocommerce_loop;
// Store column count for displaying the grid
$loop = (get_query_var('et_cat-cols') && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) ? get_query_var('et_cat-cols') : wc_get_loop_prop( 'columns' );

$view_mode = get_query_var('et_view-mode');
if( !empty($woocommerce_loop['view_mode'])) {
	$view_mode = $woocommerce_loop['view_mode'];
} else {
	$woocommerce_loop['view_mode'] = $view_mode;
}

if ( get_query_var('view_mode_smart', false) && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) {
	if ( isset( $_GET['et_columns-count'] ) ) {
		$loop = $_GET['et_columns-count'];
	}
	else {
		$view_mode_smart_active = get_query_var('view_mode_smart_active', 4);;
		$loop = $view_mode_smart_active != 'list' ? $view_mode_smart_active : 4;
		$view_mode = $view_mode_smart_active == 'list' ? 'list' : $view_mode;
	}
}

if($view_mode == 'list') {
	$view_class = 'products-list';
}else{
	$view_class = 'products-grid';
}

if ( ! empty( $woocommerce_loop['isotope'] ) && $woocommerce_loop['isotope'] || etheme_get_option( 'products_masonry', 0 ) && ( is_shop() || is_product_category() ) ) {
	$view_class .= ' et-isotope';
}

$product_view = etheme_get_option('product_view', 'disable');
if( !empty($woocommerce_loop['product_view'])) {
	$product_view = $woocommerce_loop['product_view'];
}

$custom_template = get_query_var('et_custom_product_template');
if( !empty($woocommerce_loop['custom_template'])) {
	$custom_template = $woocommerce_loop['custom_template'];
}

if ( $product_view == 'custom' && $custom_template != '' ) {
	$view_class .= ' products-with-custom-template';
	$view_class .= ' products-with-custom-template-' . ( $view_mode == 'list' ? 'list' : 'grid' );
	$view_class .= ' products-template-'.$custom_template;
}

$view_class .= isset($woocommerce_loop['product_loop_class']) ? ' ' . $woocommerce_loop['product_loop_class'] : '';

$view_class .= (etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 )) ? ' with-ajax' : '';

$variable_products_detach = etheme_get_option('variable_products_detach', false);
$show_attributes = etheme_get_option('variation_product_name_attributes', true);

if ( $variable_products_detach && $show_attributes ) {
	add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
}

?>
<div class="row products-loop <?php echo esc_attr( $view_class ); ?> row-count-<?php echo esc_attr( $loop ); ?>"<?php if ($product_view == 'custom' && $custom_template != '' ) : ?> data-post-id="<?php echo esc_attr( $custom_template ); ?>"<?php endif; ?> data-row-count="<?php echo esc_attr( $loop ); ?>">

<?php if ( etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 ) ): ?>
	<?php etheme_loader( true, 'product-ajax' ); ?>
    <div class="ajax-content clearfix">
<?php endif ?>