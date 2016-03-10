@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/admin.css') }}"/>
    <script type="text/javascript" src="{{ asset($theme.'/js/admin.js') }}"></script>
    <input type="hidden" name="_token" id="TOKEN" value="{{ csrf_token() }}"/>
    <div class="container-fluid" id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    @include($theme.'.admin.left')
                </div>
                <div class="col-sm-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            文章管理 @if(isset($type)) - {{ $type->title }} @endif
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>名称</th>
                                    <th>
                                        <div class="dropdown">
                                            <a id="dLabel" data-target="#" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                                分类
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                <li><a href="{{ url('admin/articles') }}">全部</a></li>
                                                @foreach(Theme::categories() as $category)
                                                    <li><a href="{{ url('admin/articles/type',$category->id) }}">{{ $category->title }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </th>
                                    <th class="operation">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>{{ $article->id }}</td>
                                        <td><a href="{{ url('article', [$article->id]) }}"
                                               target="_blank">{{ $article->title }}</a></td>
                                        <td><a href="{{ url('admin/articles/type',$article->category_id) }}">{{ $article->category->title }}</a></td>
                                        <td>
                                            <a href="{{ url('admin/articles/edit', [$article->id]) }}">
                                                <i class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="编辑文章"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-id="{{ $article->id }}" class="article_Del">
                                                <i class="glyphicon glyphicon-trash pull-right" data-toggle="tooltip" data-placement="top" title="删除文章"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <div class="pagination"
                                             style="text-align:center;">{!! $articles->render() !!}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8"><a href="{{ url('admin/articles/add') }}"
                                                       class="btn btn-info pull-right">添加文章</a></td>
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