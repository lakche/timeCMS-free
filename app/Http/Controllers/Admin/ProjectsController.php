<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Project;
use App\Model\Category;
use App\Model\Person;
use App\Libs\Uploader;
use App\Libs\Plupload;
use Response;
use Request;
use Validator;
use Redirect;
use Image;
use Theme;

class ProjectsController extends Controller
{
  public function getIndex()
  {
    $projects = Project::sortByDesc('id')->paginate(20);
    $categories = Category::where('is_nav_show','>','0' )->sortByDesc('id')->get();
    return Theme::view('admin.projects.index',compact(['projects','categories']));
  }

  public function getType($id)
  {
    $id = intval($id);
    $projects = Project::where('category_id',$id)->sortByDesc('id')->paginate(20);
    $categories = Category::where('is_nav_show','>','0' )->sortByDesc('id')->get();
    $type = Category::find($id);
    return Theme::view('admin.projects.index',compact(['projects','categories','type']));
  }

  public function getAdd()
  {
    $project = new Project;
    $project->id = 0;
    $project->is_show = 1;
    $project->sort = 0;
    $project->views = 0;
    $project->cost = 0;
    $project->period = 0;
    $project->category_id = 1;
    $project->tag = json_encode([]);
    $project->person_id = json_encode([]);
    $types = Category::sortByDesc('id')->get();
    $categoryTree = $this->categoryTree();
    $persons = Person::sortByDesc('point')->get();
    return Theme::view('admin.projects.show',compact(['project','areas','types','categoryTree','persons']));
  }

  public function getEdit($id)
  {
    $id = intval($id);
    $project = Project::find($id);

    if(!$project) return Redirect::to('/admin/articles');

    $types = Category::sortByDesc('id')->get();
    $categoryTree = $this->categoryTree();
    $persons = Person::sortByDesc('point')->get();
    return Theme::view('admin.projects.show',compact(['project','types','categoryTree','persons']));
  }

  public function postSave($id = 0)
  {
    $id = intval($id);
    $rules = [
        'title' => 'required',
        'category_id' => 'required|integer|exists:categories,id',
        'sort' => 'required|integer',
        'views' => 'required|integer',
        'cost' => 'required|numeric',
        'period' => 'required|integer',
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
        "title" => '项目名称',
        'category_id' => '项目分类',
        'sort' => '项目排序',
        'views' => '浏览量',
        "tag" => '项目标签',
        'is_recommend' => '是否推荐',
        'is_show' => '是否显示',
        "cover" => '封面图',
        "thumb" => '封面微缩图',
        "cost" => '项目费用',
        "period" => '项目周期',
        "person_id" => '参与人员',
        "info" => '项目简介',
        "url" => '外链网址',
        'keywords' => 'seo关键字',
        'description' => 'seo描述',
        'text' => '项目详情',
        'time' => '进度时间',
        'event' => '进度事件',
    );
    $input = Request::only(['title','category_id','sort','views','tag','is_recommend','is_show','cover','thumb','cost','period','person_id','info','url','keywords','description','text','time','event']);

    $validator = Validator::make($input, $rules, $messages,$attributes);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator)->withInput();
    } else {
      if ($id>0) {
        $project = Project::find($id);
        if(!$project){
          $project = new Project;
        }
      } else {
        $project = new Project;
      }
      $speed = [];
      foreach($input['time'] as $key => $value){
        if($input['time'][$key] != '') {
          $speed[] = ['time' => strip_tags($input['time'][$key]), 'event' => strip_tags($input['event'][$key])];
        }
      }
      $speed = array_sort($speed, function ($value) {
        return $value['time'];
      });
      $project->title = strip_tags($input['title']);
      $project->category_id = $input['category_id'];
      $project->sort = $input['sort'];
      $project->views = $input['views'];
      $project->tag = json_encode(explode(',',strip_tags($input['tag'])));
      $project->is_recommend = $input['is_recommend'] ? 1 : 0;
      $project->is_show = $input['is_show'] ? 1 : 0;
      $project->cover = strip_tags($input['cover']);
      $project->thumb = strip_tags($input['thumb']);
      $project->cost = $input['cost'];
      $project->period = $input['period'];
      $project->person_id = json_encode(explode(',',strip_tags($input['person_id'])));
      $project->info = strip_tags($input['info']);
      $project->url = strip_tags($input['url']);
      $project->keywords = strip_tags($input['keywords']);
      $project->description = strip_tags($input['description']);
      $project->text = $input['text'] ? $input['text'] : '';
      $project->speed = json_encode($speed);
      $project->save();
    }

    $message = '项目发布成功，请选择操作！';
    $url = [];
    $url['返回项目列表'] = ['url'=>url('admin/projects')];
    if($project->category_id > 0) $url['返回栏目项目列表'] = ['url'=>url('admin/projects/type',$project->category_id)];
    $url['继续编辑'] = ['url'=>url('admin/projects/edit',$project->id)];
    $url['查看项目'] = ['url'=>url('project',$project->id)];
    return Theme::view('admin.message.show',compact(['message','url']));
  }

  public function postUpdateImage()
  {
    $config = array(
        "savePath" => '' ,
        "maxSize" => 1000 ,
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )
    );
    $Path = "/uploads/projects/images/";
    $config[ "savePath" ] = $Path;
    $up = new Uploader( "upfile" , $config );
    $info = $up->getFileInfo();

    return json_encode($info);
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/projects/covers/'.date( "Ymd" ).'/';
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
    $project = Project::find($id);

    if(!$project) return Redirect::to('/admin/projects');

    $project->delete();
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
