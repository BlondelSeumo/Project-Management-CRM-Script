<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @link: https://codeigniter4.github.io/CodeIgniter4/
 */
if (!function_exists('parse_form_attributes')) {

    /**
     * Parse the form attributes
     *
     * Helper function used by some of the form helpers
     *
     * @param string|array $attributes List of attributes
     * @param array        $default    Default values
     *
     * @return string
     */
    function parse_form_attributes($attributes, array $default): string {
        if (is_array($attributes)) {
            foreach ($default as $key => $val) {
                if (isset($attributes[$key])) {
                    $default[$key] = $attributes[$key];
                    unset($attributes[$key]);
                }
            }
            if (!empty($attributes)) {
                $default = array_merge($default, $attributes);
            }
        }

        $att = '';

        foreach ($default as $key => $val) {
            if (!is_bool($val)) {
                if ($key === 'value') {
                    $val = esc($val);
                } elseif ($key === 'name' && !strlen($default['name'])) {
                    continue;
                }
                $att .= $key . '="' . $val . '" ';
            } else if (is_bool($val)) {
                $att .= $key . '="' . $val . '" ';
            } else {
                $att .= $key . ' ';
            }
        }

        return $att;
    }

    //--------------------------------------------------------------------
}