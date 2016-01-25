<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use Theme;

class CategoriesController extends Controller
{
  public function getIndex()
  {
    $categories = Category::all();
    return Theme::view('admin.categories.index', compact('categories'));
  }
}
