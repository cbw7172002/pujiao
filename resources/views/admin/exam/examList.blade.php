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
                    <a href="{{url('/admin/exam/examList')}}">试卷管理</a>
                </li>
                <li class="active">试卷列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    试卷管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        试卷列表
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

                <form action="" method="get" class="form-search" ms-controller="searchSelect">
                    <span style=""  class="searchtype" id="form-field-1">
                        <input type="text" name="beginTime" id="form-field-1" placeholder="开始时间" class="col-xs-10 col-sm-5" value="{{$data->beginTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>&nbsp;&nbsp;
                        <input type="text" name="endTime" id="form-field-1" placeholder="结束时间" class="col-xs-10 col-sm-5" value="{{$data->endTime}}" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:170px;margin-left:10px;background:url('{{asset("admin/image/2.png")}}') no-repeat;background-position:right;"/>
                    </span>

                    <select id="grade" name="resourceGrade" class="grade" ms-change="getChapter(1)" ms-duplex="defaultGrade">
                        <option value="0">-- 年级 --</option>
                        <option ms-repeat="grades" ms-attr-value="el.id" ms-text="el.gradeName"></option>
                    </select>

                    <select id="subject" name="resourceSubject" class="subject" ms-change="getChapter(2)" ms-duplex="defaultSubject">
                        <option value="0">-- 科目 --</option>
                        <option ms-repeat="subjects" ms-attr-value="el.id" ms-text="el.subjectName"></option>

                    </select>

                    <select id="edition" name="resourceEdition" class="edition" ms-change="getChapter(3)" ms-duplex="defaultEdition">
                        <option value="0">-- 版本 --</option>
                        <option ms-repeat="editions" ms-attr-value="el.id" ms-text="el.editionName"></option>
                    </select>

                    <select id="book" name="resourceBook" class="book" ms-change="getChapter(4)" ms-duplex="defaultBook">
                        <option value="0">-- 册别 --</option>
                        <option ms-repeat="books" ms-attr-value="el.id" ms-text="el.bookName"></option>
                    </select>


                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>试卷标题</option>
                        <option value="3" @if($data->type == 3) selected @endif>发布人</option>
                        <option value="">全部</option>
                    </select>
                    <span class="input-icon">
                        <span style="" class="input-icon" id="search1">
                            <input type="text" name="search" placeholder="Search ..." class="nav-search-input" value="" id="nav-search-input" autocomplete="off" />
                             <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:50px;height:28px ;border:0;color:#fff;padding-left: 5px;" type="submit" value="搜索" />
                        </span>
                    </span>
                </form>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>试卷标题</th>
                                    <th>试卷类型</th>
                                    <th>年级</th>
                                    <th>学科</th>
                                    <th>版本</th>
                                    <th>册别</th>
                                    <th>发布人</th>
                                    <th>题数</th>
                                    <th>出题时间</th>
                                    <th>更新时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $exam)
                                    <tbody>
                                    <tr>
                                        <td>{{$exam->id}}</td>
                                        <td><a href="{{url('/evaluateManageTea/testPaperTea/'.$exam->id)}}" target="_blank">{{$exam->title}}</a></td>
                                        <td>{{$exam->type ? '测验试卷' : '同步练习'}}</td>
                                        <td>{{$exam->gradeName}}</td>
                                        <td>{{$exam->subjectName}}</td>
                                        <td>{{$exam->editionName}}</td>
                                        <td>{{$exam->bookName}}</td>
                                        <td>{{$exam->username}}</td>
                                        <td>{{$exam->count}}</td>
                                        <td>{{$exam->created_at}}</td>
                                        <td>{{$exam->updated_at}}</td>
                                        <td>{{$exam->status ? '锁定' : '激活'}}</td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">


                                                <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                    <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                        状态
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                    </span>
                                                    <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                        <li><a href="{{url('admin/exam/status/'.$exam->id).'/0'}}">激活</a></li>

                                                        <li><a href="{{url('admin/exam/status/'.$exam->id.'/1')}}">锁定</a></li>
                                                    </ul>
                                                </span>


                                                {{--@permission('')--}}
                                                <a href="{{url('/admin/exam/delExam/'.$exam->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                {{--@endpermission--}}

                                                {{--<a href="" class="btn btn-xs btn-success">--}}
                                                    {{--<i class="icon-list bigger-120"></i>详情--}}
                                                {{--</a>--}}






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
        require(['/searchSelect'], function (detail) {
            detail.defaultGrade = '{{$data->resourceGrade }}' || null;
            detail.defaultSubject = '{{$data->resourceSubject }}' || null;
            detail.defaultEdition = '{{$data->resourceEdition }}' || null;
            detail.defaultBook = '{{$data->resourceBook }}' || null;
            avalon.scan();
        });
    </script>

@endsection