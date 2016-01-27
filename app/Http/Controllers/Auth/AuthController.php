<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\User;
use Response;
use Request;
use Validator;
use Redirect;
use Cookie;
use Hash;
use Theme;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        if(Request::cookie('remember_name')){
            $user_name = Request::cookie('remember_name');
        } else {
            $user_name = '';
        }
        return Theme::view('auth.login', compact('user_name'));
    }

    public function getRegister()
    {
        return Theme::view('auth.register');
    }

    public function postLogin()
    {
        $input = Request::only(['name', 'password','remember','captcha']);
        $rules = [
            'name' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ];
        $messages = [
            'required' => ':attribute 不能为空',
            'captcha' => ':attribute 不正确',
        ];
        $attributes = [
            "name" => '用户名',
            'password' => '用户密码',
            'captcha' => '验证码',
        ];
        $validator = Validator::make($input, $rules, $messages, $attributes);
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        } else {
            if (auth()->attempt(['name' => $input['name'], 'password' => $input['password']])) {
                if($input['remember']) {
                    return Redirect::to('/')->withCookie(cookie('remember_name', $input['name'], 60*24*15));
                }
                else {
                    return Redirect::to('/')->withCookie(cookie('remember_name'));
                }
            }
        }
        return Redirect::back()->withInput()->withErrors(array('password' => '登录失败，密码错误！'));
    }

    public function postRegister()
    {
        $rules = array(
            'name' => 'required|between:1,20|unique:users',
            'password' => 'required|confirmed|between:6,20',
        );
        $message = array(
            'password.confirmed' => '重复密码不一致',
            "required"           => ":attribute 不能为空",
            "unique"           => ":attribute 已被使用",
            "between"            => ":attribute 长度必须在 :min 和 :max 之间"
        );

        $attributes = array(
            "name" => '用户名',
            'password' => '用户密码',
        );

        $input = Request::only(['name', 'password', 'password_confirmation']);

        $validator = Validator::make(
            $input,
            $rules,
            $message,
            $attributes
        );
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $input['name'];
        $user->password = Hash::make($input['password']);
        if($user->save()){
            auth()->login($user);
        }

        return Redirect::to('/');
    }

    public function postAjaxLogin()
    {
        $input = Input::only(['name', 'password','remember']);
        $rules = [
            'name' => 'required',
            'password' => 'required',
        ];
        $messages = [
            'required' => ':attribute 不能为空',
        ];
        $attributes = [
            "name" => '用户名',
            'password' => '用户密码',
        ];
        $validator = Validator::make($input, $rules, $messages, $attributes);
        if ($validator->fails()) {
            return $validator;
        } else {
            if (auth()->attempt(['name' => $input['name'], 'password' => $input['password']])) {
                if($input['remember']) {
                    return Response::json(['error' => '0','message' => '登录成功！'])->withCookie(cookie('remember_name', $input['email'], 60*24*15));
                }
                else {
                    return Response::json(['error' => '0','message' => '登录成功！'])->withCookie(cookie('remember_name'));
                }
            }
        }
        return ['error' => '1','message' => '用户名密码错误！'];
    }

    public function getLogout()
    {
        auth()->logout();
        return Redirect::to('/');
    }

    public function getCaptcha()
    {
        $captcha = captcha_src();
        return ['error'=>0,'captcha'=>$captcha,'message'=>''];
    }
}
