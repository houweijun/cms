@extends('layouts.common._layout')

@section('title', '权限管理')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            权限管理
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/system/authority/index')}}
        @endslot

        @slot('search')
            <div class="row">

                @if(roleShow('admin/system/authorityadd'))
                    <div class="col-sm-1">
                        <div class="form-group">
                            <a class="btn btn-outline btn-primary " href="{{url('admin/system/authority/add')}}">
                                <i class="fa fa-plus-square-o"></i>添加权限
                            </a>
                        </div>
                    </div>
                @endif

                <div class=" col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="date_added">权限名词</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$name}}"
                                   name="name" placeholder="请输入权限名词"/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2 ">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/system/authorityindex')}}">
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
        @slot('thead')
            <tr>
                <th>权限编号</th>
                <th>权限名词</th>
                <th>备注</th>

                @if(roleShow('admin/system/authority/edit'))
                    <th>管理操作</th>
                @endif

                @if(roleShow('admin/system/authority/del'))
                    <th>删除权限</th>
                @endif
            </tr>
        @endslot
        @slot('tbody')
            @foreach($data as $v)
                <tr>

                    <td>
                        {{$v->id}}
                    </td>

                    <td>
                        {{$v->name}}
                    </td>

                    <td>{{$v->description}}</td>

                    @if(roleShow('admin/system/authority/edit'))
                        <td>
                            <a class="btn btn-outline btn-warning" href="/admin/system/authority/edit/{{$v->id}}">
                                <i class="fa fa-paste"></i>编辑
                            </a>
                        </td>
                    @endif

                    @if(roleShow('admin/system/authority/del'))
                        <td>
                            <a class="btn btn-outline btn-danger" href="javascript:;"
                               onclick="return checkDel('删除权限{{$v->name}}','你确定要删除该权限{{$v->name}}吗?','/admin/system/authority/del',this)"
                               data-id="{{$v->id}}">
                                <i class="fa fa-trash-o fa-lg"></i>删除
                            </a>
                        </td>
                    @endif

                </tr>
            @endforeach
        @endslot

@section('tfoot')
    {!! $data->appends(['name' => $name])->links() !!}
@endsection
@endcomponent
@endsection