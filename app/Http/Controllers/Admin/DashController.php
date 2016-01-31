<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Article;
use App\User;
use Theme;

class DashController extends Controller
{
    public function index()
    {
        $article_num = Article::count();
        $articles = Article::sortByDesc('id')->take(5)->get();
        $user_num = User::count();
        $users = User::sortByDesc('id')->take(5)->get();
        return Theme::view('admin.dash.index',compact(['article_num','user_num','articles','users']));
    }
}
