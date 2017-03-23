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
                    <a href="{{url('/admin/order/orderList')}}">订单管理</a>
                </li>
                <li class="active">实付金额</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    实付金额
                    <small>
                        <i class="icon-double-angle-right"></i>
                        实付金额
                    </small>
                </h1>
            </div><!-- /.page-header -->

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
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form action="{{url('admin/order/doEditPaymoney')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="status" value="{{$data->status}}">
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单号 </label>

                            <div class="col-sm-9">
                                <input type="text" disabled name="orderSn" id="form-field-1" placeholder="订单号" class="col-xs-10 col-sm-5" value="{{$data->orderSn}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 实付金额 </label>

                            <div class="col-sm-9">
                                <input type="text" name="payPrice" id="form-field-1" placeholder="实付金额" class="col-xs-10 col-sm-5" value="{{$data->payPrice}}" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <label class="middle">
                                    <span class="lbl"></span>
                                </label>
                            </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 支付方式 </label>

                            <div class="col-sm-9">
                                <select name="payType" id="form-field-1"class="col-xs-10 col-sm-5">
                                    <option value="0" @if($data->payType == 0) selected @endif>支付宝</option>
                                    <option value="1" @if($data->payType == 1) selected @endif>微信</option>
                                    <option value="2" @if($data->payType == 2) selected @endif>其它</option>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>



                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                    Submit
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->

    </div><!-- /.main-content -->
@endsection