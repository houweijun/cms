<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">--}}
    <title>@yield('title')-financial后台</title>
    <meta name="description" content="financial管理后台">
    <meta name="keywords" content="financial管理后台">
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <!-- HTML5 shim for IE8 support of HTML5 elements -->

    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="/js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/favicon.ico" type="image/gif">
    {{--css样式 引入--}}
    @section('css')
        <link href="/css/bootstrap.min.css?v=20190313" rel="stylesheet">
        <link href="/js/plugins/layui/css/layui.css?v=20190313" rel="stylesheet">
        <link href="/font-awesome/css/font-awesome.css?v=20190313" rel="stylesheet">
        <link href="/css/animate.css?v=20190313" rel="stylesheet">
        <link href="/css/plugins/toastr/toastr.min.css?v=20190313" rel="stylesheet">
        <link href="/js/plugins/gritter/jquery.gritter.css?v=20190313" rel="stylesheet">
        <link href="/css/pintuer.css?v=20190313" rel="stylesheet">
        <link href="/css/style.css?v=20190313" rel="stylesheet">
        <link href="/css/common.css?v=20190313" rel="stylesheet">
        <script src="/js/jquery.js?v=20190313"></script>
    @show
</head>

<body @yield('gray-bg') data-code="{{session('code')}}" data-url_id="{{session('parent_id')}}"
      data-active="{{session('url_address')}}">

{{--引入主题内容--}}
@yield('content')

{{--js引入js文件--}}
@section('js')
    <script src="/js/bootstrap.min.js?v=20190313"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js?v=20190313"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js?v=20190313"></script>

    <!-- Flot -->
    <script src="/js/plugins/flot/jquery.flot.js?v=20190313"></script>
    <script src="/js/plugins/flot/jquery.flot.tooltip.min.js?v=20190313"></script>
    <script src="/js/plugins/flot/jquery.flot.spline.js?v=20190313"></script>
    <script src="/js/plugins/flot/jquery.flot.resize.js?v=20190313"></script>
    <script src="/js/plugins/flot/jquery.flot.pie.js?v=20190313"></script>

    <!-- Peity -->
    <script src="/js/plugins/peity/jquery.peity.min.js?v=20190313"></script>

    <!-- Custom and plugin javascript -->
    <script src="/js/inspinia.js?v=20190313"></script>
    <script src="/js/plugins/pace/pace.min.js?v=20190313"></script>

    <!-- jQuery UI -->
    <script src="/js/plugins/jquery-ui/jquery-ui.min.js?v=20190313"></script>

    <!-- GITTER -->
    <script src="/js/plugins/gritter/jquery.gritter.min.js?v=20190313"></script>

    <!-- Sparkline -->
    <script src="/js/plugins/sparkline/jquery.sparkline.min.js?v=20190313"></script>

    <script src="/js/pintuer.js?v=20190313"></script>
    <script src="/js/plugins/layer/layer.js?v=20190313"></script>
    <script src="/js/wind.js?v=20190313"></script>
    <script src="/js/common.js?v=20190313"></script>
@show

</body>

</html>
