<?php

namespace Pagup\Mobilook\Traits;

trait PluginHelperTrait
{
    /**
     * Check if the current page is the Settings page.
     *
     * @param string $plugin_slug
     * @return bool
     */
    protected function is_settings_page( string $plugin_slug ) : bool {
        return isset( $_GET['page'] ) && $_GET['page'] === $plugin_slug;
    }

    /**
     * Generates a URL for installing a specific WordPress plugin.
     *
     * @param string $plugin_slug The slug of the plugin to install.
     * @return string The URL for installing the specified WordPress plugin.
     */
    public function plugin_install_url( string $plugin_slug ) : string {
        $nonce = wp_create_nonce( 'install-plugin_' . $plugin_slug );
        return admin_url( "update.php?action=install-plugin&plugin=" . $plugin_slug . "&_wpnonce=" . $nonce );
    }

    /**
     * Checks if a plugin with a given slug is installed.
     *
     * @param string $plugin_slug The slug of the plugin to check.
     * @return bool True if the plugin is installed, false otherwise.
     */
    public function is_plugin_installed( string $plugin_slug ) : bool {
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $installed_plugins = get_plugins();
        foreach ( $installed_plugins as $path => $details ) {
            if ( strpos( $path, $plugin_slug ) === 0 ) {
                return true;
            }
        }
        return false;
    }

    /**
     * List of installable plugins
     *
     * @return array Array of objects with url string & installed boolean value for each plugin
     */
    public function installable_plugins() : array {
        return [
            'bialty'    => [
                'url'       => $this->plugin_install_url( 'mobilook' ),
                'installed' => $this->is_plugin_installed( 'mobilook' ),
            ],
            'bigta'     => [
                'url'       => $this->plugin_install_url( 'bulk-image-title-attribute' ),
                'installed' => $this->is_plugin_installed( 'bulk-image-title-attribute' ),
            ],
            'autofkw'   => [
                'url'       => $this->plugin_install_url( 'auto-focus-keyword-for-seo' ),
                'installed' => $this->is_plugin_installed( 'auto-focus-keyword-for-seo' ),
            ],
            'autoLinks' => [
                'url'       => $this->plugin_install_url( 'automatic-internal-links-for-seo' ),
                'installed' => $this->is_plugin_installed( 'automatic-internal-links-for-seo' ),
            ],
            'massPing'  => [
                'url'       => $this->plugin_install_url( 'mass-ping-tool-for-seo' ),
                'installed' => $this->is_plugin_installed( 'mass-ping-tool-for-seo' ),
            ],
            'metaTags'  => [
                'url'       => $this->plugin_install_url( 'meta-tags-for-seo' ),
                'installed' => $this->is_plugin_installed( 'meta-tags-for-seo' ),
            ],
            'appAds'    => [
                'url'       => $this->plugin_install_url( 'app-ads-txt' ),
                'installed' => $this->is_plugin_installed( 'app-ads-txt' ),
            ],
        ];
    }

    /**
     * List of recommendations
     *
     * @return array Array of objects with details for each recommendation
     */
    public function recommendations_list() : array {
        $base_url = plugin_dir_url( __FILE__ );
        $free_plugins = [
            [
                "name" => __( "Schema App Structured Data by Hunch Manifest", "mobilook" ),
                "desc" => __( "Get Schema.org structured data for all pages, posts, categories and profile pages on activation.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/schema-app-structured-data-for-schemaorg/",
                "img"  => "../assets/imgs/1.jpg",
            ],
            [
                "name" => __( "Yasr – Yet Another Stars Rating by Dario Curvino", "mobilook" ),
                "desc" => __( "Boost the way people interact with your website, e-commerce or blog with an easy and intuitive WordPress rating system!", "mobilook" ),
                "link" => "https://wordpress.org/plugins/yet-another-stars-rating/",
                "img"  => "../assets/imgs/2.jpg",
            ],
            [
                "name" => __( "Better Robots.txt optimization – Website indexing, traffic, ranking & SEO Booster + Woocommerce", "mobilook" ),
                "desc" => __( "Better Robots.txt is an all in one SEO robots.txt plugin, it creates a virtual robots.txt including your XML sitemaps (Yoast or else) to boost your website ranking on search engines.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/better-robots-txt/",
                "img"  => "../assets/imgs/3.png",
            ],
            [
                "name" => __( "Smush Image Compression and Optimization By WPMU DEV", "mobilook" ),
                "desc" => __( "Compress and optimize (or optimise) image files, improve performance and boost your SEO rank using Smush WordPress image compression and optimization.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/wp-smushit/",
                "img"  => "../assets/imgs/4.jpg",
            ],
            [
                "name" => __( "404 to 301 By Joel James", "mobilook" ),
                "desc" => __( "Automatically redirect, log and notify all 404 page errors to any page using 301 redirection...", "mobilook" ),
                "link" => "https://wordpress.org/plugins/404-to-301/",
                "img"  => "../assets/imgs/5.png",
            ],
            [
                "name" => __( "Yoast SEO By Team Yoast", "mobilook" ),
                "desc" => __( "Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using the Yoast SEO plugin.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/wordpress-seo/",
                "img"  => "../assets/imgs/6.png",
            ]
        ];
        $pro_plugins = [
            [
                "name" => __( "WP-Optimize by David Anderson, Ruhani Rabin, Team Updraft", "mobilook" ),
                "desc" => __( "WP-Optimize is WordPress's most-installed optimization plugin. With it, you can clean up your database easily and safely, without manual queries.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/wp-optimize/",
                "img"  => "../assets/imgs/p01.png",
            ],
            [
                "name" => __( "WordPress Share Buttons Plugin – AddThis By The AddThis Team", "mobilook" ),
                "desc" => __( "Share buttons from AddThis help you get more traffic from sharing through social networks.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/addthis/",
                "img"  => "../assets/imgs/p02.png",
            ],
            [
                "name" => __( "WP-SpamShield WordPress Anti-Spam Plugin by Red Sand Media Group", "mobilook" ),
                "desc" => __( "WP-SpamShield is a leading WordPress anti-spam plugin that stops spam instantly and improves your site's security.", "mobilook" ),
                "link" => "https://codecanyon.net/item/wpspamshield/21067720",
                "img"  => "../assets/imgs/p03.jpg",
            ],
            [
                "name" => __( "OneSignal – Free Web Push Notifications By OneSignal", "mobilook" ),
                "desc" => __( "Increase engagement and drive more repeat traffic to your WordPress site with desktop push notifications. Now supporting Chrome, Firefox, and Safari.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/onesignal-free-web-push-notifications/",
                "img"  => "../assets/imgs/p04.png",
            ],
            [
                "name" => __( "WPfomify - Social Proof & FOMO Marketing Plugin", "mobilook" ),
                "desc" => __( "WPfomify increases conversion rates on your websites by displaying recent interaction, sales and sign-ups.", "mobilook" ),
                "link" => "https://wpfomify.com/ref/65/",
                "img"  => "../assets/imgs/p05.jpg",
            ],
            [
                "name" => __( "Recart - Abandoned Cart Toolbox", "mobilook" ),
                "desc" => __( "Recart Messenger Marketing and Abandoned Cart Toolbox is a All in one cart recovery marketing tools for ecommerce solution.", "mobilook" ),
                "link" => "https://recart.com/",
                "img"  => "../assets/imgs/p06.jpg",
            ],
            [
                "name" => __( "Wp-Roket - Probably the best caching Plugin for WordPress", "mobilook" ),
                "desc" => __( "Speed up your WordPress website, more traffic, conversions and money with WP Rocket caching plugin.", "mobilook" ),
                "link" => "https://shareasale.com/r.cfm?b=1075949&u=1849247&m=74778&urllink=&afftrack",
                "img"  => "../assets/imgs/p07.png",
            ],
            [
                "name" => __( "Nofollow for external link By CyberNetikz", "mobilook" ),
                "desc" => __( "Automatically insert rel=nofollow and target=_blank to all the external links into your website posts, pages or menus. Support exclude domain.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/nofollow-for-external-link/",
                "img"  => "../assets/imgs/p08.jpg",
            ],
            [
                "name" => __( "Google Reviews Widget By RichPlugins", "mobilook" ),
                "desc" => __( "Google Reviews Widget show Google Places Reviews on your WordPress website to increase user confidence and SEO.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/widget-google-reviews/",
                "img"  => "../assets/imgs/p09.png",
            ],
            [
                "name" => __( "WP Chatbot for facebook Messenger customer chat By HoliThemes", "mobilook" ),
                "desc" => __( "Speed up your WordPress website, more traffic, conversions and money with WP Rocket caching plugin.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/wp-chatbot/",
                "img"  => "../assets/imgs/p10.png",
            ],
            [
                "name" => __( "WP Google My Business Auto Publish By Martin Gibson", "mobilook" ),
                "desc" => __( "WP Google My Business Auto Publish lets you publish posts, custom posts and pages automatically from WordPress to your Google My Business page.", "mobilook" ),
                "link" => "https://wordpress.org/plugins/wp-google-my-business-auto-publish/",
                "img"  => "../assets/imgs/p11.png",
            ],
            [
                "name" => __( "WP Search Console", "mobilook" ),
                "desc" => __( "A new way to boost your marketing content. Writing Web Content that is ranking like those of SEO Experts.", "mobilook" ),
                "link" => "https://www.wpsearchconsole.com/",
                "img"  => "../assets/imgs/p12.png",
            ]
        ];
        foreach ( $free_plugins as $key => $value ) {
            $free_plugins[$key]['img'] = $base_url . $value['img'];
        }
        foreach ( $pro_plugins as $key => $value ) {
            $pro_plugins[$key]['img'] = $base_url . $value['img'];
        }
        return [
            'plugins' => $free_plugins,
        ];
    }

}