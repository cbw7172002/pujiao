@extends('layouts.layoutHome')

@section('title', '名师列表')

@section('css')
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/community/theteacher.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/games/pagination.css')}}">

@endsection

@section('content')

	<div class="background"  ms-controller="theteacher">



		<div style="height:20px"></div>
		

		<!-- 主体div -->
		<div class="main"   >

				<!-- 面包屑导航 -->
				<div class="crumbs">
					<a href="{{asset('/community')}}">社区</a><span>></span><a href="#">名师主页</a>
				</div>


				<!-- 排序说明 -->
				<div class="order">
						<div>以下名师按姓氏排序，排序不分先后</div>
				</div>

				<!-- 排序字母 -->
				<div class="orderletter">
					<div ms-click="tabs('tabss')" class="tabss">全部</div>
					<div ms-repeat="firstletter" ms-click="tabs(el.firstletter)" ms-attr-class="el.firstletter" ms-html="el.firstletter"></div>
				</div>

				<div style="height:20px"></div>


			
			<!-- table循环 -->
			<div class="table_loop"  >

				<!-- 内容 -->
				<div class="content" ms-repeat="theteacherlist">

					<!--字母标题  -->
					<div class="lettertitle">
						<div ms-html="el.firstletter"></div>
					</div>
					{{--<div style="height:10px"></div>--}}
					<!-- 详细内容 -->
					<div class="detailcontent" >
						<!-- 左侧图片 -->
						<div class="content_left">
							<a ms-attr-href="teacherhomepage + el.userId"><img ms-attr-src="el.cover"  alt="" /></a>
						</div>
						<!-- 右侧内容 -->
						<div class="content_right">
							<!-- 姓名 -->
							<div class="content_name">
								<a ms-attr-href="teacherhomepage + el.userId"><div ms-html="el.name" ></div></a>
							</div>
							<!-- 学校 -->
							<div class="content_school">
								<a ms-attr-href="teacherhomepage + el.userId"><div ms-html="el.school"></div></a>
							</div>
							<!-- 介绍 -->
							<div class="content_introduce">
								<a ms-attr-href="teacherhomepage + el.userId"><div ms-html="el.intro" ms-theteacheryincang></div></a>
							</div>
						</div>
					</div>
				</div>

				{{--<div class="noteacher" ms-if="No">暂无名师...</div>--}}


			</div>


		</div>

		<div style="height:225px"></div>
		<div class="pagecon" >
			<div style="display: inline-block"><div id="page" ms-if="page"></div></div>
		</div>

		<div style="height:35px">


		</div>

	</div>

	<div class="screen1200"></div>
@endsection

@section('js')
	{{--<script type="text/javascript" src="{{asset('home/js/community/theteacher.js')}}"></script>--}}
	    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>


	<script>
		require(['/community/theteacher.js'], function ($) {

			avalon.scan();
		});
	</script>
@endsection

