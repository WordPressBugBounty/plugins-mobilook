<?php
namespace Pagup\Mobilook\Core;

use Pagup\Mobilook\Core\Plugin;

class Asset 
{
    /**
     * Registers and enqueues a local CSS stylesheet.
     *
     * @param string $name The handle name for the stylesheet.
     * @param string $file The relative path to the stylesheet file.
     * @return void
     */
    public static function style(string $name, string $file): void
    {
        wp_register_style($name, Plugin::url($file), array(), filemtime(Plugin::path($file)));
        wp_enqueue_style($name);
    }

    /**
     * Registers and enqueues a remote CSS stylesheet.
     *
     * @param string $name The handle name for the stylesheet.
     * @param string $file The URL to the remote stylesheet file.
     * @return void
     */
    public static function style_remote(string $name, string $file): void
    {
        wp_register_style($name, "{$file}");
        wp_enqueue_style($name);
    }

    /**
     * Registers and enqueues a local JavaScript file.
     *
     * @param string $name The handle name for the script.
     * @param string $file The relative path to the script file.
     * @param array $array An array of dependencies for the script.
     * @param bool $footer Whether to load the script in the footer. Default false.
     * @return void
     */
    public static function script(string $name, string $file, array $array = [], bool $footer = false): void
    {
        wp_register_script($name, Plugin::url($file), $array, filemtime(Plugin::path($file)), $footer);
        wp_enqueue_script($name);
    }

    /**
     * Registers and enqueues a remote JavaScript file.
     *
     * @param string $name The handle name for the script.
     * @param string $file The URL to the remote script file.
     * @param array $array An array of dependencies for the script.
     * @param bool|string $ver The script version number. Default false.
     * @param bool $footer Whether to load the script in the footer. Default false.
     * @return void
     */
    public static function script_remote(string $name, string $file, array $array = [], $ver = false, bool $footer = false): void
    {
        wp_register_script($name, $file, $array, $ver, $footer);
        wp_enqueue_script($name);
    }
}
