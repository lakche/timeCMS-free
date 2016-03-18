<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
  use SoftDeletes;

  protected $hidden = ['deleted_at', 'created_at'];

  protected $fillable = ['title', 'category_id', 'sort', 'views', 'tag', 'is_recommend', 'is_show', 'info', 'url', 'cover', 'thumb', 'text', 'subtitle', 'author', 'source', 'keywords', 'description', 'hash'];

  public function setIsRecommendAttribute($value)
  {
    $this->attributes['is_recommend'] = intval($value);
  }

  public function setIsShowAttribute($value)
  {
    $this->attributes['is_show'] = intval($value);
  }

  public function setTagAttribute($value)
  {
    $this->attributes['tag'] = json_encode(explode(',', strip_tags($value)));
  }

  public function setDescriptionAttribute($value)
  {
    $this->attributes['description'] = $value ? $value : '';
  }

  public function setTextAttribute($value)
  {
    $this->attributes['text'] = $value ? $value : '';
  }

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

  public function getCover()
  {
    if($this->thumb != ''){
      return $this->thumb;
    } else {
      $this->cover;
    }
  }

}
