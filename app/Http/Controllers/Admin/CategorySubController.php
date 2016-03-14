<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;

use App\Http\Controllers\Controller;

use Redirect;
use Theme;

class CategorySubController extends Controller
{
  public function show($id = 0)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $parent = Category::find($id);
    if(!$parent) return Redirect::to(route('admin.category.index'));

    $categories = Category::where('parent_id',$id)->get();
    return Theme::view('admin.categorysub.show',compact('categories','parent'));
  }
}
