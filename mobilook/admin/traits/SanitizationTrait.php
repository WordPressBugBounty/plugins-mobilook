<?php
namespace Pagup\Mobilook\Traits;

use Pagup\Mobilook\Core\Option;
use Pagup\Mobilook\Core\Request;

trait SanitizationTrait
{
    /**
     * Sanitizes option values based on predefined rules and a list of safe values.
     *
     * @param array $options Array of options to be sanitized.
     * @param array $safe Array of values considered safe for certain options.
     * @return array Sanitized array of options.
     */
    public function sanitize_options(array $options, array $safe): array
    {
        foreach ($options as $key => $value) {
            if ($key === 'post_types' || $key === 'disable_devices') {
                $options[$key] = maybe_serialize(Request::array($value));
            } else {
                if (in_array($value, $safe)) {
                    $options[$key] = sanitize_text_field($value);
                } else {
                    $options[$key] = "";
                }
            }
        }

        return $options;
    }

    /**
     * Unserializes an option value if it exists and is not empty.
     *
     * @param array $options Array of options.
     * @param string $key The key of the option to unserialize.
     * @return array Unserialized value or an empty array.
     */
    public function unserialize($options, $key)
    {
        if (isset($options[$key]) && !empty($options[$key])) {
            return maybe_unserialize($options[$key]);
        } else {
            return [];
        }
    }
}
