@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/jscolor/jquery.bigcolorpicker.css') }}" />
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
                    <a href="{{url('/admin/baseInfo/chapterList')}}">知识点管理</a>
                </li>
                <li class="active">添加</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    知识点管理列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        添加
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

                    <form action="{{url('admin/chapter/doAddChapter')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>


                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属学段 </label>--}}
                            {{--<div class="col-sm-9">--}}
                                {{--<select name="sectionId" id="form-field-1" class="col-xs-10 col-sm-5">--}}
                                    {{--<option value="0">选择学段</option>--}}
                                    {{--<option value="1">小学</option>--}}
                                    {{--<option value="2">初中</option>--}}
                                    {{--<option value="3">高中</option>--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="space-4"></div>--}}



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属年级 </label>
                            <div class="col-sm-9">
                                <select name="gradeId" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="0">选择年级</option>
                                    @foreach($grade as $g)
                                        <option value="{{$g->id}}">{{$g->gradeName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-4"></div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属学科 </label>
                            <div class="col-sm-9">
                                <select name="subjectId" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="0">选择学科</option>
                                    @foreach($subject as $s)
                                        <option value="{{$s->id}}">{{$s->subjectName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-4"></div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属册别 </label>
                            <div class="col-sm-9">
                                <select name="bookId" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="0">选择册别</option>
                                    @foreach($book as $b)
                                        <option value="{{$b->id}}">{{$b->bookname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-4"></div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属版本 </label>
                            <div class="col-sm-9">
                                <select name="editionId" id="form-field-1" class="col-xs-10 col-sm-5">
                                    <option value="0">选择版本</option>
                                    @foreach($edition as $e)
                                        <option value="{{$e->id}}">{{$e->editionName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-4"></div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 知识点名称 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="chapterName" id="form-field-1" placeholder="知识点名称" class="col-xs-10 col-sm-5" value="{{old
                                ('url')
                                }}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>






                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                    确定
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
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


@endsection