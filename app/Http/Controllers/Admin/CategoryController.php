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
use Cache;
use Theme;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::where('parent_id',0)->get();
    return Theme::view('admin.category.index',compact('categories'));
  }

  public function create()
  {
    $parent_id = intval(Request::get('parent_id'));
    $category = new Category;
    $category->is_nav_show = 1;
    $category->id = 0;
    $category->parent_id = $parent_id;
    $category->sort = 0;
    return Theme::view('admin.category.create',compact('category'));
  }

  public function edit($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $category = Category::find($id);
    if(!$category) return Redirect::to(route('admin.category.index'));

    return Theme::view('admin.category.edit',compact('category'));
  }

  public function store(CategoryRequest $request)
  {
    $category = Category::create([
        'title' => $request->get('title'),
        'info' => $request->get('info'),
        'sort' => $request->get('sort'),
        'parent_id' => $request->get('parent_id'),
        'cover' => $request->get('cover'),
        'thumb' => $request->get('thumb'),
        'is_nav_show' => $request->get('is_nav_show'),
        'keywords' => $request->get('keywords'),
        'description' => $request->get('description'),
        'templet_all' => $request->get('templet_all'),
        'templet_nosub' => $request->get('templet_nosub'),
        'templet_article' => $request->get('templet_article'),
    ]);

    if ($category) {
      $message = '栏目添加成功，请选择操作！';
      $url = [];
      $url['返回根栏目'] = ['url'=>route('admin.category.index')];
      if($category->parent_id > 0) $url['返回子栏目'] = ['url'=>route('admin.category.sub.show',$category->parent_id)];
      $url['继续添加'] = ['url'=>route('admin.category.create')];
      $url['继续编辑'] = ['url'=>route('admin.category.edit',$category->id)];
      $url['查看栏目'] = ['url'=>route('category.show',$category->id),'target'=>'_blank'];
      return Theme::view('admin.message.show',compact('message','url'));
    } else {
      return back()->withErrors(['title' => '添加失败']);
    }
  }

  public function update(CategoryRequest $request, $id)
  {
    $category = Category::findOrFail($id);
    $category->update([
        'title' => $request->get('title'),
        'info' => $request->get('info'),
        'sort' => $request->get('sort'),
        'parent_id' => $request->get('parent_id'),
        'cover' => $request->get('cover'),
        'thumb' => $request->get('thumb'),
        'is_nav_show' => $request->get('is_nav_show'),
        'keywords' => $request->get('keywords'),
        'description' => $request->get('description'),
        'templet_all' => $request->get('templet_all'),
        'templet_nosub' => $request->get('templet_nosub'),
        'templet_article' => $request->get('templet_article'),
    ]);

    if ($category) {
      $message = '栏目修改成功，请选择操作！';
      $url = [];
      $url['返回根栏目'] = ['url'=>route('admin.category.index')];
      if($category->parent_id > 0) $url['返回子栏目'] = ['url'=>route('admin.category.sub.show',$category->parent_id)];
      $url['继续添加'] = ['url'=>route('admin.category.create')];
      $url['继续编辑'] = ['url'=>route('admin.category.edit',$category->id)];
      $url['查看栏目'] = ['url'=>route('category.show',$category->id),'target'=>'_blank'];
      return Theme::view('admin.message.show',compact('message','url'));
    } else {
      return back()->withErrors(['title' => '添加失败']);
    }
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/category/covers/';
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

  public function destroy($id)
  {
    Category::destroy($id);
    Cache::store('category')->flush();
    return ['error' => 0, 'message' => '删除成功！'];
  }

}
