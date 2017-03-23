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
                    <a href="{{url('/admin/order/orderList')}}">订单管理</a>
                </li>
                <li class="active">备注列表</li>
            </ul><!-- .breadcrumb -->

            {{--<div class="nav-search" id="nav-search">--}}
                {{--<form action="{{url('/admin/order/refundList')}}" method="get" class="form-search">--}}
                    {{--<select name="type" id="form-field-1" class="searchtype">--}}
                        {{--<option value="1"  @if($data->type == 1) selected @endif>订单号</option>--}}
                        {{--<option value="2"  @if($data->type == 2) selected @endif>订单名称</option>--}}
                        {{--<option value="3"  @if($data->type == 3) selected @endif>购买用户</option>--}}
                        {{--<option value="4"  @if($data->type == 4) selected @endif>时间筛选</option>--}}
                        {{--<option value="">全部</option>--}}
                    {{--</select>--}}
                    {{--<span class="input-icon">--}}
                        {{--<span style="@if($data->type != 4) display: block;  @else display: none; @endif" class="input-icon" id="search1">--}}
                            {{--<input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />--}}
                            {{--<i class="icon-search nav-search-icon"></i>--}}
                            {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />--}}
                        {{--</span>--}}
                        {{--<span style="@if($data->type == 4) display: block;  @else display: none; @endif" class="input-icon" id="search2">--}}
                            {{--<input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />--}}
                            {{--<input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;" />--}}
                            {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />--}}
                        {{--</span>--}}
                    {{--</span>--}}
                {{--</form>--}}
            {{--</div><!-- #nav-search -->--}}
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    备注列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        备注列表
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
                                        {{--<th class="center">--}}
                                            {{--<label>--}}
                                                {{--<input type="checkbox" class="ace" />--}}
                                                {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                        {{--</th>--}}
                                        <th>ID</th>
                                        <th>订单号</th>
                                        <th>备注内容</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $remark)
                                        <tr>
                                        {{--<td class="center">--}}
                                            {{--<label>--}}
                                                {{--<input type="checkbox" class="ace" />--}}
                                                {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                        {{--</td>--}}

                                        <td>
                                            <a href="#">{{$remark->id}}</a>
                                        </td>
                                        <td>{{$remark->orderSn}}</td>
                                        <td>{{$remark->content}}</td>
                                        <td>{{$remark->created_at}}</td>


                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                {{--</button>--}}


                                                @permission('del.order')
                                                <a href="{{url('/admin/order/delRemark/'.$remark->orderid.'/'.$remark->id.'/'.$data->status)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission

                                                {{--<div href="" class="btn btn-xs btn-warning" ms-click="commentdetailpop({{$order->id}})">--}}
                                                    {{--<i class="icon-flag bigger-120"></i>--}}
                                                {{--</div>--}}

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
        <div id="detailpupUpback" class="detailpupUpback" ms-visible="popup">
            <div class="popup1">
                <div class="detailtopbaner">
                    <div class="detailtitle">详情</div>
                    <div class="deldetail" ms-click="deldetail"></div>
                </div>
                <div class="content1">

                    <div class="form-group">
                        <lable class="labtitle">标题:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseTitle" />
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">演奏学员:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.username">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">学员手机号:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.phone">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">学员留言:</lable>
                        <textarea name="" id="" readonly cols="30" rows="10" ms-duplex="info.message"></textarea>
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">名师:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.teachername">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">名师手机号:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.teacherphone">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">浏览数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseView">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">观看数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.coursePlayView">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">收藏数:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.courseFav">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">上传日期:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.addTime">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">最近审核时间:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.lastCheckTime">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">创建时间:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.created_at">
                    </div>

                    <div class="form-group">
                        <lable class="labtitle">更新时间:</lable>
                        <input type="text" readonly placeholder="" ms-duplex="info.updated_at">
                    </div>


                </div>
            </div>
        </div>

    </div><!-- /.main-content -->

@endsection
@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/order/order.js') }}"></script>
    <script>
//         require(['/selectCheck'], function (detail) {
//             avalon.scan();
//        });
    </script>
@endsection