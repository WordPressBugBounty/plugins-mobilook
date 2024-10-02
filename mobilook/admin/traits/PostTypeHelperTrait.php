<?php

namespace Pagup\Mobilook\Traits;

use Pagup\Mobilook\Core\Option;
trait PostTypeHelperTrait
{
    /**
     * Retrieves allowed post types from plugin options and unserializes them.
     *
     * @return array An array of allowed post types.
     */
    protected function get_allowed_post_types() : array {
        $post_types = ['post', 'page'];
        if ( Option::check( 'post_types' ) ) {
            $post_types = maybe_unserialize( Option::get( 'post_types' ) );
        }
        return $post_types;
    }

    /**
     * Retrieves a string of allowed post types, formatted for use in SQL queries.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     * @return string A string of post types formatted for an SQL IN clause.
     */
    public function post_types() : string {
        global $wpdb;
        $allowed_post_types = ( Option::check( 'post_types' ) ? Option::get( 'post_types' ) : [] );
        if ( in_array( 'product', $allowed_post_types ) ) {
            unset($allowed_post_types[array_search( 'product', $allowed_post_types )]);
        }
        // Create a string of placeholders and prepare the whole list of post types
        $placeholders = implode( ', ', array_fill( 0, count( $allowed_post_types ), '%s' ) );
        $post_types = $wpdb->prepare( $placeholders, $allowed_post_types );
        return $post_types;
    }

    /**
     * Retrieves custom post types (CPTs), optionally excluding specified types.
     *
     * @param array $excludes An array of post type names to exclude from the results.
     * @return array An associative array of custom post types, excluding specified types.
     */
    public function cpts( array $excludes = [] ) : array {
        $post_types = get_post_types( [
            'public' => true,
        ], 'objects' );
        foreach ( $excludes as $exclude ) {
            unset($post_types[$exclude]);
        }
        $types = [];
        foreach ( $post_types as $post_type ) {
            $label = get_post_type_labels( $post_type );
            $types[$label->name] = $post_type->name;
        }
        return $types;
    }

    /**
     * Get post type label from post type object
     *
     * @param int $post_id
     * @return string
     */
    public function post_type( $post_id ) {
        $post_type_obj = get_post_type_object( get_post_type( $post_id ) );
        return $post_type_obj->labels->singular_name;
    }

    /**
     * Check if the post type is allowed.
     *
     * @param string $post_type
     * @param array $allowed_post_types
     * @return bool
     */
    protected function is_allowed_post_type( string $post_type, array $allowed_post_types ) : bool {
        return in_array( $post_type, $allowed_post_types );
    }

}