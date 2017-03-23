@extends('layouts.layoutHome')

@section('title', '意见反馈')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/feedback.css') }}">
@endsection

@section('content')
    <div class="cont">
        @if (session('right'))
            <div class="editResInfo dui">* {{session('right')}}</div>
        @elseif(session('wrong'))
            <div class="editResInfo cuo">* {{session('wrong')}}</div>
        @endif
        <form action="{{url('community/dofeedback')}}" method="post">
        {{ csrf_field() }}
        <div style="height: 10px;"></div>
        <div class="cont_top">意见反馈</div>

        <div class="cont_type">问题分类</div>
        <div class="cont_type_sel">
            <input class="cont_type_sel_a" type="radio" name="type" value="1" checked>课程学习
            <input class="cont_type_sel_a" type="radio" name="type" value="2">网站建议
            <input class="cont_type_sel_a" type="radio" name="type" value="3">其他问题
        </div>

        <div class="cont_type">问题描述</div>
        <div class="cont_des_con">
            <textarea name="content" class="cont_des_con_det" placeholder="请描述您的问题"></textarea>
        </div>
        <div style="height: 20px;"></div>

        <div class="cont_type">相关网页地址</div>
        <div class="cont_url">
            <input type="text" class="cont_url_det" name="weburl">
        </div>

        <div class="cont_type">联系方式</div>
        <div class="cont_url">
            <input type="text" class="cont_url_det" placeholder="手机/QQ/Email" name="contact">
        </div>
        <div style="height: 60px;"></div>
        <button class="cont_btn">提交</button>
        <div style="height: 60px;"></div>

        </form>
    </div>
@endsection

@section('js')
    <script>
        //编辑结果显信息提示示框
        window.onload = function(){$(".editResInfo").slideUp(3500)};
    </script>
@endsection
