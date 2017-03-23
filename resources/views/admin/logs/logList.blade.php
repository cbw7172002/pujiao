@extends('layouts.layoutAdmin')
@section('content')
    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>

                <li>
                    <a href="{{url('/admin/logs/logList')}}">日志管理</a>
                </li>
                <li class="active">日志列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                    <input type="text" style="width:180px;padding-left:5px; padding-right: 5px;background:#fff url('/admin/image/2.png') no-repeat 153px 3px" name="beginTime"  placeholder="开始时间" class="col-xs-10 col-sm-5 " value="{{$search['beginTime'] ? $search['beginTime'] : ''}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                    <input type="text" style="width:180px;padding-left:5px;padding-right: 5px;margin-left:5px;background:#fff url('/admin/image/2.png') no-repeat 153px 3px" name="endTime"  placeholder="结束时间" class="col-xs-10 col-sm-5 " value="{{$search['endTime'] ? $search['endTime'] : ''}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;" />

                    <select name="type" id="searchType" class="searchtype input-select" style="margin-left:5px;">
                        <option value="0" {{$search['type'] == 0 ? 'selected':''}}>用户名</option>
                        {{--<option value="1" {{$search['type'] == 2 ? 'selected':''}}>时间筛选</option>--}}
                        <option value="1" {{$search['type'] == 1 ? 'selected':''}}>全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="display: block;" class="input-icon" id="search1">
                            <input type="text" placeholder="Search ..." style="margin-left:8px" name="search" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon" style="margin-left:8px"></i>
                            <input type="text" style="padding-left:10px; padding-right: 5px;" value="{{$search['time'] ?:''}}" name="time"  class="nav-search-input" placeholder="请选择月份 (可不选)"  value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;padding-left: 8px;" type="submit" value="搜索" />
                        </span>
                    </span>
                </form>

            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    日志管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        日志列表
                    </small>
                </h1>

            </div><!-- /.page-header -->

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if(count($errors))
                @if(is_object($errors))
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @else
                    <div class="alert alert-danger">{{$errors}}</div>
                @endif

            @endif


            <div class="row">

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                            <form action="{{url('admin/logs/multiDelete/'.$tableName.'/'.($search['time']?:''))}}" method="post" class="form-search" onsubmit="return confirm('确定要删除该日志记录？');">
                                {{csrf_field()}}
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover center" style="table-layout:fixed">
                                    <thead>
                                    <tr>
                                        @if(count($data) > 0)
                                        <input type="submit"  style="display:inline-block;width:80px;height:30px;line-height: 30px;text-align:center;cursor: pointer;font-size:13px;margin-bottom:10px;letter-spacing: 2px;border:none;background:#209EEA; color:#fff;" value="多删除">
                                        @endif
                                        {{--表单所需隐藏域--}}
                                        <input type="hidden" name="currentPage" value="{{$currentPage}}">
                                        <input type="hidden" name="lastPage" value="{{$lastPage}}">
                                        <input type="hidden" name="count" value="{{$count}}">

                                        <th class="center" style="width:5%;"><input type="checkbox" name="multiple"></th>
                                        <th class="center" style="width:8%;">日志ID</th>
                                        <th class="center" style="width:10%;">用户名</th>
                                        <th class="center" style="width:8%;">操作类型</th>
                                        <th class="center" style="width:30%;">操作动作</th>
                                        <th class="center">客户端IP</th>
                                        <th class="center">创建时间</th>
                                        <th style="width:8%;">操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $value)
                                        <tr>
                                            <td class="center"><input type="checkbox" name="check[]" value="{{$value->id}}"></td>
                                            <td class="center">{{$value->id}}</td>
                                            <td>{{$value->username}}</td>
                                            <td>@if($value->type == 0)登录@elseif($value->type == 1)其他所有@endif</td>
                                            <td style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;" title="{{$value->action}}">{{$value->action}}</td>
                                            <td>{{$value->client_ip}}</td>
                                            <td>{{date('Y-m-d H:i:s',$value->create_at)}}</td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">


                                                    @permission('delete.log')
                                                    <a href="{{url('admin/logs/deleteLog/'.$tableName.'/'.$value->id.'/'.($search['time']?:''))}}"
                                                       class="btn btn-xs btn-danger" onclick=" return confirm('确定要删除该日志记录？');">
                                                        <strong>删除</strong>
                                                    </a>
                                                    @endpermission
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                </form>
                                {!! $data -> appends( app('request') -> all() ) -> render() !!}
                                {{--@permission('user.list')--}}
                                {{--@if(count($excels))--}}
                                    {{--<form action="{{url('admin/excel/userInfoExport')}}" method="post" style="float: right;margin-top:65px;margin-right:-130px;">--}}
                                        {{--<input type="submit" class="btn btn-xs btn-info"  value="导出用户信息" style="width:86px; cursor: pointer; margin-top:-87px;margin-right:130px;" />--}}
                                        {{--{{csrf_field()}}--}}
                                        {{--<input type="hidden" name="excels" value="{{$excels}}"/>--}}
                                    {{--</form>--}}
                                {{--@endif--}}
                                {{--@endpermission--}}
                            </div><!-- /.table-responsive -->
                        </div><!-- /span -->
                    </div><!-- /row -->

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script type="text/javascript" type="text/javascript" src="{{asset('admin/js/checkboxMultiple.js') }}"></script>
@endsection