@extends('layouts.common._base')
@section('title', '用户后台登录')
@section('gray-bg',"class=gray-bg")
@section('content')
    <div class="outside-log">
        <div class="center-vertical middle-box text-center loginscreen animated fadeInDown">
            <div class="head-log">
                <img src="/img/login.png"/>
            </div>
            <form class="m-t js-ajax-form" role="form" action="{{url('admin/login')}}" method="post"
                  >
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="field field-icon-right">
                        <input type="text" class="form-control input input-big" name="username"
                               value="{{old('username')}}" placeholder="登录账号"
                               data-validate="required:请填写账号"/>
                        <span class="icon icon-user margin-small"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="field field-icon-right">
                        <input type="password" class="form-control input input-big" name="password"
                               value="{{old('password')}}" placeholder="登录密码"
                               data-validate="required:请填写密码"/>
                        <span class="icon icon-key margin-small"></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b log-btn js-ajax-submit">
                    登&nbsp;&nbsp;录
                </button>
            </form>
        </div>
        <div class="company-info">&copyvastlee版权所有</div>
    </div>
@endsection