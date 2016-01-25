@extends('layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}" />
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
                        分类管理
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>分类名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form method="POST" action="/admin/categories/{{ $category->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="_method" value="PATCH" />
                                <tr>
                                    <td></td>
                                    <td>
                                        <input class="text" type="text" name="name" value="{{ $category->name }}" placeholder="栏目标题...">
                                        <p class="bg-danger">{{ $errors->first('name') }}</p>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary">保存</button>
                                    </td>
                                </tr>
                            </form>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary pull-right">返回列表</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection