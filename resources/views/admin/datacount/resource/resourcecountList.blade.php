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
					<a href="{{url('/admin/datacount/resourcecountList')}}">数据统计</a>
				</li>
				<li class="active">资源历史统计列表</li>
			</ul>
		</div><!-- /.breadcrumbs -->

		<div class="page-content">
			<div class="page-header">
				<h1>
					数据统计
					<small>
						<i class="icon-double-angle-right"></i>
                        资源历史统计列表
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

            <div class="nav-search" id="nav-search">
                <form action="" method="get" class="form-search">
                    <select  id="type" name="type" id="form-field-1" class="searchtype">
                        <option value="1">全部</option>
                        <option value="2" @if($data->type == 2) selected @endif>资源名称</option>
                        <option value="3" @if($data->type == 3) selected @endif>发布人</option>
                    </select>

                     <span class="input-icon">
                        <span style="display: block;" class="input-icon" id="search1">
                            <input type="text" placeholder="Search ..." name="search" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <input id="hidden" type="hidden"  name="hidden" value=""  />
                            <i class="icon-search nav-search-icon"></i>
                            <input style="background: #6FB3E0;width:60px;height:28px ;border:0;color:#fff;padding-left: 8px;" type="submit" value="搜索" />
                        </span>
                    </span>

                </form>
            </div><!-- #nav-search -->

            @if(count($excel))
                <form action="{{url('admin/excel/resourcehistCountExport')}}" method="post" style="float: left;">
                    <input type="submit" class="btn btn-xs btn-info"  value="导出当前统计" style="width:86px; cursor: pointer;" />
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
										<th>
                                            <a>id</a>
                                        </th>
										<th>资源名称</th>
										<th>发布人</th>
                                        <th>
                                            <a>游览量</a>
                                        </th>
                                        <th>
                                            <a>下载量</a>
                                        </th>
                                        <th>
                                            <a>收藏量</a>
                                        </th>
                                        {{--<th>操作</th>--}}
									</tr>
									</thead>

                                    @foreach($data as $d)
										<tbody>
											<tr>
												<td>{{$d->id}}</td>
												<td>{{$d->resourceTitle}}</td>
                                                <td>{{$d->resourceAuthor}}</td>
                                                <td>{{$d->resourceView}}</td>
                                                <td>{{$d->resourceDownload}}</td>
                                                <td>{{$d->resourceFav}}</td>
												{{--<td>--}}
													{{--<!-- 操作 -->--}}
													{{--<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">--}}

														{{--<a class="btn btn-xs btn-info">--}}
															{{--<i class="icon-edit bigger-120"></i>--}}
														{{--</a>--}}
													{{--</div>--}}
												{{--</td>--}}
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
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/TableSorterV2.js') }}"></script>

    <script>
        $('#type').change(function(){
            var typeId = $('#type').val()
            var hidden = $("#hidden").attr("value",typeId);
            var hidden = $("#hidden").val()
        })
    </script>

    <script language="javascript" type="text/javascript">
        //实现点击表头可降序升序的功能
        //插件在admin/js/TableSorterV2.js
        //0,3,4,5分别对应id,游览量,下载量,收藏量
        window.onload = function()
        {
            new TableSorter("sample-table-1", 0,3,4,5);

        }
    </script>


@endsection