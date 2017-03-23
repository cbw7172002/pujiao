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
                    <a href="{{url('/admin/companyUser/companyUserList')}}">后台用户管理</a>
                </li>
                <li class="active">后台用户管理列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">
                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($company->type == 1) selected @endif>姓名</option>
                        <option value="2" @if($company->type == 2) selected @endif>部门</option>
                        <option value="3" @if($company->type == 3) selected @endif>岗位</option>
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
                    后台用户管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        后台用户管理列表
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
                @permission('add.companyUser')
                <a href="{{url('/admin/companyUser/addcompanyUser')}}" class="btn btn-xs btn-info">
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
                                    <th>用户名</th>
                                    <th>真实姓名</th>
                                    <th>电话</th>
                                    <th>邮箱</th>
                                    <th>部门</th>
                                    <th>岗位</th>
                                    <th>状态</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($company as $com)
                                    <tbody>
                                    <tr>
                                        <td>{{$com->id}}</td>
                                        <td>{{$com->username}}</td>
                                        <td>{{$com->realname}}</td>
                                        <td>{{$com->phone}}</td>
                                        <td>{{$com->email}}</td>
                                        <td>{{$com->departName}}</td>
                                        <td>{{$com->postName}}</td>
                                        <td>
                                            @if($com->checks == 0)
                                                激活
                                            @elseif($com->checks == 1)
                                                锁定
                                            @endif
                                        </td>

                                        <td>{{$com->created_at}}</td>
                                    
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                @permission('edit.companyUser')
                                                <a href="{{url('/admin/companyUser/editcompanyUser/'.$com->id)}}" class="btn btn-xs btn-info">
                                                    <i class="icon-edit bigger-120"></i>
                                                </a>
                                                @endpermission

                                                @permission('delete.companyUser')
                                                <a href="{{url('/admin/companyUser/delcompanyUser/'.$com->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission

                                                @permission('edit.companyUser')
                                                <a href="{{url('admin/companyUser/resetPassword/'.$com->id)}}"
                                                   class="btn btn-xs btn-inverse" name="reset-pass">
                                                    <strong>重置密码</strong>
                                                </a>

                                                <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                    <strong>审核状态</strong>
                                                    <span class="icon-caret-down icon-on-right"></span>
                                                    <select id="courseChecks" class="col-xs-10 col-sm-2" onchange="companyStatus({{$com->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">
                                                        <option value="11" selected></option>
                                                        <option value="0" >激活</option>
                                                        <option value="1" >锁定</option>
                                                    </select>
                                                </span>
                                                @endpermission



                                            </div>

                                        </td>
                                    </tr>

                                    </tbody>
                                @endforeach

                            </table>
                            {!! $company->appends(app('request')->all())->render() !!}
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
                url: "/admin/companyUser/companyStatus",

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