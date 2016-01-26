<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\Article;
use Redirect;
use Theme;

class CategoryController extends Controller
{
  public function index()
  {
    $types = Category::where('parent_id',0)->isNavShow()->sortByDesc('sort')->get();
    return Theme::view('category.index',compact('types'));
  }

  public function show($id = 0)
  {
    if($id != intval($id)) return Redirect::to('/');

    $type = Category::find($id);
    if(empty($type)) return Redirect::to('/');

    $articles = Article::where('category_id',$id)->sortByDesc('id')->paginate(20);
    return Theme::view('category.show',compact('type','articles'));
  }

}
