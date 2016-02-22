<?php

namespace App\Http\Controllers;

use App\Model\Person;
use Redirect;
use Theme;

class PersonController extends Controller
{
  public function index()
  {
    $persons = Person::sortByDesc('point')->get();
    return Theme::view('person.index',compact(['persons']));
  }

  public function show($id = 0)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $person = Person::find($id);
    if(empty($person)) return Redirect::to('/');

    $keywords = $person->keywords;
    $description = $person->description;

    if($person->url != '') return Redirect::to($person->url);

    return Theme::view('person.show',compact('person','keywords','description'));
  }
}
