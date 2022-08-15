<?php

use Webman\Config;

if (!function_exists('with')) {
    /**
     * Return the given value, optionally passed through the given callback.
     *
     * @template TValue
     *
     * @param TValue $value
     * @param callable|null $callback
     * @return TValue
     */
    function with($value, callable $callback = null)
    {
        return is_null($callback) ? $value : $callback($value);
    }
}

if (!function_exists('module_path')) {
    /**
     * Get the module path of name.
     *
     * @param $module_name
     * @param string $dir
     * @return string
     */
    function module_path($module_name = null, string $dir = ''): string
    {
        return Config::get('plugin.ifui.webman-module.app.paths.module', base_path() . DIRECTORY_SEPARATOR . 'module')
            . ($module_name ? DIRECTORY_SEPARATOR . $module_name : '')
            . ($dir ? DIRECTORY_SEPARATOR . $dir : '');
    }
}