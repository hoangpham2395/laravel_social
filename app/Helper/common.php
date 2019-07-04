<?php

if (! function_exists('getConstant')) {
    /**
     * @param $key
     * @param null $default
     * @return \Illuminate\Config\Repository|mixed
     */
    function getConstant($key, $default = null)
    {
        return config('constant.' . $key, $default);
    }
}

if (! function_exists('getMessage')) {
    /**
     * @param $key
     * @param array $params
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    function getMessage($key, $params = [])
    {
        return trans('messages.' . $key, $params);
    }
}

if (! function_exists('getConfig')) {
    /**
     * @param $key
     * @param null $default
     * @return \Illuminate\Config\Repository|mixed
     */
    function getConfig($key, $default = null)
    {
        return config('config.' . $key, $default);
    }
}