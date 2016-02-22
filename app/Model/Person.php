<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

define('Male', 0);   //男
define('Female', 1);   //女

class Person extends Model
{
  use SoftDeletes;

  protected $table = 'persons';

  protected $hidden = ['deleted_at', 'created_at'];

  public function scopeSortByDesc($query,$key)
  {
    if($key != 'id') return $query->orderBy($key,'desc')->orderBy('id','desc');
    return $query->orderBy($key,'desc');
  }

  public function scopeSortBy($query,$key)
  {
    return $query->orderBy($key);
  }

  public function getHead()
  {
      if($this->head_thumbnail != ''){
        return $this->head_thumbnail;
      } else {
        $this->head;
      }
  }

}
