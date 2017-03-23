@extends('layouts.layoutHome')

@section('title', '新闻资讯列表')

@section('css')
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/community/newlist.css') }}">
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/games/pagination.css')}}">
@endsection

@section('content')

	<div class="background"  ms-controller="newlist">



		<div style="height:15px"></div>

		<!-- 主体 -->
		<div class="main" >
			<!-- 面包屑导航 -->
			<div class="crumbs">
				<a href="{{asset('/community')}}">社区</a><span>></span><a href="#">新闻资讯</a>
			</div>
			<div style="height:12px"></div>
			<!-- 列表 -->
			<div class="list">

				<!-- 每一行 -->

				<div class="list_line" ms-repeat="newlist" >
					<div class="title_time">
						<div class="title"  >
							<a ms-attr-href="lujing + el.id" ms-html="el.description"  target="_blank" >  </a>
						</div>
						<div class="time" ms-html="el.time">

						</div>
					</div>
				</div>





			</div>

		</div>

		<div style="height:175px">
			<div style="height:50px;"></div>
			<div class="pagecon">
				<div style="display: inline-block"><div id="demo" ms-if="page" ></div></div>
			</div>
			<div style="height:100px;">
			</div>
		</div>

	</div>

	<div class="screen1200"></div>

@endsection

@section('js')

	<script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
	<script>
         require(['/community/newlist.js'], function () {

             avalon.scan();
         });
    </script>


    <script>
//        function getdata(){
//
//            $('#demo').pagination({
//                dataSource: function(done) {
//                    $.ajax({
//                        type: 'GET',
//                        url: '/community/getnewlist',
//                        dataType : 'json',
//                        success: function(response) {
//                        	// console.log(response);
//                            if(response.statuss){
//                                done(response.data);
//                            }
//                        }
//                    });
//                },
//                pageSize: 10,
//                className:"paginationjs-theme-blue",
//                showGoInput: true,
//                showGoButton: true,
//                callback: function(data) {
//                	console.log(data);
//                    if(data){
//                        var str = '';
//                        $.each(data,function(id,info){
//                            str += "<div class=\"list_line\"><div class=\"title_time\"><div class=\"title\"><a href=\""+'/community/newdetail/'+info.id+"\">"+info.description+"</a></div><div class=\"time\">"+info.time+"</div></div></div>";
//                        })
//                        $('.list').html(str);
//                    }
//
//               // console.log(data);
//                }
//            })
//        }
//
//        getdata();

    </script>

@endsection

