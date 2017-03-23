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
                    <a href="{{url('/admin/resource/resourceList')}}">资源管理</a>
                </li>
                <li class="active">编辑资源</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    资源管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑资源
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

            <div class="row" ms-controller="searchSelect">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form action="{{url('admin/resource/doEditResource')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源标题 </label>

                            <div class="col-sm-9">
                                <input type="text"  name="resourceTitle" id="form-field-1"  placeholder="资源标题" class="col-xs-10 col-sm-5" value="{{$data->resourceTitle}}" />
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源类型 </label>

                            <div class="col-sm-9">
                                <select id="form-field-1" name="resourceType" class="col-xs-10 col-sm-5" ms-duplex="defaultType">
                                    <option ms-repeat="resourceType" ms-attr-value="[--el.id--]" ms-text="el.resourceTypeName"></option>
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
                                <input type="hidden" name="resourcePic" id="resourcePic" value="{{$data->resourcePic}}">
                                <img id="showImg" src="{{asset($data->resourcePic)}}" width="280" height="180"  alt="" onerror="this.src='/admin/image/back.png'">
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源描述 </label>

                            <div class="col-sm-9">
                                <textarea name="resourceIntro"  placeholder="资源描述" id="container" class="col-xs-10 col-sm-5" cols="50" rows="10" style="resize: none">{{$data->resourceIntro}}</textarea>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 资源标签 </label>

                            <div class="col-sm-9">
                                <select id="grade" name="resourceGrade"  class="col-xs-10 col-sm-2" ms-change="getChapter(1)" ms-duplex="defaultGrade">
                                    <option value="0">-- 年级 --</option>
                                    <option ms-repeat="grades" ms-attr-value="el.id" ms-text="el.gradeName"></option>
                                </select>

                                <select id="subject" name="resourceSubject"  class="col-xs-10 col-sm-2" ms-change="getChapter(2)" ms-duplex="defaultSubject">
                                    <option value="0">-- 科目 --</option>
                                    <option ms-repeat="subjects" ms-attr-value="el.id" ms-text="el.subjectName"></option>

                                </select>

                                <select id="edition" name="resourceEdition"  class="col-xs-10 col-sm-2" ms-change="getChapter(3)" ms-duplex="defaultEdition">
                                    <option value="0">-- 版本 --</option>
                                    <option ms-repeat="editions" ms-attr-value="el.id" ms-text="el.editionName"></option>
                                </select>

                                <select id="book" name="resourceBook"  class="col-xs-10 col-sm-2" ms-change="getChapter(4)" ms-duplex="defaultBook">
                                    <option value="0">-- 册别 --</option>
                                    <option ms-repeat="books" ms-attr-value="el.id" ms-text="el.bookName"></option>
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属知识点 </label>

                            <div class="col-sm-9">
                                <select id="form-field-1" name="resourceChapter" class="col-xs-10 col-sm-5" ms-duplex="defaultChapter">
                                    <option value="0">--知识点--</option>
                                    <option ms-repeat="chapter" ms-attr-value="[--el.id--]" ms-text="el.chapterName"></option>
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
                                    提交
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

    <script type="text/javascript" src="{{asset('admin/cut/Jcrop/Jcrop.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/cut/Jcrop/jquery.uploadify.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('/admin/cut/cut.js') }}"></script>

    <script>
        require(['/searchSelect'], function (detail) {
            detail.defaultGrade = '{{$data->resourceGrade }}' || null;
            detail.defaultSubject = '{{$data->resourceSubject }}' || null;
            detail.defaultEdition = '{{$data->resourceEdition }}' || null;
            detail.defaultBook = '{{$data->resourceBook }}' || null;
            detail.defaultChapter = '{{$data->resourceChapter }}' || null;
            detail.defaultType = '{{$data->resourceType }}' || null;
            detail.getChapter(5);

            avalon.scan();
        });
    </script>
@endsection