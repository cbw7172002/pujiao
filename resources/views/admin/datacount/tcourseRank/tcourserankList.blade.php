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
                    <a href="{{url('/admin/datacount/tcourserankList')}}">数据统计</a>
                </li>
                <li class="active">教师资源发布量排名列表</li>
            </ul>
        </div><!-- ./breadcrumbs -->

        <div class="page-content">
            <div class="page-header">
                <h1>
                    数据统计
                    <small>
                        <i class="icon-double-angle-right"></i>
                        教师资源发布量排名列表
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

            {{--<a href="{{url('./admin/baseInfo/addA')}}" class="btn btn-xs btn-info">--}}
                {{--<i class="icon-ok bigger-110">搜索</i>--}}
            {{--</a>--}}
            @if(count($excel))
                <form action="{{url('admin/excel/tcourseRankExport')}}" method="post" style="float: left;">
                    <input type="submit" class="btn btn-xs btn-info"  value="导出排名统计" style="width:86px; cursor: pointer;" />
                    {{csrf_field()}}
                    <input type="hidden" name="excels" value="{{$excel}}"/>
                </form>
            @endif

            <div class="row">

                <div class="col-xs-12">
                    <!-- Page content begin -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>教师id</th>
                                        <th>教师名称</th>
                                        <th>授课量</th>
                                        {{--<th>操作</th>--}}
                                    </tr>
                                    </thead>
                                    @foreach($data as $d)
                                    <tbody>
                                        <tr>
                                            <td>{{$d->teacherId}}</td>
                                            <td>{{$d->courseTeacher}}</td>
                                            <td>{{$d->amount}}</td>
                                            {{--<td>--}}
                                                {{--<!-- 操作 -->--}}
                                                {{--<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">--}}
                                                    {{--<a href="{{url('./admin/baseInfo/editA')}}" class="btn btn-xs btn-info">--}}
                                                        {{--<i class="icon-edit bigger-120"></i>--}}
                                                    {{--</a>--}}
                                                {{--</div>--}}
                                            {{--</td>--}}
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

        $('#type').change(function(){
            var typeId = $('#type').val()
            var hidden = $("#hidden").attr("value",typeId);
            var hidden = $("#hidden").val()
            //            console.log(typeId)
            console.log(hidden)
        })
    </script>

@endsection