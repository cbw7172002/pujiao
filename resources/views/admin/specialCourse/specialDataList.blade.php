@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
@endsection
@section('content')
    <div class="main-content" ms-controller="specialcommentdetail">
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
                    <a href="{{url('/admin/specialCourse/specialCourseList')}}">创课课程</a>
                </li>
                <li class="active">课程资料列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="{{url('/admin/specialCourse/dataList/'.$data->courseId)}}" method="get" class="form-search">
                    <span style=""  class="searchtype" iid="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>资料名称</option>
                        <option value="">全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                        </span>
                    </span>

                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    课程资料列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        课程资料列表
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

            {{--@permission('add.course')--}}
            {{--<a href="{{url('/admin/specialCourse/addData/'.$data->courseId)}}" class="btn btn-xs btn-info">--}}
                {{--<i class="icon-ok bigger-110">添加</i>--}}
            {{--</a>--}}
            {{--@endpermission--}}

            <div class="row" ms-controller="data">

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
                                        {{--<th>课程名称</th>--}}
                                        <th>资料名称</th>
                                        <th>资料</th>
                                        <th>大小</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>

                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $coursedata)
                                        <tr>
                                            {{--<td class="center">--}}
                                            {{--<label>--}}
                                            {{--<input type="checkbox" class="ace" />--}}
                                            {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                            {{--</td>--}}

                                            <td>
                                                <a href="#">{{$coursedata->id}}</a>
                                            </td>
                                            <td>{{$coursedata->dataName}}</td>
                                            <td>
                                                @if(!$coursedata->courseLowPath && $coursedata->type != 'pdf')
                                                    @if($coursedata->msg['code'] == '200')
                                                        正在转码...
                                                    @else
                                                        {{$coursedata->msg['message']}}
                                                    @endif
                                                @else
                                                    <span class="lookVideo" onclick="lookVideo('{{$coursedata->dataPath}}')" style="color: #00a0e9;cursor:pointer;">查看</span>
                                                @endif
                                            </td>
                                            <td>{{$coursedata->size}}</td>
                                            <td>{{$coursedata->status ? '锁定' : '激活'}}</td>
                                            <td>{{$coursedata->created_at}}</td>
                                            <td>{{$coursedata->updated_at}}</td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                    {{--</button>--}}

                                                    @permission('edit.course')
                                                    <span class="btn btn-xs btn-inverse" style="position: relative;display: inline-block;">
                                                        <strong>状态</strong>
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                        <select id="" class="col-xs-10 col-sm-2" onchange="coursedataCheck({{$coursedata->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:50px;">
                                                            <option value="44" selected></option>
                                                            <option value="0" >激活</option>
                                                            <option value="1" >锁定</option>
                                                        </select>
                                                    </span>
                                                    @endpermission


                                                    @permission('edit.course')
                                                    <a href="{{url('/admin/specialCourse/editData/'.$coursedata->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    @endpermission

                                                    @permission('del.course')
                                                    <a href="{{url('/admin/specialCourse/delData/'.$data->courseId.'/'.$coursedata->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
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

        <!--弹窗显示详情-->
        <div id="videodetailpupUpback" class="videodetailpupUpback" ms-click="bakPop">
            <div class="videopopup1" style="width: 800px;height: 400px; margin-top: -200px;margin-left: -380px;">
                <div class="videodetailtopbaner">
                    <div class="detailtitle" style="width: 600px;padding-left: 40px;"></div>
                    <div class="deldetail" style="float: right;"></div>
                </div>
                <div class="">
                    <iframe border=2 id="detailimg" frameborder=0 width=800 height=400 marginheight=0 marginwidth=0 scrolling=no src="">
                    </iframe>
                {{--</div>--}}
                </div>
            </div>
        </div><!-- /.main-content -->

@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/specialCourse/specialChapter.js') }}"></script>

    <script>
        function lookVideo(dataPath){
//            console.log(dataPath);
            $('#detailimg').attr('src',dataPath);
            $('#videodetailpupUpback').css('display','block');
        }
        $('.deldetail').click(function(){
            $('#videodetailpupUpback').css('display','none');
        })
    </script>



@endsection