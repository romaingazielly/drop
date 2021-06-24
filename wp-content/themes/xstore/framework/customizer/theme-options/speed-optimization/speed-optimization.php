<?php  
/**
 * The template created for displaying general optimization options 
 *
 * @version 0.0.3
 * @since 6.0.0
 * @log
 * 0.0.2
 * ADDED: Disable Gutenberg CSS option
 * ADDED: Wishlist for variation products
 * 0.0.3
 * ADDED: Always load wc-cart-fragments
 */
add_filter( 'et/customizer/add/sections', function($sections)  use($priorities){

	$args = array(
		'general-optimization'	 => array(
			'name'        => 'general-optimization',
			'title'          => esc_html__( 'Speed Optimization', 'xstore' ),
			'icon' => 'dashicons-dashboard',
			'priority' => $priorities['speed-optimization'],
			'type'		=> 'kirki-lazy',
			'dependency'    => array()
		)
	);
	return array_merge( $sections, $args );
});

$hook = class_exists('ETC_Initial') ? 'et/customizer/add/fields/general-optimization' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();

	// Array of fields
	$args = array(
		'images_loading_type_et-desktop'	=> array(
			'name'		  => 'images_loading_type_et-desktop',
			'type'        => 'select',
			'settings'    => 'images_loading_type_et-desktop',
			'label'       => esc_html__( 'Image Loading Type', 'xstore' ),
			'description' => esc_html__( 'It can improve the loading time. Lazy Load - images will be loaded only as they enter the viewport and reduces the number of requests.
				LQIP(Low-Quality Image Placeholders) - initially loads a low-quality (smaller version) of the final image to fill in the container until the high-resolution version can load.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 'lazy',
			'choices'     => array(
				'lazy' => esc_html__( 'Lazy', 'xstore' ),
				'lqip' => esc_html__( 'LQIP', 'xstore' ),
				'default' => esc_html__( 'Default', 'xstore' ),
			),
			'priority'	  => 1,
		),

		'et_optimize_js'	=> array(
			'name'		  => 'et_optimize_js',
			'type'        => 'toggle',
			'settings'    => 'et_optimize_js',
			'label'       => esc_html__( 'Old browser support', 'xstore' ),
			'description' => esc_html__( 'Turn on to load additional JS library to support old browsers.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 2,
		),

		'et_optimize_css'	=> array(
			'name'		  => 'et_optimize_css',
			'type'        => 'toggle',
			'settings'    => 'et_optimize_css',
			'label'       => esc_html__( 'Optimize frontend CSS', 'xstore' ),
			'description' => esc_html__( 'Turn on to load optimized CSS. Read our documentation to do it in a properly way if you are using child theme installed before 5.0 theme release.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 3,
		),

		'global_masonry'	=> array(
			'name'		  => 'global_masonry',
			'type'        => 'toggle',
			'settings'    => 'global_masonry',
			'label'       => esc_html__( 'Masonry scripts', 'xstore' ),
			'description' => esc_html__( 'Turn on to load masonry scripts to all pages. Enable this option if you plan to use WPBakery Brands list, 8theme Product Looks elements.', 'xstore' ),
			'tooltip' => esc_html__( 'Loads masonry scripts needed to work for masonry elements (115kb of page size)', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 4,
		),

		'fa_icons'	=> array(
			'name'		  => 'fa_icons',
			'type'        => 'toggle',
			'settings'    => 'fa_icons',
			'label'       => esc_html__( 'FontAwesome support', 'xstore' ),
			'description' => esc_html__( 'Turn on to load FontAwesom 4.7 icons font and scripts.', 'xstore' ),
			'tooltip' => esc_html__( 'Running FontAwesome scripts and styles needed to work for some elements that use those icons, e.g. menu subitem item icons (41kb of page size)', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 5,
		),

		'menu_cache'	=> array(
			'name'		  => 'menu_cache',
			'type'        => 'toggle',
			'settings'    => 'menu_cache',
			'label'       => esc_html__( 'Menu cache', 'xstore' ),
			'description' => esc_html__( 'Enable object cache for menu.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 1,
			'priority'	  => 6,
		),

		'static_block_cache'	=> array(
			'name'		  => 'static_block_cache',
			'type'        => 'toggle',
			'settings'    => 'static_block_cache',
			'label'       => esc_html__( 'Static Blocks cache', 'xstore' ),
			'description' => esc_html__( 'Enable object cache for Static Blocks.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 1,
			'priority'	  => 7,
		),

		'disable_wp_block_css'	=> array(
			'name'		  => 'disable_wp_block_css',
			'type'        => 'toggle',
			'settings'    => 'disable_wp_block_css',
			'label'       => esc_html__( 'Disable Gutenberg CSS', 'xstore' ),
			'description' => esc_html__( 'Remove the Gutenberg Block Library CSS from WordPress and WooCommerce ', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 10,
		),

		'wishlist_for_variations_new'	=> array(
			'name'		  => 'wishlist_for_variations_new',
			'type'        => 'toggle',
			'settings'    => 'wishlist_for_variations_new',
			'label'       => esc_html__( 'Wishlist for variation products', 'xstore' ),
			'description' => esc_html__( 'Wishlist from shop page will add selected product variation.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 11,
		),

		'load_wc_cart_fragments'	=> array(
			'name'		  => 'load_wc_cart_fragments',
			'type'        => 'toggle',
			'settings'    => 'load_wc_cart_fragments',
			'label'       => esc_html__( 'Always load wc-cart-fragments', 'xstore' ),
			'description' => esc_html__( 'WooCommerce “Cart Fragments” is a script using admin ajax to update the cart without refreshing the page. This functionality will slow down the speed of your site.', 'xstore' ),
			'section'     => 'general-optimization',
			'default'     => 0,
			'priority'	  => 12,
		),
	);

	if ( defined( 'ET_CORE_VERSION' ) ) {

		$args = array_merge( $args, array(
			'cssjs_ver'	=> array(
				'name'		  => 'cssjs_ver',
				'type'        => 'toggle',
				'settings'    => 'cssjs_ver',
				'label'       => esc_html__( 'Remove query strings from theme static resources', 'xstore' ),
				'description' => esc_html__( 'Enable to remove the version query string from static resources to improve the Remove query strings from static resources grade on GT Metrix. Don\'t enable if you use cache plugin where this option is also enabled', 'xstore' ),
				'section'     => 'general-optimization',
				'default'     => 0,
				'priority'	  => 8,
			),

			'disable_emoji'	=> array(
				'name'		  => 'disable_emoji',
				'type'        => 'toggle',
				'settings'    => 'disable_emoji',
				'label'       => esc_html__( 'Disable emoji', 'xstore' ),
				'description' => esc_html__( 'It generates an additional HTTP request on your WordPress site to load the wp-emoji-release.min.js file. ', 'xstore' ),
				'section'     => 'general-optimization',
				'default'     => 0,
				'priority'	  => 9,
			),
		));

	}

	return array_merge( $fields, $args );

});