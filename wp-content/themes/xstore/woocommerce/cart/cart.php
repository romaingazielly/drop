<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

do_action( 'woocommerce_before_cart' ); ?>

<div class="row">
	<div class="col-md-7"> 

		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

		<?php do_action( 'woocommerce_before_cart_table' ); ?>
		<div class="table-responsive">
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-details" colspan="2"><?php esc_html_e( 'Product', 'xstore' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Price', 'xstore' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'xstore' ); ?></th>
					<th class="product-subtotal" colspan="2"><?php esc_html_e( 'Subtotal', 'xstore' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">


							<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'xstore' ); ?>">
		                        <div class="product-thumbnail">
		                            <?php
		                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

		                                if ( ! $_product->is_visible() || ! $product_permalink){
		                                    echo wp_kses_post( $thumbnail );
		                                } else {
		                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
		                                }
		                            ?>
		                        </div>
							</td>
							<td class="product-details">
		                        <div class="cart-item-details">
		                            <?php
		                            if ( ! $_product->is_visible() || ! $product_permalink  ){
                                    	echo apply_filters( 'woocommerce_cart_item_name', esc_html($_product->get_name()), $cart_item, $cart_item_key );
                                    } else {
                                    	echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="product-title">%s</a>', esc_url( $product_permalink ) , esc_html($_product->get_name()) ), $cart_item, $cart_item_key );
                                    }

		                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

		                            // Meta data
									//if (  etheme_get_option( 'enable_swatch' ) && class_exists( 'St_Woo_Swatches_Base' ) ) {
									//	$Swatches = new St_Woo_Swatches_Base();
									//	echo //$Swatches->st_wc_get_formatted_cart_item_data( $cart_item );
									//} else {
										echo wc_get_formatted_cart_item_data( $cart_item );
									//}

		                    // Backorder notification
		                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
		                         	echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'xstore' ) . '</p>', $product_id ) );
		                            ?>
		                            <?php
		                            	echo apply_filters( 'woocommerce_cart_item_remove_link',
		                            		sprintf(
				                            	'<a href="%s" class="remove-item text-underline" title="%s">%s</a>',
				                            	esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
				                            	esc_html__( 'Remove this item', 'xstore' ),
				                            	esc_html__('Remove', 'xstore')
			                            	),
			                            $cart_item_key );
		                            ?>
		                            <span class="mobile-price">
		                            	<?php
											echo (int) $cart_item['quantity'] . ' x ' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
		                            </span>
		                        </div>
							</td>

							<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'xstore' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'xstore' ); ?>">
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->get_max_purchase_quantity(),
											'min_value'   => '0',
											'product_name'  => $_product->get_name(),
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'xstore' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
								?>
							</td>
						</tr>
						<?php
					}
				}

				do_action( 'woocommerce_cart_contents' );
				?>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

				<div class="actions clearfix">
		<?php $cols = 12; ?>
		<?php if ( wc_coupons_enabled() ) : $cols = 6; ?>
			<div class="col-md-<?php echo esc_attr($cols); ?> col-sm-<?php echo esc_attr($cols); ?> text-left mob-center">
				<form class="checkout_coupon" method="post">
				<a href="#" class="et-open to_open-coupon"><i class="et-icon et-coupon"></i><?php esc_html_e('Enter your promotional code', 'xstore'); ?></a>
					<div class="coupon">

						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'xstore' ); ?>" />
						<!-- <input type="submit" class="btn" name="apply_coupon" value="&#9166;" /> -->
						<input type="submit" class="btn" name="apply_coupon" value="<?php esc_attr_e('OK', 'xstore'); ?>" />

						<?php do_action('woocommerce_cart_coupon'); ?>

					</div>
				</form>
			</div>
			<?php endif; ?>
			<div class="col-md-<?php echo esc_attr($cols); ?> col-sm-<?php echo esc_attr($cols); ?> mob-center">
				<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
					<a class="return-shop" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><i class="et-icon et-<?php echo (is_rtl()) ? 'right' : 'left'; ?>-arrow"></i><?php esc_html_e('Return to shop', 'xstore') ?></a>
				<?php endif; ?>
				<button type="submit" class="btn gray medium bordered" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'xstore' ); ?>"><?php esc_html_e( 'Update cart', 'xstore' ); ?></button>
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				<?php do_action( 'woocommerce_cart_actions' ); ?>
			</div>
		</div>
		
		</form>
	</div>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="col-md-5 cart-order-details">
		<div class="cart-collaterals">
			<?php do_action( 'woocommerce_cart_collaterals' ); ?>
		</div>
		<?php  if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('cart-area'))): ?>
        <?php endif; ?>
	</div>
</div>
<!-- end row -->

<?php woocommerce_cross_sell_display(); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>