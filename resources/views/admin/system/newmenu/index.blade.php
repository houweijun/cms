@extends('layouts.common._layout')

@section('title', '菜单列表')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            菜单管理
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/system/newmenu/index')}}
        @endslot

        @slot('search')
            <div class="row">
                @if(roleShow('admin/system/newmenu/add'))
                    <div class="col-sm-1">
                        <div class="form-group">
                            <a class="btn btn-outline btn-primary " href="{{url('admin/system/newmenu/add')}}">
                                <i class="fa fa-plus-square-o"></i>
                                添加菜单
                            </a>
                        </div>
                    </div>
                @endif

                <div class="col-sm-3 ml10">
                    <div class="form-group">
                        <label class="control-label" for="title">菜单名称:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$title}}"
                                   name="title" placeholder="请输入菜单名称"/>
                        </div>
                    </div>
                </div>


                <div class="col-sm-2">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/system/newmenuindex')}}">
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
                @if(roleShow('admin/system/newmenu/order'))
                    <th width="90">排序</th>
                @endif
                <th width="60">ID</th>
                <th>菜单名称</th>
                <th>访问地址</th>
                <th>描述</th>
                <th>创建时间</th>
                @if(roleShow('admin/system/newmenu/add')||roleShow('admin/system/newmenu/edit')||roleShow('admin/system/newmenu/del'))
                    <th>操作</th>
                @endif
            </tr>
        @endslot
        @slot('tbody')
            @if(!empty($title))
                @foreach($category as $v)
                    <tr id='node-{{$v->id}}'>
                        @if(roleShow('admin/system/newmenu/order'))
                            <td style='padding-left:20px'><input name='sort' type='text' size='3' value='{{$v->sort}}'
                                                                 @endif                                    class='input input-order'
                                                                 data-id='{{$v->id}}'
                                                                 onchange='listOrder(this,"/admin/system/newmenu/order")'/>
                            </td>
                            <td>{{$v->id}}</td>
                            <td>{{$v->title}}</td>
                            <td>{{$v->url}}</td>
                            <td>{{$v->description}}</td>
                            <td>{{$v->created_at}}</td>
                            @if(roleShow('admin/system/newmenu/add')||roleShow('admin/system/newmenu/edit')||roleShow('admin/system/newmenu/del'))
                                <td>
                                    @if(roleShow('admin/system/newmenu/add'))
                                        <a class="btn btn-outline btn-primary"
                                           href="{{url('admin/system/newmenuadd', [$v->id])}}"><i
                                                    class="fa fa-plus-square-o"></i>添加子菜单</a>
                                    @endif
                                    @if(roleShow('admin/system/newmenuedit'))
                                        <a class="btn btn-outline btn-warning"
                                           href="{{url('admin/system/newmenu/edit', [$v->id])}}"><i
                                                    class="fa fa-paste"></i>编辑</a>
                                    @endif
                                    @if(roleShow('admin/system/newmenu/del'))
                                        <a class="btn btn-outline btn-danger" href="javascript:;"
                                           onclick="return checkDel('删除菜单','你确定要删除菜单{{$v->title}}吗?','/admin/system/newmenu/del',this)"
                                           data-id="{{$v->id}}"><i class="fa fa-trash-o fa-lg"></i>删除</a>
                                    @endif
                                </td>
                            @endif
                    </tr>
                @endforeach
            @else
                {!! $category !!}
            @endif
        @endslot


        @if(!empty($title))
            @section('tfoot')
                {!! $category->appends(['title' => $title])->links() !!}
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