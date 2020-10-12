@extends('layouts.common._base')
@section('title', '404错误页面')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/404/reset.css"/>
    <link rel="stylesheet" href="/css/404/404.css">
@endsection

@section('content')
    <div class="auto">
        <div class="container">
            <div class="settings">
                <i class="icon"></i>
                <h4>很抱歉！没有找到您要访问的页面！</h4>
                <p><span id="num">5</span> 秒后将自动跳转到首页</p>
                <div>
                    <a href="/admin/newlogin" title="返回后台登录页">返回台登录页</a>
                    <a href="javascript:;" title="上一步" id="reload-btn">上一步</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script>
        //倒计时跳转到首页的js代码
        var $_num = $("#num");
        var num = parseInt($_num.html());
        var numId = setInterval(function () {
            num--;
            $_num.html(num);
            if (num === 0) {
                //跳转地址写在这里
                 window.history.go(-1);
                //window.location.href = "/admin/newlogin";
            }
        }, 1000);
        //返回按钮单击事件
        var reloadPage = $("#reload-btn");
        reloadPage.click(function (e) {
            window.history.back();
        });
    </script>
@endsection

