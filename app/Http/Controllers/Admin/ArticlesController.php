<?php

namespace App\Http\Controllers\Admin;

use App\Model\Article;
use App\Model\Category;

use App\Libs\Uploader;
use App\Libs\Plupload;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;

use Request;
use Redirect;
use Image;
use Hash;
use Theme;

class ArticlesController extends Controller
{
  public function getIndex()
  {
    $articles = Article::sortByDesc('id')->paginate(20);
    return Theme::view('admin.articles.index',compact('articles'));
  }

  public function getType($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $type = Category::find($id);
    if(!$type) return Redirect::to(route('admin.articles'));

    $articles = Article::where('category_id',$id)->sortByDesc('id')->paginate(20);

    return Theme::view('admin.articles.index',compact('articles','type'));
  }

  public function getAdd()
  {
    $article = new Article;
    $article->id = 0;
    $article->is_show = 1;
    $article->sort = 0;
    $article->views = 0;
    $article->category_id = 1;
    $article->tag = json_encode([]);
    $article->hash = Hash::make(time());
    return Theme::view('admin.articles.show',compact('article'));
  }

  public function getEdit($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $article = Article::find($id);
    if(!$article) return Redirect::to(route('admin.articles'));

    return Theme::view('admin.articles.show',compact('article'));
  }

  public function postSave(ArticleRequest $request, $id = 0)
  {
    if(!preg_match("/^[0-9]\d*$/",$id)) return Redirect::to('/');

    if ($id > 0) {
      $article = Article::find($id);
      if (!$article) {
        $article = new Article;
      }
    } else {
      $article = new Article;
    }
    $article->title = $request->get('title');
    $article->category_id = $request->get('category_id');
    $article->sort = $request->get('sort');
    $article->views = $request->get('views');
    $article->tag = json_encode(explode(',', strip_tags($request->get('tag'))));
    $article->is_recommend = $request->get('is_recommend') ? 1 : 0;
    $article->is_show = $request->get('is_show') ? 1 : 0;
    $article->info = $request->get('info');
    $article->url = $request->get('url');
    $article->cover = $request->get('cover');
    $article->thumb = $request->get('thumb');
    $article->text = $request->get('text') ? $request->get('text') : '';
    $article->subtitle = $request->get('subtitle');
    $article->author = $request->get('author');
    $article->source = $request->get('source');
    $article->keywords = $request->get('keywords');
    $article->description = $request->get('description');
    $article->save();

    $message = '文章提交成功，请选择操作！';
    $url = [];
    $url['返回文章列表'] = ['url'=>route('admin.articles')];
    if($article->category_id > 0) $url['返回栏目文章列表'] = ['url'=>route('admin.articles.type',$article->category_id)];
    $url['继续添加'] = ['url'=>route('admin.articles.add')];
    $url['继续编辑'] = ['url'=>route('admin.articles.edit',$article->id)];
    $url['查看文章'] = ['url'=>route('article.show',$article->id),'target'=>'_blank'];
    return Theme::view('admin.message.show',compact('message','url'));
  }

  public function postUpdateImage()
  {
    $config = array(
        "savePath" => '' ,
        "maxSize" => 1000 ,
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )
    );
    $Path = "/uploads/articles/images/";
    $config[ "savePath" ] = $Path;
    $up = new Uploader( "upfile" , $config );
    $info = $up->getFileInfo();

    return $info;
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/articles/covers/'.date( "Ymd" ).'/';
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
    Article::destroy($id);
    return ['error' => 0, 'message' => '删除成功！'];
  }

}
