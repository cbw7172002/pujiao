@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/video/css2/jquery.fancybox.css')}}" />
@endsection
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
                    <a href="{{url('/admin/specialCourse/specialCourseList')}}">创课课程</a>
                </li>
                <li class="active">课程章节列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="{{url('/admin/specialCourse/specialChapterList/'.$data->courseId)}}" method="get" class="form-search">
                    <span style=""  class="searchtype" iid="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        {{--<option value="2" @if($data->type == 2) selected @endif>课程名称</option>--}}
                        <option value="3" @if($data->type == 3) selected @endif>章节名称</option>
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
                    课程章节列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        课程章节列表
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

            @permission('add.course')
            <a href="{{url('/admin/specialCourse/addSpecialChapter/'.$data->courseId)}}" class="btn btn-xs btn-info">
                <i class="icon-ok bigger-110">添加</i>
            </a>
            @endpermission

            <div class="row" ms-controller="specialcommentdetail">

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
                                        {{--<th>课程名称</th>--}}
                                        <th>章节名称</th>
                                        <th>课程目录</th>
                                        <th>父级ID</th>
                                        <th>课程</th>
                                        <th>在线浏览</th>
                                        <th>格式</th>
                                        {{--<th>封面图</th>--}}
                                        {{--<th>时长</th>--}}
                                        {{--<th>大小</th>--}}
                                        {{--<th>播放数</th>--}}
                                        {{--<th>是否试学</th>--}}
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>

                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $chapter)
                                        <tr>
                                            {{--<td class="center">--}}
                                            {{--<label>--}}
                                            {{--<input type="checkbox" class="ace" />--}}
                                            {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                            {{--</td>--}}

                                            <td>
                                                <a href="#">{{$chapter->id}}</a>
                                            </td>
                                            <td>{{$chapter->title}}</td>
                                            <td>
                                                @if($chapter->courseType == 0)
                                                    课前导学
                                                @elseif($chapter->courseType == 1)
                                                    课堂授课
                                                @elseif($chapter->courseType == 2)
                                                    课后指导
                                                @endif
                                            </td>
                                            <td>{{$chapter->parentId}}</td>
                                            <td style="color: #0b6cbc">
                                                @if($chapter->parentId != 0)
                                                    @if(!$chapter->courseLowPath && !$chapter->courseMediumPath && !$chapter->courseHighPath && !$chapter->coursePath)
                                                        {{--@if($data->isTranscode)--}}
                                                            @if(isset($chapter->msg))
                                                                @if($chapter->msg['code'] == '200')
                                                                    正在转码...
                                                                @else
                                                                    {{$chapter->msg['message']}}
                                                                @endif
                                                            @endif
                                                        {{--@else--}}
                                                            {{--@if(in_array(strtolower($chapter->courseFormat),['doc','docx','xls','xlsx','ppt','pptx','pdf']))--}}
                                                                {{--<a href="{{url('/studentCourse/stuCatalog/'.$data->courseId)}}" target="_blank">查看</a>--}}
                                                            {{--@else--}}
                                                                {{--@if(isset($chapter->msg))--}}
                                                                    {{--@if($chapter->msg['code'] == '200')--}}
                                                                        {{--正在转码...--}}
                                                                    {{--@else--}}
                                                                        {{--{{$chapter->msg['message']}}--}}
                                                                    {{--@endif--}}
                                                                {{--@else--}}

                                                                {{--@endif--}}
                                                            {{--@endif--}}
                                                        {{--@endif--}}

                                                    @else
                                                        <a href="{{url('/studentCourse/stuCatalog/'.$data->courseId.'/'.$chapter->parentId)}}" target="_blank">查看</a>
                                                        {{--<span class="lookVideo" onclick="lookVideo('{{$chapter->courseLowPathurl}}')" style="color: #00a0e9;cursor:pointer;">查看</span>--}}
                                                    @endif
                                                @endif



                                            </td>
                                            <td>
                                                @if(!$data->isTranscode)
                                                    @if(in_array(strtolower($chapter->courseFormat),['doc','docx','xls','xlsx','ppt','pptx','pdf']))
                                                        <a href="{{url('/studentCourse/stuCatalog/'.$data->courseId).'/'.$chapter->parentId}}" target="_blank">查看</a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{$chapter->courseFormat}}</td>
                                            {{--<td>--}}
                                                {{--@if($chapter->parentId != 0)--}}
                                                    {{--<img src="{{asset($chapter->coursePic)}}" alt="" width="50px" height="50px">--}}
                                                {{--@endif--}}
                                            {{--</td>--}}
                                            {{--<td>{{$chapter->courseTime}}</td>--}}
                                            {{--<td>{{$chapter->courseSize}}</td>--}}
                                            {{--<td>{{$chapter->coursePlayView}}</td>--}}
                                            {{--<td>--}}
                                                {{--@if($chapter->parentId != 0)--}}
                                                    {{--{{$chapter->isTrylearn ? '是' : '否'}}--}}
                                                {{--@endif--}}
                                            {{--</td>--}}
                                            <td>{{$chapter->status ? '锁定' : '正常'}}</td>
                                            <td>{{$chapter->created_at}}</td>
                                            <td>{{$chapter->updated_at}}</td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                    {{--</button>--}}

                                                    @permission('edit.course')
                                                    <span class="btn btn-xs btn-inverse" style="position: relative;display: inline-block;">
                                                        <strong>课程状态</strong>
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                        <select id="" class="col-xs-10 col-sm-2" onchange="courseCheck({{$chapter->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">
                                                            <option value="44" selected></option>
                                                            <option value="0" >正常</option>
                                                            <option value="1" >锁定</option>
                                                        </select>
                                                    </span>
                                                    @endpermission


                                                    @permission('edit.course')
                                                    <a href="{{url('/admin/specialCourse/editSpecialChapter/'.$data->courseId.'/'.$chapter->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    @permission('del.course')
                                                    <a href="{{url('/admin/specialCourse/delSpecialChapter/'.$data->courseId.'/'.$chapter->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                        <i class="icon-trash bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    {{--<div href="" class="btn btn-xs btn-warning" ms-click="commentdetailpop()">--}}
                                                        {{--<i class="icon-flag bigger-120"></i>--}}
                                                    {{--</div>--}}

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

        <!--弹窗显示详情-->
        <div id="videodetailpupUpback" class="videodetailpupUpback">
            <div class="videopopup1" style="width: 700px;height: 400px;margin-top: -150px;margin-left: -350px;">
                <div class="videodetailtopbaner">
                    <div class="detailtitle" style="width: 650px;padding-left: 40px">视频</div>
                    <div class="deldetail" style="float: right"></div>
                </div>
                <div class="content1">
                    <div id="myplayer" ></div>
                </div>
            </div>
        </div>

    </div><!-- /.main-content -->

@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/specialCourse/specialChapter.js') }}"></script>


    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin/js/videoPop.js') }}"></script>
    <script>
//        jwplayer('myplayer').setup({
//            flashplayer: 'jwplayer/jwplayer.flash.swf',
//            file: '/uploads/video/introduce/default.mp4',
//            image:'/home/image/index/vdo.png',
//            width: '700',
//            height: '400',
//            type:'mp4'
//        });
    </script>


@endsection