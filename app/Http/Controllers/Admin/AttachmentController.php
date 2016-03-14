<?php

namespace App\Http\Controllers\Admin;

use App\Model\Attachment;

use App\Libs\Uploader;
use App\Libs\Plupload;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachmentRequest;

use Request;
use Image;

class AttachmentController extends Controller
{
  public function store(AttachmentRequest $request)
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
}
