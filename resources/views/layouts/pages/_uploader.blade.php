<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--css样式 引入--}}
    @section('css')
        <script src="/js/jquery.js"></script>
    @show
</head>

<body @yield('gray-bg') data-code="{{session('code')}}" data-url_id="{{session('parent_id')}}" data-active="{{session('url_address')}}">

{{--引入主题内容--}}
@yield('content')

{{--js引入js文件--}}
@section('js')
@show

</body>

</html>
