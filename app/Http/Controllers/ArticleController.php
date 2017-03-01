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

    $article = Article::where('id',$id)->where('is_show','>',0)->first();
    if(empty($article)) return Redirect::to('/');

    $type = Category::find($article->category_id);
    if(empty($type)) return Redirect::to('/');

    ++$article->views;
    $article->save();

    $keywords = $article->keywords;
    $description = $article->description;

    if($article->url != '') return Redirect::to($article->url);

    $templet = 'show';
    if($type->templet_article != '') $templet = $type->templet_article;
    return Theme::view('article.'.$templet,compact('article','type','keywords','description'));
  }
}
