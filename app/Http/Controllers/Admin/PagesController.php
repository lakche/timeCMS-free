<?php

namespace App\Http\Controllers\Admin;

use App\Model\Page;

use App\Libs\Plupload;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;

use Request;
use Redirect;
use Image;
use Hash;
use Theme;

class PagesController extends Controller
{
  public function getIndex()
  {
    $pages = Page::sortByDesc('id')->paginate(20);
    return Theme::view('admin.pages.index',compact('pages'));
  }

  public function getAdd()
  {
    $page = new Page;
    $page->id = 0;
    $page->views = 0;
    $page->is_open = 1;
    return Theme::view('admin.pages.show',compact('page'));
  }

  public function getEdit($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $page = Page::find($id);
    if(!$page) return Redirect::to('/admin/pages');

    return Theme::view('admin.pages.show',compact('page'));
  }

  public function postSave(PageRequest $request, $id = 0)
  {
    if(!preg_match("/^[0-9]\d*$/",$id)) return Redirect::to('/');

    if ($id > 0) {
      $page = Page::find($id);
      if (!$page) {
        $page = new Page;
      }
    } else {
      $page = new Page;
    }
    $page->url = $request->get('url');
    $page->view = $request->get('view');
    $page->views = $request->get('views');
    $page->is_open = $request->get('is_open') ? 1 : 0;
    $page->openurl = $request->get('openurl');
    $page->cover = $request->get('cover');
    $page->thumb = $request->get('thumb');
    $page->save();

    $message = '单页发布成功，请选择操作！';
    $url = [];
    $url['返回单页列表'] = ['url'=>url('admin/pages')];
    $url['继续添加'] = ['url'=>url('admin/pages/add')];
    $url['继续编辑'] = ['url'=>url('admin/pages/edit',$page->id)];
    $url['查看单页'] = ['url'=>url('page',$page->url),'target'=>'_blank'];
    return Theme::view('admin.message.show',compact('message','url'));
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/pages/covers/'.date( "Ymd" ).'/';
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
    Page::destroy($id);
    return ['error' => 0, 'message' => '删除成功！'];
  }

}
