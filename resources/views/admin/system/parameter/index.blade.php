@extends('layouts.common._layout')

@section('title', '参数列表')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            添加参数
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/system/parameter/index')}}
        @endslot

        @slot('search')
            <div class="row">

                @if(roleShow('admin/system/parameter/add'))
                    <div class="col-sm-1">
                        <div class="form-group">
                            <a class="btn btn-outline btn-primary " href="{{url('admin/system/parameter/add')}}">
                                <i class="fa fa-plus-square-o"></i>添加参数
                            </a>
                        </div>
                    </div>
                @endif

                <div class=" col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="date_added">参数名称:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$name}}"
                                   name="name" placeholder="请输入参数名称"/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2 ">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/system/parameterindex')}}">
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
                <th>ID</th>
                <th>名称</th>
                <th>描述</th>
                <th>字段列表</th>
                <th>创建时间</th>
                @if(roleShow('admin/system/parameter/edit'))
                    <th>操作</th>
                @endif
            </tr>
        @endslot
        @slot('tbody')
            @foreach($data as $parameter)
                <tr>
                    <td> {{$parameter->id}}</td>
                    <td><span class="text-success">{{$parameter->name}}</span></td>
                    <td>{{$parameter->description}}</td>
                    <td>
                        @foreach ($parameter->options as $key=>$value){{$key.':'}}{{$value.','}}@endforeach

                    </td>
                    <td>{{$parameter->created_at}}</td>
                    @if(roleShow('admin/system/parameter/edit'))
                        <td>

                            <a class="btn btn-outline btn-warning"
                               href="/admin/system/parameter/edit/{{$parameter->id}}"><i class="fa fa-paste"></i>修改</a>

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