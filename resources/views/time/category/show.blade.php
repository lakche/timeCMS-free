@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{ $type->title }}</div>
                        <div class="panel-body">
                            <div class="list-group">
                            @foreach($articles as $article)
                                    <a href="{{ url('article',$article->id) }}" class="list-group-item">{{ $article->title }}<span class="pull-right">{{ $article->updated_at->format('Y-m-d') }}</span></a>
                            @endforeach
                            </div>
                        </div>
                        <div class="panel-footer text-center">
                            {!! $articles->render() !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    @include($theme.'.category.right')
                </div>
            </div>
        </div>
    </div>
@endsection