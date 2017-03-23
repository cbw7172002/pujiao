@extends('layouts.layoutAdmin')
@section('content')
	@if (session('status'))
	    <div class="alert alert-success">
	        <h1>{{ session('status') }}</h1>
	        页面跳转等待时间: <span id="div1"></span> 秒...
	    </div>
	@endif
	<script>
		//设定倒数秒数
		var t = 2;

		//显示倒数秒数
		function showTime(){
		    t -= 1;
		    document.getElementById('div1').innerHTML= t;
		    if(t==0){
		        location.href="{{session('redirect')}}";
				exit();
		    }
		    //每秒执行一次,showTime()
		    setTimeout("showTime()",1000);
		}


		//执行showTime()
		showTime();


	</script>
@endsection