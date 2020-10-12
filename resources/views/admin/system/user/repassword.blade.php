@extends('layouts.common._layout')

@section('title', '修改用户密码')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            修改用户密码
        @endslot
    @endcomponent
@endsection


@section('inside-content')

    <fieldset class="form-horizontal">
        <form class="m-t js-ajax-form" role="form" action="/admin/system/repassword/{{$id}}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">旧密码：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="password" class="form-control input input-big border-left" placeholder="请输入旧密码" name="old_password"
                           data-validate="required:请输入旧密码"/>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">新密码：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="password" class="form-control input input-big border-left" placeholder="请输入新密码" name="password"
                           data-validate="required:请输入新密码"/>
                    <div class="tips"></div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">确认密码：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="password" class="form-control input input-big border-left" placeholder="请输入确认密码" name="re_password"
                           data-validate="required:请输入确认密码"/>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">

                </label>
                <div class="col-sm-10 sm-offset-2">
                    <button type="submit" class="btn btn-primary   m-b js-ajax-submit">
                        提交
                    </button>
                    <button type="reset" class="btn btn-default  m-b">重置</button>
                    <a class="btn btn-warning m-b" href="javascript:history.back(-1);">返回</a>
                </div>
            </div>


        </form>
    </fieldset>


@endsection