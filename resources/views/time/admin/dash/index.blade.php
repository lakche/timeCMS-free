@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    @include($theme.'.admin.left')
                </div>
                <div class="col-sm-10">
                    <div class="panel panel-primary">
                        <div class="panel-heading">欢迎使用{{ config('system.title') }}</div>
                        <div class="panel-body">
                            <div class="col-sm-4">
                                注册人数
                                <span>0</span>
                            </div>
                            <div class="col-sm-4">
                                文章数量
                                <span>0</span>
                            </div>
                            <div class="col-sm-4">
                                留言数量
                                <span>0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection