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
                    <a href="{{url('/admin/specialCourse/specialCourseList')}}">创课课程</a>
                </li>
                <li class="active">添加创课课程</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    添加创课课程
                    <small>
                        <i class="icon-double-angle-right"></i>
                        添加创课课程
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

                    <form action="{{url('admin/specialCourse/doAddSpecialCourse')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 标题 </label>

                            <div class="col-sm-9">
                                <input type="text" name="courseTitle" id="form-field-1" placeholder="标题" class="col-xs-10 col-sm-5" value="{{old('courseTitle')}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 描述 </label>

                            <div class="col-sm-9">
                                <textarea name="courseIntro" placeholder="描述" id="form-field-1" class="col-xs-10 col-sm-5" cols="30" rows="10" style="resize: none">{{old('courseIntro')}}</textarea>
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

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 讲师 </label>

                            <div class="col-sm-9">
                                {{--<input type="text" name="teacherId" id="form-field-1" placeholder="讲师" class="col-xs-10 col-sm-5" value="" />--}}
                                <select id="form-field-2" class="col-xs-10 col-sm-5" name="teacherId">
                                    @foreach($data['typeNames'] as $typenames)
                                    <option value="{{$typenames->id}}">{{$typenames->realname}}</option>
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 封面图 </label>

                            <div class="col-sm-9">
                                <img src="" id="uploadPreview" class="col-xs-10 col-sm-5" onerror="this.src='/admin/image/back.png'"/>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

                            <div class="col-sm-9">
                                <input type="file" id="uploadImage" name="coursePic" onchange="loadImageFile();" style="width:170px;height:40px;position: absolute;top: 0px;opacity: 0;"/>
                                <div class="second_file"><img src="/admin/image//1.png"/></div>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 课程公告 </label>

                            <div class="col-sm-9">
                                <textarea name="courseNotice" placeholder="课程公告" id="form-field-1" class="col-xs-10 col-sm-5" cols="30" rows="10" style="resize: none">{{old('courseNotice')}}</textarea>
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
                                {{--<input type="text" name="coursePrice" ms-duplex="price" id="form-field-1" placeholder="课程价格" class="col-xs-10 col-sm-5" value="{{old('coursePrice')}}" />--}}
                            {{--<span class="help-inline col-xs-12 col-sm-7">--}}
                                {{--<label class="middle">--}}
                                    {{--<span class="lbl"></span>--}}
                                {{--</label>--}}
                            {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="space-4"></div>--}}

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 课程类型 </label>

                            <div class="col-sm-9">
                                <select id="form-field-2" class="col-xs-10 col-sm-5" name="courseType" ms-change="typeSelect(this.value)">
                                    <option value="0">无</option>
                                    @foreach($data['coursetypes'] as $courseTypes)
                                        <option value="{{$courseTypes->id}}">{{$courseTypes->typeName}}</option>
                                    @endforeach
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        {{--<div class="space-4"></div>--}}

                        {{--<div class="form-group" ms-visible="watchSelect">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 折扣 </label>--}}

                            {{--<div class="col-sm-9">--}}
                                {{--<select id="zhekou" class="col-xs-10 col-sm-5" name="courseDiscount"  ms-change="discountSelect(this.value)">--}}
                                    {{--<option value="9">9折</option>--}}
                                    {{--<option value="8">8折</option>--}}
                                    {{--<option value="7">7折</option>--}}
                                    {{--<option value="6">6折</option>--}}
                                    {{--<option value="5">5折</option>--}}
                                    {{--<option value="4">4折</option>--}}
                                    {{--<option value="3">3折</option>--}}
                                    {{--<option value="2">2折</option>--}}
                                    {{--<option value="1">1折</option>--}}
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

                        {{--<div class="space-4"></div>--}}

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态 </label>

                            <div class="col-sm-9">
                                {{--<input type="text" name="teacherId" id="form-field-1" placeholder="讲师" class="col-xs-10 col-sm-5" value="" />--}}
                                <select id="form-field-2" class="col-xs-10 col-sm-5" name="courseStatus">
                                    <option value="0">激活</option>
                                    <option value="1">锁定</option>
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
    <script>
        require(['/specialCourse/specialCourse_avalon'], function (detail) {
            avalon.scan();
        });
    </script>
@endsection