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
                    <a href="{{url('/admin/complaint/complaintList')}}">资源管理</a>
                </li>
                <li class="active">资源列表</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">

                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                   资源管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        资源列表
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

                    <select id="form-field-1" name="resourceChapter">
                        <option value="0">--知识点--</option>
                        <option ms-repeat="chapter" ms-attr-value="el.id" ms-text="el.chapterName"></option>
                    </select>

                    <select id="form-field-1" name="resourceType" ms-duplex="defaultType">
                        <option value="0">--资源类型--</option>
                        <option ms-repeat="resourceType" ms-attr-value="el.id" ms-text="el.resourceTypeName"></option>
                    </select>

                    <select name="type" id="form-field-1" class="searchtype">
                        <option value="">--请选择--</option>
                        <option value="1" @if($data->type == 1) selected @endif>ID</option>
                        <option value="2" @if($data->type == 2) selected @endif>资源名称</option>
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
                            <form action="{{url('admin/resource/delMultiResource')}}" method="post" class="form-search" onsubmit="return confirm('确定要删除该课程记录？');">
                                {{csrf_field()}}
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">

                                <thead>
                                <tr>
                                    @if(count($data) > 0)
                                        <input type="submit"  style="display:inline-block;width:80px;height:30px;line-height: 30px;text-align:center;cursor: pointer;font-size:13px;margin: 10px auto;letter-spacing: 2px;border:none;background:#209EEA; color:#fff;" value="多删除">
                                    @endif
                                    <th class="center" style="width:3%;"><input type="checkbox" name="multiple"></th>
                                    <th>ID</th>
                                    <th>资源名称</th>
                                    <th>资源类型</th>
                                    <th>上传者</th>
                                    <th>封面图</th>
                                    <th>年级</th>
                                    <th>学科</th>
                                    <th>版本</th>
                                    <th>册别</th>
                                    <th>知识点</th>
                                    <th>格式</th>
                                    <th>资源</th>
                                    <th>在线浏览</th>
                                    <th>浏览数</th>
                                    <th>下载数</th>
                                    <th>收藏数</th>
                                    <th>上传时间</th>
                                    <th>修改时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                @foreach($data as $resource)
                                    <tbody>
                                    <tr>
                                        <td class="center"><input type="checkbox" name="check[]" value="{{$resource->id}}"></td>
                                        <td>{{$resource->id}}</td>
                                        <td>{{$resource->resourceTitle}}</td>
                                        <td>{{$resource->resourceTypeName}}</td>
                                        <td>{{$resource->username}}</td>
                                        <td>
                                            <img src="{{asset($resource->resourcePic)}}" alt="" width="50px" height="50px" onerror="this.src='/admin/image/back.png'">
                                        </td>
                                        <td>{{$resource->gradeName}}</td>
                                        <td>{{$resource->subjectName}}</td>
                                        <td>{{$resource->editionName}}</td>
                                        <td>{{$resource->bookName}}</td>
                                        <td>{{$resource->chapterName}}</td>
                                        <td>{{$resource->resourceFormat}}</td>
                                        <td style="color: #00A0E8">
                                            @if(!$resource->courseLowPath && !$resource->courseMediumPath && !$resource->courseHighPath && !$resource->resourcePath)
                                                {{--@if($data->isTranscode)--}}
                                                    @if(isset($resource->msg))
                                                        @if($resource->msg['code'] == '200')
                                                            正在转码...
                                                        @else
                                                            {{$resource->msg['message']}}
                                                        @endif
                                                    @else
                                                        正在转码...
                                                    @endif
                                                {{--@else--}}
                                                    {{--@if(in_array(strtolower($resource->resourceFormat),['doc','docx','xls','xlsx','ppt','pptx','pdf']))--}}
                                                        {{--<a href="{{url('/resource/resDetail/'.$resource->id)}}" target="_blank">查看</a>--}}
                                                    {{--@else--}}
                                                        {{--@if(isset($resource->msg))--}}
                                                            {{--@if($resource->msg['code'] == '200')--}}
                                                                {{--正在转码...--}}
                                                            {{--@else--}}
                                                                {{--{{$resource->msg['message']}}--}}
                                                            {{--@endif--}}
                                                        {{--@else--}}
                                                            {{--正在转码...--}}
                                                        {{--@endif--}}
                                                    {{--@endif--}}

                                                {{--@endif--}}

                                            @else
                                                <a href="{{url('/resource/resDetail/'.$resource->id)}}" target="_blank">查看</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$data->isTranscode)
                                                @if(in_array(strtolower($resource->resourceFormat),['doc','docx','xls','xlsx','ppt','pptx','pdf']))
                                                    <a href="{{url('/resource/resDetail/'.$resource->id)}}" target="_blank">查看</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{$resource->resourceView}}</td>
                                        <td>{{$resource->resourceDownload}}</td>
                                        <td>{{$resource->resourceFav}}</td>
                                        <td>{{$resource->created_at}}</td>
                                        <td>{{$resource->updated_at}}</td>
                                        <td>
                                            @if($resource->resourceStatus == 0)
                                                上架
                                            @elseif($resource->resourceStatus == 1)
                                                下架
                                            @endif
                                        </td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                                                @permission('edit.resource')
                                                <span class="btn btn-xs btn-primary" style="position: relative;display: inline-block;">
                                                    <span data-toggle="dropdown" class="btn btn-xs btn-primary" style="border: 0;width: 70px;height: 17px;line-height: 17px;">
                                                        审核状态
                                                        <span class="icon-caret-down icon-on-right"></span>
                                                    </span>
                                                    <ul class="dropdown-menu dropdown-inverse" style="min-width: 80px;font-size:12px;color: #000;">
                                                        <li><a href="{{url('admin/resource/status/'.$resource->id).'/0'}}">上架</a></li>

                                                        <li><a href="{{url('admin/resource/status/'.$resource->id.'/1')}}">下架</a></li>
                                                    </ul>
                                                </span>

                                                <a href="{{url('/admin/resource/editResource/'.$resource->id)}}" class="btn btn-xs btn-info">
                                                    <i class="icon-edit bigger-120"></i>
                                                </a>
                                                @endpermission

                                                @permission('del.resource')
                                                <a href="{{url('/admin/resource/delResource/'.$resource->id)}}" style="width:29px" class="btn btn-xs btn-danger" onclick="return confirm('删除后将无法找回,确定要删除吗?');">
                                                    <i class="icon-trash bigger-120"></i>
                                                </a>
                                                @endpermission

                                                @permission('check.resource')
                                                <a href="{{url('/admin/resource/getCommentList/'.$resource->id)}}" class="btn btn-xs btn-success">
                                                    <i class="icon-comments bigger-120"></i>
                                                    评论
                                                </a>
                                                @endpermission





                                            </div>

                                        </td>
                                    </tr>

                                    </tbody>
                                @endforeach

                            </table>
                            </form>
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
    <script type="text/javascript" type="text/javascript" src="{{asset('admin/js/checkboxMultiple.js') }}"></script>

    <script>
        require(['/searchSelect'], function (detail) {
            detail.defaultGrade = '{{$data->resourceGrade }}' || null;
            detail.defaultSubject = '{{$data->resourceSubject }}' || null;
            detail.defaultEdition = '{{$data->resourceEdition }}' || null;
            detail.defaultBook = '{{$data->resourceBook }}' || null;
            detail.defaultType = '{{$data->resourceType }}' || null;
            avalon.scan();
        });
    </script>

@endsection