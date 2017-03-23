@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
@endsection
@section('content')
    <div class="main-content" ms-controller="specialcommentdetail">
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
                    <a href="{{url('/admin/count/specialCountList')}}">数据统计</a>
                </li>
                <li class="active">课程播放统计列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="{{url('/admin/count/specialCountList')}}" method="get" class="form-search">
                    <span style=""  class="searchtype" iid="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>课程名称</option>
                        <option value="">全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                        </span>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    课程播放统计列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        课程播放统计列表
                    </small>
                </h1>
            </div><!-- /.page-header -->

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        {{--<th class="center">--}}
                                        {{--<label>--}}
                                        {{--<input type="checkbox" class="ace" />--}}
                                        {{--<span class="lbl"></span>--}}
                                        {{--</label>--}}
                                        {{--</th>--}}
                                        <th>ID</th>
                                        <th>课程名称</th>
                                        <th>课程观看数</th>
                                        <th>课程详情页访问数</th>
                                        <th>点击率</th>
                                        <th>课程视频观看完成数</th>
                                        <th>播放完成率</th>
                                        <th>课程创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $count)
                                        <tr>
                                            {{--<td class="center">--}}
                                            {{--<label>--}}
                                            {{--<input type="checkbox" class="ace" />--}}
                                            {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                            {{--</td>--}}
                                            <td>
                                                <a href="#">{{$count->id}}</a>
                                            </td>
                                            <td>{{$count->courseTitle}}</td>
                                            <td>{{$count->coursePlayView}}</td>
                                            <td>{{$count->courseView}}</td>
                                            <td>{{$count->click}}%</td>
                                            <td>{{$count->completecount}}</td>
                                            <td>{{$count->complete}}%</td>
                                            <td>{{$count->created_at}}</td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                    {{--<a href="{{url('/admin/specialCourse/delSpecialFeedback/'.$count->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">--}}
                                                        {{--<i class="icon-trash bigger-120"></i>--}}
                                                    {{--</a>--}}

                                                </div>

                                                <div class="visible-xs visible-sm hidden-md hidden-lg">
                                                    <div class="inline position-relative">
                                                        <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-cog icon-only bigger-110"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                            <li>
                                                                <a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="icon-zoom-in bigger-120"></i>
																				</span>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="icon-edit bigger-120"></i>
																				</span>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="icon-trash bigger-120"></i>
																				</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $data->appends(app('request')->all())->render() !!}

                                @if(count($excel))
                                    <form action="{{url('admin/excel/specialCountExport')}}" method="post" style="float: right;margin-top:65px;margin-right:-130px;">
                                        <input type="submit" class="btn btn-xs btn-info"  value="导出播放统计" style="width:86px; cursor: pointer; margin-top:-87px;margin-right:130px;" />
                                        {{csrf_field()}}
                                        <input type="hidden" name="excels" value="{{$excel}}"/>
                                    </form>
                                @endif

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
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/specialCourse/specialChapter.js') }}"></script>


@endsection