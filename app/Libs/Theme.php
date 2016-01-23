<?php
/**
 * Created by Joy.
 * User: Joy
 */
namespace App\Libs;

class Theme
{
    public static function view($view, $data = array())
    {
        $theme = 'time';
        $data['theme'] = $theme;
        return view($theme . '/' . $view, $data);
    }
}