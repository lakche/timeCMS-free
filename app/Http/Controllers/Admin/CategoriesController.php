<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;

use App\Libs\Plupload;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

use Request;
use Redirect;
use Image;
use Hash;
use Theme;

class CategoriesController extends Controller
{
  public function getIndex()
  {
    $categories = Category::where('parent_id',0)->get();
    return Theme::view('admin.categories.index',compact('categories'));
  }

  public function getSubs($id = 0)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $parent = Category::find($id);
    if(!$parent) return Redirect::to('/admin/categories');

    $categories = Category::where('parent_id',$id)->get();
    return Theme::view('admin.categories.index',compact('categories','parent'));
  }

  public function getAdd($parent_id = 0)
  {
    if(!preg_match("/^[0-9]\d*$/",$parent_id)) return Redirect::to('/');

    $category = new Category;
    $category->is_nav_show = 1;
    $category->id = 0;
    $category->sort = 0;
    $category->parent_id = $parent_id;
    return Theme::view('admin.categories.show',compact('category','parent_id'));
  }

  public function getEdit($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $category = Category::find($id);
    if(!$category) return Redirect::to('/admin/categories');

    return Theme::view('admin.categories.show',compact('category'));
  }

  public function postSave(CategoryRequest $request, $id = 0)
  {
    if(!preg_match("/^[0-9]\d*$/",$id)) return Redirect::to('/');

    if ($id > 0) {
      $category = Category::find($id);
    } else {
      $category = new Category;
    }
    if (!$category) $category = new Category;
    $parent_id = intval($request->get('parent_id'));

    $category->title = $request->get('title');
    $category->info = $request->get('info');
    $category->sort = $request->get('sort');
    $category->parent_id = $parent_id;
    $category->cover = $request->get('cover');
    $category->thumb = $request->get('thumb');
    $category->is_nav_show = $request->get('is_nav_show') ? 1 : 0;
    $category->keywords = $request->get('keywords');
    $category->description = $request->get('description');
    $category->templet_all = $request->get('templet_all');
    $category->templet_nosub = $request->get('templet_nosub');
    $category->templet_article = $request->get('templet_article');
    $category->save();

    $message = '栏目设置成功，请选择操作！';
    $url = [];
    $url['返回根栏目'] = ['url'=>url('admin/categories')];
    if($parent_id > 0) $url['返回子栏目'] = ['url'=>url('admin/categories/subs',$category->parent_id)];
    $url['继续添加'] = ['url'=>url('admin/categories/add')];
    $url['继续编辑'] = ['url'=>url('admin/categories/edit',$category->id)];
    $url['查看栏目'] = ['url'=>url('category',$category->id),'target'=>'_blank'];
    return Theme::view('admin.message.show',compact(['message','url']));
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/categories/covers/';
    if (Request::hasFile('file')) {
      $plupload = new Plupload();
      $fileName = date("_YmdHis") . rand(1000, 9999) . '.';
      $info = $plupload->process('file', function ($file) use (&$fileName,&$filePath) {
        $fileName = $fileName . $file->getClientOriginalExtension();
        $file->move(public_path($filePath), $fileName);
      });
    }

    if (file_exists(public_path($filePath) . $fileName)) {
      $img = Image::make(public_path($filePath) . $fileName);
      $imgMime = explode('/', $img->mime());
      if ($imgMime[0] != 'image') {
        $info['result'] = false;
        return $info;
      }
    } else {
      $info['result'] = false;
      return $info;
    }

    $img->resize(300, null, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    });
    $img->save(public_path($filePath) . 'thumb' . $fileName);

    $info['result'] = true;
    $info['cover'] = $filePath . $fileName;
    $info['thumb'] = $filePath . 'thumb' . $fileName;
    return $info;
  }

  public function postDelete($id)
  {
    Category::destroy($id);
    return Response::json(['error' => 0, 'message' => '删除成功！']);
  }

}
