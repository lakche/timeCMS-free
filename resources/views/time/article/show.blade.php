@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-9" id="article">
                    <div class="page-header text-center">
                        <h2>{{ $article->title }}</h2>
                        <span>发布时间：{{ $article->updated_at->format('Y-m-d') }} 浏览量：{{ $article->views }}</span>
                    </div>
                    <div class="page-body">
                        {!! $article->description !!}
                    </div>
                    <div class="page-footer clearfix">
                        <p>相关阅读：</p>
                        @if($articles = $type->articles->sortByDesc('id')->take(6))
                            @foreach($articles as $article)
                            <div class="col-sm-6">
                                <a href="{{ url('article',$article->id) }}">{{ $article->title }}</a>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-sm-3">
                    @include($theme.'.category.right')
                </div>
            </div>
        </div>
    </div>
@endsection