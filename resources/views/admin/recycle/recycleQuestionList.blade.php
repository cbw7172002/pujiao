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
                    <a href="{{url('/admin/companyUser/companyUserList')}}">问答管理</a>
                </li>
                <li class="active">问答管理列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                    <span style=""  class="searchtype" iid="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>问答题目</option>
                        <option value="3" @if($data->type == 3) selected @endif>提问学生</option>
                        <option value="4" @if($data->type == 4) selected @endif>邀约教师</option>
                        <option value="">全部</option>
                    </select>

                     <span class="input-icon">
                        <span style="display: block;" class="input-icon" id="search1">
                            <input type="text" placeholder="Search ..." name="search" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;padding-left: 8px;" type="submit" value="筛选" />
                        </span>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    问答管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        问答管理列表
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



            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{--@permission('add.companyUser')--}}
                {{--<a href="{{url('/admin/companyUser/addcompanyUser')}}" class="btn btn-xs btn-info">--}}
                {{--<i class="icon-ok bigger-110">添加</i>--}}
                {{--</a>--}}
                {{--@endpermission@permission('add.companyUser')--}}
                {{--<a href="{{url('/admin/companyUser/addcompanyUser')}}" class="btn btn-xs btn-info">--}}
                {{--<i class="icon-ok bigger-110">添加</i>--}}
                {{--</a>--}}
                {{--@endpermission--}}
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>问答题目</th>
                                    <th>提问学生(用户名)</th>
                                    <th>提问学生(真实姓名)</th>
                                    <th>邀约教师</th>
                                    <th>提问状态</th>
                                    <th>学科分类</th>
                                    <th>提问时间</th>
                                    <th>回答时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $d)
                                    <tbody>
                                    <tr>
                                        <td>{{$d->id}}</td>
                                        <td>{{$d->qestitle}}</td>
                                        {{--<td>{{$d->username}}</td>--}}
                                        @if($d->type == 0)
                                            <td>{{$d->username}}</td>
                                        @elseif($d->type == 2)
                                            <td>{{$d->name}}</td>
                                        @endif
                                        @if($d->type == 0)
                                            <td>{{$d->realname}}</td>
                                        @elseif($d->type == 2)
                                            <td>{{$d->stuName}}</td>
                                        @endif
                                        {{--<td>{{$d->realname}}</td>--}}
                                        <td>{{$d->teaId}}</td>
                                        <td>
                                            @if($d->status == 1)
                                                未解答
                                            @elseif($d->status == 2)
                                                已解答
                                            @endif
                                        </td>
                                        <td>{{$d->subjectName}}</td>
                                        <td>{{$d->asktime}}</td>
                                        <td>{{$d->anstime}}</td>

                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                {{--@permission('edit.companyUser')--}}
                                                {{--<a href="{{url('/admin/question/editquestion/'.$d->id)}}" class="btn btn-xs btn-info">--}}
                                                {{--<i class="icon-edit bigger-120"></i>--}}
                                                {{--</a>--}}
                                                {{--@endpermission--}}

                                                {{--@permission('edit.companyUser')--}}
                                                {{--@if($d->status == 1)--}}
                                                    {{--<a href="{{url('/admin/question/viewquestion/'.$d->id)}}" class="btn btn-xs btn-info">--}}
                                                        {{--查看提问内容--}}
                                                    {{--</a>--}}
                                                {{--@elseif($d->status == 2)--}}
                                                    {{--<a href="{{url('/admin/question/viewquestion/'.$d->id)}}" class="btn btn-xs btn-info">--}}
                                                        {{--查看提问内容--}}
                                                    {{--</a>--}}

                                                    {{--<a href="{{url('/admin/question/replyquestion/'.$d->id)}}" class="btn btn-xs btn-success">--}}
                                                        {{--查看回答内容--}}
                                                    {{--</a>--}}
                                                {{--@endif--}}
                                                {{--@endpermission--}}


                                                @permission('edit.recycle')
                                                <a href="{{url('/admin/recycle/editRecycleQuestion/'.$d->id)}}" class="btn btn-xs btn-warning">
                                                    <i class="icon-reply bigger-120"></i>还原
                                                </a>
                                                @endpermission

                                                @permission('delete.companyUser')
                                                <a href="{{url('/admin/recycle/delRecycleQuestion/'.$d->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission

                                            </div>

                                        </td>
                                    </tr>

                                    </tbody>
                                @endforeach

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


    {{--<script>--}}



    {{--//审核状态--}}
    {{--function companyStatus(id,status){--}}

    {{--$.ajax({--}}
    {{--type: "get",--}}
    {{--data: {'id':id, 'status':status},--}}
    {{--url: "/admin/companyUser/companyStatus",--}}

    {{--dataType: 'json',--}}
    {{--success: function (res) {--}}

    {{--if(res == 1){--}}
    {{--location.reload();//刷新页面--}}
    {{--}--}}
    {{--}--}}
    {{--})--}}
    {{--}--}}




    {{--</script>--}}


    <script>

        //        $('.commentContentjs').each(function(){
        //            // var bbb = $(this).text().length;
        //            // alert(bbb);
        //            var maxwidth=15;
        //            if($(this).text().length>maxwidth){
        //                $($(this)).text($($(this)).text().substring(0,maxwidth));
        //                $($(this)).html($($(this)).html()+'…');
        //            }
        //        });


    </script>


@endsection