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

if ( ! function_exists('public_app_path'))
{
    function public_app_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}

if ( ! function_exists('get_last_id'))
{
    function get_last_id($array, $key)
    {
        if(count($array) > 0) {
            return $array[count($array) - 1]->{$key};
        } else {    
            return null;
        }
    }
}

if (!function_exists('get_sql')) {
    function get_sql($model)
    {
        $replace = function ($sql, $bindings) {
            $needle = '?';
            foreach ($bindings as $replace) {
                $pos = strpos($sql, $needle);
                if ($pos !== false) {
                    if (gettype($replace) === "string") {
                        $replace = ' "' . addslashes($replace) . '" ';
                    }
                    $sql = substr_replace($sql, $replace, $pos, strlen($needle));
                }
            }
            return $sql;
        };

        $sql = $replace($model->toSql(), $model->getBindings());
        return $sql;
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