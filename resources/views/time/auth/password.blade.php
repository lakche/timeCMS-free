@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user.css') }}" />
    <script src="{{ asset('js/user.js') }}"></script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        找回密码
                    </div>
                    <div class="panel-body user-info">
                        <form method="POST" action="{{asset('user/phone')}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td class="title">手机号码</td>
                                    <td>
                                        <input class="text" type="text" name="tel" id="find-tel" value="" placeholder="手机号码...">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">验证码</td>
                                    <td>
                                        <input class="text input-code" type="text" name="code" id="find-code" value="" placeholder="验证码只在十分钟内有效...">
                                        <button type="button" class="btn btn-primary sendcode-guest">点击发送验证码</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title">新密码</td>
                                    <td>
                                        <input class="text" type="text" name="password" id="find-password" value="" placeholder="密码长度在8-20位之间...">
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="button" class="btn btn-primary upcode-guest">提交修改</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection