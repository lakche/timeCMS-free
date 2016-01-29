<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Libs\Plupload;
use Response;
use Requests;
use Validator;
use Redirect;
use Image;
use Request;
use Theme;

class CategoriesController extends Controller
{
  public function getIndex()
  {
    $types = Category::where('parent_id',0)->get();
    return Theme::view('admin.categories.index',compact('types'));
  }

  public function getSubs($id = 0)
  {
    $parent = Category::find($id);
    if(!$parent) return Redirect::to('/admin/categories');
    $types = Category::where('parent_id',$id)->get();
    return Theme::view('admin.categories.index',compact('types','parent'));
  }

  public function getAdd($parent_id = 0)
  {
    $parent_id = intval($parent_id);
    $type = new Category;
    $type->is_nav_show = 1;
    $type->id = 0;
    $type->sort = 0;
    $type->parent_id = $parent_id;
    $categoryTree = $this->categoryTree();
    return Theme::view('admin.categories.show',compact('type','categoryTree','parent_id'));
  }

  public function getEdit($id)
  {
    $id = intval($id);
    $type = Category::find($id);

    if(!$type) return Redirect::to('/admin/categories');

    $categoryTree = $this->categoryTree();
    return Theme::view('admin.categories.show',compact('type','categoryTree'));
  }

  public function postSave($id = 0)
  {
    $id = intval($id);
    $rules = [
        'title' => 'required',
        'sort' => 'integer',
    ];
    $messages = [
        'title.required' => '栏目标题不能为空',
        'sort.integer' => '栏目排序只能为整数',
    ];
    $input = Request::only(['title','info','sort','cover','thumb','is_nav_show','parent_id','keywords','description','templet_all','templet_nosub','templet_article']);

    $validator = Validator::make($input, $rules, $messages);
    if ($validator->fails()) {
      return Redirect::back()->withInput()->withErrors($validator->errors());
    } else {
      if ($id) {
        $type = Category::find($id);
      } else {
        $type = new Category;
      }
      if(!$type) $type = new Category;
      $parent_id = intval($input['parent_id']);

      $type->title = strip_tags($input['title']);
      $type->info = strip_tags($input['info']);
      $type->sort = $input['sort'];
      $type->parent_id = $parent_id;
      $type->cover = strip_tags($input['cover']);
      $type->thumb = strip_tags($input['thumb']);
      $type->is_nav_show = intval($input['is_nav_show']) ? 1 : 0;
      $type->keywords = strip_tags($input['keywords']);
      $type->description = strip_tags($input['description']);
      $type->templet_all = strip_tags($input['templet_all']);
      $type->templet_nosub = strip_tags($input['templet_nosub']);
      $type->templet_article = strip_tags($input['templet_article']);
      $type->save();
    }

    $message = '栏目设置成功，请选择操作！';
    $url = [];
    $url['返回根栏目'] = ['url'=>url('admin/categories')];
    if($parent_id > 0) $url['返回子栏目'] = ['url'=>url('admin/categories/subs',$type->parent_id)];
    $url['继续编辑'] = ['url'=>url('admin/categories/edit',$type->id)];
    $url['查看栏目'] = ['url'=>url('category',$type->id)];
    return Theme::view('admin.message.show',compact(['message','url']));
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/categories/covers/';
    if (Request::hasFile('file')) {
      $plupload = new Plupload();
      $fileName = date("_YmdHis") . rand(1000, 9999) . '.';
      $response = $plupload->process('file', function ($file) use (&$fileName,&$filePath) {
        $fileName = $fileName . $file->getClientOriginalExtension();
        $file->move(public_path($filePath), $fileName);
      });
    }

    if (file_exists(public_path($filePath) . $fileName)) {
      $img = Image::make(public_path($filePath) . $fileName);
      $imgMime = explode('/', $img->mime());
      if ($imgMime[0] != 'image') {
        $response['result'] = false;
        return Response::json($response);
      }
    } else {
      $response['result'] = false;
      return Response::json($response);
    }

    $img->resize(300, null, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    });
    $img->save(public_path($filePath) . 'thumb' . $fileName);

    $response['result'] = true;
    $response['cover'] = $filePath . $fileName;
    $response['thumb'] = $filePath . 'thumb' . $fileName;
    return Response::json($response);
  }

  public function postDelete($id)
  {
    $id = intval($id);
    $type = Category::find($id);

    if(!$type) return Redirect::to('/admin/categories');

    $type->delete();
    return Response::json(['error' => 0, 'message' => '删除成功！']);
  }

  public function categoryTree($id = 0,$step = 0){
    $categories = Category::where('parent_id',$id)->get();
    if($step == 0){
      $tree = '<option value="0">根分类</option>';
      $prefix = '';
    } else {
      $tree = '';
      $prefix = '';
      for($i=0;$i<$step;$i++){
        $prefix .= '　';
      }
      $prefix .= '┖';
    }
    foreach($categories as $category){
      $tree .= "<option value='".$category->id."'>".$prefix.$category->title."</option>";
      $subs = Category::where('parent_id',$category->id)->get();
      if($subs->count() > 0){
        $tree .= $this->categoryTree($category->id,$step+1);
      }
    }
    return $tree;
  }
}
