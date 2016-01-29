@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid jumbotron">
        <div class="container">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="item active text-center">
                        <h1>obday {{ config('system.title') }}</h1>
                        <p>这里是timeCMS官方站点</p>
                        <p>网站建设中...</p>
                    </div>
                    <div class="item text-center">
                        <h1>时光CMS timeCMS</h1>
                        <p>满足你日常的使用需要</p>
                        <p>系统持续更新中...【<a href="https://git.oschina.net/lakche/timeCMS-free.git" target="_blank">查看</a>】</p>
                    </div>
                    <div class="item text-center">
                        <h1>时光如水 岁月如歌</h1>
                        <p>工作累了吧，进来休息休息吧</p>
                        <p>【<a href="#">开始静思</a>】 【<a href="#">开始缅怀</a>】</p>
                    </div>
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    @foreach($types as $type)
                        <div class="panel panel-primary">
                            <div class="panel-heading">{{ $type->title }}<span class="pull-right"><a href="{{ url('category',$type->id) }}">更多>></a></span></div>
                            <div class="panel-body">
                                @if($articles = $type->articles->sortByDesc('id')->take(9))
                                    <div class="list-group">
                                        @foreach($articles as $article)
                                            <a href="{{ url('article',$article->id) }}" class="list-group-item">{{ $article->title }}<span class="pull-right">{{ $article->updated_at->format('Y-m-d') }}</span></a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-sm-3">
                    @include($theme.'.category.right')
                </div>
            </div>
        </div>
    </div>
@endsection