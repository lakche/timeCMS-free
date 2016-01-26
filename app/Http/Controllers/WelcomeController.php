<?php namespace App\Http\Controllers;

use App\Model\Category;
use Theme;

class WelcomeController extends Controller
{
  public function index()
  {
    $types = Category::where('parent_id',0)->isNavShow()->sortByDesc('sort')->get();
    return Theme::view('welcome.index',compact('types'));
  }
}
