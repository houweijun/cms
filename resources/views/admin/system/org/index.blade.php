@extends('layouts.common._layout')

@section('title', '渠道管理')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            渠道管理
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/system/org/index')}}
        @endslot

        @slot('search')
            <div class="row">
                @if(roleShow('admin/system/org/add'))
                    <div class="col-sm-1">
                        <div class="form-group">
                            <a class="btn btn-outline btn-primary " href="{{url('admin/system/org/add')}}">
                                <i class="fa fa-plus-square-o"></i>
                                添加渠道
                            </a>
                        </div>
                    </div>
                @endif

                <div class="col-sm-3 ml10">
                    <div class="form-group">
                        <label class="control-label" for="title">渠道名称:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$name}}"
                                   name="name" placeholder="请输入渠道名称"/>
                        </div>
                    </div>
                </div>


                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="status">状态:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <select class="form-control m-b" name="status">
                                <option value="">全部</option>
                                @foreach($status_list as $key=>$value)
                                    <option value="{{$key}}" {{$key == $status ? 'selected':''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/system/orgindex')}}">
                            <i class="fa fa-bank"></i>清空
                        </a>
                    </div>
                </div>

            </div>
        @endslot
    @endcomponent
@endsection

@section('inside-content')
    @component('layouts.components._table')
        {{--定义table id--}}
@section('table-id', 'menus-table')
@slot('thead')
    <tr>
        @if(roleShow('admin/system/orgorder'))
            <th width="90">排序</th>
        @endif
        <th width="60">ID</th>
        <th>名称</th>
        <th>状态</th>
        <th>描述</th>
        <th>创建时间</th>
        @if(roleShow('admin/system/org/add')||roleShow('admin/system/org/edit')||roleShow('admin/system/org/del'))
            <th>操作</th>
        @endif
    </tr>
@endslot
@slot('tbody')
    @if(!empty($name)||!empty($status))
        @foreach($org_data as $v)
            <tr id='node-{{$v->id}}'>
                @if(roleShow('admin/system/newmenu/order'))
                    <td style='padding-left:20px'><input name='sort' type='text' size='3' value='{{$v->sort}}'
                                                         @endif                                    class='input input-order'
                                                         data-id='{{$v->id}}'
                                                         onchange='listOrder(this,"/admin/system/org/order")'/>
                    </td>
                    <td>{{$v->id}}</td>
                    <td>{{$v->name}}</td>
                    <td>
                        @if($v->status =='激活')
                            <span class="btn btn-outline btn-primary">激活</span>
                        @else
                            <span class="btn btn-outline btn-danger">注销</span>
                        @endif
                    </td>
                    <td>{{$v->description}}</td>
                    <td>{{$v->created_at}}</td>
                    @if(roleShow('admin/system/orgadd')||roleShow('admin/system/orgedit')||roleShow('admin/system/org/del'))
                        <td>
                            @if(roleShow('admin/system/org/add'))
                                <a class="btn btn-outline btn-primary"
                                   href="{{url('admin/system/org/add', [$v->id])}}"><i
                                            class="fa fa-plus-square-o"></i>添加子渠道</a>
                            @endif
                            @if(roleShow('admin/system/org/edit'))
                                <a class="btn btn-outline btn-warning"
                                   href="{{url('admin/system/org/edit', [$v->id])}}"><i
                                            class="fa fa-paste"></i>编辑</a>
                            @endif
                            @if(roleShow('admin/system/org/del'))
                                <a class="btn btn-outline btn-danger" href="javascript:;"
                                   onclick="return checkDel('删除渠道','你确定要删除渠道{{$v->name}}吗?','/admin/system/org/del',this)"
                                   data-id="{{$v->id}}"><i class="fa fa-trash-o fa-lg"></i>删除</a>
                            @endif
                        </td>
                    @endif
            </tr>
        @endforeach
    @else
        {!! $org_data !!}
    @endif
@endslot


@if(!empty($name)||!empty($status))
@section('tfoot')
    {!! $org_data->appends(['name' => $name,'status'=>$status])->links() !!}
@endsection
@endif

@endcomponent
@endsection

{{--初始化菜单插件--}}
@section('js')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            Wind.css('treeTable');
            Wind.use('treeTable', function () {
                $("#menus-table").treeTable({
                    indent: 15
                });
            });
        });
    </script>
@endsection