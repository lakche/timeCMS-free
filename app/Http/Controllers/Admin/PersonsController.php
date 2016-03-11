<?php

namespace App\Http\Controllers\Admin;

use App\Model\Person;

use App\Libs\Uploader;
use App\Libs\Plupload;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonRequest;

use Request;
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
    return Theme::view('admin.persons.show',compact('person'));
  }

  public function getEdit($id)
  {
    if(!preg_match("/^[1-9]\d*$/",$id)) return Redirect::to('/');

    $person = Person::find($id);
    if(!$person) return Redirect::to(route('admin.persons'));

    return Theme::view('admin.persons.show',compact('person'));
  }

  public function postSave(PersonRequest $request, $id = 0)
  {
    if(!preg_match("/^[0-9]\d*$/",$id)) return Redirect::to('/');

    if ($id > 0) {
      $person = Person::find($id);
      if (!$person) {
        $person = new Person;
      }
    } else {
      $person = new Person;
    }
    $person->name = $request->get('name');
    $person->title = $request->get('title');
    $person->sex = $request->get('sex') ? Female : Male;
    $person->sort = $request->get('sort');
    $person->point = $request->get('point');
    $person->age = $request->get('age');
    $person->tag = json_encode(explode(',', strip_tags($request->get('tag'))));
    $person->is_recommend = $request->get('is_recommend') ? 1 : 0;
    $person->is_show = $request->get('is_show') ? 1 : 0;
    $person->head = $request->get('head');
    $person->head_thumbnail = $request->get('head_thumbnail');
    $person->url = $request->get('url');
    $person->keywords = $request->get('keywords');
    $person->description = $request->get('description');
    $person->info = $request->get('info');
    $person->text = $request->get('text') ? $request->get('text') : '';
    $person->save();

    $message = '人物发布成功，请选择操作！';
    $url = [];
    $url['返回人物列表'] = ['url'=>route('admin.persons')];
    $url['继续添加'] = ['url'=>route('admin.persons.add')];
    $url['继续编辑'] = ['url'=>route('admin.persons.edit',$person->id)];
    $url['查看人物'] = ['url'=>route('person.show',$person->id),'target'=>'_blank'];
    return Theme::view('admin.message.show',compact('message','url'));
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

    return $info;
  }

  public function postSaveHead()
  {
    $filePath = '/uploads/persons/heads/'.date( "Ymd" ).'/';
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
    Person::destroy($id);
    return ['error' => 0, 'message' => '删除成功！'];
  }

}
