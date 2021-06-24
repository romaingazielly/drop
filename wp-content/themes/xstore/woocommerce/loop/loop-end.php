<?php
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

remove_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
?>
<?php if ( etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 ) ): ?>
	</div>
<?php endif; ?>
</div> <!-- .row -->