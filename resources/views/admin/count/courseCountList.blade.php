@extends('layouts.layoutAdmin')
@section('content')
    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {
                }
            </script>
            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>
                <li>
                    <a href="{{url('/admin/count/courseCountList')}}">课程播放统计</a>
                </li>
                <li class="active">近7日课程播放统计列表</li>
            </ul><!-- .breadcrumb -->
        </div>
        <div class="page-content">
            <div class="page-header">
                <h1>
                    课程播放统计
                    <small>
                        <i class="icon-double-angle-right"></i>
                        近7日课程播放统计列表
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

                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>课程ID</th>
                                        <th>课程名称</th>
                                        <th>课程播放数</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $count)
                                        <tr>
                                            <td>
                                                <a href="#">{{$count->courseId}}</a>
                                            </td>
                                            <td>{{$count->courseTitle}}</td>
                                            <td>{{$count->num}}</td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    {{--<a href="{{url('/admin/specialCourse/delSpecialFeedback/'.$count->id)}}" class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">--}}
                                                    {{--<i class="icon-trash bigger-120"></i>--}}
                                                    {{--</a>--}}
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
                                {{--{!! $data->appends(app('request')->all())->render() !!}--}}
                                @if(count($excel))
                                    <form action="{{url('admin/excel/courseCountExport')}}" method="post" style="float: right;margin-top:65px;margin-right:-130px;">
                                        <input type="submit" class="btn btn-xs btn-info" value="导出近7日课程播放统计" style="width:150px; cursor: pointer; margin-top:-87px;margin-right:130px;"/>
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
    </div><!-- /.main-content -->
@endsection