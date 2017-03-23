@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
@endsection
@section('content')
    <div class="main-content" ms-controller="commentdetail">
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
                    <a href="{{url('/admin/loginVideo/loginVideoList')}}">内容管理</a>
                </li>
                <li class="active">登录视频推荐列表</li>
            </ul><!-- .breadcrumb -->

        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    登录视频推荐列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        登录视频推荐列表
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

            @permission('add.course')
            <a href="{{url('/admin/loginVideo/addLoginVideo')}}" class="btn btn-xs btn-info">
                <i class="icon-ok bigger-110">添加</i>
            </a>
            @endpermission

            <div class="row">

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        {{--<th class="center">--}}
                                            {{--<label>--}}
                                                {{--<input type="checkbox" class="ace" />--}}
                                                {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                        {{--</th>--}}
                                        <th>ID</th>
                                        <th>课程</th>
                                        <th>封面图</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $videos)
                                        <tr>
                                        {{--<td class="center">--}}
                                            {{--<label>--}}
                                                {{--<input type="checkbox" class="ace" />--}}
                                                {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                        {{--</td>--}}

                                        <td>
                                            <a href="#">{{$videos->id}}</a>
                                        </td>
                                        <td style="color: #0b6cbc">
                                            <span class="lookVideo" onclick="lookVideo('{{$videos->video}}')" style="color: #00a0e9;cursor:pointer;">查看</span>
                                        </td>
                                        <td>
                                            <img src="{{asset($videos->pic)}}" alt="" width="50px" height="50px" onerror="this.src='/admin/image/back.png'">
                                        </td>

                                        <td>{{$videos->created_at}}</td>
                                        <td>{{$videos->updated_at}}</td>


                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                {{--</button>--}}

                                                @permission('edit.course')
                                                <a href="{{url('/admin/loginVideo/editLoginVideo/'.$videos->id)}}" class="btn btn-xs btn-info">
                                                    <i class="icon-edit bigger-120"></i>
                                                </a>
                                                @endpermission

                                                @permission('del.course')
                                                <a href="{{url('/admin/loginVideo/delLoginVideo/'.$videos->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission

                                            </div>

                                            <div class="visible-xs visible-sm hidden-md hidden-lg">
                                                <div class="inline position-relative">
                                                    <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-cog icon-only bigger-110"></i>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                        <li>
                                                            <a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="icon-zoom-in bigger-120"></i>
																				</span>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="icon-edit bigger-120"></i>
																				</span>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="icon-trash bigger-120"></i>
																				</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $data->appends(app('request')->all())->render() !!}

                            </div><!-- /.table-responsive -->
                        </div><!-- /span -->
                    </div><!-- /row -->

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->


        <!--视频弹窗显示详情-->
        <div id="videodetailpupUpback" class="videodetailpupUpback">
            <div class="videopopup1" style="width: 700px;height: 400px;margin-top: -150px;margin-left: -350px;">
                <div class="videodetailtopbaner">
                    <div class="detailtitle" style="width: 650px;padding-left: 40px">视频</div>
                    <div class="deldetail" style="float: right"></div>
                </div>
                <div class="content1">
                    <div id="myplayer" ></div>
                </div>
            </div>
        </div>

    </div><!-- /.main-content -->

@endsection
@section('js')

    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>


    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin/js/videoPop.js') }}"></script>
@endsection