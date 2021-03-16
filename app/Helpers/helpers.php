<?php

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if ( ! function_exists('public_path'))
{
    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}

if ( ! function_exists('string_value_to_int'))
{
    function string_value_to_int($value)
    {
        $tmp = [];

        if(is_array($value)) {
            foreach ($value as $key => $value) {
                $tmp[$key] = string_value_to_int($value);
            }
        } else {
            if(is_numeric($value)) {
                $tmp = $value + 0;
            } else {
                $tmp = $value;
            }
        }

        return $tmp;
    }
}