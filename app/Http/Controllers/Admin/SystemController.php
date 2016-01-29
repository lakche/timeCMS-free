<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\System;
use Request;
use Redirect;
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
        $input = Request::only(['title','keywords','description']);
        System::saveValue($input);
        return Redirect::to('/admin/system');
    }
}
