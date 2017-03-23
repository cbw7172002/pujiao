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
                    <a href="{{url('/admin/departmentPost/postList')}}">岗位列表</a>
                </li>
                <li class="active">添加</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    岗位列表
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

                    <form action="{{url('admin/departmentPost/addspost')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  对应部门 </label>
                            <div class="col-sm-9">
                                    <select name="depart" id="depart" class="col-xs-10 col-sm-5" style="width:120px">
                                        <option value="" selected>--部门--</option>
                                        @foreach($depart as $depart)
                                            <option value="{{$depart->id}}">{{$depart->departName}}</option>
                                        @endforeach
                                    </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>

                            </div>
                        </div>

                        <div class="space-4"></div>





                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 岗位名称 </label>

                            <div class="col-sm-9">
                                <input    type="text" id="postName"  name="postName" id="form-field-1" placeholder="岗位名称" class="col-xs-10 col-sm-5" value="" />
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
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/admin/js/addSubject.js') }}"></script>




@endsection