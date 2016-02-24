@extends($theme.'.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/admin.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($theme.'/css/bootstrap-switch.min.css') }}"/>
    <script type="text/javascript" src="{{ asset($theme.'/js/bootstrap-switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset($theme.'/editjs/ueditor.config.js') }}"></script>
    <script type="text/javascript" src="{{ asset($theme.'/editjs/ueditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset($theme.'/editjs/lang/zh-cn/zh-cn.js') }}"></script>
    <link type="text/css" rel="stylesheet" href="{{ asset($theme.'/editjs/themes/default/_css/umeditor.css') }}">
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
                            项目管理
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ url('admin/articles/save',$article->id) }}"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="TOKEN" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="type" id="UPTYPE" value="article"/>
                                <div class="input-group">
                                    <div class="input-group-addon">文章标题</div>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') ? old('title') : $article->title }}">
                                </div>
                                @if($errors->first('title'))
                                    <p class="bg-danger">{{ $errors->first('title') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">　副标题</div>
                                    <input type="text" class="form-control" name="subtitle" value="{{ old('subtitle') ? old('subtitle') : $article->subtitle }}">
                                </div>
                                @if($errors->first('subtitle'))
                                    <p class="bg-danger">{{ $errors->first('subtitle') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">文章分类</div>
                                    <select name="category_id" id="category_id" class="form-control">
                                        {!! $categoryTree !!}
                                    </select>
                                </div>
                                @if($errors->first('category_id'))
                                    <p class="bg-danger">{{ $errors->first('category_id') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">文章排序</div>
                                    <input type="number" class="form-control" name="sort" value="{{ old('sort') ? old('sort') : $article->sort }}">
                                </div>
                                @if($errors->first('sort'))
                                    <p class="bg-danger">{{ $errors->first('sort') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">　浏览量</div>
                                    <input type="number" class="form-control" name="views" value="{{ old('views') ? old('views') : $article->views }}">
                                </div>
                                @if($errors->first('views'))
                                    <p class="bg-danger">{{ $errors->first('views') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">项目标签</div>
                                    <input type="text" class="form-control" name="tag" value="{{ old('tag') ? old('tag') : implode(',',json_decode($article->tag)) }}">
                                </div>
                                @if($errors->first('tag'))
                                    <p class="bg-danger">{{ $errors->first('tag') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">文章作者</div>
                                    <input type="text" class="form-control" name="author" value="{{ old('author') ? old('author') : $article->author }}">
                                </div>
                                @if($errors->first('author'))
                                    <p class="bg-danger">{{ $errors->first('author') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">文章来源</div>
                                    <input type="text" class="form-control" name="source" value="{{ old('source') ? old('source') : $article->source }}">
                                </div>
                                @if($errors->first('source'))
                                    <p class="bg-danger">{{ $errors->first('source') }}</p>
                                @endif
                                <div class="input-group checkbox">
                                    <div class="input-group-addon">是否推荐</div>
                                    <input type="checkbox" name="is_recommend" value="1" data-on-text="推荐中" data-off-text="不推荐" @if($article->is_recommend) checked @endif />
                                </div>
                                @if($errors->first('is_recommend'))
                                    <p class="bg-danger">{{ $errors->first('is_recommend') }}</p>
                                @endif
                                <div class="input-group checkbox">
                                    <div class="input-group-addon">是否显示</div>
                                    <input type="checkbox" name="is_show" value="1" data-on-text="显示" data-off-text="隐藏" @if($article->is_show) checked @endif />
                                </div>
                                @if($errors->first('is_show'))
                                    <p class="bg-danger">{{ $errors->first('is_show') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">文章封面</div>
                                    <input type="text" class="form-control" name="cover" id="CPIC"
                                           value="{{ $article->cover }}" readonly>
                                    <input type="hidden" class="form-control" name="thumb" id="CPCP" value="{{ $article->thumb }}" readonly>

                                    <div class="input-group-addon btn btn-primary" id="article_cover">上传封面</div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">文章简介</div>
                                    <input type="text" class="form-control" name="info" value="{{ old('info') ? old('info') : $article->info }}">
                                </div>
                                @if($errors->first('info'))
                                    <p class="bg-danger">{{ $errors->first('info') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon"><span data-toggle="tooltip" data-placement="bottom" title="添加外链网址则直接跳转到该网址">外链网址</span></div>
                                    <input type="text" class="form-control" name="url" value="{{ old('url') ? old('url') : $article->url }}">
                                </div>
                                @if($errors->first('url'))
                                    <p class="bg-danger">{{ $errors->first('url') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">seo关键字</div>
                                    <input type="text" class="form-control" name="keywords" value="{{ old('keywords') ? old('keywords') : $article->keywords }}">
                                </div>
                                @if($errors->first('keywords'))
                                    <p class="bg-danger">{{ $errors->first('keywords') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">seo描述</div>
                                    <input type="text" class="form-control" name="description" value="{{ old('description') ? old('description') : $article->description }}">
                                </div>
                                @if($errors->first('description'))
                                    <p class="bg-danger">{{ $errors->first('description') }}</p>
                                @endif
                                <div class="input-group">
                                    <div class="input-group-addon">文章详情</div>
                                    <script type="text/plain" id="content" name="text"
                                            style="width:800px;height:240px;">{!! old('text') ? old('text') : $article->text !!}</script>
                                </div>
                                @if($errors->first('text'))
                                    <p class="bg-danger">{{ $errors->first('text') }}</p>
                                @endif
                                <div class="input-group col-sm-12">
                                    <button type="submit" class="btn btn-primary pull-left">保存文章</button>
                                    <a href="{{ url('admin/articles') }}" class="btn btn-warning pull-right">返回列表</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var um = UM.getEditor('content',{imageUrl:"{{ url('admin/projects/update-image') }}"});
        $.fn.bootstrapSwitch.defaults.onColor = 'primary';
        $.fn.bootstrapSwitch.defaults.offColor = 'danger';
        $("[type='checkbox']").bootstrapSwitch();
        $("#category_id").val({{ $article->category_id }});
    </script>
@endsection