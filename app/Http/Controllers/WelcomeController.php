<?php namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\Person;
use App\Model\Project;
use Theme;

class WelcomeController extends Controller
{
  public function index()
  {
    $types = Category::where('parent_id',0)->isNavShow()->sortByDesc('sort')->get();
    $persons = Person::sortByDesc('point')->take(4)->get();
    $projects = Project::sortByDesc('id')->take(4)->get();
    return Theme::view('welcome.index',compact(['types','persons','projects']));
  }
}
