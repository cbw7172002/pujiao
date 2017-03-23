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
                    <a href="{{url('/admin/companyUser/systemList')}}">系统管理</a>
                </li>
                <li class="active">系统列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    系统管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        系统列表
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




            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>类型</th>
                                    <th>是否</th>
                                    <th>修改时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $type)
                                    <tbody>
                                    <tr>
                                        <td>{{$type->id}}</td>
                                        <td>{{$type->type == 0 ? 'word excel pdf ppt是否在线浏览' : ''}}</td>
                                        <td>{{$type->isTrue ? '是' : '否'}}</td>
                                        <td>{{$type->updated_at}}</td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                    <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                        审核状态
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                    </span>
                                                    <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                        <li><a href="{{url('admin/companyUser/status/'.$type->id).'/1'}}">是</a></li>

                                                        <li><a href="{{url('admin/companyUser/status/'.$type->id.'/0')}}">否</a></li>
                                                    </ul>
                                                </span>

                                            </div>

                                        </td>
                                    </tr>

                                    </tbody>
                                @endforeach

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
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>

@endsection