<?php
namespace Pagup\Mobilook\Core;

class Option
{
    /**
     * Retrieves all options.
     *
     * @return array The option value or false if not found.
     */
    public static function all(): array
    {
        $option = get_option('mobilook');
        return is_array($option) ? $option : [];
    }

    /**
     * Retrieves a specific option value by key.
     *
     * @param string $key The key for the option value.
     * @return mixed The option value.
     */
    public static function get(string $key): mixed
    {
        $option = static::all();
        return $option[$key];
    }

    /**
     * Checks if a specific option key is set and not empty.
     *
     * @param string $key The key for the option.
     * @return bool True if the option key is set and not empty, false otherwise.
     */
    public static function check(string $key): bool
    {
        $option = static::all();
        return isset($option[$key]) && !empty($option[$key]);
    }

    /**
     * Validates if a specific option key matches a given value.
     *
     * @param string $option The key for the option.
     * @param mixed $val The value to compare.
     * @return bool True if the option key matches the value, false otherwise.
     */
    public static function valid(string $option, $val): bool
    {
        return static::check($option) && static::get($option) == $val;
    }

    /**
     * Retrieves post meta value by key for the current queried object.
     *
     * @param string $key The key for the post meta value.
     * @return mixed The post meta value.
     */
    public static function post_meta(string $key): mixed
    {
        $post_id = get_queried_object_id();
        return get_post_meta($post_id, $key, true);
    }
}
