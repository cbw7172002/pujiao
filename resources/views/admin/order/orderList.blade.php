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
                    <a href="{{url('/admin/order/orderList/5')}}">订单管理</a>
                </li>
                <li class="active">订单列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="{{url('/admin/order/orderList/'.$data->status)}}" method="get" class="form-search">

                    <span style=""  class="searchtype" id="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1"  @if($data->type == 1) selected @endif>ID</option>
                        <option value="2"  @if($data->type == 2) selected @endif>订单号</option>
                        <option value="3"  @if($data->type == 3) selected @endif>交易编号</option>
                        <option value="4"  @if($data->type == 4) selected @endif>订单名称</option>
                        <option value="5"  @if($data->type == 5) selected @endif>购买用户ID</option>
                        <option value="6"  @if($data->type == 6) selected @endif>购买用户</option>
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
                    订单列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        @if($data->status == 5)
                            未付款订单列表
                        @elseif($data->status == 0)
                            已付款订单列表
                        @elseif($data->status == 1)
                            待点评订单列表
                        @elseif($data->status == 2)
                            已完成订单列表
                        @elseif($data->status == 3)
                            退款中订单列表
                        @elseif($data->status == 4)
                            已退款订单列表
                        @elseif($data->status == 8)
                            全部订单
                        @endif
                    </small>

                    @permission('del.order')
                        @if($data->status == 5)
                        <a href="{{url('/admin/order/deleteOrders')}}" class="btn btn-xs btn-info" onclick="return confirm('确定要清除垃圾订单吗?');" style="float: right;margin-right: 50px;">
                            清除垃圾订单
                        </a>
                        @endif
                    @endpermission

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

            @permission('del.order')
            @if($data->status == 4)
                <div class="btn btn-xs btn-info" id="alldelete" onclick="return confirm('确定要批量删除订单吗?');">
                    批量删除
                </div>
            @endif
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
                                        @if($data->status == 4)
                                        <th class="center">
                                            <label>
                                                <input type="checkbox" class="ace" id="checkAll" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        @endif
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
                                        <th>创建时间</th>
                                        <th>订单状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($data as $order)
                                        <tr>
                                            @if($data->status == 4)
                                            <td class="center">
                                                <label>
                                                    <input type="checkbox" class="ace" name="id[]" value="{{$order->id}}"/>
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            @endif

                                        <td>
                                            <a href="#">{{$order->id}}</a>
                                        </td>
                                        <td>{{$order->orderSn}}</td>
                                        <td>{{$order->tradeSn}}</td>
                                        <td>{{$order->orderTitle}}</td>
                                        <td>{{$order->orderPrice}}</td>
                                        <td>{{$order->payPrice}}</td>
                                        <td>
                                            @if($order->payType == 0)
                                                支付宝
                                            @elseif($order->payType == 1)
                                                微信
                                            @elseif($order->payType == 2)
                                                其它
                                            @endif
                                        </td>
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
                                        <td>{{$order->created_at}}</td>
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
                                            @elseif($order->status == 5)
                                                未付款
                                            @endif
                                        </td>

                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                {{--<button class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-ok bigger-120"></i>--}}
                                                {{--</button>--}}

                                                @permission('edit.order')
                                                <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                    <strong>订单状态</strong>
                                                    <span class="icon-caret-down icon-on-right"></span>
                                                    <select id="selectCheck" class="col-xs-10 col-sm-2" onchange="selectCheck({{$order->id}},this.value,'{{$order->orderSn}}');" style="filter:alpha(opacity=0); -moz-opacity:0; -khtml-opacity:0;opacity: 0;position:absolute;top:-2px;left:0;z-index: 2;cursor: pointer;height:23px;width:73px;">
                                                        <option value="44" selected></option>
                                                        @if($order->orderType != 0 && $order->orderType != 2)
                                                            <option value="0" >已付款</option>
                                                            <option value="1" >待辅导</option>
                                                        @endif
                                                        <option value="2" >已完成</option>
                                                        <option value="3" >退款中</option>
                                                        <option value="4" >已退款</option>
                                                    </select>
                                                </span>
                                                @endpermission


                                                @permission('check.order')
                                                    @if($order->status == 3 || $order->status ==4)
                                                    <a href="{{url('/admin/order/refundList/'.$order->orderSn.'/'.$data->status)}}" class="btn btn-xs btn-success">
                                                        <i class="icon-pencil bigger-120"></i>退款信息
                                                    </a>
                                                    @endif

                                                    <div href="" class="btn btn-xs btn-warning" ms-click="commentdetailpop({{$order->id}})">
                                                        <i class="icon-pencil bigger-120"></i>添加备注
                                                    </div>

                                                    <a href="{{url('/admin/order/remarkList/'.$order->id).'/'.$data->status}}" class="btn btn-xs btn-warning">
                                                        <i class=""></i>查看备注
                                                    </a>
                                                @endpermission

                                                @permission('edit.Paymoney')
                                                {{--未付款的订单修改实付金额--}}
                                                @if($order->status == 5)
                                                    <a href="{{url('/admin/order/editPaymoney/'.$order->id.'/'.$data->status)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-pencil bigger-120"></i>编辑实付金额
                                                    </a>
                                                @endif
                                                @endpermission

                                                @permission('del.order')
                                                    {{--只有已退款的订单才可以删除--}}
                                                    @if($order->status ==4)
                                                        {{--删除--}}
                                                        <a href="{{url('/admin/order/delOrder/'.$order->id.'/'.$data->status)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                            <i class="icon-trash bigger-120"></i>
                                                        </a>
                                                    @endif
                                                @endpermission

                                                @permission('edit.Refundmoney')
                                                    @if($order->status == 3)
                                                    <a href="{{url('/admin/order/editRefundmoney/'.$order->id.'/'.$data->status)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-pencil bigger-120"></i>编辑应退金额
                                                    </a>
                                                    @endif
                                                @endpermission

                                                @permission('edit.Retiredmoney')
                                                    @if($order->status == 3)
                                                    <a href="{{url('/admin/order/editRetiredmoney/'.$order->id.'/'.$data->status)}}" class="btn btn-xs btn-success">
                                                        <i class="icon-pencil bigger-120"></i>编辑已退金额
                                                    </a>
                                                    @endif
                                                @endpermission

                                                @permission('edit.Retiredmoney')
                                                    @if($order->status == 3)
                                                        {{--支付宝支付--}}
                                                        @if($order->payType == 0)
                                                            <a href="{{url('/admin/order/alipayRefund/'.$order->id)}}" target="_blank" class="btn btn-xs btn-danger">
                                                                <i class="icon- bigger-120"></i>确认退款
                                                            </a>
                                                        {{--微信支付--}}
                                                        @elseif($order->payType == 1)
                                                            <a href="{{url('/admin/order/weiXinRefund/'.$order->id)}}" class="btn btn-xs btn-danger">
                                                                <i class="icon- bigger-120"></i>确认退款
                                                            </a>
                                                        @endif
                                                    @endif
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

                                @permission('check.order')
                                    @if(count($excel))
                                        <form action="{{url('admin/excel/orderExport')}}" method="post" style="float: right;margin-top:65px;margin-right:-130px;">
                                            <input type="submit" class="btn btn-xs btn-info"  value="导出订单" style="width:86px; cursor: pointer; margin-top:-87px;margin-right:130px;" />
                                            {{csrf_field()}}
                                            <input type="hidden" name="excels" value="{{$excel}}"/>
                                        </form>
                                    @endif
                                @endpermission

                            </div><!-- /.table-responsive -->
                        </div><!-- /span -->
                    </div><!-- /row -->

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->


        <!--弹窗显示详情-->
        <div id="detailpupUpback" class="detailpupUpback" ms-visible="popup">
            <div class="popup1" style="margin-top: -150px;margin-left: -350px;">
                <div class="detailtopbaner">
                    <div class="detailtitle">备注</div>
                    <div class="deldetail" ms-click="deldetail"></div>
                </div>
                <div class="content1">
                    <div class="contenterror">
                        <div class="errortitle" style="height: 30px"></div>
                        <div class="errormsg">
                            <lable>备注:</lable>
                            <textarea name="content" maxlength="100"  placeholder="请填写备注..." id="errortext" cols="30" rows="10" ms-duplex="content" required></textarea>
                        </div>

                    </div>
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    <div class="bottom" id="surebtn">
                        <button class="suer_btn" ms-click="submit" style="margin-left: 250px;">确认</button>
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