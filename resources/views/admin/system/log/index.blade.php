@extends('layouts.common._layout')

@section('title', '日志列表')

@section('pannel-about')
    @component('layouts.components._pannel_about')
        @slot('title')
            日志列表
        @endslot
    @endcomponent
@endsection

@section('search')
    @component('layouts.components._search')
        @slot('url')
            {{url('admin/system/log/index')}}
        @endslot

        @slot('search')

            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="date_added">用户:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <select class="form-control m-b" name="user_id">
                                <option value="">所有</option>
                                @foreach($user_list as $user)
                                    <option value="{{$user->id}}" {{$user->id == $user_id ? 'selected' : ''}} >{{$user->username}}
                                        -{{$user->nickname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class=" col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="date_added">开始日期</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control default-read start_date" value="{{$start_time}}"
                                   name="start_time" placeholder="请选择开始日期" readonly/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="date_added">结束日期</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control default-read end_date" value="{{$end_time}}"
                                   name="end_time" placeholder="请选择结束日期" readonly/>

                        </div>
                    </div>
                </div>


                <div class=" col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="date_added">IP地址:</label>
                        <div class="input-group date">
                            <span class="input-group-addon border-none"></span>
                            <input type="text" class="form-control" value="{{$ip}}"
                                   name="ip" placeholder="请输入IP地址"/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">

                        <button type="submit" class="btn  btn-primary search-btn">
                            <i class="fa fa-search"></i>搜索
                        </button>
                        <a class="btn btn-default search-btn" href="{{url('admin/system/logindex')}}">
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
                <th>用户</th>
                <th>访问时间</th>
                <th>访问IP</th>
                <th>访问路径</th>
                <th>访问终端</th>
                {{--@if(roleShow('admin/system/logdel'))--}}
                    {{--<th>操作</th>--}}
                {{--@endif--}}
            </tr>
        @endslot
        @slot('tbody')
            @foreach($data as $log)
                <tr>
                    <td>{{$log->id}}</td>
                    <td>{{$log->user_id}}</td>
                    <td>{{date('Y-m-d H:i:s',$log->login_at)}}</td>
                    <td>{{$log->login_ip}}</td>
                    <td>{{$log->url}}</td>
                    <td>{{$log->agent}}</td>
                    {{--@if(roleShow('admin/system/logdel'))--}}
                        {{--<td>--}}
                            {{--<a class="js-ajax-status btn btn-outline btn-danger"--}}
                               {{--href="/admin/system/logdel/{{$log->id}}"><i class="fa fa-trash-o fa-lg"></i>删除</a>--}}
                        {{--</td>--}}
                    {{--@endif--}}
                </tr>
            @endforeach
        @endslot
@section('tfoot')
    {!! $data->appends(['user_id'=>$user_id,'start_time' => $start_time,'end_time' => $end_time,'ip'=>$ip])->links() !!}
@endsection
@endcomponent
@endsection