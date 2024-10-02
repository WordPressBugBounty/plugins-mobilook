<?php

namespace Pagup\Mobilook\Core;

class Plugin
{
    /**
     * Generates the URL for a given file path in the plugin directory.
     *
     * @param string $filePath The relative path to the file.
     * @return string The full URL to the file.
     */
    public static function url(string $filePath): string
    {
        return plugins_url('', __DIR__) . "/{$filePath}";
    }

    /**
     * Generates the filesystem path for a given file in the plugin directory.
     *
     * @param string $filePath The relative path to the file.
     * @return string The full filesystem path to the file.
     */
    public static function path(string $filePath): string
    {
        return plugin_dir_path(__DIR__) . "{$filePath}";
    }

    /**
     * Includes a view file and extracts data for use within that view.
     *
     * @param string $file The name of the view file (without extension).
     * @param array $data An associative array of data to be extracted for use in the view.
     * @return void
     */
    public static function view(string $file, array $data = []): void
    {
        extract($data);
        require realpath(plugin_dir_path(__DIR__) . "admin/views/{$file}.view.php");
    }
    
    /**
     * Retrieves the plugin's text domain.
     *
     * @return string The plugin's text domain.
     */
    public static function domain(): string
    {
        return "mobilook";
    }

    /**
     * Verifies the nonce for AJAX requests.
     *
     * @param string $action The nonce action.
     * @param string $name The nonce name.
     * @return bool True if the nonce is valid, false otherwise.
     */
    public static function verify_nonce(string $action, string $name): bool
    {
        if (check_ajax_referer($action, $name, false) === false) {
            wp_send_json_error('Invalid nonce', 401);
            wp_die();
            return false;
        }
        return true;
    }

    /**
     * Checks if the current user has the 'manage_options' capability.
     *
     * @return bool True if the user has the capability, false otherwise.
     */
    public static function current_user_can_manage_options(): bool
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized user', 403);
            wp_die();
            return false;
        }
        return true;
    }
}
