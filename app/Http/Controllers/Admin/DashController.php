<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Theme;

class DashController extends Controller
{
    public function index()
    {
        return Theme::view('admin.dash.index');
    }
}
