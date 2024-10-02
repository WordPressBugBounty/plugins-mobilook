<?php
namespace Pagup\Mobilook;

use Pagup\Mobilook\Controllers\AssetController;
use Pagup\Mobilook\Controllers\MetaboxController;
use Pagup\Mobilook\Controllers\SettingsController;

class Settings
{
    private $assetController;
    private $settingsController;
    private $metaboxController;

    public function __construct()
    {
        $this->assetController = new AssetController();
        $this->settingsController = new SettingsController();
        $this->metaboxController = new MetaboxController();

        // Add settings page
        add_action('admin_menu', [$this->settingsController, 'add_settings']);

        add_action('wp_ajax_mobilook_options', [$this->settingsController, 'save_options']);
        add_action('wp_ajax_mobilook_onboarding', [$this->settingsController, 'onboarding']);

        // Add metabox to post-types
        add_action('add_meta_boxes', [$this->metaboxController, 'add_metabox']);

        // Add setting link to plugin page
        $plugin_base = MOBILOOK_PLUGIN_BASE;
        add_filter("plugin_action_links_{$plugin_base}", [$this, 'setting_link']);

        // Add styles and scripts
        add_action('admin_enqueue_scripts', [$this->assetController, 'assets']);

        // Add type module to app script
        add_filter('script_loader_tag', [$this->assetController, 'add_module_to_script'], 10, 3);

        add_action('enqueue_block_editor_assets', [$this->assetController, 'listen_block_editor']);
    }

    /**
     * Adds a settings link to the plugin action links.
     *
     * @param array $links Existing array of plugin action links.
     * @return array Modified array of plugin action links with the settings link added.
     */
    public function setting_link($links)
    {
        array_unshift($links, '<a href="admin.php?page=mobilook">Settings</a>');
        return $links;
    }

}

$settings = new Settings;
