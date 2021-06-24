<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

$l = etheme_page_config();

if(!$l['sidebar'] || $l['sidebar'] == 'without' || $l['sidebar'] == 'no_sidebar') return;


if( etheme_get_option( 'shop_sticky_sidebar', 0 ) && !get_query_var('is_yp') ) {
    $l['sidebar-class'] .= ' sticky-sidebar';
}

?>

<div class="<?php echo esc_attr( $l['sidebar-class'] ); ?> sidebar sidebar-<?php echo esc_attr( $l['sidebar'] ); ?> <?php echo (etheme_get_option('shop_sidebar_hide_mobile', 0)) ? 'hidden-xs' : '' ; ?>">
    <?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('shop-sidebar')):

        if(!get_query_var('et_is-woocommerce', false)) return;
        // **********************************************************************//
        // ! Categories
        // **********************************************************************//
        $args = array(
            'widget_id' => 'woocommerce_product_categories',
            'before_widget' => '<div id="product_categories-1" class="sidebar-widget widget_product_categories widget_onsale">',
            'after_widget' => '</div><!-- //sidebar-widget -->',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>'
        );

        $instance = array(
            'title' => 'Categories',
            'hierarchical' => true,
            'count' => false,
            'dropdown' => false,
            'orderby' => ''
        );

        $widget = new WC_Widget_Product_Categories();
        $widget->widget($args, $instance);

    endif; ?>
</div>