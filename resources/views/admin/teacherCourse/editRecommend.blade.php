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
                    <a href="{{url('/admin/commentCourse/commentCourseList')}}">辅导课程</a>
                </li>
                <li class="active">编辑辅导课程推荐</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    编辑辅导课程推荐
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑辅导课程推荐
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

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form action="{{url('admin/commentCourse/doEditRecommendCourse')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 标题 </label>

                            <div class="col-sm-9">
                                <input type="text" readonly name="courseName" id="form-field-1" placeholder="标题" class="col-xs-10 col-sm-5" value="{{$data->courseName}}" />
                                <input type="hidden"  name="courseId" id="form-field-1" placeholder="标题" class="col-xs-10 col-sm-5" value="{{$data->courseId}}" />

                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 推荐位 </label>

                            <div class="col-sm-9">
                                {{--<input type="text" name="sort" id="form-field-1" placeholder="推荐位" class="col-xs-10 col-sm-5" value="" />--}}
                                <select name="sort" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="1" @if($data->sort == 1) selected @endif>1</option>
                                    <option value="2" @if($data->sort == 2) selected @endif>2</option>
                                    <option value="3" @if($data->sort == 3) selected @endif>3</option>
                                    <option value="4" @if($data->sort == 4) selected @endif>4</option>
                                    <option value="5" @if($data->sort == 5) selected @endif>5</option>
                                    <option value="6" @if($data->sort == 6) selected @endif>6</option>
                                    <option value="7" @if($data->sort == 7) selected @endif>7</option>
                                    <option value="8" @if($data->sort == 8) selected @endif>8</option>
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