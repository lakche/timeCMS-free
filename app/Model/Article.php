<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
  use SoftDeletes;

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

  public function category()
  {
    return $this->belongsTo('App\Model\Category');
  }

  public function setCover($project = null)
  {
    if($this->is_json($project)){
      if($project->gallery_id > 0){
        if($project->gallery->thumb != ''){
          return $project->gallery->thumb;
        } else {
          return $project->gallery->pic;
        }
      } else {
        return 'jingzhou/images/no-cover.jpg';
      }
    }
    return 'jingzhou/images/no-cover.jpg';
  }

  private function is_json($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
  }
}
