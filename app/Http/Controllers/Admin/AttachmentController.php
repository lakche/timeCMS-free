<?php

namespace App\Http\Controllers\Admin;

use App\Model\Attachment;
use App\Libs\Plupload;
use Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachmentRequest;

class AttachmentController extends Controller
{
  public function store(AttachmentRequest $request)
  {
    //上传目录
    $filePath = '/uploads/'.$request->get('class').'/'.$request->get('type').'/'.date( "Ymd" ).'/';
    //检查是否有文件
    if ($request->hasFile('file')) {
      $plupload = new Plupload();
      $fileName = date("_YmdHis") . rand(1000, 9999) . '.';
      $info = $plupload->process('file', function ($file) use (&$fileName,&$filePath) {
        $fileName = $fileName . $file->getClientOriginalExtension();
        $file->move(public_path($filePath), $fileName);
      });
    } else {
      $info['result'] = false;
      return $info;
    }
    //如果是图片，生成微缩图
    $thumbUrl = '';
    $fullPath = public_path($filePath) . $fileName;
    $fileMime = '';
    if (file_exists($fullPath)) {
      $img = Image::make($fullPath);
      $imgMime = explode('/', $img->mime());
      $fileMime = $imgMime[0];
      if ($fileMime == 'image') {
        $img->resize(300, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        $img->save(public_path($filePath) . 'thumb' . $fileName);
        $thumbUrl = $filePath . 'thumb' . $fileName;
      }
    } else {
      $info['result'] = false;
      return $info;
    }
    //附件入库
    $attachment = Attachment::create([
        'url' => $filePath . $fileName,
        'name' => '',
        'thumb' => $thumbUrl,
        'sort' => 0,
        'is_recommend' => 0,
        'is_show' => 0,
        'is_cover' => 0,
        'type' => $fileMime,
        'attr' => $request->get('class'),
        'hash' => $request->get('hash'),
        'project_id' => 0,
    ]);
      if($request->get('type') == 'cover'){
          Attachment::where('hash', $attachment->hash)->update(['is_cover' => 0]);
          Attachment::find($attachment->id)->update(['is_cover' => 1]);
      }
    //返回json
    $info['result'] = true;
    $info['file'] = $filePath . $fileName;
    $info['thumb'] = $thumbUrl;
    return $info;
  }
}
