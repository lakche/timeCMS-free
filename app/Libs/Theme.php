<?php
/**
 * Created by Joy.
 * User: Joy
 */
namespace App\Libs;

use App\Model\System;

class Theme
{
    public static function view($view, $data = array())
    {
        $system = System::getValue();
        if(!isset($system['theme'])) $system['theme'] = '';
        $theme = $system['theme'] == '' ? 'time' : $system['theme'];
        $data['theme'] = $theme;
        $data['system'] = $system;
        return view($theme . '/' . $view, $data);
    }
}