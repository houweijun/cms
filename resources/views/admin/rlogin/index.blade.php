@extends('layouts.common._layout')

@section('title', '后台管理首页')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            后台首页
        @endslot
    @endcomponent
@endsection

{{--后台首页版图--}}
@section('inside-content')
    <div class="admin-home">
        <div class="font admin-home-word text-center">欢迎登录</div>
        <div class="font admin-home-word text-center">FINANCIAL 后台管理系统</div>
    </div>
@endsection