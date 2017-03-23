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
                    <a href="{{url('/admin/sensitive/sensitiveList')}}">违禁词库</a>
                </li>
                <li class="active">词库列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">
                    <span class="input-icon">
                        <span style="" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                             <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                        </span>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    违禁词库
                    <small>
                        <i class="icon-double-angle-right"></i>
                        词库列表
                    </small>
                    <a href="{{url('/admin/sensitive/onekey')}}" class="btn btn-xs btn-info" id="alldelete" style="float: right">
                        一键缓存
                    </a>
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
                <div class="btn btn-xs btn-info" id="alldelete" onclick="return confirm('确定要批量删除订单吗?');">
                    批量删除
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    <th class="center">
                                        <label>
                                            <input type="checkbox" class="ace" id="checkAll" />
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th>ID</th>
                                    <th>词语</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $type)
                                    <tbody>
                                    <tr>
                                        <td class="center">
                                            <label>
                                                <input type="checkbox" class="ace" name="id[]" value="{{$type->id}}" />
                                                <span class="lbl"></span>
                                            </label>
                                        </td>
                                        <td>{{$type->id}}</td>
                                        <td>{{$type->word}}</td>
                                        <td>{{$type->created_at}}</td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                {{--@permission('')--}}
                                                <a href="{{url('/admin/sensitive/delSensitive/'.$type->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                {{--@endpermission--}}





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
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/order/order.js') }}"></script>
@endsection