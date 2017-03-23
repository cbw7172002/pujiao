@extends('layouts.layoutHome')

@section('title', '教师回答')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/answer.css') }}">
@endsection

@section('content')
    <div class="cont">
        @if (session('right'))
            <div class="editResInfo dui">* {{session('right')}}</div>
        @elseif(session('wrong'))
            <div class="editResInfo cuo">* {{session('wrong')}}</div>
        @endif
        <div class="cont_bar">社区>问答>问答详情</div>
        {{--左部--}}
        <div class="cont_left">
            {{--左上--}}
            <div class="cont_left_top">
                <div style="height:5px;"></div>
                <div class="cont_left_top_title">{{$data->qestitle}}</div>
                <div class="cont_left_top_content">{!! $data->content !!}</div>
                <div style="height: 30px;"></div>
            </div>
            {{--左下--}}
            @if (!Auth::check() || \Auth::user()->type == 2)
            <div class="cont_left_bot">
                <div class="cont_left_bot_con">
                    <form action="{{url('community/doanswer')}}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <input type="hidden" name="stuid" value="{{$data->stuid}}">
                    <div class="cont_left_bot_con_text">
                        <div style="height: 30px;"></div>
                        <script id="container" name="answer" type="text/plain"></script>
                    </div>
                    <div style="height: 50px;"></div>
                    <div class="cont_xy">
                        <input class="deal" type="checkbox" >我已阅读<a href="/aboutUs/firmintro/4" target="_blank"><span style="color:#209EEA">《创课在线账号使用协议》</span></a>
                    </div>
                    <div style="height: 50px;"></div>
                    <button class="cont_btn unclick" type="submit" disabled>提交答案</button>
                    <div style="height: 50px;"></div>
                    </form>
                </div>
            </div>
            @endif
        </div>
        {{--右部--}}
        <div class="cont_right">
            <div class="cont_right_type">提问学员</div>
            <div style="height: 15px;"></div>
            <div class="cont_right_info">
                <div class="cont_right_info_img">
                    <a href="/lessonComment/student/{{$data->stuid}}"><img src="{{url($data->stuPic)}}" alt=""></a>
                </div>
                <div class="cont_right_info_info">
                    <a href="/lessonComment/student/{{$data->stuid}}"><div class="cont_right_info_info_top">{{$data->stuName}}</div></a>
                    <div class="cont_right_info_info_bot">{{$data->asktime}}</div>
                </div>
            </div>
            <div style="height: 25px;"></div>

        </div>

        <div style="clear: both;height:30px;"></div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.config2.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.all.js') }}"></script>
    <script>
        //提交结果显信息提示示框
        window.onload = function(){$(".editResInfo").slideUp(3500)};
        //初始化富文本编辑器
        var ue = UE.getEditor('container',{
                    initialFrameHeight:260,
//                    textarea:'content',
                });

        //用户协议
        $('.deal').click(function(){
            if($('.deal').is(':checked')){
                $('.cont_btn').removeAttr("disabled");
                $('.cont_btn').removeClass("unclick");
            }else{
                $('.cont_btn').attr({ disabled: "disabled"});
                $('.cont_btn').addClass("unclick");
            }
        });
    </script>
@endsection
