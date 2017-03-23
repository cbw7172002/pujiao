@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/cut/Jcrop/Jcrop.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/cut/Jcrop/uploadify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/cut/cut.css')}}">
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
                <li class="active">编辑创课课程</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    编辑创课课程
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑创课课程
                    </small>
                </h1>
            </div><!-- /.page-header -->

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row" ms-controller="specialcommentdetail">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form action="{{url('admin/specialCourse/doEditSpecialCourse')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ID </label>

                            <div class="col-sm-9">
                                <input type="text" readonly name="id" id="form-field-1" placeholder="ID" class="col-xs-10 col-sm-5" value="{{$data->id}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 课程名称 </label>

                            <div class="col-sm-9">
                                <input type="text" name="courseTitle" id="form-field-1" placeholder="课程名称" class="col-xs-10 col-sm-5" value="{{$data->courseTitle}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>


                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 视频格式 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="courseFormat" id="form-field-1" placeholder="视频格式" class="col-xs-10 col-sm-5" value="{{$data->courseFormat}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 讲师 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="teacherId" id="form-field-1" placeholder="讲师" class="col-xs-10 col-sm-5" value="{{$data->realname}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 讲师 </label>

                            <div class="col-sm-9">
                                {{--<input type="text" name="teacherId" id="form-field-1" placeholder="讲师" class="col-xs-10 col-sm-5" value="" />--}}
                                <select id="form-field-2" class="col-xs-10 col-sm-5" name="teacherId">
                                    @foreach($data->teacherNames as $teacherNames)
                                        <option value="{{$teacherNames->id}}" @if($data->teacherId == $teacherNames->id) selected @endif>{{$teacherNames->realname}}</option>
                                    @endforeach
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源封面图 </label>
                            <div class="col-sm-9">
                                <input type="hidden" name="coursePic" id="resourcePic">
                                <img id="showImg" src="{{asset($data->coursePic)}}" width="280" height="180"  alt="" onerror="this.src='/admin/image/back.png'">
                                <div class="right_head_img_upload">上传图片</div>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl" style="color: red;font-size: 14px;"></span>
                                    </label>
                                </span>
                            </div>

                        </div>


                        {{--裁剪弹出层--}}
                        <div class="headImg hide">
                            <div class="headImg_tit">
                                <div class="headImg_tit_l">上传图片</div>
                                <div class="headImg_tit_r"><img src="{{asset('admin/cut/close.png')}}" alt=""></div>
                            </div>
                            <div class="headImg_con">
                                <div class="headImg_con_l">
                                    <div style="height:20px;"></div>
                                    <div id="imgs">
                                        <div style="height:50px;"></div>
                                        <img id="imghead" style="" src="{{asset('admin/cut/unload.png')}}">
                                    </div>
                                </div>

                            </div>
                            <div class="headImg_foot">
                                <div class="headImg_foot_selImg">
                                    <div class="sel_btn">选择图片</div>
                                    <input id="file_upload"  name="file_upload" type="file" multiple="false" value="" />
                                </div>
                                <div class="headImg_foot_cutImg">
                                    <div class="sel_btn saveImg">保存图片</div>
                                </div>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 介绍 </label>

                            <div class="col-sm-9">
                                <textarea name="courseIntro" placeholder="介绍..." id="form-field-1" class="col-xs-10 col-sm-5" cols="30" rows="10" style="resize: none">{{$data->courseIntro}}</textarea>
                                <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 课程价格 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="coursePrice" id="form-field-1" placeholder="课程价格" class="col-xs-10 col-sm-5" ms-duplex="coursePrice" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 课程类型 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="courseType" id="form-field-1" placeholder="课程类型" class="col-xs-10 col-sm-5" value="{{$data->typeName}}" />--}}
                                {{--<input type="text" name="" id="form-field-1" placeholder="课程类型" class="col-xs-10 col-sm-5" value="{{$data->courseType}}" />--}}
                                {{--<select id="coursetype" class="col-xs-10 col-sm-5" name="courseType" ms-change="typeSelect(this.value,{{$data->coursePrice}})">--}}
                                    {{--<option value="0">无</option>--}}
                                    {{--@foreach($data->typeNames as $typenames)--}}
                                        {{--<option value="{{$typenames->id}}" @if($data->courseType == $typenames->id) selected @endif>{{$typenames->typeName}}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                                {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="space-4"></div>

                        {{--@if($data->courseType == 1)--}}
                            {{--<div class="form-group" ms-if="watchSelect">--}}
                                {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 折扣 </label>--}}

                                {{--<div class="col-sm-9">--}}
                                    {{--<select id="zhekou" class="col-xs-10 col-sm-5" name="courseDiscount"  ms-change="discountSelect(this.value,{{$data->coursePrice}})">--}}
                                        {{--<option value="9" @if($data->courseDiscount == 9) selected @endif>9折</option>--}}
                                        {{--<option value="8" @if($data->courseDiscount == 8) selected @endif>8折</option>--}}
                                        {{--<option value="7" @if($data->courseDiscount == 7) selected @endif>7折</option>--}}
                                        {{--<option value="6" @if($data->courseDiscount == 6) selected @endif>6折</option>--}}
                                        {{--<option value="5" @if($data->courseDiscount == 5) selected @endif>5折</option>--}}
                                        {{--<option value="4" @if($data->courseDiscount == 4) selected @endif>4折</option>--}}
                                        {{--<option value="3" @if($data->courseDiscount == 3) selected @endif>3折</option>--}}
                                        {{--<option value="2" @if($data->courseDiscount == 2) selected @endif>2折</option>--}}
                                        {{--<option value="1" @if($data->courseDiscount == 1) selected @endif>1折</option>--}}
                                    {{--</select>--}}
                                {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                    {{--<label class="middle">--}}
                                        {{--<span class="lbl"></span>--}}
                                    {{--</label>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        {{--<div class="form-group" ms-visible="watchSelect">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 折扣后价格 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" readonly name="" id="form-field-1" placeholder="折扣后价格" class="col-xs-10 col-sm-5"  ms-duplex="discountPrice" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--@endif--}}

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 浏览数 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="courseView" id="form-field-1" placeholder="浏览数" class="col-xs-10 col-sm-5" value="{{$data->courseView}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 观看数 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="coursePlayView" id="form-field-1" placeholder="观看数" class="col-xs-10 col-sm-5" value="{{$data->coursePlayView}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 收藏数 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<input type="text" name="courseFav" id="form-field-1" placeholder="收藏数" class="col-xs-10 col-sm-5" value="{{$data->courseFav}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 学习数 </label>

                            <div class="col-sm-9">
                                <input type="text" name="courseStudyNum" id="form-field-1" placeholder="学习数" class="col-xs-10 col-sm-5" value="{{$data->courseStudyNum}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态 </label>

                            <div class="col-sm-9">
                                {{--<input type="text" name="teacherId" id="form-field-1" placeholder="讲师" class="col-xs-10 col-sm-5" value="" />--}}
                                <select id="form-field-2" class="col-xs-10 col-sm-5" name="courseStatus">
                                    <option value="0" @if($data->courseStatus == 0) selected @endif>激活</option>
                                    <option value="1" @if($data->courseStatus == 1) selected @endif>锁定</option>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>


                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                    Submit
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script type="text/javascript" src="{{ URL::asset('/admin/js/addSubject.js') }}"></script>

    <script type="text/javascript" src="{{asset('admin/cut/Jcrop/Jcrop.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/cut/Jcrop/jquery.uploadify.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('/admin/cut/cut.js') }}"></script>

    <script>
        require(['/specialCourse/editcourse'], function (detail) {
            detail.coursePrice = '{{$data->coursePrice}}' || null

            //进入编辑页默认是打折课程类型时显示折扣和折扣后价钱
            var coursetype = $('#coursetype').val();
            if(coursetype == 1){
                detail.watchSelect = true;
                var aa = $('#zhekou').val();
                var price = detail.coursePrice;
                detail.discountPrice = Math.ceil(price * (aa / 10));
            }
            avalon.scan();
        });
    </script>
@endsection