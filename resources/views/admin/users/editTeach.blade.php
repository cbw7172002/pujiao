@extends('layouts.layoutAdmin')
@section('content')
    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {
                }
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>

                <li>
                    <a href="{{url('/admin/users/teacherList')}}">用户管理</a>
                </li>
                <li class="active">用户管理列表</li>
            </ul><!-- .breadcrumb -->
        </div>
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


        <div class="page-content">
            <div class="page-header">
                <h1>
                    用户管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑授课
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" role="form" method="post" action="{{url('admin/users/updateTeach/' .$data->id)}}">
                        {{csrf_field()}}

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">授课年级</label>

                            <div class="col-sm-9">
                                <select id="gradeId" class="col-xs-10 col-sm-4" name="gradeId" required>
                                    @foreach($data->grades as $v)
                                        <option value="{{$v->id}}" {{$data->gradeId == $v->id ? 'selected' : ''}}>{{$v->gradeName}}</option>
                                    @endforeach
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">授课班级</label>

                            <div class="col-sm-9">
                                <select id="classId" name="classId" class="col-xs-10 col-sm-4" required>
                                    <option value="{{$data->classId}}">{{$data->className}}</option>
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">授课科目</label>

                            <div class="col-sm-9">
                                <select id="subjectId" name="subjectId" class="col-xs-10 col-sm-4" required>
                                    <option value="{{$data->subjectId}}">{{$data->subjectName}}</option>
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit" id="btnSub">
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

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script>
        $(function () {
            //Ajax提交，发送_token
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            });

            //获取所属班级
            $("#gradeId").change(function () {
                $('#classId').html('<option value="">-班级-</option>');
                $('#subjectId').html('<option value="">-学科-</option>');
                var gradeId = $("#gradeId").val();
                $.ajax({
                    type: "post",
                    url: "{{url('admin/users/getClass')}}",
                    data: {parentId: gradeId},
                    dataType: 'json',

                    success: function (result) {
                        var str = '';
//                        var str = '<option value="">-班级-</option>';
                        //学校
                        if (result.status == true) {
                            $.each(result.data, function (i, val) {
                                str += '<option value="' + val.id + '">' + val.className + '</option>';
                            })
                            $('#classId').html(str);
                        }
                    }
                });
            });
            //获取所属班级
            $("#classId").change(function () {
                $('#subjectId').html('<option value="">-学科-</option>');
                $.ajax({
                    type: "post",
                    url: "{{url('admin/users/getSubject')}}",
                    data: {},
                    dataType: 'json',

                    success: function (result) {
                        var str = '';
//                        var str = '<option value="">-学科-</option>';
                        //学校
                        if (result.status == true) {
                            $.each(result.data, function (i, val) {
                                str += '<option value="' + val.id + '">' + val.subjectName + '</option>';
                            })
                            $('#subjectId').html(str);
                        }
                    }
                });
            });
        });

    </script>
@endsection