<?php

namespace App\Http\Controllers\Admin;

use App\Model\Project;
use App\Model\Category;
use App\Model\Person;

use App\Libs\Uploader;
use App\Libs\Plupload;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;

use Request;
use Redirect;
use Image;
use Hash;
use Theme;

class ProjectsController extends Controller
{
  public function getIndex()
  {
    $projects = Project::sortByDesc('id')->paginate(20);
    return Theme::view('admin.projects.index',compact('projects'));
  }

  public function getType($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $type = Category::find($id);
    if(!$type) return Redirect::to(route('admin.projects'));

    $projects = Project::where('category_id',$id)->sortByDesc('id')->paginate(20);

    return Theme::view('admin.projects.index',compact('projects','type'));
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
    $project->hash = Hash::make(time());
    return Theme::view('admin.projects.show',compact('project'));
  }

  public function getEdit($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $project = Project::find($id);
    if(!$project) return Redirect::to(route('admin.projects'));


    return Theme::view('admin.projects.show',compact('project'));
  }

  public function postSave(ProjectRequest $request, $id = 0)
  {
    if(!preg_match("/^[0-9]\d*$/",$id)) return Redirect::to('/');

    if ($id > 0) {
      $project = Project::find($id);
      if (!$project) {
        $project = new Project;
      }
    } else {
      $project = new Project;
    }
    $speed = [];
    $time = $request->get('time');
    $event = $request->get('event');
    foreach ($time as $key => $value) {
      if ($time[$key] != '') {
        $speed[] = ['time' => strip_tags($time[$key]), 'event' => strip_tags($event[$key])];
      }
    }
    $speed = array_sort($speed, function ($value) {
      return $value['time'];
    });
    $project->title = $request->get('title');
    $project->category_id = $request->get('category_id');
    $project->sort = $request->get('sort');
    $project->views = $request->get('views');
    $project->tag = json_encode(explode(',', strip_tags($request->get('tag'))));
    $project->is_recommend = $request->get('is_recommend') ? 1 : 0;
    $project->is_show = $request->get('is_show') ? 1 : 0;
    $project->cover = $request->get('cover');
    $project->thumb = $request->get('thumb');
    $project->cost = $request->get('cost');
    $project->period = $request->get('period');
    $project->person_id = json_encode(explode(',', strip_tags($request->get('person_id'))));
    $project->info = $request->get('info');
    $project->url = $request->get('url');
    $project->keywords = $request->get('keywords');
    $project->description = $request->get('description');
    $project->text = $request->get('text') ? $request->get('text') : '';
    $project->speed = json_encode($speed);
    $project->save();


    $message = '项目提交成功，请选择操作！';
    $url = [];
    $url['返回项目列表'] = ['url'=>route('admin.projects')];
    if($project->category_id > 0) $url['返回栏目项目列表'] = ['url'=>route('admin.projects.type',$project->category_id)];
    $url['继续添加'] = ['url'=>route('admin.projects.add')];
    $url['继续编辑'] = ['url'=>route('admin.projects.edit',$project->id)];
    $url['查看项目'] = ['url'=>route('project.show',$project->id),'target'=>'_blank'];
    return Theme::view('admin.message.show',compact('message','url'));
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

    return $info;
  }

  public function postSaveCover()
  {
    $filePath = '/uploads/projects/covers/'.date( "Ymd" ).'/';
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
    Project::destroy($id);
    return ['error' => 0, 'message' => '删除成功！'];
  }

}
