<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\System;
use Request;
use Cache;
use Theme;

class SystemController extends Controller
{
    public function getIndex()
    {
        $system = System::getValue();
        return Theme::view('admin.system.index',compact('system'));
    }

    public function postSave()
    {
        $input = Request::only(['title','keywords','description','copyright','record','is_open','qq','wechat','weibo','theme','subtitle']);
        $input['is_open'] = $input['is_open'] ? 1 : 0;

        System::saveValue($input);

        Cache::forget('system_info');

        $system = System::getValue();
        $message = '参数设置成功！';
        return Theme::view('admin.system.index',compact('system','message'));
    }
}
