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
                    <a href="{{url('/admin/complaint/complaintList')}}">意见反馈管理</a>
                </li>
                <li class="active">意见反馈列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">
                    <select  id="type" name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>未处理</option>
                        <option value="2" @if($data->type == 2) selected @endif>已处理</option>
                    </select>

                     <span class="input-icon">
                        <span style="display: block;" class="input-icon" id="search1">
                            {{--<input type="text" placeholder="Search ..." name="search" class="nav-search-input" id="nav-search-input" autocomplete="off" />--}}
                            <input id="hidden" type="hidden"  name="hidden" value=""  />
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
                    意见反馈管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        意见反馈列表
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

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>反馈用户</th>
                                    <th>联系方式</th>
                                    <th>问题分类</th>
                                    <th>问题描述</th>
                                    <th>状态</th>
                                    <th>相关网页地址</th>
                                    <th>反馈时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $d)
                                    <tbody>
                                    <tr>
                                        <td>{{$d->id}}</td>
                                        <td>{{$d->username}}</td>
                                        <td>{{$d->contact}}</td>
                                        <td>
                                            @if($d->type == 1)
                                                课程学习
                                            @elseif($d->type == 2)
                                                网站建议
                                            @elseif($d->type == 3)
                                                其他问题
                                            @endif
                                        </td>
                                        <td>{{$d->content}}</td>
                                        <td>
                                            @if($d->status == 0)
                                                未处理
                                            @elseif($d->status == 1)
                                                已处理
                                            @endif
                                        </td>
                                        <td>{{$d->weburl}}</td>
                                        <td>{{$d->feedbacktime}}</td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">


                                                @permission('delete.companyUser')
                                                <a href="{{url('/admin/complaint/delcomplaint/'.$d->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission


                                                <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                    <strong>审核状态</strong>
                                                    <span class="icon-caret-down icon-on-right"></span>
                                                    <select id="courseChecks" class="col-xs-10 col-sm-2" onchange="companyStatus({{$d->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">
                                                        <option value="11" selected></option>
                                                        <option value="0" >未处理</option>
                                                        <option value="1" >已处理</option>
                                                    </select>
                                                </span>


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


    <script>



        //审核状态
        function companyStatus(id,status){
            $.ajax({
                type: "get",
                data: {'id':id, 'status':status},
                url: "/admin/complaint/complaintStatus",
                dataType: 'json',
                success: function (res) {
                    if(res == 1){
                        location.reload();//刷新页面
                    }
                }
            })
        }


    </script>



    <script>

        $('#type').change(function(){
            var typeId = $('#type').val()
            var hidden = $("#hidden").attr("value",typeId);
            var hidden = $("#hidden").val()
            //            console.log(typeId)
            console.log(hidden)
        })



    </script>




@endsection