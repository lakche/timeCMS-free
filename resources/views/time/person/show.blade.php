@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-9" id="person">
                    <div class="page-header text-center">
                        <h2>{{ $person->title }} {{ $person->name }}</h2>
                    </div>
                    <div class="page-body">
                        <div class="info">
                            <div class="btn btn-primary btn-lg">性别 <span class="badge">{{ $person->sex ? '女' : '男' }}</span></div>
                            <div class="btn btn-primary btn-lg">从业时间 <span class="badge">{{ $person->age }}</span></div>
                            <div class="btn btn-primary btn-lg">贡献 <span class="badge">{{ $person->point }}</span></div><br>
                            @foreach( json_decode($person->tag) as $tag )<div class="btn btn-primary btn-lg">{{ $tag }}</div>@endforeach
                        </div>
                        {!! $person->text !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    @include($theme.'.category.right')
                </div>
            </div>
        </div>
    </div>
@endsection