@extends('layouts.common._layout')

@section('title', '管理员管理')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            管理员管理
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/system/adminuser/index')}}
        @endslot

        @slot('search')
            <div class="row">

                @if(roleShow('admin/system/adminuser/add'))
                    <div class="col-sm-1">
                        <div class="form-group">
                            <a class="btn btn-outline btn-primary" href="{{url('admin/system/adminuser/add')}}">
                                <i class="fa fa-plus-square-o"></i>增加管理员
                            </a>
                        </div>
                    </div>
                @endif

                <div class=" col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="date_added">管理员名称</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$username}}"
                                   name="username" placeholder="请输入管理员名称"/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="date_added">权限</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <select class="form-control m-b" name="authority_id">
                                <option value="">全部</option>
                                @foreach($roleData as $v)
                                    <option value="{{$v['id']}}" {{$authority_id == $v['id']? 'selected':''}}>{{$v['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                @if(roleShow('admin/system/adminuser/status'))
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="date_added">状态</label>
                            <div class="input-group date">
                                <span class="input-group-addon border-none"></span>
                                <select class="form-control m-b" name="status">
                                    <option value="">全部</option>
                                    <option value="1" {{$status == 1? 'selected':''}}>开启</option>
                                    <option value="2" {{$status == 2? 'selected':''}}>关闭</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-sm-3">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/system/adminuser/index')}}">
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
                <th>管理员账号</th>
                <th>权限</th>
                @if(roleShow('admin/system/adminuser/status'))
                    <th>状态</th>
                @endif
                <th>备注</th>
                <th>隶属渠道</th>
                @if(roleShow('admin/system/adminuser/edit'))
                    <th>管理员管理</th>
                @endif

                @if(roleShow('admin/system/adminuser/del'))
                    <th>删除管理员</th>
                @endif
            </tr>
        @endslot
        @slot('tbody')
            @foreach($data as $v)
                <tr>
                    <td>
                        {{$v->username}}
                    </td>

                    <td>
                        {{get_props_value($roleData,$v->authority_id)}}
                    </td>

                    @if(roleShow('admin/system/adminuser/status'))
                        <td>
                            <a href="/admin/system/adminuser/status/{{$v->id}}/{{$v->status == '激活'  ? 1 : 2}}"
                               class="js-ajax-status btn btn-outline btn-primary">
                                {!! $v->status == '激活'  ? '开启' : '关闭' !!}
                            </a>
                        </td>
                    @endif

                    <td>{{$v->description}}</td>
                    <td>{{$v->org_id}}</td>

                    @if(roleShow('admin/system/adminuser/edit'))
                        <td>
                            <a class="btn btn-outline btn-warning" href="/admin/system/adminuser/edit/{{$v->id}}">
                                <i class="fa fa-paste"></i>编辑
                            </a>
                        </td>
                    @endif

                    @if(roleShow('admin/system/adminuser/del'))
                        <td>
                            <a class="btn btn-outline btn-danger" href="javascript:;"
                               onclick="return checkDel('删除管理员{{$v->username}}','你确定要删除管理员{{$v->username}}吗?','/admin/system/adminuser/del',this)"
                               data-id="{{$v->id}}">
                                <i class="fa fa-trash-o fa-lg"></i>删除
                            </a>
                        </td>
                    @endif

                </tr>
            @endforeach
        @endslot

@section('tfoot')
    {!! $data->appends(['username' => $username,'authority_id'=>$authority_id,'status' => $status])->links() !!}
@endsection
@endcomponent
@endsection