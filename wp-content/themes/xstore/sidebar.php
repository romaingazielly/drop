<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

$l = etheme_page_config();

if(!$l['sidebar'] || $l['sidebar'] == 'without' || $l['sidebar'] == 'no_sidebar') return;

$sidebar = 'main-sidebar';

if(!empty($l['widgetarea']) && $l['widgetarea'] != 'default') {
	$sidebar = $l['widgetarea'];
}

if( etheme_get_option( 'sticky_sidebar', 0 ) && !get_query_var('is_yp') ) {
	$l['sidebar-class'] .= ' sticky-sidebar';
}

?>

<div class="<?php echo esc_attr( $l['sidebar-class'] ); ?> sidebar sidebar-<?php echo esc_attr( $l['sidebar'] ); ?>">
	<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar($sidebar)): ?>
		<div class="sidebar-widget widget_search">
			<h4 class="widget-title"><?php esc_html_e('Search', 'xstore') ?></h4>
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>
</div>