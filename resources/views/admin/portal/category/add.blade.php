@extends('layouts.common._layout')

@section('title', '文章分类列表')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            添加文章分类
        @endslot
    @endcomponent
@endsection


@section('inside-content')

    <fieldset class="form-horizontal">
        <form class="m-t js-ajax-form" role="form" action="{{url('admin/portal/category/add')}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">所属分类:</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <select class="form-control m-b border-left" name="parent_id">
                        <option value="0">--作为一级分类--</option>
                        {!! $select_category !!}
                    </select>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">分类名称：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="text" class="form-control input input-big border-left" placeholder="请输入分类名称"
                           name="name"
                           data-validate="required:请输入分类名称"/>
                    <div class="tips"></div>
                </div>
            </div>




            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">分类描述：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <textarea class="form-control input input-big" rows="5" placeholder="请输入分类描述"
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

