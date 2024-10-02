<?php
namespace Pagup\Mobilook\Controllers;

use Pagup\Mobilook\Core\Option;
use Pagup\Mobilook\Core\Plugin;
use Pagup\Mobilook\Traits\SanitizationTrait;
use Pagup\Mobilook\Traits\PostTypeHelperTrait;
use Pagup\Mobilook\Traits\PluginHelperTrait;
use Pagup\Mobilook\Traits\NotificationHelperTrait;

class SettingsController
{
    use SanitizationTrait, PostTypeHelperTrait, PluginHelperTrait, NotificationHelperTrait;

    /**
     * Adds the settings menu page.
     */
    public function add_settings(): void
    {
        add_menu_page(
            __('Mobilook Settings', 'mobilook'),
            __('Mobilook', 'mobilook'),
            'manage_options',
            'mobilook',
            [$this, 'page'],
            'data:image/svg+xml;base64,CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmlld0JveD0iMCAwIDI0IDI0Ij4KICAgIDxwYXRoIGQ9Ik0xNiAxSDhDNi4zNCAxIDUgMi4zNCA1IDR2MTZjMCAxLjY2IDEuMzQgMyAzIDNoOGMxLjY2IDAgMy0xLjM0IDMtM1Y0YzAtMS42Ni0xLjM0LTMtMy0zem0xIDE3SDdWNGgxMHYxNHptLTMgM2gtNHYtMWg0djF6IiBmaWxsPSJjdXJyZW50Q29sb3IiPjwvcGF0aD4KPC9zdmc+Cg=='
        );
    }

    /**
     * Renders the settings page.
     */
    public function page(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('Sorry, you are not allowed to access this page.', 'mobilook'));
        }

        if (!current_user_can('unfiltered_html')) {
            wp_die(__('Sorry, you are not allowed to edit this page. Ask your administrator for assistance.', 'mobilook'));
        }

        if (MOBILOOK_PLUGIN_MODE !== 'production') {
            echo $this->devNotification();
        }

        $post_types = $this->cpts(['attachment']);
        $options = Option::all();

        $options['post_types'] = $this->get_allowed_post_types();
        $options['disable_devices'] = $this->unserialize($options, 'disable_devices');

        wp_localize_script('mobilook__main', 'data', [
            'post_types' => $post_types,
            'options' => $options,
            'home_url' => home_url(),
            'onboarding' => get_option('mobilook_tour'),
            'assets' => plugins_url('assets', dirname(__FILE__)),
            'pro' => mobilook_fs()->can_use_premium_code__premium_only(),
            'plugins' => $this->installable_plugins(),
            'language' => get_locale(),
            'nonce' => wp_create_nonce('mobilook__nonce'),
            'purchase_url' => mobilook_fs()->get_upgrade_url(),
            'recommendations' => $this->recommendations_list(),
        ]);

        echo '<div id="mobilook__app"></div>';
    }

    /**
     * Handles saving options via AJAX.
     */
    public function save_options(): void
    {
        if (!Plugin::verify_nonce('mobilook__nonce', 'nonce') || !Plugin::current_user_can_manage_options()) {
            return;
        }

        $safe = ["remove_settings", "allow"];
        $options = $this->sanitize_options($_POST['options'], $safe);

        $result = update_option('mobilook', $options);

        if ($result) {
            wp_send_json_success([
                'options' => $options,
                'message' => 'Saved Successfully',
            ]);
        } else {
            wp_send_json_error([
                'options' => $options,
                'message' => 'Error Saving Options'
            ]);
        }
    }

    /**
     * Handles the onboarding process via AJAX.
     */
    public function onboarding(): void
    {
        if (!Plugin::verify_nonce('mobilook__nonce', 'nonce') || !Plugin::current_user_can_manage_options()) {
            return;
        }

        $closed = isset($_POST['closed']) ? filter_var($_POST['closed'], FILTER_VALIDATE_BOOLEAN) : false;
        $result = update_option('mobilook_tour', $closed);

        if ($result) {
            wp_send_json_success([
                'mobilook_tour' => get_option('mobilook_tour'),
                'message' => 'Tour closed value saved successfully',
            ]);
        } else {
            wp_send_json_error([
                'mobilook_tour' => get_option('mobilook_tour'),
                'message' => 'Error Saving Tour closed value'
            ]);
        }
    }
}