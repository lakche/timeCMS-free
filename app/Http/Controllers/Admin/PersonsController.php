<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Person;
use App\Libs\Uploader;
use App\Libs\Plupload;
use Response;
use Request;
use Validator;
use Redirect;
use Image;
use Theme;

class PersonsController extends Controller
{
  public function getIndex()
  {
    $persons = Person::sortByDesc('point')->paginate(20);
    return Theme::view('admin.persons.index',compact(['persons']));
  }

  public function getAdd()
  {
    $person = new Person;
    $person->id = 0;
    $person->is_show = 1;
    $person->sort = 0;
    $person->sex = 0;
    $person->age = 0;
    $person->point = 0;
    $person->is_recommend = 0;
    $person->tag = json_encode([]);
    return Theme::view('admin.persons.show',compact(['person']));
  }

  public function getEdit($id)
  {
    $id = intval($id);
    $person = Person::find($id);

    if(!$person) return Redirect::to('/admin/persons');

    return Theme::view('admin.persons.show',compact(['person']));
  }

  public function postSave($id = 0)
  {
    $id = intval($id);
    $rules = [
        'name' => 'required',
        'sort' => 'required|integer',
        'point' => 'required|integer',
        'age' => 'required|integer',
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
        "name" => '姓名',
        "title" => '头衔',
        "sex" => '性别',
        'sort' => '排序',
        "point" => '贡献度',
        "age" => '从业时间',
        "tag" => '特长',
        'is_recommend' => '是否推荐',
        'is_show' => '是否显示',
        "head" => '头像',
        "head_thumbnail" => '头像微缩图',
        "url" => '外链网址',
        'keywords' => 'seo关键字',
        'description' => 'seo描述',
        "info" => '简介',
        'text' => '详情',

    );
    $input = Request::only(['id','name','title','sex','sort','point','age','tag','is_recommend','is_show','head','head_thumbnail','url','keywords','description','info','text']);

    $validator = Validator::make($input, $rules, $messages,$attributes);
    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator)->withInput();
    } else {
      if ($id>0) {
        $person = Person::find($id);
        if(!$person){
          $person = new Person;
        }
      } else {
        $person = new Person;
      }
      $person->name = strip_tags($input['name']);
      $person->title = strip_tags($input['title']);
      $person->sex = $input['sex'] ? Female : Male;
      $person->sort = $input['sort'];
      $person->point = $input['point'];
      $person->age = $input['age'];
      $person->tag = json_encode(explode(',',strip_tags($input['tag'])));
      $person->is_recommend = $input['is_recommend'] ? 1 : 0;
      $person->is_show = $input['is_show'] ? 1 : 0;
      $person->head = strip_tags($input['head']);
      $person->head_thumbnail = strip_tags($input['head_thumbnail']);
      $person->url = strip_tags($input['url']);
      $person->keywords = strip_tags($input['keywords']);
      $person->description = strip_tags($input['description']);
      $person->info = strip_tags($input['info']);
      $person->text = $input['text'] ? $input['text'] : '';
      $person->save();
    }

    $message = '人物发布成功，请选择操作！';
    $url = [];
    $url['返回人物列表'] = ['url'=>url('admin/persons')];
    $url['继续编辑'] = ['url'=>url('admin/persons/edit',$person->id)];
    $url['查看人物'] = ['url'=>url('person',$person->id)];
    return Theme::view('admin.message.show',compact(['message','url']));
  }

  public function postUpdateImage()
  {
    $config = array(
        "savePath" => '' ,
        "maxSize" => 1000 ,
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )
    );
    $Path = "/uploads/persons/images/";
    $config[ "savePath" ] = $Path;
    $up = new Uploader( "upfile" , $config );
    $info = $up->getFileInfo();

    return json_encode($info);
  }

  public function postSaveHead()
  {
    $filePath = '/uploads/persons/heads/'.date( "Ymd" ).'/';
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
    $person = Person::find($id);

    if(!$person) return Redirect::to('/admin/persons');

    $person->delete();
    return Response::json(['error' => 0, 'message' => '删除成功！']);
  }

}
