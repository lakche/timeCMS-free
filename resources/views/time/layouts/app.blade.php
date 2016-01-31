<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $title or $system['title'] }}@if($system['subtitle']!='') - {{ $system['subtitle'] }} @endif</title>
    <meta name="keywords" content="{{ $keywords or $system['keywords'] }}" />
    <meta name="description" content="{{ $description or $system['description'] }}" />
    <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{{ asset($theme.'/css/css.css') }}">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
@include($theme.'.layouts/_nav')
@yield('content')
@include($theme.'.layouts/_footer')
@include($theme.'.layouts/_pop')
</body>
</html>
