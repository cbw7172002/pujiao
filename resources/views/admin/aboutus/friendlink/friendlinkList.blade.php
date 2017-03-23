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
                    <a href="{{url('/admin/aboutUs/firmIntroList')}}">关于我们</a>
                </li>
                <li class="active">友情链接</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                    <span style=""  class="searchtype" iid="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$links->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$links->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($links->type == 1) selected @endif>ID</option>
                        <option value="2" @if($links->type == 2) selected @endif>友链名称</option>
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
                    友情链接
                    <small>
                        <i class="icon-double-angle-right"></i>
                        友情链接列表
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

            @permission('add.aboutus')
            <a href="{{url('/admin/aboutUs/addfriendlink')}}" class="btn btn-xs btn-info">
            <i class="icon-ok bigger-110">添加</i>
            </a>
            @endpermission

            <div class="row">
                {{--<div >--}}
                {{--<br>--}}
                {{--<form action="" method="get" >--}}
                {{--<input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="background:url('{{asset("/admin/image/2.png")}}') no-repeat;background-position:right;width:170px;" />--}}
                {{--<input type="text" name="beginTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="background:url('{{asset("/admin/image/2.png")}}') no-repeat;background-position:right;width:170px;margin-left: 10px;" />--}}
                {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;margin-left: 10px;" type="submit" value="筛选" />--}}
                {{--</form>--}}
                {{--</div>--}}

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                    <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>链接名称</th>
                                        <th>图片</th>
                                        <th>链接地址</th>
                                        <th>排序位置</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    @foreach($links as $link)
                                        <tbody>
                                        <tr>
                                            <td>{{$link->id}}</td>
                                            <td>{{$link->title}}</td>
                                            <td> <img src="{{asset($link->path)}}" alt="" width="60" height="40" > </td>
                                            <td>{{$link->url}}</td>
                                            <td>{{$link->postion}}</td>
                                            <td>
                                                @if($link->status == 0)
                                                    激活
                                                @elseif($link->status == 1)
                                                    禁用
                                                @endif
                                            </td>
                                            <td>{{$link->created_at}}</td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                    @permission('edit.aboutus')
                                                    <a href="{{url('/admin/aboutUs/editfriendlink/'.$link->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    @permission('delete.aboutus')
                                                    <a href="{{url('/admin/aboutUs/delfriendlink/'.$link->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                        <i class="icon-trash bigger-120"></i>
                                                    </a>
                                                    @endpermission


                                                    @permission('edit.aboutus')
                                                    <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                        <strong>友链状态</strong>
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                        <select id="activityStatus" class="col-xs-10 col-sm-2" onchange="frinendStatus({{$link->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">
                                                            <option value="11" selected></option>
                                                            <option value="0" >激活</option>
                                                            <option value="1" >禁用</option>
                                                        </select>
                                                    </span>
                                                    @endpermission


                                                </div>

                                            </td>
                                        </tr>

                                        </tbody>
                                    @endforeach


                                </table>
                                {!! $links->appends(app('request')->all())->render() !!}

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

        function frinendStatus(id,status){

            $.ajax({
                type: "get",
                data:{'id':id,'status':status},
                url: "/admin/aboutUs/frinendStatus",

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