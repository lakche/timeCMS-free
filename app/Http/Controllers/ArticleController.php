<?php

namespace App\Http\Controllers;

use App\Model\Article;
use App\Model\Category;
use Redirect;
use Theme;

class ArticleController extends Controller
{
  public function index()
  {
    return Redirect::to('/');
  }

  public function show($id = 0)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $article = Article::find($id);
    if(empty($article)) return Redirect::to('/');

    $type = Category::find($article->category_id);
    if(empty($type)) return Redirect::to('/');

    ++$article->views;
    $article->save();

    return Theme::view('article.show',compact('article','type'));
  }
}
