@extends($theme.'.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="page-header text-center">
                        <h2>{{ $project->title }}</h2>
                        <span>项目分类：<a href="{{ url('project/type',$project->category_id) }}">{{ $project->category->title }}</a> 发布时间：{{ $project->updated_at->format('Y-m-d') }} 浏览量：{{ $project->views }}</span>
                    </div>
                    <div class="page-body" id="project">
                        <div class="info">
                            <div class="btn btn-primary">项目费用 <span class="badge">{{ $project->cost }}元</span></div>
                            <div class="btn btn-primary">项目周期 <span class="badge">{{ $project->period }}天</span></div><br>
                            @if($project->tag != '[""]')
                                <div class="btn btn-default">特点：</div>@foreach( json_decode($project->tag) as $tag )<div class="btn btn-primary">{{ $tag }}</div>@endforeach
                            @endif
                        </div>
                        @if($project->cover != '')
                            <div class="cover text-center"><img src="{{ $project->cover }}" alt="{{ $project->title }}"></div>
                        @endif
                        {!! $project->text !!}
                    </div>
                    <div class="panel panel-primary" id="person">
                        <div class="panel-heading">参与人员</div>
                        <div class="panel-body">
                            @if($persons = $project->persons())
                                @foreach($persons as $person)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">
                                            <div class="pic">
                                                <p><a href="{{ url('person',$person->id) }}">
                                                        <img src="{{ $person->getHead() }}" alt="{{ $person->name }}">
                                                    </a>
                                                </p>
                                                <span><s></s><div>{{ $person->point }}</div></span>
                                            </div>
                                            <div class="caption text-center">
                                                <a href="{{ url('person',$person->id) }}"><h3>{{ $person->name }}</h3></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-primary speed">
                        <div class="panel-heading">项目进度</div>
                        <div class="panel-body">
                            @if($project->speed)
                                @foreach(json_decode($project->speed) as $speed)
                                    <p><span>{{ $speed->time }}</span> {{ $speed->event }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="page-footer clearfix">
                        <p>相关阅读：</p>
                        @if($projects = $type->projects->sortByDesc('id')->take(6))
                            @foreach($projects as $project)
                            <div class="col-sm-6">
                                <a href="{{ url('project',$project->id) }}">{{ $project->title }}</a>
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