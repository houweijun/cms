@extends('layouts.common._layout')

@section('title', '权限管理-新增')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            权限管理新增
        @endslot
    @endcomponent
@endsection


@section('inside-content')

    <fieldset class="form-horizontal">
        <form class="m-t js-ajax-form" role="form" action="{{url('admin/system/authority/add')}}" method="post">
            {{ csrf_field() }}

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">模板名称：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <input type="text" class="form-control input input-big border-left" placeholder="请输入模板名称" name="name"
                           data-validate="required:请输入模板名称"/>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">填写备注：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <textarea class="form-control input input-big" placeholder="" name="description" rows="3"
                    ></textarea>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">
                    <span class="text-inherit">权限选择：</span>
                </label>
                <div class="col-sm-10 col-md-6 field">
                    <table class="table table-bordered" id="authrule-tree">
                        <tbody>
                        {!! $category !!}
                        </tbody>
                    </table>
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

{{--定义js选择菜单--}}
@section('js')
    @parent

    <script type="text/javascript">
        $(document).ready(function () {
            Wind.css('treeTable');
            Wind.use('treeTable', function () {
                $("#authrule-tree").treeTable({
                    indent: 20
                });
            });
        });

        function checknode(obj) {
            var chk = $("input[type='checkbox']");
            var count = chk.length;

            var num = chk.index(obj);
            var level_top = level_bottom = chk.eq(num).attr('level');
            for (var i = num; i >= 0; i--) {
                var le = chk.eq(i).attr('level');
                if (le <level_top) {
                    chk.eq(i).prop("checked", true);
                    var level_top = level_top - 1;
                }
            }
            for (var j = num + 1; j < count; j++) {
                var le = chk.eq(j).attr('level');
                if (chk.eq(num).prop("checked")) {

                    if (le > level_bottom){
                        chk.eq(j).prop("checked", true);
                    }
                    else if (le == level_bottom){
                        break;
                    }
                } else {
                    if (le >level_bottom){
                        chk.eq(j).prop("checked", false);
                    }else if(le == level_bottom){
                        break;
                    }
                }
            }
        }
    </script>
@endsection

