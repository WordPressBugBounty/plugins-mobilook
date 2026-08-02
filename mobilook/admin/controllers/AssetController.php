<?php
namespace Pagup\Mobilook\Controllers;

use Pagup\Mobilook\Core\Asset;
use Pagup\Mobilook\Core\Option;
use Pagup\Mobilook\Traits\PluginHelperTrait;
use Pagup\Mobilook\Traits\PostTypeHelperTrait;

class AssetController
{
    use PluginHelperTrait, PostTypeHelperTrait;

    private const MODULE_SCRIPT_HANDLES = [
        'mobilook__helpers',
        'mobilook__main',
        'mobilook__metabox',
        'mobilook__select',
        'mobilook__client',
    ];

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

        if (
            $current_screen &&
            $current_screen->base === 'post' &&
            $this->is_allowed_post_type($current_screen->post_type, $allowed_post_types)
        ) {
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
            // Register Vite client first, then scripts that depend on it
            Asset::script_remote('mobilook__client', 'http://localhost:5173/@vite/client', [], true, true);
            Asset::script_remote('mobilook__main', 'http://localhost:5173/src/main.ts', ['mobilook__client'], true, true);
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
            // Register Vite client first, then scripts that depend on it
            Asset::script_remote('mobilook__client', 'http://localhost:5173/@vite/client', [], true, true);
            Asset::script_remote('mobilook__metabox', 'http://localhost:5173/src/metabox.ts', ['mobilook__client'], true, true);
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
        if (!in_array($handle, self::MODULE_SCRIPT_HANDLES, true)) {
            return $tag;
        }

        // WordPress can prepend translations and inline "before" blocks to the
        // external script. Only the opening tag carrying src belongs to this
        // handle; inline scripts must retain their original execution mode.
        return (string) preg_replace_callback(
            '/<script\b[^>]*\ssrc=[^>]*>/i',
            static function (array $match): string {
                $open = $match[0];

                if (preg_match('/\stype=(["\'])[^"\']*\1/i', $open)) {
                    return (string) preg_replace(
                        '/\stype=(["\'])[^"\']*\1/i',
                        ' type="module"',
                        $open,
                        1
                    );
                }

                return (string) preg_replace('/<script\b/i', '<script type="module"', $open, 1);
            },
            $tag,
            1
        );
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
