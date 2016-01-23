<header>
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false">
                <span class="sr-only">{{ config('system.title') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{ url('/') }}" class="navbar-brand">{{ config('system.title') }}</a>
        </div>
        <nav id="bs-navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="">技术漫谈</a></li>
                <li><a href="">说天道地</a></li>
                <li><a href="">荣誉殿堂</a></li>
                <li><a href="">作品展示</a></li>
                <li><a href="">静思堂</a></li>
                <li><a href="">通天塔</a></li>
              </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (auth()->check())
                    <li><a href="">{{ str_limit(auth()->user()->name,10,'...') }}</a></li>
                    <li><a href="{{ url("auth/logout") }}">退出登录</a></li>
                @else
                    <li><a href="{{ asset('auth/register') }}">注册</a></li>
                    <li><a href="{{ asset('auth/login') }}">登录</a></li>
                @endif
            </ul>
        </nav>
    </div>
</header>