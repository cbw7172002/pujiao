@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/assets/css/main.css')}}" />
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
                    <a href="{{url('/admin/specialCourse/specialCourseList')}}">创课课程</a>
                </li>
                <li class="active">创课课程列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                @if(\Auth::user()->id == 1)
                <div class="checkbox checkbox-slider--b-flat">
                    <label>
                        <input type="checkbox" id="checkcourse"><span style="color: red;">是否需要审核</span>
                    </label>
                </div>
                @endif

            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    创课课程列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        创课课程列表
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
            {{--<a href="{{url('admin/specialCourse/addSpecialCourse')}}" class="btn btn-xs btn-info">--}}
                {{--<i class="icon-ok bigger-110">添加</i>--}}
            {{--</a>--}}
            {{--@endpermission--}}

            <div class="row">

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form action="{{url('/admin/specialCourse/specialCourseList')}}" method="get" class="form-search" ms-controller="searchSelect">
                        <span style=""  class="searchtype" iid="form-field-1">
                            <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                            <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                        </span>

                        <select id="grade" name="resourceGrade" class="grade" ms-change="getChapter(1)" ms-duplex="defaultGrade">
                            <option value="0">-- 年级 --</option>
                            <option ms-repeat="grades" ms-attr-value="el.id" ms-text="el.gradeName"></option>
                        </select>

                        <select id="subject" name="resourceSubject" class="subject" ms-change="getChapter(2)" ms-duplex="defaultSubject">
                            <option value="0">-- 科目 --</option>
                            <option ms-repeat="subjects" ms-attr-value="el.id" ms-text="el.subjectName"></option>

                        </select>

                        <select id="edition" name="resourceEdition" class="edition" ms-change="getChapter(3)" ms-duplex="defaultEdition">
                            <option value="0">-- 版本 --</option>
                            <option ms-repeat="editions" ms-attr-value="el.id" ms-text="el.editionName"></option>
                        </select>

                        <select id="book" name="resourceBook" class="book" ms-change="getChapter(4)" ms-duplex="defaultBook">
                            <option value="0">-- 册别 --</option>
                            <option ms-repeat="books" ms-attr-value="el.id" ms-text="el.bookName"></option>
                        </select>

                        <select id="form-field-1" name="resourceChapter">
                            <option value="0">--知识点--</option>
                            <option ms-repeat="chapter" ms-attr-value="el.id" ms-text="el.chapterName"></option>
                        </select>

                        <select name="type" id="form-field-1" class="searchtype">
                            <option value="">--请选择--</option>
                            <option value="1" @if($data->type == 1) selected @endif>ID</option>
                            <option value="2" @if($data->type == 2) selected @endif>课程名称</option>
                            <option value="3" @if($data->type == 3) selected @endif>授课讲师</option>
                            <option value="" id="allSearch">全部</option>
                        </select>
                    <span class="input-icon">
                       <span style="" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                             <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                        </span>
                    </span>
                    </form>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <form action="{{url('admin/specialCourse/delMultiSpecialCourse')}}" method="post" class="form-search" onsubmit="return confirm('确定要删除该课程记录？');">
                                    {{csrf_field()}}
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        @if(count($data) > 0)
                                            <input type="submit"  style="display:inline-block;width:80px;height:30px;line-height: 30px;text-align:center;cursor: pointer;font-size:13px;margin: 10px auto;letter-spacing: 2px;border:none;background:#209EEA; color:#fff;" value="多删除">
                                        @endif
                                        <th class="center" style="width:3%;"><input type="checkbox" name="multiple"></th>
                                        {{--<th class="center">--}}
                                        {{--<label>--}}
                                        {{--<input type="checkbox" class="ace" />--}}
                                        {{--<span class="lbl"></span>--}}
                                        {{--</label>--}}
                                        {{--</th>--}}
                                        <th>ID</th>
                                        <th>课程名称</th>
                                        {{--<th>课程描述</th>--}}
                                        {{--<th>课程类型</th>--}}
                                        {{--<th>视频格式</th>--}}
                                        <th>授课讲师</th>
                                        {{--<th>课程</th>--}}
                                        <th>封面图</th>
                                        <th>年级</th>
                                        <th>学科</th>
                                        <th>版本</th>
                                        <th>册别</th>
                                        <th>知识点</th>
                                        {{--<th>价格</th>--}}
                                        {{--<th>折扣</th>--}}
                                        {{--<th>折扣后价格</th>--}}
                                        <th>浏览数</th>
                                        <th>观看数</th>
                                        <th>学习数(true)</th>
                                        <th>学习数</th>
                                        <th>收藏数</th>
                                        {{--<th>课程公告</th>--}}
                                        <th>课程状态</th>

                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $special)
                                        <tr>
                                            {{--<td class="center">--}}
                                            {{--<label>--}}
                                            {{--<input type="checkbox" class="ace" />--}}
                                            {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                            {{--</td>--}}
                                            <td class="center"><input type="checkbox" name="check[]" value="{{$special->id}}"></td>
                                            <td>
                                                <a href="#">{{$special->id}}</a>
                                            </td>
                                            <td>{{$special->courseTitle}}</td>
                                            {{--<td>{{$special->courseIntro}}</td>--}}
                                            {{--<td>{{$special->typeName}}</td>--}}
                                            {{--<td>{{$special->courseFormat}}</td>--}}
                                            <td>{{$special->username}}</td>
                                            {{--<td>--}}
                                                {{--<a href="{{url('/lessonSubject/detail/'.$special->id)}}">查看</a>--}}
                                            {{--</td>--}}
                                            <td>
                                                {{--<a id="example2-2" href="{{asset($comcourse->coursePic)}}">查看--}}
                                                <img src="{{asset($special->coursePic)}}" alt="" width="50px" height="50px" onerror="this.src='/admin/image/back.png'">
                                                {{--</a>--}}
                                            </td>
                                            <td>{{$special->gradeName}}</td>
                                            <td>{{$special->subjectName}}</td>
                                            <td>{{$special->editionName}}</td>
                                            <td>{{$special->bookName}}</td>
                                            <td>{{$special->chapterName}}</td>
                                            {{--<td>{{$special->coursePrice}}</td>--}}
                                            {{--<td>--}}
                                                {{--@if($special->courseDiscount != 0)--}}
                                                    {{--{{$special->courseDiscount}} 折--}}
                                                {{--@else--}}
                                                    {{--无--}}
                                                {{--@endif--}}

                                            {{--</td>--}}
                                            {{--<td>{{$special->discountPrice}}</td>--}}
                                            <td>{{$special->courseView}}</td>
                                            <td>{{$special->coursePlayView}}</td>
                                            <td>{{$special->completecount}}</td>
                                            <td>{{$special->courseStudyNum}}</td>
                                            <td>{{$special->courseFav}}</td>
                                            {{--<td>{{$special->courseNotice}}</td>--}}
                                            <td>
                                                @if($special->courseStatus == 0)
                                                    通过审核
                                                @elseif($special->courseStatus == 1)
                                                    审核中
                                                @elseif($special->courseStatus == 2)
                                                    未通过
                                                @elseif($special->courseStatus == 3 || $special->courseStatus == 4)
                                                    待发布
                                                @elseif($special->courseStatus == 5)
                                                    下架
                                                @elseif($special->courseStatus == 6)
                                                    <span style="color: red">转码失败</span>
                                                @elseif($special->courseStatus == 7)
                                                    <span style="color: #0b6cbc">正在转码...</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                    {{--</button>--}}


                                                @permission('edit.course')
                                                {{--<span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">--}}
                                                    {{--<strong>课程状态</strong>--}}
                                                    {{--<span class="icon-caret-down icon-on-right"></span>--}}
                                                    {{--<select id="" class="col-xs-10 col-sm-2" onchange="courseCheck({{$special->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">--}}
                                                        {{--<option value="44" selected></option>--}}
                                                        {{--@if($special->courseStatus == 5 || $special->courseStatus == 2)--}}
                                                            {{--<option value="0" >通过审核</option>--}}
                                                            {{--<option value="1" >审核中</option>--}}
                                                            {{--<option value="2" >审核未通过</option>--}}
                                                        {{--@elseif($special->courseStatus == 0)--}}
                                                            {{--<option value="5" >下架</option>--}}
                                                        {{--@elseif($special->courseStatus == 6)--}}
                                                            {{--<option value="2" >审核未通过</option>--}}
                                                        {{--@endif--}}
                                                    {{--</select>--}}


                                                    @if($data->defaultStatus == 1)
                                                        @if($special->courseStatus == 5 || $special->courseStatus == 2)
                                                            <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                                <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                                    审核状态
                                                                    <span class="icon-caret-down icon-on-right"></span>
                                                                </span>
                                                                <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                                    <li onclick="courseCheck({{$special->id}},this.value,'{{$special->username}}')" value="0">通过审核</li>
                                                                    {{--<li onclick="courseCheck({{$special->id}},this.value);" value="1">审核中</li>--}}
                                                                    <li onclick="courseCheck({{$special->id}},this.value,'{{$special->username}}');" value="2">审核未通过</li>
                                                                </ul>
                                                            </span>
                                                        @elseif($special->courseStatus == 1)
                                                            <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                                <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                                    审核状态
                                                                    <span class="icon-caret-down icon-on-right"></span>
                                                                </span>
                                                                <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                                    <li onclick="courseCheck({{$special->id}},this.value,'{{$special->username}}')" value="0">通过审核</li>
                                                                    <li onclick="courseCheck({{$special->id}},this.value,'{{$special->username}}');" value="2">审核未通过</li>
                                                                </ul>
                                                            </span>
                                                        @elseif($special->courseStatus == 0)
                                                            <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                                <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                                    审核状态
                                                                    <span class="icon-caret-down icon-on-right"></span>
                                                                </span>
                                                                <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                                    <li onclick="courseCheck({{$special->id}},this.value)" value="5">下架</li>
                                                                </ul>
                                                            </span>
                                                        @elseif($special->courseStatus == 6)
                                                            <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                                <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                                    审核状态
                                                                    <span class="icon-caret-down icon-on-right"></span>
                                                                </span>
                                                                <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                                    <li onclick="courseCheck({{$special->id}},this.value,'{{$special->username}}');" value="2">审核未通过</li>
                                                                </ul>
                                                            </span>
                                                        @else
                                                            <span class="btn btn-xs btn-" style="position: relative;display: inline-block;">
                                                                <span data-toggle="dropdown" class="btn btn-xs btn-" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                                    审核状态
                                                                    {{--<span class="icon-caret-down icon-on-right"></span>--}}
                                                                </span>
                                                            </span>
                                                        @endif
                                                    @endif

                                                {{--</span>--}}

                                                @endpermission


                                                    @permission('check.course')
                                                    <a href="{{url('/admin/specialCourse/specialChapterList/'.$special->id)}}" class="btn btn-xs btn-success">
                                                        <i class="icon-list bigger-120"></i>章节
                                                    </a>
                                                    @endpermission

                                                    @permission('check.course')
                                                    <a href="{{url('/admin/specialCourse/questionList/'.$special->id)}}" class="btn btn-xs btn-success">
                                                        <i class="icon-list bigger-120"></i>问答
                                                    </a>
                                                    @endpermission
                                                    @permission('check.course')
                                                    <a href="{{url('/admin/specialCourse/notesList/'.$special->id)}}" class="btn btn-xs btn-success">
                                                        <i class="icon-list bigger-120"></i>笔记
                                                    </a>
                                                    @endpermission

                                                    {{--@permission('check.course')--}}
                                                    {{--<a href="{{url('/admin/specialCourse/dataList/'.$special->id)}}" class="btn btn-xs btn-success">--}}
                                                        {{--<i class="icon-download bigger-120"></i>资料--}}
                                                    {{--</a>--}}
                                                    {{--@endpermission--}}

                                                    @permission('edit.course')
                                                    <a href="{{url('/admin/specialCourse/editSpecialCourse/'.$special->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    @permission('check.course')
                                                    <div href="" class="btn btn-xs btn-warning" ms-click="commentdetailpop({{$special->id}})">
                                                        <i class="icon-flag bigger-120"></i>
                                                    </div>
                                                    @endpermission

                                                    @permission('del.course')
                                                        @if($special->courseStatus == 2 || $special->courseStatus == 5 || $special->courseStatus == 6)
                                                            <a href="{{url('/admin/specialCourse/delSpecialCourse/'.$special->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                                <i class="icon-trash bigger-120"></i>
                                                            </a>
                                                        @else
                                                            <div class="btn btn-xs btn-">
                                                                <i class="icon-trash bigger-120"></i>
                                                            </div>
                                                        @endif
                                                    @endpermission

                                                    @if($special->courseStatus == 0)
                                                    <a href="{{url('/admin/specialCourse/addRecommendSpecialCourse/'.$special->id)}}" class="btn btn-xs btn-info">
                                                        <i class=""></i>推荐
                                                    </a>
                                                    @endif

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
                                </form>
                                {!! $data->appends(app('request')->all())->render() !!}

                            </div><!-- /.table-responsive -->
                        </div><!-- /span -->
                    </div><!-- /row -->

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->


        <!--弹窗显示详情-->
        <div id="detailpupUpback" class="detailpupUpback" ms-visible="popup">
            <div class="popup1">
                <div class="detailtopbaner">
                    <div class="detailtitle">详情</div>
                    <div class="deldetail" ms-click="deldetail"></div>
                </div>
                <div class="content1">

                    <div class="form-group">
                        <lable class="labtitle">标题:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseTitle" />
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">描述:</lable>
                        <textarea name="" id="" readonly cols="30" rows="5" ms-duplex="info.courseIntro"></textarea>
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<lable class="labtitle">课程类型:</lable>--}}
                        {{--<input type="text" readonly placeholder="" ms-duplex="info.typeName">--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                    {{--<lable class="labtitle">视频格式:</lable>--}}
                    {{--<input type="text" readonly placeholder="" ms-duplex="info.courseFormat">--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <lable class="labtitle">讲师:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.username">
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<lable class="labtitle">课程折扣:</lable>--}}
                        {{--<input type="text" readonly placeholder="" ms-duplex="info.courseDiscount">--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--<lable class="labtitle">价格:</lable>--}}
                        {{--<input type="text" readonly placeholder="" ms-duplex="info.coursePrice">--}}
                    {{--</div>--}}


                    {{--<div class="form-group">--}}
                        {{--<lable class="labtitle">课程公告:</lable>--}}
                        {{--<textarea name="" id="" readonly cols="30" rows="5" ms-duplex="info.courseNotice"></textarea>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <lable class="labtitle">浏览数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseView">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">观看数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.coursePlayView">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">学习数(true):</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.completecount">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">学习数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseStudyNum">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">收藏数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseFav">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">创建时间:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.created_at">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">更新时间:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.updated_at">
                    </div>


                </div>
            </div>
        </div>

        <!--审核未通过弹窗-->
        <div id="pupUpback2" class="pupUpback">
            <div class="popup">
                <div class="topbaner">
                    <div class="topleft">审核结果</div>
                    <div class="topright" id="closenopass"></div>
                </div>
                {{--<form action="{{url('/admin/commentCourse/noPassMsg')}}" method="post">--}}
                <div class="contenterror">
                    <div class="errortitle">审核未通过</div>
                    <div class="errormsg">
                        <lable>原因:</lable>
                        <textarea name="content" maxlength="200"  placeholder="请输入审核未通过的原因(不超过200个汉字)..." id="errortext" cols="30" rows="7" required></textarea>
                    </div>

                </div>
                <input type="hidden" name="actionId" class="actionId"  value="">
                <input type="hidden" name="state" class="state"  value="">
                <input type="hidden" name="username" class="username" value="">
                <input type="hidden" name="fromUsername" class="fromUsername" value="{{\Auth::user()->username}}">
                <input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
                <div class="bottom" id="surebtn">
                    <button class="suer_btn" id="nobtn">确认</button>
                </div>
            </div>
        </div>

    </div><!-- /.main-content -->

@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/specialCourse/specialCourse.js') }}"></script>
    <script type="text/javascript" type="text/javascript" src="{{asset('admin/js/checkboxMultiple.js') }}"></script>
    <script>
        require(['/specialCourse/specialCourse_avalon'], function (detail) {
            avalon.scan();
        });
    </script>

    <script>
        require(['/searchSelect'], function (detail) {
            detail.defaultGrade = '{{$data->resourceGrade }}' || null;
            detail.defaultSubject = '{{$data->resourceSubject }}' || null;
            detail.defaultEdition = '{{$data->resourceEdition }}' || null;
            detail.defaultBook = '{{$data->resourceBook }}' || null;
            avalon.scan();
        });
    </script>

    <script>
        //默认审核状态
        var default1 = {{$data->defaultStatus}};
        if(default1 == 1){
            $('#checkcourse').prop('checked','true');
        }

        //是否需要审核
        $('#checkcourse').change(function(){
            var status = $(this).prop('checked');
            console.log(status);
            if(status == true){
                var msg = '确认审核?';
            }
            if(status == false){
                var msg = '确认不审核?';
            }
            if(!confirm(msg)){
                if(status == true){
                    $(this).removeProp('checked');
                }else{
                    $(this).prop('checked','true');
                }
                return false;
            }
            $.ajax({
                type: "get",
                url: "/admin/specialCourse/isCheck/" + status,

                dataType: 'json',
                success: function (res) {
                    location.reload();
                }
            });
        })
    </script>
@endsection