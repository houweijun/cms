@extends('layouts.common._base')


@section('content')
    <div id="wrapper">

        {{--左侧导航栏--}}
        @include('layouts.common._left_nav')

        {{--右侧加载内容--}}
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    {{--右侧缩小导航按钮--}}
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:;"><i
                                    class="fa fa-bars"></i> </a>
                    </div>

                    {{--右侧导航栏 首页 修改密码退出 面板--}}
                    @include('layouts.common._right_top_nav')

                </nav>
            </div>

            {{--顶部面板介绍 animated--}}
            @yield('pannel-about')

            {{--中间内容--}}
            <div class="main-content">
                <div class="wrapper wrapper-content animated fadeInRight ecommerce">

                    {{--搜索栏--}}
                    @yield('search')

                    {{--添加面板--}}
                    @yield('pannel-add')


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox">
                                <div class="ibox-content  vh100">
                                    {{--里面内容--}}
                                    @yield('inside-content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--底部版权--}}
            @component('layouts.common._footer')
            @slot('copyright')
            &copy; vastlee版权所有
            @endslot
            @endcomponent
        </div>
    </div>
@endsection
