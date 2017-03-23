@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}" />
    <script type="text/javascript" src="{{asset('admin/js/echarts.common.min.js')}}"></script>
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
                    <a href="{{url('/admin/count/specialCountList')}}">数据统计</a>
                </li>
                <li class="active">注册用户数</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                {{--<form action="{{url('/admin/count/userCount')}}" method="get" class="form-search">--}}
                    <select name="type" id="form-field-1" class="searchtype" style="padding-right: 10px;">
                        @foreach($data as $date)
                            <option value="{{$date}}" >{{$date}}</option>
                        @endforeach
                    </select>
                    <span class="input-icon">
                        <span style="" class="input-icon" id="search1">
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 1px;" type="submit" value="筛选" />
                        </span>

                    </span>
                {{--</form>--}}
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    注册用户数
                    <small>
                        <i class="icon-double-angle-right"></i>
                        注册用户数
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div style="width: 1000px;float: left;">
                <div style="float: right;font-size: 9px;font-weight: bold">总数：<span class="totalCount"></span></div>
            </div>
            <div style="width: 1000px;float: left;">
                <div style="float: right;font-size: 9px;font-weight: bold">当前月总数：<span class="nowMonthCount"></span></div>
            </div>
            <div id="main" style="width: 1100px;height:400px;"></div>

            <form action="{{url('admin/excel/userCountExport')}}" method="post">
                <input type="submit" class="btn btn-xs btn-info"  value="导出用户数" style="width:86px; cursor: pointer;" />
                {{csrf_field()}}
                <input type="hidden" name="excels" value=""/>
            </form>


        </div><!-- /.page-content -->

    </div><!-- /.main-content -->

@endsection
@section('js')
    <script type="text/javascript" src="{{asset('admin/js/count/count.js')}}"></script>
@endsection
