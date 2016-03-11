@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/admin.css') }}"/>
    <script src="{{ asset($theme.'/js/admin.js') }}"></script>
    <div class="container-fluid" id="main">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
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
                            文章分类管理 @if(isset($parent)) - {{ $parent->title }} @endif
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>分类名称</th>
                                    <th class="operation">查看下级</th>
                                    <th class="operation">导航显示</th>
                                    <th class="operation">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->title }}</td>
                                        <td class="text-center"><a href="{{ route('admin.categories.subs',$category->id) }}" class="btn btn-primary btn-xs">查看 <span class="badge">{{ $category->subs->count() }}</span></a></td>
                                        <td class="text-center">
                                            @if($category->is_nav_show)
                                                <i class="glyphicon glyphicon-ok text-primary"></i>
                                            @else
                                                <i class="glyphicon glyphicon-remove text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                <i class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="编辑分类"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-id="{{ $category->id }}" class="categorie_Del">
                                                <i class="glyphicon glyphicon-trash pull-right" data-toggle="tooltip" data-placement="top" title="删除分类"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                    @if(isset($parent))
                                        @if($parent->parent_id > 0)
                                            <a href="{{ route('admin.categories.subs',$parent->parent_id) }}"
                                                               class="btn btn-warning">返回上级</a>
                                            @else
                                                <a href="{{ route('admin.categories') }}"
                                                   class="btn btn-warning">返回根分类</a>
                                        @endif
                                        <a href="{{ route('admin.categories.add',$parent->id) }}"
                                                           class="btn btn-primary pull-right">添加下级分类</a>
                                    @else
                                        <a href="{{ route('admin.categories.add') }}"
                                                           class="btn btn-primary pull-right">添加分类</a>
                                    @endif
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection