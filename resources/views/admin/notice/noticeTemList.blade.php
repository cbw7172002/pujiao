@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" href="{{asset('admin/css/popUp.css')}}"/>
@endsection
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
                    <a href="{{url('/admin/notice/noticeTemList')}}">通知模板管理</a>
                </li>
                <li class="active">通知模板列表</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    通知模板列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        通知模板列表
                    </small>
                </h1>
            </div><!-- /.page-header -->
            @permission('add.noticeTem')
            <a href="{{url('/admin/notice/addNoticeTem')}}" class="btn btn-xs btn-info">
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
                                        <th>ID</th>
                                        <th>针对对象</th>
                                        <th>模板名称</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $type)
                                        <tr>
                                            <td><a href="#">{{$type->id}}</a></td>
                                            <td>
                                                @if($type->type == '0')
                                                    学生
                                                @elseif($type->type == '1')
                                                    专家
                                                @else
                                                    教师
                                                @endif
                                            </td>
                                            <td>{{$type->tempName}}</td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                                    @permission('edit.noticeTem')
                                                    <a href="{{url('/admin/notice/editNoticeTem/'.$type->id)}}"
                                                       class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    @endpermission
                                                    @permission('del.noticeTem')
                                                    <a href="{{url('/admin/notice/delNoticeTem/'.$type->id)}}"
                                                       class="btn btn-xs btn-danger" onclick="return confirm('确定要删除吗?');">
                                                        <i class="icon-trash bigger-120"></i>
                                                    </a>
                                                    @endpermission
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
    </div><!-- /.main-content -->
@endsection
@section('js')
@endsection