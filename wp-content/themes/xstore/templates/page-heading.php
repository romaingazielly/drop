<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * The template for displaying theme page heading
 *
 * @since   6.4.5
 * @version 1.0.1
 */
$l = etheme_page_config();

if ( $l['banner'] ) {
	echo '<div class="container">';
		etheme_static_block($l['banner'], true);
	echo '</div>';
}

if ($l['breadcrumb'] !== 'disable' && !$l['slider']): ?>

	<div class="page-heading bc-type-<?php echo esc_attr( $l['breadcrumb'] ); ?> bc-effect-<?php echo esc_attr( $l['bc_effect'] ); ?> bc-color-<?php echo esc_attr( $l['bc_color'] ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-12 a-center">
					<?php etheme_breadcrumbs(); ?>
				</div>
			</div>
		</div>
	</div>

<?php endif;

if($l['slider']): ?>
	<div class="page-heading-slider">
		<?php echo do_shortcode('[rev_slider alias="'.$l['slider'].'"][/rev_slider]'); ?>
	</div>
<?php endif;