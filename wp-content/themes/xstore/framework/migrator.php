<?php 
    /**
     * Etheme Redux to Kirki migrator.
     *
     * Migrate Xstore theme options from Redux to Kirki
     *
     * @since   4.0.0
     * @version 1.0.0
     */

    class Etheme_Xstore_Options_Migrator {
        private $options_keys = array(
            'switch' => array(
                'site_preloader',
                'static_blocks',
                'testimonials_type',
                'shopping_cart_total',
                'favicon_label_zero',
                'header_full_width',
                'header_border_bottom',
                'header_overlap',
                'top_bar',
                'top_panel',
                'search_ajax',
                'search_ajax_post',
                'search_ajax_page',
                'search_ajax_product',
                'search_by_sku',
                'mobile_account',
                'mobile_menu_logo_switcher',
                'mobile_promo_popup',
                'smart_header_menu',
                'menu_full_width',
                'secondary_menu',
                'secondary_menu_home',
                'secondary_menu_darkening',
                'cart_special_breadcrumbs',
                'return_to_previous',
                'footer_demo',
                'footer_fixed',
                'to_top',
                'to_top_mobile',
                'dark_styles',
                'bold_icons',
                'shop_sticky_sidebar',
                'shop_sidebar_hide_mobile',
                'sidebar_widgets_scroll',
                'sidebar_widgets_open_close',
                'shop_full_width',
                'products_masonry',
                'top_toolbar',
                'filter_opened',
                'ajax_product_filter',
                'ajax_product_pagination',
                'just_catalog',
                'single_product_hide_sidebar',
                'fixed_images',
                'fixed_content',
                'product_name_signle',
                'share_icons',
                'ajax_add_to_cart',
                'product_zoom',
                'thumbs_autoheight',
                'stretch_product_slider',
                'product_posts_links',
                'first_tab_closed',
                'tabs_scroll',
                'show_related',
                'blog_full_width',
                'blog_masonry',
                'only_blog_sidebar',
                'sticky_sidebar',
                'blog_byline',
                'views_counter',
                'blog_pagination_prev_next',
                'cats_accordion',
                'first_catItem_opened',
                'out_of_icon',
                'sale_icon',
                'sale_percentage',
                'quick_view',
                'quick_descr',
                'enable_swatch',
                'enable_brands',
                'show_brand',
                'show_brand_image',
                'show_brand_title',
                'show_brand_desc',
                'brand_title',
                'promo_popup',
                'promo_open_scroll',
                'promo_link',
                'blog_featured_image',
                'single_post_title',
                'post_share',
                'about_author',
                'posts_links',
                'post_related',
                'portfolio_projects',
                'portfolio_fullwidth',
                'port_first_wide',
                'portfolio_masonry',
                'port_single_nav',
                'et_optimize_js',
                'et_optimize_css',
                'global_masonry',
                'fa_icons',
                'menu_cache',
                'static_block_cache',
                'cssjs_ver',
                'disable_emoji',
            ),
            'media' => array(
                'preloader_img',
                'logo',
                'logo_fixed',
                'mobile_menu_logo',
                'size_guide_img',
            ),
            'slider_spinner' => array(
                'site_width',
                'logo_width',
                'mini-cart-items-count',
                'header_width',
                'header_margin_bottom',
                'header_bg_opacity',
                'top_bar_bg_opacity',
                'fixed_header_bg_opacity',
                'products_per_page',
                'sidebar_widgets_height',
                'filters_columns',
                'product_title_limit',
                'count_slides',
                'tab_height',
                'related_limit',
                'excerpt_length',
                'excerpt_length_sliders',
                'sale_br_radius',
                'secondary-links-border-width',
            ),
            'multitypes' => array(
                'main_layout',
                'header_banner_pos',
                'cart_widget',
                'cart_icon_label',
                'top_wishlist_widget',
                'top_links',
                'sign_in_type',
                'header_color',
                'top_bar_color',
                'search_form',
                'fixed_header',
                'fixed_header_color',
                'mobile_header_color',
                'secondary_menu_visibility',
                'menu_align',
                'breadcrumb_type',
                'breadcrumb_color',
                'breadcrumb_effect',
                'footer_columns',
                'footer_color',
                'copyrights_color',
                'slider_arrows_colors',
                'category_page_columns',
                'sidebar_for_mobile',
                'sidebar_widgets_open_close_type',
                'view_mode',
                'product_bage_banner_pos',
                'product_view',
                'custom_product_template',
                'product_view_color',
                'product_img_hover',
                'empty_cart_content',
                'thumbs_slider_mode',
                'thumbs_slider_vertical',
                'upsell_location',
                'tabs_type',
                'tabs_location',
                'reviews_position',
                'related_type',
                'related_columns',
                'blog_layout',
                'blog_columns',
                'blog_sidebar_for_mobile',
                'blog_hover',
                'read_more',
                'blog_navigation_type',
                'blog_pagination_align',
                'cat_style',
                'cat_text_color',
                'cat_valign',
                'quick_images',
                'quick_view_layout',
                'swatch_position_shop',
                'swatch_layout_shop',
                'brands_location',
                'related_query',
                'portfolio_filters_type',
                'portfolio_style',
                'portfolio_columns',
                'portfolio_margin', // (could be to slider)
                'portfolio_order',
                'portfolio_orderby',

                'portfolio_page',

                // image_select -> radio->image
                'header_type',
                'shopping_cart_icon',
                'grid_sidebar',
                'category_sidebar',
                'single_layout',
                'single_sidebar',
                'blog_sidebar',
                'post_template',
                'post_sidebar',

                // editor 
                'header_custom_block',
                'cart_popup_banner',
                'product_bage_banner',
                'custom_tab',
                'pp_content',

                // colors rgb
                'activecol',
                'sale_icon_color',
                'sale_icon_bg_color',
                'secondary-title-background-color',
                'secondary-title-border-color',

                // text
                'sign_in_text',
                'all_departments_text',
                'et_ppp_options',
                'custom_tab_title',
                'excerpt_words',
                'blog_images_size',
                'blog_related_images_size',
                'sale_icon_text',
                'sale_icon_size',
                'quick_image_height',
                'quick_descr_length',
                'pp_delay',
                'promo-link-text',
                'pp_width',
                'pp_height',
                'portfolio_count',
                'portfolio_images_size',
                'facebook_app_id',
                'facebook_app_secret',
                '404_text',

                // code
                'custom_css',
                'custom_css_desktop',
                'custom_css_tablet',
                'custom_css_wide_mobile',
                'custom_css_mobile',

            ),
            'color_rgba' => array(
                'cart_badge_color',
                'cart_badge_bg',
                'header_border_color',
                'nav-menu-bg',
                'menu_dropdown_bg',
                'menu_dropdown_border_color',
                'menu_dropdown_divider',
                'container_bg',
                'forms_inputs_bg',
                'forms_inputs_br',
                'menu_level_1_hover',
                'menu_level_2_hover',
                'menu_level_3_hover',
                'mobile-search-colors',
                'mobile_bg',
                'mobile_search_input_bg',
                'mobile_search_input_active_bg',
                'mobile_search_input_border_color',
                'mobile_divider_bg',
                'secondary_title',
                'secondary-menu-border-color',
                'star-rating-color',
                'slider_arrows_bg_color',
                'slider_arrows_color'
            ),

            'typography' => array(
                'bc_breadcrumbs_font',
                'bc_title_font',
                'bc_return_font',
                'sfont',
                'headings',
                'menu_level_1',
                'menu_level_2',
                'menu_level_3',
                'mobile-links-fonts',
                'secondary-menu_level_1',
                'secondary-menu_level_2',
                'secondary-menu_level_3',
            ),

            'dimensions_background' => array(
                'header_padding',
                'mobile_header_padding',
                'breadcrumb_padding',
                'footer_padding',
                'copyrights_padding',
                'pp_spacing',
                'quick_dimentions',
                'related_slides',
                'menu_dropdown_links_padding',
                'menu-links-padding',
                'f_menu-links-padding',
                'secondary-menu-padding',

                // background 
                'header_bg',
                'top_bar_bg',
                'fixed_header_bg',
                'mobile_header_bg',
                'breadcrumb_bg',
                'footer_bg_color',
                'copyrights_bg_color',
                'background_img',
                'pp_bg',
                'secondary-links-padding',
                'mobile_search_input_border_width',
            ),

            'multicheck' => array(
                'product_page_switchers',
                'quick_view_switcher',
                'socials',
            ),

            'border_style' => array(
                'menu-border-style',
                'menu-border-style-hover',
                'f_menu-border-style',
                'f_menu-border-style-hover',
                'menu_dropdown_border_style',
                'light_buttons_border_style',
                'dark_buttons_border_style',
                'active_buttons_border_style',
                'secondary-menu-border-style',
                'secondary-links-border-style',
            ),

            'border_width' => array(
                'menu-border-width',
                'menu-border-width-hover',
                'f_menu-border-width',
                'f_menu-border-width-hover',
                'menu_dropdown_border',
                'secondary-menu-border-width',
            ),

            'border_radius' => array(
                'light_buttons_border_radius',
                'dark_buttons_border_radius',
                'active_buttons_border_radius',
                'menu-links-border-radius',
                'f_menu-links-border-radius'
            ),

            'border' => array(
                'light_buttons_border',
                'dark_buttons_border',
                'active_buttons_border',
                'footer_border',
            ),

            'border_hover' => array(
                'light_buttons_border',
                'dark_buttons_border',
                'active_buttons_border',
            ),

            'buttons_color' => array(
                'light_buttons_color',
                'dark_buttons_color',
                'active_buttons_color',
            ),

            'buttons_background' => array(
                'light_buttons_bg',
                'dark_buttons_bg',
                'active_buttons_bg',
            ),

            'multicolor' => array(
                'footer-links',
                'copyrights-links',
            ),

            'multicolor_specific' => array(
                'mobile-links-colors',
                'f_menu_level_1', // 'f_menu_level_1_hover' includes
                'swatch_border', // swatch_border_active includes
                'menu-background',
                'menu-border-color',
                // 'menu-background-hover',
                // 'menu-border-color-hover',
                'f_menu-background',
                'f_menu-border-color',
                // 'f_menu-background-hover',
                // 'f_menu-border-color-hover',
                'menu_dropdown_links_bg',
                // 'menu_dropdown_links_bg_hover',
                'secondary-links-border-color',
                'secondary-links-background',
            ),

            'multibackground_specific' => array(
                'secondary-menu-background',
            ),
        );

        function __construct(){
            $this->ot_options = get_option( 'et_options' );
            $this->options = get_theme_mods();
            // var_dump($this->ot_options);
            // var_dump($this->options);
            $this->migration();
        }

        private function migration() {

            foreach ($this->options_keys as $type => $options) {
                switch ($type) {
                    case 'border_style':
                        foreach ($options as $value ) { 
                            if ( $value == 'f_menu-border-style' ) {
                                $ot_option = (isset( $this->ot_options[$value.'_s'] ) && is_array($this->ot_options[$value.'_s']) && isset($this->ot_options[$value.'_s']['border-style'])) ? $this->ot_options[$value.'_s']['border-style'] : '';
                                $this->options[$value] = $ot_option;
                            }
                            else {
                               $ot_option = (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) && isset($this->ot_options[$value]['border-style'])) ? $this->ot_options[$value]['border-style'] : '';
                                if ( $value == 'mobile_search_input_border_width' ) {
                                    $this->options['mobile_search_input_border_style'] = $ot_option;
                                }
                                else {
                                    $this->options[$value] = $ot_option;
                                }
                            }
                        }
                    break;
                    case 'border_width':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) ) ? $this->ot_options[$value] : '';
                            if ( is_array($ot_option) ) {
                                unset($ot_option['border-style']);
                                unset($ot_option['border-color']);
                            }

                            if ( $value == 'menu_dropdown_border' ) {
                                $this->options[$value.'_width'] = $ot_option;
                            }
                            else {
                                $this->options[$value] = $ot_option;
                            }
                        }
                    break;
                    case 'border_color':
                        foreach ($options as $value ) { 
                            $ot_option = (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) && isset($this->ot_options[$value]['border-color'])) ? $this->ot_options[$value]['border-color'] : '';

                            $this->options[$value.'_color'] = $ot_option;
                        }
                    break;
                    case 'border_radius':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) ) ? $this->ot_options[$value] : '';

                            $kirki_option = array();

                            if ( is_array($ot_option) ) {
                                if ( isset($ot_option['border-top']) )
                                    $kirki_option['border-top-left-radius'] = $ot_option['border-top'];
                                if ( isset($ot_option['border-right']) )
                                    $kirki_option['border-top-right-radius'] = $ot_option['border-right'];
                                if ( isset($ot_option['border-bottom']) )
                                    $kirki_option['border-bottom-right-radius'] = $ot_option['border-bottom'];
                                if ( isset($ot_option['border-left']) )
                                    $kirki_option['border-bottom-left-radius'] = $ot_option['border-left'];
                            }

                            $this->options[$value] = $kirki_option;
                        }
                    break;
                    // change simple border for buttons because redux had in one and kirki has separated options 
                    case 'border':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) ) ? $this->ot_options[$value] : array();

                            if ( $value == 'footer_border' && isset($ot_option['border-color']) ) {;
                                $this->options[$value.'_color'] = $ot_option['border-color'];
                            }
                            elseif ( isset($ot_option['border-color']) ) {
                                if ( is_array($this->options[$value.'_color']) ) {
                                    $this->options[$value.'_color']['regular'] = $ot_option['border-color'];
                                }
                                else {
                                    $this->options[$value.'_color'] = array(
                                        'regular' => $ot_option['border-color']
                                    );
                                }
                            }

                            if ( isset($ot_option['border-style'])) {
                                $this->options[$value.'_style'] = $ot_option['border-style'];
                            }

                            if ( is_array($ot_option) ) {
                                unset($ot_option['border-style']);
                                unset($ot_option['border-color']);
                            }

                            if ( $value == 'footer_border' && isset($ot_option['border-bottom'])) { 
                                $this->options[$value.'_width'] = ($ot_option['border-bottom'] == '' ? 0 : intval($ot_option['border-bottom']));
                            }
                            else {
                                $this->options[$value.'_width'] = $ot_option;
                            }
                        }
                    break;

                    case 'border_hover':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value.'_hover'] ) && is_array($this->ot_options[$value.'_hover']) ) ? $this->ot_options[$value.'_hover'] : array();

                            if ( isset($ot_option['border-color'])) {
                                if ( is_array($this->options[$value.'_color']) ) {
                                    $this->options[$value.'_color']['hover'] = $ot_option['border-color'];
                                }
                                else {
                                    $this->options[$value.'_color'] = array(
                                        'hover' => $ot_option['border-color']
                                    );
                                }
                            }

                            if ( isset($ot_option['border-style'])) {
                                $this->options[$value.'_style_hover'] = $ot_option['border-style'];
                            }

                            if ( is_array($ot_option) ) {
                                unset($ot_option['border-style']);
                                unset($ot_option['border-color']);
                            }

                            $this->options[$value.'_width_hover'] = $ot_option;
                        }
                    break;

                    case 'buttons_color':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) ) ? $this->ot_options[$value] : '';
                            $ot_option_hover = (is_array($this->ot_options[$value.'_hover']) && isset($this->ot_options[$value.'_hover']['hover'])) ? $this->ot_options[$value.'_hover']['hover'] : '';

                            if ( is_array($ot_option) ) {
                                $this->options[$value] = $ot_option;
                            }
                            else {
                                $this->options[$value] = array(
                                    $ot_option
                                );
                            }
                            if ( isset($this->options[$value]) && is_array($this->options[$value]) ) {
                                $this->options[$value]['hover'] = $ot_option_hover;
                            }
                            else {
                                $this->options[$value]['hover'] = $ot_option_hover;
                            }
                        }
                    break;

                    case 'buttons_background':
                        foreach ($options as $value ) {
                            $ot_option = (is_array($this->ot_options[$value])) ? $this->ot_options[$value] : array();
                            $ot_option_hover = (is_array($this->ot_options[$value.'_hover'])) ? $this->ot_options[$value.'_hover'] : array();

                            if ( isset($this->options[$value]) ) {
                                if ( !is_array($this->options[$value] ) ) {
                                    $this->options[$value] = array();
                                }
                                if ( isset($ot_option['rgba'] ) ) {
                                    $this->options[$value]['regular'] = $ot_option['rgba'];
                                }
                                if ( isset($ot_option_hover['rgba']) ) {
                                    $this->options[$value]['hover'] = $ot_option_hover['rgba'];
                                }
                            }
                        }
                    break;

                    case 'multicolor':
                        foreach ($options as $value ) {
                            if (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value])) {
                                $this->options[$value] = $this->ot_options[$value];
                            }
                        }
                    break;

                    case 'multicolor_specific':
                        foreach ($options as $value ) {
                            switch ($value) {
                                case 'mobile-links-colors':
                                    if (isset( $this->ot_options[$value.'-regular']['rgba'] ) && is_array($this->ot_options[$value.'-regular'])) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['regular'] = $this->ot_options[$value.'-regular']['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'regular' => $this->ot_options[$value.'-regular']['rgba']
                                            );
                                        }
                                    }
                                    if (isset( $this->ot_options[$value.'-hover']['rgba'] ) && is_array($this->ot_options[$value.'-hover'])) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['hover'] = $this->ot_options[$value.'-hover']['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'hover' => $this->ot_options[$value.'-hover']['rgba']
                                            );
                                        }
                                    }
                                break;
                                case 'swatch_border':
                                    if (isset( $this->ot_options[$value]['rgba'] ) ) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['regular'] = $this->ot_options[$value]['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'regular' => $this->ot_options[$value]['rgba']
                                            );
                                        }
                                    }
                                    if (isset( $this->ot_options[$value.'_active']['rgba'] ) ) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['hover'] = $this->ot_options[$value.'_active']['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'hover' => $this->ot_options[$value.'_active']['rgba']
                                            );
                                        }
                                    }
                                break;
                                case 'f_menu_level_1':
                                case 'menu_dropdown_links_bg':
                                    if (isset( $this->ot_options[$value]['rgba'] ) ) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['regular'] = $this->ot_options[$value]['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'regular' => $this->ot_options[$value]['rgba']
                                            );
                                        }
                                    }
                                    if (isset( $this->ot_options[$value.'_hover']['rgba'] ) ) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['hover'] = $this->ot_options[$value.'_hover']['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'hover' => $this->ot_options[$value.'_hover']['rgba']
                                            );
                                        }
                                    }
                                break;
                                default:
                                    if (isset( $this->ot_options[$value]['rgba'] )) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['regular'] = $this->ot_options[$value]['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'regular' => $this->ot_options[$value]['rgba']
                                            );
                                        }
                                    }
                                    if (isset( $this->ot_options[$value.'-hover']['rgba'] ) ) {
                                        if ( is_array($this->options[$value])) {
                                            $this->options[$value]['hover'] = $this->ot_options[$value.'-hover']['rgba'];
                                        }
                                        else {
                                            $this->options[$value] = array(
                                                'hover' => $this->ot_options[$value.'-hover']['rgba']
                                            );
                                        }
                                    }
                                break;
                            }
                        }
                    break;
                    case 'multibackground_specific':
                        foreach ($options as $value ) {
                            $ot_option = isset($this->ot_options[$value.'-image']) && is_array($this->ot_options[$value.'-image']) ? $this->ot_options[$value.'-image'] : array();
                            $kirki_option = isset($this->options[$value.'-image']) && is_array($this->options[$value.'-image']) ? $this->options[$value.'-image'] : array();

                            if ( is_array($ot_option) ) {
                                foreach ($ot_option as $property => $val) {
                                    $kirki_option[$property] = $val;
                                }
                                if ( isset( $this->ot_options[$value.'-color'] ) ) {
                                    $kirki_option['background-color'] = $this->ot_options[$value.'-color'];
                                }
                                $this->options[$value.'-image'] = $kirki_option;
                            }
                        }
                    break;
                    case 'switch':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) && '1' == $this->ot_options[$value]) ? true : false;

                            $this->options[$value] = $ot_option;
                        }
                    break;
                    case 'media':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) ) ? $this->ot_options[$value] : '';

                            $kirki_option = isset($this->options[$value]) ? $this->options[$value] : array();

                            if ( is_array($ot_option) ) {
                                if ( isset($ot_option['url']) )
                                    $kirki_option['url'] = $ot_option['url'];
                                if ( isset($ot_option['id']) )
                                    $kirki_option['id'] = $ot_option['id'];
                                if ( isset($ot_option['height']) )
                                    $kirki_option['height'] = $ot_option['height'];
                                if ( isset($ot_option['width']) )
                                    $kirki_option['width'] = $ot_option['width'];

                            }
                            else {
                                $kirki_option = array(
                                    'url' => $ot_option
                                );
                            }
                            $this->options[$value] = $kirki_option;
                        }
                    break;
                    case 'slider_spinner':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) ) ? $this->ot_options[$value] : '';
                            $ot_option = (int)$ot_option;

                            $this->options[$value] = $ot_option;
                        }
                    break;
                    case 'multitypes':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) ) ? $this->ot_options[$value] : '';

                            if ( $value == 'custom_css' ) {
                                $this->options[$value.'_global'] = $ot_option;
                            }
                            else {
                                $this->options[$value] = $ot_option;
                            }
                        }
                    break;
                    case 'color_rgba':
                        foreach ($options as $value ) {
                            $ot_option = '';

                            if (isset( $this->ot_options[$value] ) && is_array($this->ot_options[$value]) ) {
                                if ( isset($this->ot_options[$value]['rgba']) && isset($this->ot_options[$value]['color'] ) && $this->ot_options[$value]['color'] != '' ) {
                                    $ot_option = $this->ot_options[$value]['rgba'];
                                }
                            }

                            $this->options[$value] = $ot_option;
                        }
                        break;
                    case 'dimensions_background':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) ) ? $this->ot_options[$value] : '';

                            $kirki_option = isset($this->options[$value]) ? $this->options[$value] : array();

                            if ( is_array($ot_option) ) {
                                unset($ot_option['units']);
                                unset($ot_option['media']);
                                unset($ot_option['border-style']);
                                unset($ot_option['border-color']);
                                unset($ot_option['border-width']);
                                foreach ($ot_option as $property => $val) {
                                    $kirki_option[$property] = $val;
                                }
                                $this->options[$value] = $kirki_option;
                            }
                        }
                    break;
                    case 'multicheck':
                        foreach ($options as $value ) {
                            $ot_option = (isset( $this->ot_options[$value] ) ) ? $this->ot_options[$value] : '';

                            $kirki_option = array();

                            if ( is_array($ot_option) && isset( $ot_option['enabled'] ) && is_array($ot_option['enabled']) ) {
                                    unset($ot_option['enabled']['placebo']);
                                    foreach ($ot_option['enabled'] as $key => $val) {
                                        array_push($kirki_option, $key);
                                    }
                                $this->options[$value] = $kirki_option;
                            }
                        }
                    break;
                    case 'typography':
                        foreach ($options as $value ) {

                            $ot_option = (isset( $this->ot_options[$value] ) && '' != $this->ot_options[$value]) ? $this->ot_options[$value] : array();
                            $redux_option = isset($this->options[$value]) ? $this->options[$value] : array();
                            $redux_option = !is_array($redux_option) ? array() : $redux_option;

                            foreach ($ot_option as $option_name => $option_value) {
                                switch ($option_name) {
                                    case 'font-family':
                                    // var_dump(get_site_transient( 'kirki_googlefonts_cache' ));
                                    // echo in_array($option_value, get_site_transient( 'kirki_googlefonts_cache' );
                                    if ( array_key_exists($option_value, get_site_transient( 'kirki_googlefonts_cache' ) ) || in_array($option_value, array(
                                        "Arial, Helvetica, sans-serif",
                                        "Courier, monospace",
                                        "Garamond, serif",
                                        "Georgia, serif",
                                        "Impact, Charcoal, sans-serif",
                                        "Tahoma,Geneva, sans-serif",
                                        "Verdana, Geneva, sans-serif",
                                    ) ) ) 
                                        $redux_option[$option_name] = $option_value;
                                    elseif ( '' != trim($option_value) ) 
                                        $redux_option[$option_name] = '"'.$option_value.'"';
                                    else 
                                        $redux_option[$option_name] = '';
                                    break;
                                    case 'font-family':
                                    case 'font-style':
                                    case 'letter-spacing':
                                    case 'font-size':
                                    case 'line-height':
                                    case 'color':
                                    case 'text-transform':
                                        $redux_option[$option_name] = $option_value;
                                        break;
                                    case 'font-weight':
                                        $redux_option[$option_name] = $option_value;
                                        if ( isset( $ot_option['font-style'] ) )
                                           $redux_option['variant'] = $option_value.$ot_option['font-style'];
                                        else 
                                            $redux_option['variant'] = $option_value;
                                    break;

                                    default:
                                        break;
                                }
                            }

                            $this->options[$value] = $redux_option;
                        }
                    break;

                    default;
                }
            }

            foreach ($this->options as $key => $value) {
                set_theme_mod($key, $value);    
            }

            // update_option( 'et_kirki_options', $this->options );
            update_option( 'xstore_theme_migrated', true );

            if ( isset( $_GET['xstore_theme_migrate_options'] ) ) {
                wp_safe_redirect( admin_url( 'customize.php' ) );
            }

        }
    }

?>