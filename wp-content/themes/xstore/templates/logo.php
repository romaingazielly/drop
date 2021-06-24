<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme logo if it not from builder
 *
 * @since   6.4.5
 * @version 1.0.0
 */
$logo = etheme_get_logo_data();

?>

<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<img src="<?php echo esc_url($logo['logo']['src']); ?>" alt="<?php echo esc_attr($logo['logo']['alt']); ?>" width="<?php echo esc_attr($logo['logo']['width']); ?>" height="<?php echo esc_attr($logo['logo']['height']); ?>" class="logo-default" />
	<img src="<?php echo esc_url($logo['fixed_logo']['src']);?>" alt="<?php echo esc_attr($logo['fixed_logo']['alt']); ?>" width="<?php echo esc_attr($logo['fixed_logo']['width']); ?>" height="<?php echo esc_attr($logo['fixed_logo']['height']); ?>" class="logo-fixed" />
</a>
