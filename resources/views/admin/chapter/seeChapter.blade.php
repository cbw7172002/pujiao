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
                    <a href="{{url('/admin/chapter/chapterList')}}">知识点管理</a>
                </li>
                <li class="active">知识点列表</li>
            </ul><!-- .breadcrumb -->

        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    知识点管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        知识点列表
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

            @if($data)
            @foreach($data as $d)
            @endforeach

            <a href="{{url('/admin/chapter/addSee/'.$d->id)}}" class="btn btn-xs btn-info">
            <i class="icon-ok bigger-110">添加知识点</i>
            </a>
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
                                        <th>id</th>
                                        <th>学段</th>
                                        <th>年级</th>
                                        <th>科目</th>
                                        <th>册别</th>
                                        <th>版本</th>
                                        <th>知识点名称</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    @if($data)
                                    @foreach($data as $d)
                                        <tbody>
                                        <tr>
                                            <td>{{$d->id}}</td>
                                            <td>{{$d->sectionName}}</td>
                                            <td>{{$d->gradeName}}</td>
                                            <td>{{$d->subjectName}}</td>
                                            <td>{{$d->bookName}}</td>
                                            <td>{{$d->editionName}}</td>
                                            <td>{{$d->chapterName}}</td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                    {{--@permission('edit.contentManager')--}}
                                                    <a href="{{url('/admin/chapter/editSee/'.$d->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    {{--@endpermission--}}

                                                    {{--@permission('delete.contentManager')--}}
                                                    <a href="{{url('/admin/chapter/delSee/'.$d->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                        <i class="icon-trash bigger-120"></i>
                                                    </a>
                                                    {{--@endpermission--}}

                                                </div>

                                            </td>
                                        </tr>

                                        </tbody>
                                    @endforeach
                                    @endif


                                </table>
                                {{--{!! $data->appends(app('request')->all())->render() !!}--}}
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


    </script>
@endsection