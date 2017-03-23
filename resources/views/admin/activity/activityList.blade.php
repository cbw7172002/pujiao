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
                    <a href="{{url('/admin/activity/activityList')}}">活动赛事管理</a>
                </li>
                <li class="active">活动赛事列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                     <span style=""  class="searchtype" iid="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$activity->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$activity->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                     </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="5" @if($activity->type == 5) selected @endif>ID</option>
                        <option value="6" @if($activity->type == 6) selected @endif>活动名称</option>
                        <option value="1" @if($activity->type == 1) selected @endif>未上线</option>
                        <option value="2" @if($activity->type == 2) selected @endif>进行中</option>
                        <option value="3" @if($activity->type == 3) selected @endif>未开始</option>
                        <option value="4" @if($activity->type == 4) selected @endif>已结束</option>
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
                    活动赛事管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        活动赛事列表
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
                    @permission('add.activity')
                    <a href="{{url('/admin/activity/addactivity')}}" class="btn btn-xs btn-info">
                        <i class="icon-ok bigger-110">添加</i>
                    </a>
                    @endpermission

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>活动名称</th>
                                            <th>开始时间</th>
                                            <th>结束时间</th>
                                            <th>封面图片</th>
                                            <th>活动状态</th>
                                            <th>创建时间</th>
                                            <th>更新时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    
                                    @foreach($activity as $act)
                                    <tbody>
                                        <tr>
                                            <td>{{$act->id}}</td>
                                            <td>{{$act->title}}</td>
                                            <td>{{$act->beginTime}}</td>
                                            <td>{{$act->endTime}}</td>
                                            <td> <img src="{{asset($act->path)}}" alt="" width="80" height="60" > </td>
                                            <td >
                                                @if($act->status == 0)
                                                    未上线
                                                @elseif($act->status == 1)
                                                    进行中
                                                @elseif($act->status == 2)
                                                    未开始
                                                @elseif($act->status == 3)
                                                    已结束
                                                @endif
                                            </td>
                                            <!-- <td>
                                                @if($act->status == 0)
                                                        未上线
                                                    @elseif($act->status == 1)
                                                        进行中
                                                    @elseif($act->status == 2)
                                                        未开始
                                                    @elseif($act->status == 3)
                                                        已结束
                                                @endif
                                            </td> -->
                                            <td>{{$act->created_at}}</td>
                                            <td>{{$act->updated_at}}</td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                    @permission('edit.activity')
                                                    <a href="{{url('/admin/activity/editactivity/'.$act->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    @permission('delete.activity')
                                                    <a href="{{url('/admin/activity/delactivity/'.$act->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                        <i class="icon-trash bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    @permission('edit.activity')
                                                    <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                        <strong>审核状态</strong>
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                        <select id="courseChecks" class="col-xs-10 col-sm-2" onchange="courseChecks({{$act->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">
                                                            <option value="11" selected></option>
                                                            <option value="0" >未上线</option>
                                                            <option value="1" >进行中</option>
                                                            <option value="2" >未开始</option>
                                                            <option value="3" >已结束</option>
                                                        </select>
                                                    </span>
                                                    @endpermission


                                                </div>

                                            </td>
                                        </tr>
                                   
                                    </tbody>
                                    @endforeach

                                </table>
                                {!! $activity->appends(app('request')->all())->render() !!}
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


    <script>

        function courseChecks(id,status){

            $.ajax({
                type: "get",
                data:{'id':id,'status':status},
                url: "/admin/activity/activityStatus",

                dataType: 'json',
                success: function (res) {
                    if(res == 1){
                        location.reload();//刷新页面
                    }
                }
            })
        }


    </script>

@endsection