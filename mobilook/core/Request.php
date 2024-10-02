<?php
namespace Pagup\Mobilook\Core;

class Request
{
    /**
     * Sanitizes a value if it exists, is not empty, and is in the safe list.
     *
     * @param mixed $value The value to be sanitized.
     * @param array $safe An array of safe values.
     * @return string The sanitized value or an empty string if the value is not safe.
     */
    public static function safe($value, array $safe): string
    {
        if (isset($value) && !empty($value) && in_array($value, $safe)) {
            return sanitize_text_field($value);
        }
        
        return "";
    }

    /**
     * Checks if a POST variable exists and is not empty.
     *
     * @param string $value The key to check in the POST array.
     * @return bool True if the POST variable exists and is not empty, false otherwise.
     */
    public static function check(string $value): bool
    {
        return isset($_POST[$value]) && !empty($_POST[$value]);
    }

    /**
     * Sanitizes and retrieves a POST variable as text.
     *
     * @param string $value The key to retrieve from the POST array.
     * @return string The sanitized text or an empty string if the POST variable does not exist or is empty.
     */
    public static function text(string $value): string
    {
        return static::check($value) ? sanitize_text_field($_POST[$value]) : '';
    }

    /**
     * Sanitizes a numeric value.
     *
     * @param mixed $value The value to be sanitized.
     * @return string|null The sanitized value or null if the value is not numeric.
     */
    public static function numeric($value): ?string
    {
        if (isset($value) && is_numeric($value)) {
            return sanitize_text_field($value);
        } else {
            return null;
        }
    }

    /**
     * Sanitizes an array, including nested arrays.
     *
     * @param array $array The array to be sanitized.
     * @return array The sanitized array.
     */
    public static function array(array $array): array
    {
        foreach ((array) $array as $k => $v) {
            if (is_array($v)) {
                // Recursive call for nested arrays
                $array[$k] = self::array($v);
            } else {
                $array[$k] = sanitize_key($v);
            }
        }
     
        return $array;                                                       
    }    
}
