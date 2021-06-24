<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

get_header();

$l = etheme_page_config();

$full_width = etheme_get_option('portfolio_fullwidth', 0);

$class = ( $full_width ) ? 'port-full-width' : 'container';

?>

<?php do_action( 'etheme_page_heading' ); ?>

	<div class="<?php echo esc_attr($class); ?>">
		<div class="page-content sidebar-position-without">
			<div class="content">
				<?php if ( ! etheme_xstore_plugin_notice() ): ?>
					<?php if( have_posts() && get_query_var( 'portfolio_category' ) == '' ): while( have_posts() ) : the_post(); ?>
	                    <?php the_content(); ?>
	                <?php endwhile; endif; ?>
					
					<?php if ( get_query_var( 'portfolio_category' ) ): ?>
						<?php echo '<div class="portfolio-category-description">' . term_description() . '</div>'; ?>
					<?php endif; ?>

					<?php 
						if ( etheme_get_option('portfolio_projects', 1) ) {
							etheme_portfolio(); 
						}
					?>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php
get_footer();
?>