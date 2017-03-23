@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
@endsection
@section('content')
    <div class="main-content" ms-controller="teachercommentdetail">
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
                    <a href="{{url('/admin/specialCourse/specialCourseList')}}">课程管理</a>
                </li>
                <li class="active">专题课程推荐</li>
            </ul><!-- .breadcrumb -->

            {{--<div class="nav-search" id="nav-search">--}}
                {{--<form action="{{url('/admin/commentCourse/teacherCourseList')}}" method="get" class="form-search">--}}
                    {{--<select name="type" id="form-field-1" class="searchtype">--}}
                        {{--<option value="1" @if($data->type == 1) selected @endif>ID</option>--}}
                        {{--<option value="2" @if($data->type == 2) selected @endif>作品名称</option>--}}
                        {{--<option value="3" @if($data->type == 3) selected @endif>演奏学员</option>--}}
                        {{--<option value="4" @if($data->type == 4) selected @endif>邀评名师</option>--}}
                        {{--<option value="5" @if($data->type == 5) selected @endif>时间筛选</option>--}}
                        {{--<option value="">全部</option>--}}
                    {{--</select>--}}
                    {{--<span class="input-icon">--}}
                        {{--<span style="@if($data->type != 5) display: block;  @else display: none; @endif" class="input-icon" id="search1">--}}
                            {{--<input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />--}}
                            {{--<i class="icon-search nav-search-icon"></i>--}}
                            {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />--}}
                        {{--</span>--}}
                        {{--<span style="@if($data->type == 5) display: block;  @else display: none; @endif" class="input-icon" id="search2">--}}
                            {{--<input type="text" name="beginTime" id="form-field-1" placeholder="上传开始时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />--}}
                            {{--<input type="text" name="endTime" id="form-field-1" placeholder="上传结束时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;" />--}}
                            {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />--}}
                        {{--</span>--}}
                    {{--</span>--}}
                {{--</form>--}}
            {{--</div><!-- #nav-search -->--}}
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    专题课程推荐
                    <small>
                        <i class="icon-double-angle-right"></i>
                        专题课程推荐
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

            {{--@permission('add.course')--}}
            {{--<a href="{{url('/admin/specialCourse/addRecommendSpecialCourse')}}" class="btn btn-xs btn-info">--}}
                {{--<i class="icon-ok bigger-110">添加</i>--}}
            {{--</a>--}}
            {{--@endpermission--}}

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
                                        <th>课程标题</th>
                                        <th>推荐位</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>

                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $recommend)
                                        <tr>
                                        {{--<td class="center">--}}
                                            {{--<label>--}}
                                                {{--<input type="checkbox" class="ace" />--}}
                                                {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                        {{--</td>--}}

                                        <td>
                                            <a href="#">{{$recommend->id}}</a>
                                        </td>
                                        <td>{{$recommend->courseName}}</td>
                                        <td>{{$recommend->sort}}</td>

                                        <td>{{$recommend->created_at}}</td>

                                        <td>{{$recommend->updated_at}}</td>

                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                @permission('edit.course')
                                                <a href="{{url('/admin/specialCourse/editRecommendSpecialCourse/'.$recommend->id)}}" class="btn btn-xs btn-info">
                                                    <i class="icon-edit bigger-120"></i>
                                                </a>
                                                @endpermission

                                                @permission('del.course')
                                                <a href="{{url('/admin/specialCourse/delRecommendSpecialCourse/'.$recommend->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission

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
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/teacherCourse/teacherCourse.js') }}"></script>
    <script>
         require(['/teacherCourse/teacCourse_avalon'], function (detail) {
             avalon.scan();
        });
    </script>
@endsection