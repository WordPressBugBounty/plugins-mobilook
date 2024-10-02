<?php
namespace Pagup\Mobilook\Controllers;

use Pagup\Mobilook\Core\Asset;
use Pagup\Mobilook\Core\Option;
use Pagup\Mobilook\Traits\PluginHelperTrait;
use Pagup\Mobilook\Traits\PostTypeHelperTrait;

class AssetController
{
    use PluginHelperTrait, PostTypeHelperTrait;

    private $isProduction;

    public function __construct()
    {
        $this->isProduction = MOBILOOK_PLUGIN_MODE === 'production';
    }

    /**
     * Enqueue assets based on the current page and post type.
     */
    public function assets(): void
    {

        if ($this->is_settings_page('mobilook')) {
            $this->enqueue_settings_assets();
        }

        $current_screen = get_current_screen();

        $allowed_post_types =  $this->get_allowed_post_types();

        if ($current_screen && $this->is_allowed_post_type($current_screen->post_type, $allowed_post_types)) {
            $this->enqueue_metabox_assets();
        }
    }
    /**
     * Enqueue assets for the Mobilook Settings page.
     */
    protected function enqueue_settings_assets(): void
    {
        

        if ($this->isProduction) {
            Asset::style('mobilook__helpers_styles', 'admin/ui/helpers.css');
            Asset::script('mobilook__helpers', 'admin/ui/helpers.js', [], true);
            Asset::style('mobilook__styles', 'admin/ui/settings.css');
            Asset::script('mobilook__main', 'admin/ui/settings.js', ['mobilook__helpers'], true);
        } else {
            Asset::script_remote('mobilook__main', 'http://localhost:5173/src/main.ts', ['mobilook__client'], true, true);
            Asset::script_remote('mobilook__metabox', 'http://localhost:5173/src/metabox.ts', ['mobilook__client'], true, true);
            Asset::script_remote('mobilook__client', 'http://localhost:5173/@vite/client', [], true, true);
        }
    }

    /**
     * Enqueue assets for the Metabox.
     */
    protected function enqueue_metabox_assets(): void
    {
        if ($this->isProduction) {
            Asset::style('mobilook__helpers_styles', 'admin/ui/helpers.css');
            Asset::script('mobilook__helpers', 'admin/ui/helpers.js', [], true);
            Asset::style('mobilook_metabox_styles', 'admin/ui/metabox.css');
            Asset::script('mobilook__metabox', 'admin/ui/metabox.js', ['mobilook__helpers'], true);
        } else {
            Asset::script_remote('mobilook__metabox', 'http://localhost:5173/src/metabox.ts', ['mobilook__client'], true, true);
            Asset::script_remote('mobilook__client', 'http://localhost:5173/@vite/client', [], true, true);
        }
    }

    /**
     * Modify the script tag to include type="module" for specific handles.
     *
     * @param string $tag The script tag.
     * @param string $handle The script handle.
     * @param string $src The script source URL.
     * @return string Modified script tag.
     */
    public function add_module_to_script(string $tag, string $handle, string $src): string
    {
        $handles = ['mobilook__main', 'mobilook__metabox', 'mobilook__select', 'mobilook__client'];

        if (in_array($handle, $handles)) {
            $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        }

        return $tag;
    }

    public function listen_block_editor(): void
    {
        wp_enqueue_script(
            'mobilook__listen',
            plugins_url('assets/block_editor.js', __DIR__),
            array('wp-edit-post'),
            null,
            true
        );
    }

}
