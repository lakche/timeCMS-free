<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class System extends Model
{
  use SoftDeletes;

  protected $hidden = ['deleted_at', 'created_at'];

  public static function getValue(){
    $value = System::all();
    $system = [];
    foreach($value as $value){
      $system[$value['key']] = $value['value'];
    }
    return $system;
  }

  public static function saveValue($date = []){
    if(is_array($date)){
      foreach($date as $key => $value){
        $option = System::where('key',$key)->first();
        if(!$option) $option = new System;
        $option->key = $key;
        $option->value = $value;
        $option->save();
      }
    }
  }

}
