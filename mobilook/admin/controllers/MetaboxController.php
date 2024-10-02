<?php
namespace Pagup\Mobilook\Controllers;

use Pagup\Mobilook\Core\Option;
use Pagup\Mobilook\Traits\SanitizationTrait;
use Pagup\Mobilook\Traits\PostTypeHelperTrait;
use Pagup\Mobilook\Traits\NotificationHelperTrait;

class MetaboxController
{
    use SanitizationTrait, NotificationHelperTrait, PostTypeHelperTrait;

    /**
     * Adds a custom meta box to the specified post types.
     *
     * @return void
     */
    public function add_metabox(): void
    {
        $post_types = $this->get_allowed_post_types();

        foreach ($post_types as $post_type) {
            add_meta_box(
                'mobilook_post_metabox', // id, used as the html id att
                __('MOBILOOK - Instant Mobile Viewer [mobilook.co]'), // meta box title
                [$this, 'metabox'], // callback function, spits out the content
                $post_type, // post type or page. This adds to posts only
                'normal', // context, where on the screen
                'high' // priority, where should this go in the context
            );
        }
    }

    /**
     * Renders the content of the meta box.
     *
     * @param \WP_Post $post The current post object.
     * @return void
     */
    public function metabox(\WP_Post $post): void
    {
        // if (MOBILOOK_PLUGIN_MODE !== "production") {
        //     echo $this->devNotification();
        // }

        $options = Option::all();

        if (!is_array($options)) {
            $options = [];
        }

        $options['post_types'] = $this->unserialize($options, 'post_types');
        $options['disable_devices'] = $this->unserialize($options, 'disable_devices');

        wp_localize_script('mobilook__metabox', 'data', [
            'post_link' => get_permalink($post->ID),
            'post_status' => get_post_status($post->ID),
            'pro' => mobilook_fs()->can_use_premium_code__premium_only(),
            'options' => $options,
            'onboarding' => get_option('mobilook_metabox_tour'),
            'purchase_url' => mobilook_fs()->get_upgrade_url(),
        ]);

        echo '<div id="mobilook__metabox"></div>';
    }
}