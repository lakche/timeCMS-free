@extends('layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}"/>
    <script type="text/javascript" src="{{ asset('editjs/ueditor.config.js') }}"></script>
    <script type="text/javascript" src="{{ asset('editjs/ueditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('editjs/lang/zh-cn/zh-cn.js') }}"></script>
    <link href="{{ asset('editjs/themes/default/_css/umeditor.css') }}" type="text/css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="list-group">
                    @include('admin.left')
                </div>
            </div>
            <div class="col-sm-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        文章管理
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="/admin/articles/{{ $article->id }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="_method" value="PATCH"/>
                            <input type="hidden" name="type" id="UPTYPE" value="article"/>
                            <input type="hidden" name="project_id" id="PROJECTID" value="{{ $article->id }}"/>
                            <input type="hidden" name="hash" id="HASH" value="{{ $article->hash }}"/>
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td>文章标题</td>
                                    <td>
                                        <input class="text" type="text" name="title" value="{{ old('title') ? old('title') : $article->title }}" placeholder="文章标题...">
                                        <p class="bg-danger">{{ $errors->first('title') }}</p>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>tag</td>
                                    <td>
                                        <input class="text" type="text" name="tag" value="{{ old('tag') ? old('tag') : $article->tag }}" placeholder="tag...">
                                        <p class="bg-danger">{{ $errors->first('tag') }}</p>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>简介</td>
                                    <td>
                                        <input class="text" type="text" name="profile" value="{{ old('profile') ? old('profile') : $article->profile }}" placeholder="简介...">
                                        <p class="bg-danger">{{ $errors->first('profile') }}</p>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>文章分类</td>
                                    <td>
                                        <select name="category_id">
                                            {!! $categroys !!}
                                        </select>
                                        <p class="bg-danger">{{ $errors->first('category_id') }}</p>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>文章内容</td>
                                    <td>
                                        <script type="text/plain" id="content" name="content" style="width:800px;height:240px;">{!! old('content') ? old('content') : $article->content !!}</script>
                                        <p class="bg-danger">{{ $errors->first('content') }}</p>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <button type="submit" class="btn  btn-primary">保存</button>
                                        <a href="{{ route('admin.articles.index') }}" class="btn  btn-primary pull-right">返回列表</a>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var um = UM.getEditor('content');
    </script>
@endsection