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
                    <div class="panel panel-primary">
                        <div class="panel-heading">系统设置</div>
                        <div class="panel-body">
                            <form method="POST" action="{{ url('admin/system/save') }}"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="TOKEN" value="{{ csrf_token() }}"/>

                                <div class="input-group">
                                    <div class="input-group-addon">网站标题</div>
                                    <input type="text" class="form-control" name="title"
                                           value="{{ $system['title'] or ''  }}">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">seo关键字</div>
                                    <input type="text" class="form-control" name="keywords"
                                           value="{{ $system['keywords'] or '' }}">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">seo描述</div>
                                    <input type="text" class="form-control" name="description"
                                           value="{{ $system['description'] or '' }}">
                                </div>
                                <div class="input-group col-sm-12">
                                    <button type="submit" class="btn btn-primary pull-right">保存设置</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection