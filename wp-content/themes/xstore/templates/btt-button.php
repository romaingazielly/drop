<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme back to top btn
 *
 * @since   6.4.5
 * @version 1.0.0
 */
$is_customize_preview = is_customize_preview();
$to_top = etheme_get_option('to_top', 1);
$to_top_mobile = etheme_get_option('to_top_mobile', 1);
$class = 'back-top';
if ( ! $to_top ) {
	$class .= ' dt-hide';
}
if ( ! $to_top_mobile ) {
	$class .= ' mob-hide';
}
if ($to_top || $to_top_mobile || $is_customize_preview): ?>
    <div id="back-top" style="width: 46px;height: 46px;" class="<?php echo esc_attr( $class ); ?>"><?php // @todo remove style in 2-3 updates ?>
        <span class="et-icon et-right-arrow-2" style="position: absolute; top: 50%; left: 50%;transform:translate(-50%, -50%) rotate(-90deg);animation: none;"></span>
        <svg width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 150.621;" fill="none"></path>
        </svg>
    </div>
<?php endif;