@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/admin.css') }}"/>
    <div class="container-fluid" id="main">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    @include($theme.'.admin.left')
                </div>
                <div class="col-sm-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">系统统计</div>
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