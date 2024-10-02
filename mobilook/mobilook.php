<?php

/*
* Plugin Name: MOBILOOK
* Description: Instant mobile view of website (pages, posts, products) for responsive web design on phone (+ dualscreen). This plugin also offers helpful tools on each page, such as LinkedIn Post Inspector, and Google Mobile-Friendly Test Tool.
* Author: Pagup
* Version: 2.0.2
* Author URI: https://pagup.ca/
* Text Domain: mobilook
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'mobilook_fs' ) ) {
    mobilook_fs()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'mobilook_fs' ) ) {
        if ( !defined( 'MOBILOOK_PLUGIN_BASE' ) ) {
            define( 'MOBILOOK_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'MOBILOOK_PLUGIN_DIR' ) ) {
            define( 'MOBILOOK_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        if ( !defined( 'MOBILOOK_PLUGIN_MODE' ) ) {
            define( 'MOBILOOK_PLUGIN_MODE', "production" );
        }
        require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
        /******************************************
                   Freemius Init
           *******************************************/
        function mobilook_fs() {
            global $mobilook_fs;
            if ( !isset( $mobilook_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $mobilook_fs = fs_dynamic_init( array(
                    'id'              => '3641',
                    'slug'            => 'mobilook',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_5061a0f11623f388ce4c9687f669e',
                    'is_premium'      => false,
                    'premium_suffix'  => 'for Woocommerce & Debugger',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                        'slug'       => 'mobilook',
                        'first-path' => 'admin.php?page=mobilook',
                        'support'    => false,
                    ),
                    'is_live'         => true,
                ) );
            }
            return $mobilook_fs;
        }

        // Init Freemius.
        mobilook_fs();
        // Signal that SDK was initiated.
        do_action( 'mobilook_fs_loaded' );
        function mobilook_fs_settings_url() {
            return admin_url( 'admin.php?page=mobilook' );
        }

        mobilook_fs()->add_filter( 'connect_url', 'mobilook_fs_settings_url' );
        mobilook_fs()->add_filter( 'after_skip_url', 'mobilook_fs_settings_url' );
        mobilook_fs()->add_filter( 'after_connect_url', 'mobilook_fs_settings_url' );
        mobilook_fs()->add_filter( 'after_pending_connect_url', 'mobilook_fs_settings_url' );
        // freemius opt-in
        function mobilook_fs_custom_connect_message(
            $message,
            $user_first_name,
            $product_title,
            $user_login,
            $site_link,
            $freemius_link
        ) {
            $break = "<br><br>";
            $more_plugins = '<p><a target="_blank" href="https://wordpress.org/plugins/meta-tags-for-seo/">Meta Tags for SEO</a>, <a target="_blank" href="https://wordpress.org/plugins/automatic-internal-links-for-seo/">Auto internal links for SEO</a>, <a target="_blank" href="https://wordpress.org/plugins/mobilook/">Bulk auto image Alt Text</a>, <a target="_blank" href="https://wordpress.org/plugins/bulk-image-title-attribute/">Bulk auto image Title Tag</a>, <a target="_blank" href="https://wordpress.org/plugins/mobilook/">Mobile view</a>, <a target="_blank" href="https://wordpress.org/plugins/better-robots-txt/">Wordpress Better-Robots.txt</a>, <a target="_blank" href="https://wordpress.org/plugins/wp-google-street-view/">Wp Google Street View</a>, <a target="_blank" href="https://wordpress.org/plugins/vidseo/">VidSeo</a>, ...</p>';
            return sprintf( esc_html__( 'Hey %1$s, %2$s Click on Allow & Continue to start optimizing your responsive design on all devices (including foldable screen phones) with MOBILOOK! Don’t spend your time checking your phone to see if your website looks properly. MOBILLOK is a time-saver and features many very helpful tools, like LinkedIn Post Inspector, and Google Mobile-Friendly Test Tool.', 'mobilook' ), $user_first_name, $break ) . $more_plugins;
        }

        mobilook_fs()->add_filter(
            'connect_message',
            'mobilook_fs_custom_connect_message',
            10,
            6
        );
        class Mobilook {
            function __construct() {
                register_deactivation_hook( __FILE__, array(&$this, 'deactivate') );
            }

            public function deactivate() {
                if ( \Pagup\Mobilook\Core\Option::check( 'remove_settings' ) ) {
                    delete_option( 'mobilook' );
                    delete_option( 'mobilook_tour' );
                }
            }

        }

        $mobilook = new Mobilook();
        /*-----------------------------------------
                  FRONTEND CONTROLLER
          ------------------------------------------*/
        require_once 'admin/controllers/FrontendController.php';
        /*-----------------------------------------
                          Settings
          ------------------------------------------*/
        if ( is_admin() ) {
            include_once \Pagup\Mobilook\Core\Plugin::path( 'admin/Settings.php' );
        }
    }
}
