@extends('layouts.common._layout')

@section('title', '添加参数')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            添加参数
        @endslot
    @endcomponent
@endsection


@section('inside-content')

    <fieldset class="form-horizontal">
        <form class="m-t js-ajax-form" role="form" action="{{url('admin/system/parameter/add')}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">参数名称：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="text" class="form-control input input-big border-left" placeholder="请输入参数名称"
                           name="name"
                           data-validate="required:请输入参数名称"/>
                    <div class="tips"></div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">参数内容：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <textarea class="form-control input input-big border-left" rows="5" placeholder="请输入参数内容"
                              name="options"
                              data-validate="required:请输入参数内容"
                    ></textarea>
                    <div class="tips"></div>
                </div>
                <div class="fw_bt pull-center mt130">
                    <span class="form-required">强制格式:</span>
                    <span class="bt_span">1:运营商, 2:渠道商, 3:供应商</span>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">描述:</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <textarea class="form-control input input-big" rows="5" placeholder="请输入描述"
                              name="description"

                    ></textarea>
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

