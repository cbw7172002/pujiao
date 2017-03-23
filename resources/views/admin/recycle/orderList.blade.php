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
                <li class="active">订单列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="{{url('/admin/order/orderList')}}" method="get" class="form-search">
                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="1"  @if($data->type == 1) selected @endif>订单号</option>
                        <option value="2"  @if($data->type == 2) selected @endif>订单名称</option>
                        <option value="3"  @if($data->type == 3) selected @endif>购买用户ID</option>
                        <option value="4"  @if($data->type == 4) selected @endif>购买用户</option>
                        <option value="5"  @if($data->type == 5) selected @endif>时间筛选</option>
                        <option value="">全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="@if($data->type != 5) display: block;  @else display: none; @endif" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />
                        </span>
                        <span style="@if($data->type == 5) display: block;  @else display: none; @endif" class="input-icon" id="search2">
                            <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                            <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;" />
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />
                        </span>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    订单列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        订单列表
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
                                        <th>交易编号</th>
                                        <th>订单名称</th>
                                        <th>订单价格</th>
                                        <th>实付金额</th>
                                        <th>支付方式</th>
                                        <th>购买用户ID</th>
                                        <th>购买用户</th>
                                        <th>邀请人ID</th>
                                        <th>邀请人</th>
                                        <th>订单类型</th>
                                        {{--<th>课程</th>--}}
                                        <th>应退金额</th>
                                        <th>已退金额</th>
                                        <th>付款时间</th>
                                        <th>订单状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $order)
                                        <tr>
                                        {{--<td class="center">--}}
                                            {{--<label>--}}
                                                {{--<input type="checkbox" class="ace" />--}}
                                                {{--<span class="lbl"></span>--}}
                                            {{--</label>--}}
                                        {{--</td>--}}

                                        <td>
                                            <a href="#">{{$order->id}}</a>
                                        </td>
                                        <td>{{$order->orderSn}}</td>
                                        <td>{{$order->tradeSn}}</td>
                                        <td>{{$order->orderTitle}}</td>
                                        <td>{{$order->orderPrice}}</td>
                                        <td>{{$order->payPrice}}</td>
                                        <td>{{$order->payType ? '微信支付' : '支付宝'}}</td>
                                        <td>{{$order->userId}}</td>
                                        <td>{{$order->userName}}</td>
                                        <td>{{$order->teacherId}}</td>
                                        <td>{{$order->teacherName}}</td>
                                        <td>
                                            @if($order->orderType == 0)
                                                创课课程订单
                                            @elseif($order->orderType == 1)
                                                申请课程订单
                                            @elseif($order->orderType == 2)
                                                辅导课程订单
                                            @endif
                                        </td>
                                        {{--<td>--}}
                                            {{--@if($order->orderType == 0)--}}
                                                {{--<a href="{{url('/lessonSubject/detail/'.$order->courseId)}}">查看课程</a>--}}
                                            {{--@else--}}
                                                {{--<a href="{{url('/lessonComment/detail/'.$order->courseId)}}">查看课程</a>--}}
                                            {{--@endif--}}
                                        {{--</td>--}}
                                        <td>{{$order->refundableAmount}}</td>
                                        <td>{{$order->refundAmount}}</td>
                                        <td>{{$order->payTime}}</td>
                                        <td>
                                            @if($order->status == 0)
                                                已付款
                                            @elseif($order->status == 1)
                                                待辅导
                                            @elseif($order->status == 2)
                                                已完成
                                            @elseif($order->status == 3)
                                                退款中
                                            @elseif($order->status == 4)
                                                已退款
                                            @endif
                                        </td>

                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                {{--</button>--}}

                                                {{--<span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">--}}
                                                    {{--<strong>订单状态</strong>--}}
                                                    {{--<span class="icon-caret-down icon-on-right"></span>--}}
                                                    {{--<select id="selectCheck" class="col-xs-10 col-sm-2" onchange="selectCheck({{$order->id}},this.value);" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">--}}
                                                        {{--<option value="44" selected></option>--}}
                                                        {{--<option value="0" >已付款</option>--}}
                                                        {{--<option value="1" >待点评</option>--}}
                                                        {{--<option value="2" >已完成</option>--}}
                                                        {{--<option value="3" >退款中</option>--}}
                                                        {{--<option value="4" >已退款</option>--}}
                                                    {{--</select>--}}
                                                {{--</span>--}}


                                                {{--<div href="" class="btn btn-xs btn-warning" ms-click="commentdetailpop({{$order->id}})">--}}
                                                    {{--<i class="icon-pencil bigger-120"></i>添加备注--}}
                                                {{--</div>--}}
                                                {{--<a href="{{url('/admin/order/remarkList/'.$order->id)}}" class="btn btn-xs btn-warning">--}}
                                                    {{--<i class=""></i>查看备注--}}
                                                {{--</a>--}}
                                                {{--<a href="{{url('/admin/order/editRefundmoney/'.$order->id)}}" class="btn btn-xs btn-info">--}}
                                                    {{--<i class="icon-pencil bigger-120"></i>应退金额--}}
                                                {{--</a>--}}
                                                {{--<a href="{{url('/admin/order/editRetiredmoney/'.$order->id)}}" class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-pencil bigger-120"></i>已退金额--}}
                                                {{--</a>--}}

                                                @permission('edit.recycle')
                                                <a href="{{url('/admin/recycle/editRecycleOrder/'.$order->id)}}" class="btn btn-xs btn-warning">
                                                    <i class="icon-reply bigger-120"></i>还原
                                                </a>
                                                @endpermission

                                                @permission('del.recycle')
                                                <a href="{{url('/admin/recycle/delRecycleOrder/'.$order->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
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

                                @if(count($excel))
                                    <form action="{{url('admin/excel/orderExport')}}" method="post" style="float: right;margin-top:65px;margin-right:-130px;">
                                        <input type="submit" class="btn btn-xs btn-info"  value="导出订单" style="width:86px; cursor: pointer; margin-top:-87px;margin-right:130px;" />
                                        {{csrf_field()}}
                                        <input type="hidden" name="excels" value="{{$excel}}"/>
                                    </form>
                                @endif

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
                    <div class="detailtitle">备注</div>
                    <div class="deldetail" ms-click="deldetail"></div>
                </div>
                <div class="content1">
                    <div class="contenterror">
                        <div class="errortitle" style="height: 30px"></div>
                        <div class="errormsg">
                            <lable>备注:</lable>
                            <textarea name="content" maxlength="100"  placeholder="请填写备注..." id="errortext" cols="30" rows="13" required ms-duplex="content"></textarea>
                        </div>

                    </div>
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    <div class="bottom" id="surebtn">
                        <button class="suer_btn" ms-click="submit">确认</button>
                    </div>
                    {{--<div class="bottom" id="hiddenbtn">--}}
                    {{--<div class="suer_btn">确认</div>--}}
                    {{--</div>--}}

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
         require(['/order/order'], function (detail) {
             avalon.scan();
        });
    </script>
@endsection