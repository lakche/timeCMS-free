<?php

namespace App\Http\Controllers\Admin;

use App\Model\Attachment;
use App\Libs\Uploader;
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
      $filePath = '/uploads/' . $request->get('class') . '/' . $request->get('type') . '/' . date("Ymd") . '/';
      $info['result'] = false;
      //Plupload上传
      if ($request->hasFile('file')) {
          $info = self::plupload($filePath);
      }
      //Uploader上传
      if ($request->hasFile('upfile')) {
          $info = self::uploader();
      }

      //附件入库
      if ($info['result']) {
          $attachment = Attachment::create([
              'url' => $info['file'],
              'name' => '',
              'thumb' => $info['thumb'],
              'sort' => 0,
              'is_recommend' => 0,
              'is_show' => 0,
              'is_cover' => 0,
              'type' => $info['type'],
              'attr' => $request->get('class'),
              'hash' => $request->get('hash'),
              'project_id' => 0,
          ]);
          if ($request->get('type') == 'cover') {
              Attachment::where('hash', $attachment->hash)->update(['is_cover' => 0]);
              Attachment::find($attachment->id)->update(['is_cover' => 1]);
          }
      }

      return $info;
  }

    public function plupload($filePath){
        $plupload = new Plupload();
        $fileName = date("_YmdHis") . rand(1000, 9999) . '.';
        $plupload->process('file', function ($file) use (&$fileName,&$filePath) {
            $fileName = $fileName . $file->getClientOriginalExtension();
            $file->move(public_path($filePath), $fileName);
        });

        $fullPath = public_path($filePath) . $fileName;
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
                $info['result'] = true;
                $info['file'] = $filePath . $fileName;
                $info['thumb'] = $thumbUrl;
                $info['type'] = $fileMime;
                return $info;

            }
        } else {
            $info['result'] = false;
            return $info;
        }
    }

    public function uploader(){
        $config = array(
            "savePath" => '' ,
            "maxSize" => 1000 ,
            "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )
        );
        $Path = "/uploads/projects/images/";
        $config[ "savePath" ] = $Path;
        $up = new Uploader( "upfile" , $config );
        $info = $up->getFileInfo();
        $info['result'] = $info['state'] == 'SUCCESS' ? true : false;
        $info['file'] = $info['url'];
        $info['thumb'] = '';
        $info['type'] = str_replace('.','',$info['type']);
        return $info;
    }
}
