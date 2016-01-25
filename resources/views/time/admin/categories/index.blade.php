@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset($theme.'/css/admin.css') }}" type="text/css"/>
    <div class="bg_cont">
        <div id="content">
            <div class="row-container">
                <div class="container-fluid">
                    <div class="content-inner row-fluid">
                        <div id="component" class="span12">
                            <main role="main">
                                <div class="page page-contact page-contact__">
                                    <div class="row-fluid">
                                        <div class="contact_details span2">
                                            @include($theme.'.admin.left')
                                        </div>
                                        <div class="span10">
                                            <div class="contact_body">
                                                <div id="Kunena">
                                                    <div class="kunena_body">
                                                        <div class="kblock kgenstats">
                                                            <div class="kheader">
                                                                <span>分类列表</span>
                                                            </div>
                                                            <div class="kcontainer">
                                                                <div class="kbody">
                                                                    <table class="kblocktable">
                                                                        <tbody>
                                                                        <tr class="ksth">
                                                                            <th>#</th>
                                                                            <th>分类名称</th>
                                                                            <th style=" width:60px;">根分类</th>
                                                                            <th style=" width:80px;">操作</th>
                                                                        </tr>
                                                                        @foreach($categories as $category)
                                                                        <tr class="krow2">
                                                                            <td class="kcol-mid">{{ $category->id }}</td>
                                                                            <td class="kcol-mid">{{ $category->name }}</td>
                                                                            <td class="kcol-mid">是</td>
                                                                            <td class="kcol-mid tools">
                                                                                <a href=""><i class="fa fa-edit pull-left"></i></a>
                                                                                <a href="javascript:void(0);" data-id="{{ $category->id }}" class="categorieDel"><i class="fa fa-trash pull-right"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <td colspan="4"><a href="" class="btn btn-primary pull-right">添加分类</a></td>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
