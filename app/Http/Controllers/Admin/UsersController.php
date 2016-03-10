<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Theme;

class UsersController extends Controller
{
  public function getIndex()
  {
    $users = User::sortByDesc('id')->paginate(20);
    return Theme::view('admin.users.index', compact('users'));
  }

  public function postDelete($id = 0)
  {
    $user = User::find($id);
    if(!$user) {
      return ['error' => 1, 'message' => '用户不存在或已被删除！'];
    }

    if($user->is_admin > 0){
      return ['error' => 1, 'message' => '不能删除管理员账号！'];
    }

    $user->delete();
    return ['error' => 0, 'message' => '删除成功！'];
  }

  public function postAdmin($id = 0)
  {
    $user = User::find($id);
    if(!$user) {
      return ['error' => 1, 'message' => '用户不存在或已被删除！'];
    }

    if($user->is_admin > 0){
      if($user->id == 1) {
        return ['error' => 1, 'message' => '不能删除默认管理员账号！'];
      }
      $user->is_admin = 0;
      $user->save();
      return ['error' => 0, 'message' => '管理员权限移除成功！'];
    } else {
      $user->is_admin = 1;
      $user->save();
      return ['error' => 0, 'message' => '管理员权限添加成功！'];
    }

  }
}
