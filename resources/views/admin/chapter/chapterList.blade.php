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

            <a href="{{url('/admin/chapter/addChapter')}}" class="btn btn-xs btn-info">
            <i class="icon-ok bigger-110">添加</i>
            </a>
            <form action="" method="get" class="form-search" >

                <select id="grade" name="Grade" >
                    <option value="0">-- 年级 --</option>
                    @foreach($grade as $g)
                        <option value="{{$g->id}}" @if($data->labels['Grade'] == $g->id) selected @endif>{{$g->gradeName}}</option>
                    @endforeach
                </select>

                <select id="subject" name="Subject" >
                    <option value="0">-- 科目 --</option>
                    @foreach($subject as $s)
                        <option value="{{$s->id}}" @if($data->labels['Subject'] == $s->id) selected @endif>{{$s->subjectName}}</option>
                    @endforeach
                </select>

                <select id="book" name="Book" >
                    <option value="0">-- 册别 --</option>
                    @foreach($book as $b)
                        <option value="{{$b->id}}" @if($data->labels['Book'] == $b->id) selected @endif>{{$b->bookname}}</option>
                    @endforeach
                </select>

                <select id="edition" name="Edition" >
                    <option value="0">-- 版本 --</option>
                    @foreach($edition as $e)
                        <option value="{{$e->id}}" @if($data->labels['Edition'] == $e->id) selected @endif>{{$e->editionName}}</option>
                    @endforeach
                </select>

                {{--<select name="type" id="form-field-1" class="searchtype">--}}
                    {{--<option value="">--请选择--</option>--}}
                    {{--<option value="1" >ID</option>--}}
                    {{--<option value="">全部</option>--}}
                {{--</select>--}}

                <span class="input-icon">
                    <span style="" class="input-icon" id="search1">
                        {{--<input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />--}}
                         {{--<i class="icon-search nav-search-icon"></i>--}}
                        <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                    </span>
                </span>
            </form>


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
                                        {{--<th>知识点名称</th>--}}
                                        <th>学段</th>
                                        <th>年级</th>
                                        <th>科目</th>
                                        <th>册别</th>
                                        <th>版本</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>

                                    @foreach($data as $d)
                                        <tbody>
                                        <tr>
                                            <td>{{$d->id}}</td>
                                            {{--<td>{{$d->chapterName}}</td>--}}
                                            <td>{{$d->sectionName}}</td>
                                            <td>{{$d->gradeName}}</td>
                                            <td>{{$d->subjectName}}</td>
                                            <td>{{$d->bookName}}</td>
                                            <td>{{$d->editionName}}</td>
                                            <td>
                                                @if($d->status == 0)
                                                    激活
                                                @elseif($d->status == 1)
                                                    锁定
                                                @endif
                                            </td>

                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                    {{--@permission('edit.contentManager')--}}
                                                    <a href="{{url('/admin/chapter/editChapter/'.$d->id)}}" class="btn btn-xs btn-info">
                                                        <i class="icon-edit bigger-120"></i>
                                                    </a>
                                                    {{--@endpermission--}}

                                                    {{--@permission('delete.contentManager')--}}
                                                    <a href="{{url('/admin/chapter/delChapter/'.$d->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                        <i class="icon-trash bigger-120"></i>
                                                    </a>
                                                    {{--@endpermission--}}

                                                    <a href="{{url('/admin/chapter/seeChapter/'.$d->id)}}" class="btn btn-xs btn-success">
                                                        <i class="icon-comments bigger-120"></i>
                                                        查看知识点
                                                    </a>


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
    <script>


    </script>
@endsection