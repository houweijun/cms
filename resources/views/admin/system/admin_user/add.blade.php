@extends('layouts.common._layout')

@section('title', '添加管理员')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            添加管理员
        @endslot
    @endcomponent
@endsection


@section('inside-content')

    <fieldset class="form-horizontal">
        <form class="m-t js-ajax-form" role="form" action="{{url('admin/system/adminuser/add')}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">管理员账号：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="text" class="form-control input input-big border-left" placeholder="请输入管理员账号"
                           name="username"
                           data-validate="required:请输入管理员账号"/>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">填写备注：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <textarea class="form-control input input-big" rows="5" placeholder="请输入备注"
                              name="description"
                              ></textarea>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">选择模板：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <select class="form-control m-b border-left" name="authority_id">
                        @foreach($roleData as $v)
                            <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group" id="offset">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">选择渠道：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <select class="form-control m-b border-left" name="org_id">
                        {!! $select_org !!}
                    </select>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">登录平台：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <select class="form-control m-b border-left" name="is_system">
                        <option value="1">总平台</option>
                        <option value="2">渠道平台</option>
                    </select>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">管理员密码：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="text" class="form-control input input-big border-left" placeholder="请输入管理员密码"
                           name="password"
                           data-validate="required:请输入管理员密码"/>
                    <div class="tips"></div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">确认密码:</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="text" class="form-control input input-big border-left" placeholder="请输入确认密码"
                           name="repassword"
                           data-validate="required:请输入确认密码"/>
                    <div class="tips"></div>
                </div>
            </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        <span class="text-inherit">状态：</span>
                    </label>
                    <div class="col-sm-10 col-md-6 field">
                        <select class="form-control m-b border-left" name="status">
                            <option value="1">开启</option>
                            <option value="2">关闭</option>
                        </select>
                        <div class="tips"></div>
                    </div>
                </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">
                </label>
                <div class="col-sm-10 sm-offset-2">
                    <button type="submit" class="btn btn-primary  m-b js-ajax-submit">
                        提交
                    </button>
                    <button type="reset" class="btn btn-default  m-b">重置</button>
                    <a class="btn btn-warning m-b" href="javascript:history.back(-1);">返回</a>
                </div>
            </div>


        </form>
    </fieldset>

@endsection

