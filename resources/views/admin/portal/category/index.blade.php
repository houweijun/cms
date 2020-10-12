@extends('layouts.common._layout')

@section('title', '文章分类列表')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            文章分类管理
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/portal/category/index')}}
        @endslot

        @slot('search')
            <div class="row">
                @if(roleShow('admin/portal/category/add'))
                    <div class="col-sm-1">
                        <div class="form-group">
                            <a class="btn btn-outline btn-primary " href="{{url('admin/portal/category/add')}}">
                                <i class="fa fa-plus-square-o"></i>
                                添加文章分类
                            </a>
                        </div>
                    </div>
                @endif

                <div class="col-sm-3 ml10">
                    <div class="form-group">
                        <label class="control-label" for="title">文章分类名称:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$name}}"
                                   name="name" placeholder="请输入菜单名称"/>
                        </div>
                    </div>
                </div>


                <div class="col-sm-2">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/portal/category/index')}}">
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
                <th>分类名称</th>
                <th>描述</th>
                <th>创建时间</th>
                @if(roleShow('admin/system/newmenu/add')||roleShow('admin/system/newmenu/edit')||roleShow('admin/system/newmenu/del'))
                    <th>操作</th>
                @endif
            </tr>
        @endslot
        @slot('tbody')
            @if(!empty($name))
                @foreach($category as $v)
                    <tr id='node-{{$v->id}}'>
                        @if(roleShow('admin/system/newmenu/order'))
                            <td style='padding-left:20px'><input name='sort' type='text' size='3' value='{{$v->sort}}'
                                                                 @endif                                    class='input input-order'
                                                                 data-id='{{$v->id}}'
                                                                 onchange='listOrder(this,"/admin/portal/category/order")'/>
                            </td>
                            <td>{{$v->id}}</td>
                            <td>{{$v->name}}</td>
                            <td>{{$v->url}}</td>
                            <td>{{$v->description}}</td>
                            <td>{{$v->created_at}}</td>
                            @if(roleShow('admin/portal/category/add')||roleShow('admin/portal/category/edit')||roleShow('admin/portal/category/del'))
                                <td>
                                    @if(roleShow('admin/portal/category/add'))
                                        <a class="btn btn-outline btn-primary"
                                           href="{{url('admin/portal/category/add', [$v->id])}}"><i
                                                    class="fa fa-plus-square-o"></i>添加子分类</a>
                                    @endif
                                    @if(roleShow('admin/portal/category/edit'))
                                        <a class="btn btn-outline btn-warning"
                                           href="{{url('admin/portal/category/edit', [$v->id])}}"><i
                                                    class="fa fa-paste"></i>编辑</a>
                                    @endif
                                    @if(roleShow('admin/portal/category/del'))
                                        <a class="btn btn-outline btn-danger" href="javascript:;"
                                           onclick="return checkDel('删除分类','你确定要删除分类{{$v->name}}吗?','/admin/portal/category/del',this)"
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
                {!! $category->appends(['name' => $name])->links() !!}
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