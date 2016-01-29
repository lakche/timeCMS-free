<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Page;
use App\Libs\Plupload;
use Response;
use Validator;
use Redirect;
use Request;
use Image;
use Theme;

class PagesController extends Controller
{
  public function getIndex()
  {
    $pages = Page::sortByDesc('id')->paginate(20);
    return Theme::view('admin.pages.index',compact(['pages']));
  }

  public function getAdd()
  {
    $page = new Page;
    $page->id = 0;
    $page->views = 0;
    $page->is_open = 1;
    return Theme::view('admin.pages.show',compact(['page']));
  }

  public function getEdit($id)
  {
    $id = intval($id);
    $page = Page::find($id);

    if(!$page) return Redirect::to('/admin/pages');

    return Theme::view('admin.pages.show',compact(['page']));
  }

  public function postSave($id = 0)
  {
    $id = intval($id);
    $rules = [
        'url' => 'required',
        'views' => 'required|integer',
        'view' => 'required_without_all:openurl',
    ];
    $messages = [
        'required' => ':attribute不能为空.',
        'integer' => ':attribute只能为整数.',
        'required_without_all' => '对应模板和外链网址必选一项填写',
    ];
    $attributes = array(
        "url" => '访问路径',
        'view' => '对应模板',
        'openurl' => '外链网址',
        'views' => '浏览量',
        'is_open' => '开放浏览',
        'cover' => '封面',
        "thumb" => '封面微缩图',
    );
    $input = Request::only(['url','view','openurl','views','is_open','cover','thumb']);

    $validator = Validator::make($input, $rules, $messages,$attributes);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator)->withInput();
    } else {
      if ($id>0) {
        $page = Page::find($id);
        if(!$page){
          $page = new Page;
        }
      } else {
        $page = new Page;
      }
      $page->url = strip_tags($input['url']);
      $page->view = strip_tags($input['view']);
      $page->views = $input['views'];
      $page->is_open = $input['is_open'] ? 1 : 0;
      $page->openurl = strip_tags($input['openurl']);
      $page->cover = strip_tags($input['cover']);
      $page->thumb = strip_tags($input['thumb']);
      $page->save();
    }

    $message = '单页发布成功，请选择操作！';
    $url = [];
    $url['返回单页列表'] = ['url'=>url('admin/pages')];
    $url['继续编辑'] = ['url'=>url('admin/pages/edit',$page->id)];
    $url['查看单页'] = ['url'=>url('page',$page->id)];
    return Theme::view('admin.message.show',compact(['message','url']));
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/pages/covers/'.date( "Ymd" ).'/';
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
    $page = Page::find($id);

    if(!$page) return Redirect::to('/admin/pages');

    $page->delete();
    return Response::json(['error' => 0, 'message' => '删除成功！']);
  }

}
