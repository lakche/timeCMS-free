@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-9" id="article">
                    <div class="page-header text-center">
                        <h2>{{ $person->name }}</h2>
                    </div>
                    <div class="page-body">
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