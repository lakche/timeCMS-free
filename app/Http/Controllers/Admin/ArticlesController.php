<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\Category;
use App\Libs\Uploader;
use App\Libs\Plupload;
use Response;
use Validator;
use Redirect;
use Image;
use Theme;

class ArticlesController extends Controller
{
  public function getIndex()
  {
    $articles = Article::sortByDesc('id')->paginate(20);
    $categories = Category::where('is_nav_show','>','0' )->sortByDesc('id')->get();
    return Theme::view('admin.articles.index',compact(['articles','categories']));
  }

  public function getType($id)
  {
    $id = intval($id);
    $articles = Article::where('category_id',$id)->sortByDesc('id')->paginate(20);
    $categories = Category::where('is_nav_show','>','0' )->sortByDesc('id')->get();
    $type = Category::find($id);
    return Theme::view('admin.articles.index',compact(['articles','categories','type']));
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
    $types = Category::sortByDesc('id')->get();
    $categoryTree = $this->categoryTree();
    return Theme::view('admin.articles.show',compact(['article','areas','types','categoryTree']));
  }

  public function getEdit($id)
  {
    $id = intval($id);
    $article = Article::find($id);

    if(!$article) return Redirect::to('/admin/articles');

    $types = Category::sortByDesc('id')->get();
    $categoryTree = $this->categoryTree();
    return Theme::view('admin.articles.show',compact(['article','types','categoryTree']));
  }

  public function postSave($id = 0)
  {
    $id = intval($id);
    $rules = [
        'title' => 'required',
        'category_id' => 'required|integer|exists:categories,id',
        'sort' => 'required|integer',
        'views' => 'required|integer',
    ];
    $messages = [
        'required' => ':attribute不能为空.',
        'integer' => ':attribute只能为整数.',
        'exists' => ':attribute不存在.',
        'numeric' => ':attribute只能为数字.',
        'max' => ':attribute太大.',
        'min' => ':attribute太小.',
    ];
    $attributes = array(
        "title" => '文章标题',
        'category_id' => '文章分类',
        'sort' => '文章排序',
        'gallery_id' => '封面图',
        'views' => '浏览量',
        'is_recommend' => '是否推荐',
        'is_show' => '是否显示',
        "info" => '文章简介',
        "tag" => '文章标签',
        "url" => '外链网址',
        "cover" => '封面图',
        "thumb" => '封面微缩图',
        'text' => '文章详情',
        'subtitle' => '副标题',
        'author' => '文章作者',
        'source' => '文章来源',
        'keywords' => 'seo关键字',
        'description' => 'seo描述',
    );
    $input = Request::only(['title','category_id','sort','gallery_id','views','is_recommend','is_show','info','tag','url','text','cover','thumb','subtitle','author','source','keywords','description']);

    $validator = Validator::make($input, $rules, $messages,$attributes);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator)->withInput();
    } else {
      if ($id>0) {
        $article = Article::find($id);
        if(!$article){
          $article = new Article;
        }
      } else {
        $article = new Article;
      }
      $article->title = strip_tags($input['title']);
      $article->category_id = $input['category_id'];
      $article->sort = $input['sort'];
      $article->views = $input['views'];
      $article->tag = json_encode(explode(',',strip_tags($input['tag'])));
      $article->is_recommend = $input['is_recommend'] ? 1 : 0;
      $article->is_show = $input['is_show'] ? 1 : 0;
      $article->info = strip_tags($input['info']);
      $article->url = strip_tags($input['url']);
      $article->cover = strip_tags($input['cover']);
      $article->thumb = strip_tags($input['thumb']);
      $article->text = $input['text'] ? $input['text'] : '';
      $article->subtitle = strip_tags($input['subtitle']);
      $article->author = strip_tags($input['author']);
      $article->source = strip_tags($input['source']);
      $article->keywords = strip_tags($input['keywords']);
      $article->description = strip_tags($input['description']);
      $article->save();
    }

    $message = '文章发布成功，请选择操作！';
    $url = [];
    $url['返回文章列表'] = ['url'=>url('admin/articles')];
    if($article->category_id > 0) $url['返回栏目文章列表'] = ['url'=>url('admin/articles/type',$article->category_id)];
    $url['继续编辑'] = ['url'=>url('admin/articles/edit',$article->id)];
    $url['查看文章'] = ['url'=>url('article',$article->id)];
    return Theme::view('admin.message.show',compact(['message','url']));
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

    return json_encode($info);
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/articles/covers/'.date( "Ymd" ).'/';
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
    $article = Article::find($id);

    if(!$article) return Redirect::to('/admin/articles');

    $article->delete();
    return Response::json(['error' => 0, 'message' => '删除成功！']);
  }

  public function categoryTree($id = 0,$step = 0){
    $categories = Category::where('parent_id',$id)->get();
    if($step == 0){
      $tree = '';
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
