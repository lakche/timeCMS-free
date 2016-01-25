@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/admin.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/bootstrap-switch.min.css') }}"/>
    <script src="{{ asset($theme.'/js/bootstrap-switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset($theme.'/js/plupload/plupload.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset($theme.'/js/plupload/i18n/zh_CN.js') }}"></script>
    <script type="text/javascript" src="{{ asset($theme.'/js/admin.js') }}"></script>
    <div class="container-fluid" id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        @include($theme.'.admin.left')
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            文章分类管理 @if($type->parent_id > 0) - {{ $type->parent->title or '' }} @endif
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ url('admin/categories/save',$type->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                <div class="input-group">
                                    <div class="input-group-addon">栏目标题</div>
                                    <input type="text" class="form-control" name="title" value="{{ $type->title }}">
                                </div>
                                @if($errors->first('title'))
                                <p class="bg-danger">{{ $errors->first('title') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">上级栏目</div>
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        {!! $categoryTree !!}
                                    </select>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">栏目简介</div>
                                    <input type="text" class="form-control" name="info" value="{{ $type->info }}">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">栏目排序</div>
                                    <input type="number" class="form-control" name="sort" value="{{ $type->sort }}">
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">栏目封面</div>
                                    <input type="text" class="form-control" name="cover" id="CPIC"
                                           value="{{ $type->cover }}" readonly>
                                    <input type="hidden" class="form-control" name="thumb" id="CPCP" value="{{ $type->thumb }}" readonly>

                                    <div class="input-group-addon btn btn-primary" id="categorie_cover">上传封面</div>
                                </div>
                                <div class="input-group checkbox">
                                    <div class="input-group-addon">导航显示</div>
                                    <input type="checkbox" name="is_nav_show" value="1"
                                           data-on-text="显示" data-off-text="隐藏"
                                           @if($type->is_nav_show) checked @endif />
                                </div>
                                <button type="submit" class="btn btn-primary pull-left">保存分类</button>
                                @if($type->parent_id > 0)
                                    <a href="{{ url('admin/categories/subs',$type->parent_id) }}"
                                       class="btn btn-warning pull-right">返回上级</a>
                                @else
                                    <a href="{{ url('admin/categories/') }}"
                                       class="btn btn-warning pull-right">返回根分类</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $.fn.bootstrapSwitch.defaults.onColor = 'primary';
        $.fn.bootstrapSwitch.defaults.offColor = 'danger';
        $("[type='checkbox']").bootstrapSwitch();
        $("#parent_id").val({{ $type->parent_id }});
    </script>
@endsection