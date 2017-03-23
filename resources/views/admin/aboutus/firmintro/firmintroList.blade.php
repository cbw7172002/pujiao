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
                    <a href="{{url('/admin/aboutUs/firmIntroList')}}">关于我们</a>
                </li>
                <li class="active">公司介绍</li>
            </ul><!-- .breadcrumb -->

            {{--<div class="nav-search" id="nav-search">--}}
                {{--<form action="" method="get" class="form-search">--}}
                    {{--<select name="type" id="form-field-1" class="searchtype">--}}
                        {{--<option value="1">ID</option>--}}
                        {{--<option value="2">课程名称</option>--}}
                        {{--<option value="3">时间筛选</option>--}}
                        {{--<option value="4">全部</option>--}}
                    {{--</select>--}}
                    {{--<span class="input-icon">--}}
                        {{--<span style="display: block;" class="input-icon" id="search1">--}}
                            {{--<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />--}}
                            {{--<i class="icon-search nav-search-icon"></i>--}}
                            {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />--}}
                        {{--</span>--}}
                        {{--<span style="display: none;" class="input-icon" id="search2">--}}
                            {{--<input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />--}}
                            {{--<input type="text" name="beginTime" id="form-field-1" placeholder="线束时间" class="col-xs-10 col-sm-5" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;" />--}}
                            {{--<input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;" type="submit" value="筛选" />--}}
                        {{--</span>--}}
                    {{--</span>--}}
                {{--</form>--}}
            {{--</div><!-- #nav-search -->--}}
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    公司介绍
                    <small>
                        <i class="icon-double-angle-right"></i>
                        公司介绍列表
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

            {{--<a href="{{url('/admin/specialCourse/addSpecialCourse')}}" class="btn btn-xs btn-info">--}}
            {{--<i class="icon-ok bigger-110">添加</i>--}}
            {{--</a>--}}

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
                                        <th>id</th>
                                        <th>标题</th>
                                        <th>内容详情</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    @foreach($firmIntro as $firm)
                                        <tbody>
                                        <tr>
                                            <td>{{$firm->id}}</td>
                                            <td>{{$firm->title}}</td>
                                            <td class="firmcontent">{{$firm->content}}</td>
                                            {{--<td>{{$firm->logo}}</td>--}}
                                            {{--<td> <img src="{{asset($firm->logo)}}" alt="" width="60" height="40" > </td>--}}
                                            {{--<td>--}}
                                                {{--@if($firm->status == 0)--}}
                                                    {{--激活--}}
                                                {{--@elseif($firm->status == 1)--}}
                                                    {{--禁用--}}
                                                {{--@endif--}}
                                            {{--</td>--}}
                                            <td>{{$firm->created_at}}</td>
                                            <td>{{$firm->updated_at}}</td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                    @permission('edit.aboutus')
                                                    <a href="{{url('/admin/aboutUs/editfirmIntro/'.$firm->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120">修改</i>
                                                    </a>
                                                    @endpermission


                                                    @if($firm->id <7 )
                                                    <a href="{{url('/aboutUs/firmintro/'.$firm->id)}}"  class="btn btn-xs btn-success">
                                                        <i class="icon-ok bigger-120">预览</i>
                                                    </a>
                                                    @endif


                                                </div>

                                            </td>
                                        </tr>

                                        </tbody>
                                    @endforeach


                                </table>
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

        $('.firmcontent').each(function(){
            var maxwidth=15;
            if($(this).text().length>maxwidth){
                $($(this)).text($($(this)).text().substring(0,maxwidth));
                $($(this)).html($($(this)).html()+'…');
            }
        });

    </script>
@endsection