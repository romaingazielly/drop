<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Dashboard.
 *
 * Add admin panel dashboard pages to admin menu.
 * Output dashboard pages.
 *
 * @since   5.0.0
 * @version 1.0.7
 */

class EthemeAdmin{
	/**
	 * Theme name
	 *
	 * @var string
	 */
    protected $theme_name;

	/**
	 * Panel page
	 *
	 * @var array
	 */
	protected $page = array();

    // ! Main construct/ add actions
    function __construct(){
        add_action( 'admin_menu', array( $this, 'et_add_menu_page' ) );
        add_action( 'admin_head', array( $this, 'et_add_menu_page_target') );
        add_action( 'wp_ajax_et_ajax_panel_popup', array($this, 'et_ajax_panel_popup') );

	    if ( isset($_REQUEST['helper']) && $_REQUEST['helper']){
		    $this->require_class($_REQUEST['helper']);
	    }

	    add_action( 'wp_ajax_et_panel_ajax', array($this, 'et_panel_ajax') );

        $current_theme         = wp_get_theme();
        $this->theme_name      = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );

        add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
	    add_action('admin_init',array($this,'add_page_admin_script'), 1140);

	    if(!is_child_theme()){
		    add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
	    }

	    if ( ! $this->set_page_data() ){
		    return;
	    }

	    if (isset($this->page['class']) && ! empty($this->page['class'])){
		    $this->require_class($this->page['class']);
	    }
    }

	/**
	 * enqueue scripts for current panel page
	 *
	 * @version  1.0.0
	 * @since  7.0.0
	 */
    public function add_page_admin_script(){
        if ( isset($this->page['script']) && ! empty($this->page['script']) ){
	        wp_enqueue_script('etheme_panel_'.$this->page['script'],ETHEME_BASE_URI.'framework/panel/js/'.$this->page['script'].'.js', array('jquery','etheme_admin_js'), false,true);
        }
    }

	/**
	 * Set panel page data
	 *
	 * @version  1.0.1
	 * @since  7.0.0
	 * @log added sales_booster actions
	 */
    public function set_page_data(){
        if (! isset($_REQUEST['page'])){
            return false;
        }
	    switch ( $_REQUEST['page'] ) {
		    case 'et-panel-system-requirements':
			    $this->page['template'] = 'system-requirements';
			    break;
		    case 'et-panel-changelog':
			    $this->page['template'] = 'changelog';
			    break;
		    case 'et-panel-support':
			    $this->page['template'] = 'support';
			    $this->page['class'] = 'youtube';
			    break;
		    case 'et-panel-demos':
			    $this->page['template'] = 'demos';
			    break;
		    case 'et-panel-custom-fonts':
			    $this->page['template'] = 'custom-fonts';
			    break;
		    case 'et-panel-sales-booster':
			    $this->page['script'] = 'sales_booster.min';
			    $this->page['template'] = 'sales-booster';
			    $this->page['class'] = 'sales_booster';
			    break;
		    case 'et-panel-social':
			    $this->page['script'] = 'instagram.min';
			    $this->page['template'] = 'instagram';
			    $this->page['class'] = 'instagram';
			    break;
		    case 'et-panel-plugins':
			    $this->page['script'] = 'plugins.min';
			    $this->page['template'] = 'plugins';
			    $this->page['class'] = 'plugins';
			    break;
		    case 'et-panel-generator':
			    $this->page['script'] = 'generator.min';
			    $this->page['template'] = 'generator';
			    $this->page['class'] = 'generator';
			    break;
		    case 'et-panel-email-builder':
			    $this->page['script'] = 'email_builder.min';
			    $this->page['template'] = 'email-builder';
			    $this->page['class'] = 'email_builder';
			    break;
		    default:
			    $this->page['template'] = 'welcome';
			    break;
	    }
        return true;
    }

	/**
	 * Require page classes
	 *
	 * require page classes when ajax process and return the callbacks for ajax requests
	 *
	 * @version  1.0.0
	 * @since  7.0.0
     * @param string $class class filename
	 */
    public function require_class($class=''){
        if (! $class){
            return;
        }
	    require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'panel/classes/'.$class.'.php') );
    }

	/**
	 * Global panel ajax
     *
     * require page classes when ajax process and return the callbacks for ajax requests
	 *
	 * @version  1.0.2
	 * @since  7.0.0
     * @todo remove this
     * @log added sales_booster actions
	 */
    public function et_panel_ajax(){
        if ( isset($_POST['action_type']) ){
	        switch ( $_POST['action_type'] ) {
		        case 'et_generator':
			        $this->require_class('generator');
			        $class = new Etheme_Generator;
			        $class->generator();
			        break;
		        case 'et_generator_remover':
			        $this->require_class('generator');
			        $class = new Etheme_Generator;
			        $class->generator_remover();
			        break;
		        case 'et_instagram_user_add':
			        $this->require_class('instagram');
			        $class = new Instagram();
			        $class->et_instagram_user_add();
			        break;
                case 'et_instagram_user_remove':
	                $this->require_class('instagram');
                    $class = new Instagram();
                    $class->et_instagram_user_remove();
                    break;
		        case 'et_instagram_save_settings':
		            $this->require_class('instagram');
			        $class = new Instagram();
			        $class->et_instagram_save_settings();
			        break;
		        case 'et_email_builder_switch_default':
			        $this->require_class('email_builder');
			        $class = new Email_builder();
			        $class->et_email_builder_switch_default();
			        break;
		        case 'et_sales_booster_fake_sale_popup_switch_default':
			        $this->require_class('sales_booster');
			        $class = new Sales_Booster();
			        $class->et_sales_booster_fake_sale_popup_switch_default();
			        break;
		        case 'et_sales_booster_progress_bar_switch_default':
			        $this->require_class('sales_booster');
			        $class = new Sales_Booster();
			        $class->et_sales_booster_progress_bar_switch_default();
			        break;
		        case 'et_sales_booster_request_quote_switch_default':
			        $this->require_class('sales_booster');
			        $class = new Sales_Booster();
			        $class->et_sales_booster_request_quote_switch_default();
			        break;
		        default:
			        break;
	        }
        }
    }

    /**
     * Add admin panel dashboard pages to admin menu.
     *
     * @since   5.0.0
     * @version 1.0.3
     */
    public function et_add_menu_page(){
        $system = new Etheme_System_Requirements();
        $system->system_test();
        $result = $system->result();

        $is_et_core = class_exists('ETC\App\Controllers\Admin\Import');
        $is_activated = etheme_is_activated();
        $is_wc = class_exists('WooCommerce');
	    $info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
	    $update_info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_green-color); font-size: 22px;"></span></span>';

	    $icon = ETHEME_CODE_IMAGES . 'wp-icon.svg';
	    $label = 'XStore';
	    $show_pages = array(
		    'welcome',
		    'system_requirements',
		    'demos',
		    'plugins',
		    'customize',
		    'generator',
		    'email_builder',
		    'sales_booster',
		    'custom_fonts',
		    'social',
		    'support',
		    'changelog',
            'sponsors'
        );
	    
	    $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
	
	    if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
	        if ( $xstore_branding_settings['control_panel']['icon'] )
	            $icon = $xstore_branding_settings['control_panel']['icon'];
		    if ( $xstore_branding_settings['control_panel']['label'] )
			    $label = $xstore_branding_settings['control_panel']['label'];
		    
		    $show_pages_parsed = array();
		    foreach ( $show_pages as $show_page ) {
                if ( isset($xstore_branding_settings['control_panel']['page_'.$show_page]))
                    $show_pages_parsed[] = $show_page;
		    };
		    $show_pages = $show_pages_parsed;
        }

	    $is_update_support = 'active';

	    if (
		    $is_activated
	    ){
            if (
                    isset($xstore_branding_settings['control_panel'])
                    && isset($xstore_branding_settings['control_panel']['hide_updates'])
                    && $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
            ){
	            $is_update_support = 'active';
	            $is_update_available = false;
            } else {
	            $check_update = new ETheme_Version_Check();
	            $is_update_available = $check_update->is_update_available();
	            $is_update_support = 'active'; //$check_update->get_support_status();
            }

	    } else {
		    $is_update_available = false;
        }

	    if ($is_activated && $is_update_support !='active' && $result){
            if ($is_update_support == 'expire-soon'){
	            $info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
            } else {
	            $info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_red-color); font-size: 22px;"></span></span>';
            }
        } else if ($is_activated && $is_update_available && $result ){
	        $info = $update_info;
        } elseif(!$is_activated){
		    $info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
		    //$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_red-color); font-size: 22px;"></span></span>';
	    }

        add_menu_page( 
                $label . ' ' . ( ( !$is_activated || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
            $label . ' ' . ( ( !$is_activated || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
            'manage_options', 
            'et-panel-welcome',
            array( $this, 'etheme_panel_page' ),
	        $icon,
            65
        );
	
	    if ( in_array('welcome', $show_pages) ) {
		    add_submenu_page(
			    'et-panel-welcome',
			    esc_html__( 'Dashboard', 'xstore' )  .' '. ( !$is_activated || ($is_update_support !='active' && $result) ? $info : ''),
			    esc_html__( 'Dashboard', 'xstore' ) .' '. ( !$is_activated || ($is_update_support !='active' && $result) ? $info : ''),
			    'manage_options',
			    'et-panel-welcome',
			    array( $this, 'etheme_panel_page' )
		    );
	    }
	
	    if ( in_array('system_requirements', $show_pages) ) {


		    $server_label = esc_html__( 'Server Requirements', 'xstore' );

		    if (!$result && $is_activated){
			    $server_label = esc_html__( 'Server Reqs.', 'xstore' );
			    $server_label .= ' ' . $info;
            }

		    add_submenu_page(
			    'et-panel-welcome',
			    $server_label,
			    $server_label,
			    'manage_options',
			    'et-panel-system-requirements',
			    array( $this, 'etheme_panel_page' )
		    );
	    }
        
        if ( $is_activated ) {
	
	        if ( in_array('demos', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Import Demos', 'xstore' ),
			        esc_html__( 'Import Demos', 'xstore' ),
			        'manage_options',
			        'et-panel-demos',
			        array( $this, 'etheme_panel_page' )
		        );
	        }
	
	        if ( in_array('plugins', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Plugins Installer', 'xstore' ),
			        esc_html__( 'Plugins Installer', 'xstore' ),
			        'manage_options',
			        'et-panel-plugins',
			        array( $this, 'etheme_panel_page' )
		        );
	        }
	
	        if ( in_array('generator', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Files Generator', 'xstore' ),
			        esc_html__( 'Files Generator', 'xstore' ),
			        'manage_options',
			        'et-panel-generator',
			        array( $this, 'etheme_panel_page' )
		        );
	        }

        }

//        if ( ! etheme_is_activated() && ! class_exists( 'Kirki' ) ) {
            // add_submenu_page(
            //     'et-panel-welcome',
            //     esc_html__( 'Setup Wizard', 'xstore' ),
            //     esc_html__( 'Setup Wizard', 'xstore' ),
            //     'manage_options',
            //     admin_url( 'themes.php?page=xstore-setup' ),
            //     ''
            // );
//        } elseif( ! etheme_is_activated() ){
//
//        } elseif( ! class_exists( 'Kirki' ) ){
//            add_submenu_page(
//                'et-panel-welcome',
//                esc_html__( 'Plugins installer', 'xstore' ),
//                esc_html__( 'Plugins installer', 'xstore' ),
//	            'manage_options',
//	            'et-panel-plugins',
//	            array( $this, 'etheme_panel_page' )
//            );
//        }
//        else {
//
//            add_submenu_page(
//                'et-panel-welcome',
//                esc_html__( 'Install Plugins', 'xstore' ),
//                esc_html__( 'Install Plugins', 'xstore' ),
//                'manage_options',
//                admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
//                ''
//            );
//        }

        if ( $is_activated && $is_et_core ) {

            if ( ! class_exists( 'Kirki' ) ) {
//                add_submenu_page(
//                    'et-panel-welcome',
//                    'Theme Options',
//                    'Theme Options',
//                    'manage_options',
//                    admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
//                    ''
//                );
            }
            elseif ( get_option('et_options') && (!get_option( 'xstore_theme_migrated', false ) ) ) {
	            if ( in_array('customize', $show_pages) ) {
		            add_submenu_page(
			            'et-panel-welcome',
			            'Theme Options',
			            'Theme Options',
			            'manage_options',
			            add_query_arg( 'xstore_theme_migrate_options', 'true', wp_customize_url() ),
			            ''
		            );
		            add_submenu_page(
			            'et-panel-welcome',
			            'Header Builder',
			            'Header Builder',
			            'manage_options',
			            add_query_arg( 'xstore_theme_migrate_options', 'true', admin_url( '/customize.php?autofocus[panel]=header-builder' ) ),
			            ''
		            );
		            add_submenu_page(
			            'et-panel-welcome',
			            'Single Product Builder',
			            'Single Product Builder',
			            'manage_options',
			            ( get_option( 'etheme_single_product_builder', false ) ? add_query_arg( 'xstore_theme_migrate_options', 'true', admin_url( '/customize.php?autofocus[panel]=single_product' ) ) : add_query_arg( 'xstore_theme_migrate_options', 'true', admin_url( '/customize.php?autofocus[section]=single_product' ) ) ),
			            ''
		            );
	            }
            }
            else {
	            if ( in_array('customize', $show_pages) ) {
		            add_submenu_page(
			            'et-panel-welcome',
			            'Theme Options',
			            'Theme Options',
			            'manage_options',
			            wp_customize_url(),
			            ''
		            );
		            add_submenu_page(
			            'et-panel-welcome',
			            'Header Builder',
			            'Header Builder',
			            'manage_options',
			            admin_url( '/customize.php?autofocus[panel]=header-builder' ),
			            ''
		            );
		            add_submenu_page(
			            'et-panel-welcome',
			            'Single Product Builder',
			            'Single Product Builder',
			            'manage_options',
			            ( get_option( 'etheme_single_product_builder', false ) ? admin_url( '/customize.php?autofocus[panel]=single_product_builder' ) : admin_url( '/customize.php?autofocus[section]=single_product_builder' ) ),
			            ''
		            );
	            }
            }
	        
            if ( $is_wc ) {
	            if ( in_array( 'email_builder', $show_pages ) ) {
		            add_submenu_page(
			            'et-panel-welcome',
			            esc_html__( 'Email Builder', 'xstore' ),
			            esc_html__( 'Email Builder', 'xstore' ),
			            'manage_options',
			            'et-panel-email-builder',
			            array( $this, 'etheme_panel_page' )
		            );
	            }
	
	            if ( $is_et_core && in_array( 'sales_booster', $show_pages ) ) {
		            add_submenu_page(
			            'et-panel-welcome',
			            esc_html__( 'Sales Booster', 'xstore' ),
			            esc_html__( 'Sales Booster', 'xstore' ),
			            'manage_options',
			            'et-panel-sales-booster',
			            array( $this, 'etheme_panel_page' )
		            );
	            }
	
            }
	
	        if ( in_array('social', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Authorization APIs', 'xstore' ),
			        esc_html__( 'Authorization APIs', 'xstore' ),
			        'manage_options',
			        'et-panel-social',
			        array( $this, 'etheme_panel_page' )
		        );
	        }
	
	        if ( in_array('custom_fonts', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Custom Fonts', 'xstore' ),
			        esc_html__( 'Custom Fonts', 'xstore' ),
			        'manage_options',
			        'et-panel-custom-fonts',
			        array( $this, 'etheme_panel_page' )
		        );
	        }
	
	        if ( in_array('support', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Tutorials & Support', 'xstore' ),
			        esc_html__( 'Tutorials & Support', 'xstore' ),
			        'manage_options',
			        'et-panel-support',
			        array( $this, 'etheme_panel_page' )
		        );
	        }
	
	        if ( in_array('changelog', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        esc_html__( 'Changelog', 'xstore' ),
			        esc_html__( 'Changelog', 'xstore' ),
			        'manage_options',
			        'et-panel-changelog',
			        array( $this, 'etheme_panel_page' )
		        );
	        }
        }
        
        else {
	        if ( in_array('customize', $show_pages) ) {
		        add_submenu_page(
			        'et-panel-welcome',
			        'Theme Options',
			        'Theme Options',
			        'manage_options',
			        admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
			        ''
		        );
	        }
        }

        if ( $is_activated && in_array('sponsors', $show_pages) ) {

	        add_submenu_page(
		        'et-panel-welcome',
		        esc_html__( 'SEO Experts', 'xstore' ),
		        esc_html__( 'SEO Experts', 'xstore' ),
		        'manage_options',
		        'https://overflowcafe.com/am/aff/go/8theme',
		        ''
	        );

	        add_submenu_page(
	            'et-panel-welcome',
	            esc_html__( 'Customization Services', 'xstore' ),
	            esc_html__( 'Customization Services', 'xstore' ),
	            'manage_options',
	            'https://wpkraken.io/?ref=8theme',
	            ''
            );

//	        add_submenu_page(
//		        'et-panel-welcome',
//		        esc_html__( 'Woocommerce Hosting', 'xstore' ),
//		        esc_html__( 'Woocommerce Hosting', 'xstore' ),
//		        'manage_options',
//		        'http://www.bluehost.com/track/8theme',
//		        ''
//	        );

	        add_submenu_page(
		        'et-panel-welcome',
		        esc_html__( 'Get WPML Plugin', 'xstore' ),
		        esc_html__( 'Get WPML Plugin', 'xstore' ),
		        'manage_options',
		        'https://wpml.org/?aid=46060&affiliate_key=YI8njhBqLYnp&dr',
		        ''
	        );

//	        add_submenu_page(
//		        'et-panel-welcome',
//		        esc_html__( 'WooCommerce Plugins', 'xstore' ),
//		        esc_html__( 'WooCommerce Plugins', 'xstore' ),
//		        'manage_options',
//		        'https://yithemes.com/product-category/plugins/?refer_id=1028760',
//		        ''
//	        );

//            if ( $is_et_core ) {
//		        add_submenu_page(
//			        'et-panel-welcome',
//			        esc_html__( 'Rate Theme', 'xstore' ),
//			        esc_html__( 'Rate Theme', 'xstore' ),
//			        'manage_options',
//			        'https://themeforest.net/item/xstore-responsive-woocommerce-theme/reviews/15780546',
//			        ''
//		        );
//	        }
        }
    }

    /**
     * Add target blank to some dashboard pages.
     *
     * @since   6.2
     * @version 1.0.0
     */
    public function et_add_menu_page_target() {
        ob_start(); ?>
            <script type="text/javascript">
                jQuery(document).ready( function($) {
                    $('#adminmenu .wp-submenu a[href*=themeforest]').attr('target','_blank');
                });
            </script>
        <?php echo ob_get_clean();
    }

    /**'
     * Show Add admin panel dashboard pages.
     *
     * @since   5.0.0
     * @version 1.0.4
     */
    public function etheme_panel_page(){
        ob_start();
            get_template_part( 'framework/panel/templates/page', 'header' );
                get_template_part( 'framework/panel/templates/page', 'navigation' );
                echo '<div class="et-row etheme-page-content">';

	                if (isset($this->page['template']) && ! empty($this->page['template'])){
		                get_template_part( 'framework/panel/templates/page', $this->page['template'] );
	                }
                echo '</div>';
            get_template_part( 'framework/panel/templates/page', 'footer' );
        echo ob_get_clean();
    }

	/**
	 * Load content for panel popups
	 *
	 * @since   6.0.0
	 * @version 1.0.1
     * @log 1.0.2
     * ADDED: et_ajax_panel_popup header param
	 */
    public function et_ajax_panel_popup(){
        $response = array();

        if ( isset( $_POST['type'] ) && $_POST['type'] == 'instagram' ) {
            ob_start();
            get_template_part( 'framework/panel/templates/popup-instagram', 'content' );
            $response['content'] = ob_get_clean();
        } elseif(  isset( $_POST['type'] ) && $_POST['type'] == 'code_error' ){
	        ob_start();
	        get_template_part( 'framework/panel/templates/popup', 'code' );
	        $response['content'] = ob_get_clean();
        } else {

            if (! isset( $_POST['header'] ) || $_POST['header'] !== 'false'){
	            ob_start();
	            get_template_part( 'framework/panel/templates/popup-import', 'head' );
	            $response['head'] = ob_get_clean();
            } else {
	            $response['head'] = '';
            }

            ob_start();
                get_template_part( 'framework/panel/templates/popup-import', 'content');
            $response['content'] = ob_get_clean();
        }
        wp_send_json($response);
    }

	/**
	 * Redirect after theme was activated
	 *
	 * @since   6.0.0
	 * @version 1.0.0
	 */
    public function admin_redirects() {
        ob_start();
        if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
            return;
        }
        delete_transient( '_' . $this->theme_name . '_activation_redirect' );
        wp_safe_redirect( admin_url( 'admin.php?page=et-panel-welcome' ) );
        exit;
    }

	public function switch_theme() {
		set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
	}
}
new EthemeAdmin;