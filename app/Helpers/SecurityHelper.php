<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Safely decodes stored data.
     * Checks for JSON first. If legacy PHP serialized data is encountered,
     * it disables object instantiation (allowed_classes => false) to prevent
     * PHP Object Injection / Remote Code Execution (RCE).
     *
     * @param mixed $data
     * @return array
     */
    public static function safeDecode($data)
    {
        if (empty($data)) {
            return [];
        }

        if (is_array($data)) {
            return $data;
        }

        // Try JSON decode first
        $json = json_decode($data, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return $json;
        }

        // Fallback for legacy PHP serialize data with object instantiation disabled
        if (is_string($data)) {
            // PHP 7+ supports ['allowed_classes' => false]
            if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
                $unserialized = @unserialize($data, ['allowed_classes' => false]);
            } else {
                // If on legacy PHP 5.6, strip any object tokens before unserializing
                if (preg_match('/(^|;)O:\d+:"/', $data)) {
                    return [];
                }
                $unserialized = @unserialize($data);
            }

            if ($unserialized !== false || $data === 'b:0;') {
                return is_array($unserialized) ? $unserialized : [];
            }
        }

        return [];
    }

    /**
     * Safely encodes data to JSON format.
     *
     * @param mixed $data
     * @return string
     */
    public static function safeEncode($data)
    {
        return json_encode($data);
    }
}
