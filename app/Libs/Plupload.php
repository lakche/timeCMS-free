<?php
/**
 * Created by Joy.
 * User: Joy
 * plupload 图片上传类
 * 允许大图片上传
 * 允许多图片上传
 */
namespace App\Libs;

use Closure;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \Request;

class Plupload
{

  /**
   * @var Illuminate\Filesystem\Filesystem
   */
  protected $storage;
  private $maxFileAge = 3600; //600 secondes

  public function __construct()
  {
    $this->storage = app('files');
  }

  /**
   * Get chuck upload path
   *
   * @return string
   */
  public function getChunkPath()
  {
    $path = public_path('uploads/');
    if (!$this->storage->isDirectory($path)) {
      $this->storage->makeDirectory($path, 0777, true);
    }
    return $path;
  }

  /**
   * Process uploaded files
   *
   * @param string $name
   * @param closure $closure
   *
   * @return array
   */
  public function process($name, Closure $closure)
  {
    $response = [];
    $response['jsonrpc'] = "2.0";
    if ($this->hasChunks()) {
      $result = $this->chunks($name, $closure);
    } else {
      $result = $this->single($name, $closure);
    }
    $response['result'] = $result;
    return $response;
  }

  /**
   * Handle single uploaded file
   *
   * @param string $name
   * @param closure $closure
   *
   * @return mixed
   */
  public function single($name, Closure $closure)
  {
    if (Request::hasFile($name)) {
      return $closure(Request::file($name));
    }
  }

  /**
   * Handle single uploaded file
   *
   * @param string $name
   * @param closure $closure
   *
   * @return mixed
   */
  public function chunks($name, Closure $closure)
  {
    $result = false;
    if (Request::hasFile($name)) {
      $file = Request::file($name);
      $chunk = (int)Request::get('chunk', false);
      $chunks = (int)Request::get('chunks', false);
      $originalName = Request::get('name');
      $filePath = $this->getChunkPath() . '/' . $originalName . '.part';
      $this->removeOldData($filePath);
      $this->appendData($filePath, $file);
      if ($chunk == $chunks - 1) {
        $file = new UploadedFile($filePath, $originalName, 'blob', sizeof($filePath), UPLOAD_ERR_OK, true);
        $result = $closure($file);
        @unlink($filePath);
      }
    }
    return $result;
  }

  /**
   * Remove old chunks
   */
  protected function removeOldData($filePath)
  {
    if ($this->storage->exists($filePath) && ($this->storage->lastModified($filePath) < time() - $this->maxFileAge)) {
      $this->storage->delete($filePath);
    }
  }

  /**
   * Merge chunks
   */
  protected function appendData($filePathPartial, UploadedFile $file)
  {
    if (!$out = @fopen($filePathPartial, "ab")) {
      throw new Exception("Failed to open output stream.", 102);
    }
    if (!$in = @fopen($file->getPathname(), "rb")) {
      throw new Exception("Failed to open input stream", 101);
    }

    while ($buff = fread($in, 4096)) {
      fwrite($out, $buff);
    }
    @fclose($out);
    @fclose($in);
  }

  /**
   * Check if request has chunks
   *
   * @return bool
   */
  public function hasChunks()
  {
    return (bool)Request::get('chunks', false);
  }

}