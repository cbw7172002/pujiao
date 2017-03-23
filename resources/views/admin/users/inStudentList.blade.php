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
                    <a href="{{url('/admin/users/userList')}}">用户管理</a>
                </li>
                <li class="active">在线学生列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search" style="width:770px;">
                <form action="" method="get" class="form-search">
                    <input type="text" style="width:180px;padding-left:5px; padding-right: 5px;background:#fff url('/admin/image/2.png') no-repeat 153px 3px" name="beginTime"  placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$search['beginTime'] ? $search['beginTime'] : ''}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                    <input type="text" style="width:180px;padding-left:5px;padding-right: 5px;margin-left:5px;background:#fff url('/admin/image/2.png') no-repeat 153px 3px" name="endTime"  placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$search['endTime'] ? $search['endTime'] : ''}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;" />

                    <select name="type" id="form-field-1" class="searchtype input-select" style="width:82px;margin-left:5px;">
                        <option value="" {{$search['type'] == '' ? 'selected':''}}>-请选择-</option>
                        <option value="1" {{$search['type'] == 1 ? 'selected':''}}>用户名</option>
                        <option value="2" {{$search['type'] == 2 ? 'selected':''}}>姓名</option>
                        <option value="3" {{$search['type'] == 3 ? 'selected':''}}>手机号</option>
                        <option value="4" {{$search['type'] == 4 ? 'selected':''}}>全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="display: block;" class="input-icon" id="search1">
                            <input type="text" placeholder="请输入..." name="search" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;padding-left: 8px;" type="submit" value="搜索" />
                        </span>
                    </span>
                </form>

                @permission('user.list')
                @if(count($excels))
                    <form action="{{url('admin/excel/userInfoExport')}}" method="post" style="float:right;display: inline-block">
                        <input type="submit" class="btn btn-xs btn-info"  value="下载Excel" style="width:86px;height:28px; cursor: pointer; margin-top:-58px;" />
                        {{csrf_field()}}
                        <input type="hidden" name="excels" value="{{$excels}}"/>
                    </form>
                @endif
                @endpermission
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    用户管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        用户管理列表
                    </small>
                    @permission('add.user')
                    <a href="{{url('admin/users/addUser')}}" class="btn btn-xs btn-info"
                       style="margin-left:8px;">
                        <strong class="icon-expand-alt bigger-30">&nbsp;添加用户</strong>
                    </a>
                    @endpermission
                    {{--@permission('edit.user')--}}
                    {{--<a href="{{url('admin/users/oneClickUpgrades')}}" class="btn btn-xs btn-info"--}}
                       {{--style="margin-left:8px;" onClick="return confirm('确定要升级吗？')">--}}
                        {{--<strong class="icon-expand-alt bigger-30">&nbsp;一键升级</strong>--}}
                    {{--</a>--}}
                    {{--@endpermission--}}


                </h1>
            </div><!-- /.page-header -->

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if(count($errors))
                <div class="alert alert-danger">
                    @if(is_object($errors))
                        @foreach ($errors->all() as $error)
                           {{ $error }}
                        @endforeach
                    @else
                        {{$errors}}
                    @endif
                </div>
            @endif


            <div class="row">

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <form action="{{url('admin/excel/userInfoImport/instudent')}}" method="post" enctype="multipart/form-data" style="float:right;">
                                    @permission('add.user')
                                        <input type="submit" class="btn btn-xs btn-info" id="infoExport" value="导入用户信息" style="width:86px; cursor: pointer;margin-left:40px;" />
                                        <input type="file" name="excel" style="float:right;width:50%; cursor: pointer;margin-right:0;"/>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    @endpermission

                                </form>
                                @permission('add.user')
                                    <a href="{{url('admin/excel/userInfoTemplate/instudent')}}" class="btn btn-xs btn-info" style="float: right;margin-right:-30px;">
                                        下载导入模板
                                    </a>
                                @endpermission



                                <table id="sample-table-1" class="table table-striped table-bordered table-hover center">
                                    <thead>
                                    <tr>
                                        <th class="center" style="vertical-align: middle">用户ID</th>
                                        <th class="center" style="vertical-align: middle">用户名</th>
                                        <th class="center" style="vertical-align: middle">用户头像</th>
                                        <th class="center" style="vertical-align: middle">用户状态</th>
                                        <th class="center" style="vertical-align: middle">用户角色</th>
                                        <th class="center" style="vertical-align: middle">学号</th>
                                        <th class="center" style="vertical-align: middle">所属学级</th>
                                        <th class="center" style="vertical-align: middle">所属年级</th>
                                        <th class="center" style="vertical-align: middle">所在班级</th>
                                        <th class="center" style="vertical-align: middle">创建时间</th>
                                        <th class="center" style="vertical-align: middle">修改时间</th>
                                        <th class="center"  style="vertical-align: middle">操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $value)
                                        <tr>
                                            <td class="center" style="vertical-align: middle">{{$value->id}}</td>
                                            <td style="vertical-align: middle"><a href="{{url('admin/users/show/instudent/'.$value->id)}}">{{$value->username}}</a></td>
                                            <td style="vertical-align: middle"><img src="{{asset($value->pic)}}" alt="" width="40" height="40" onerror="this.src='/admin/image/back.png'"></td>
                                            <td id="checks{{$value->id}}" style="vertical-align: middle">@if($value->checks == 0)激活@elseif($value->checks == 1)<span style=" color:red">禁用</span>@endif</td>
                                            <td style="vertical-align: middle">学生</td>
                                            <td style="vertical-align: middle">{{$value->sno ? $value->sno : '暂无'}}</td>
                                            <td style="vertical-align: middle">{{$value->schoolYear ? $value->schoolYear : '暂无'}}</td>
                                            <td style="vertical-align: middle">{{$value->gradeName ? $value->gradeName : '暂无'}}</td>
                                            <td style="vertical-align: middle">{{$value->className ? $value->className : '暂无'}}</td>
                                            <td style="vertical-align: middle">{{$value->created_at}}</td>
                                            <td style="vertical-align: middle">{{$value->updated_at}}</td>
                                            <td style="vertical-align: middle;width:25%">
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    @permission('edit.user')
                                                    <a href="{{url('admin/users/editUser/inStudent/'.$value->id)}}"
                                                       class="btn btn-xs btn-info">
                                                        <strong>编辑</strong>
                                                    </a>
                                                    @endpermission

                                                    @permission('delete.user')
                                                    <a href="{{url('admin/users/delUser/'.$value->id)}}"
                                                       class="btn btn-xs btn-danger" onclick="return confirm('确定要删除该用户?');">
                                                        <strong>删除</strong>
                                                    </a>
                                                    @endpermission

                                                    @permission('resetPass.user')
                                                    <a href="{{url('admin/users/resetPass/instudent/'.$value->id)}}"
                                                       class="btn btn-xs btn-inverse" name="reset-pass">
                                                        <strong>重置密码</strong>
                                                    </a>
                                                    @endpermission

                                                    @permission('user.list')
                                                    <a href="{{url('admin/users/show/instudent/'.$value->id)}}"
                                                       class="btn btn-xs btn-success" name="person-detail">
                                                        <strong>查看详情</strong>
                                                    </a>
                                                    @endpermission

                                                    @permission('changeStatus.user')
                                                    <span class="btn btn-xs btn-primary" name="btn-status" style="position: relative;display: inline-block;">

                                                        <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 16px;">
                                                            审核状态
                                                            <span class="icon-caret-down icon-on-right"></span>
                                                        </span>
                                                    <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                        <li><a href="{{url('/admin/users/changeStatus/'.$value->id).'/0'}}">激活</a></li>

                                                        <li><a href="{{url('/admin/users/changeStatus/'.$value->id.'/1')}}">锁定</a></li>
                                                    </ul>
                                                    </span>
                                                    @endpermission

                                               </div>
                                            </td>
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                                {!! $data -> appends( app('request') -> all() ) -> render() !!}
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
@endsection