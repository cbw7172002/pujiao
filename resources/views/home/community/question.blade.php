@extends('layouts.layoutHome')

@section('title', '学生提问')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/question.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <style>
        /*下拉列表框*/
        .select2-container--default .select2-selection--single{
            height: 35px;
            line-height:35px;
            font-size:16px;
            text-indent:10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 35px;
        }
        .select2-results__option{
            text-indent:10px;
            height:25px;
            line-height:25px;
            font-size:16px;
            /*font-weight:bold;*/

        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: gray transparent transparent;
            border-width: 9px 6px 0;
            height: 0;
            left: 50%;
            margin-left: -8px;
            margin-top: 1px;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent gray;
            border-width: 0 6px 9px;
            height: 0;
            left: 50%;
            margin-left: -8px;
            margin-top: 1px;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            right: 5px;
        }

        /*富文本编辑器*/
        .view{
            height:150px;
        }
    </style>
@endsection

@section('content')
    <div class="cont">
        {{--@if (session('right'))--}}
        {{--<div class="editResInfo dui">* {{session('right')}}</div>--}}
        {{--@elseif(session('wrong'))--}}
        {{--<div class="editResInfo cuo">* {{session('wrong')}}</div>--}}
        {{--@endif--}}

        @if (session('right'))
            <script>
                var commitcg = true;

            </script>
            <div class="editResInfo dui">* {{session('right')}}</div>
        @elseif(session('wrong'))
            <script>
                var commitcg = false;
            </script>
            <div class="editResInfo cuo">* {{session('wrong')}}</div>
        @else
            <script>
                var commitcg = false;
            </script>
        @endif

        <form action="{{url('community/doquestion')}}" method="post">
            {{ csrf_field() }}
            <div style="height: 10px;"></div>
            {{--<input type="hidden" name="teaId" value="{{$teaId}}">--}}
            <div class="cont_top">提问</div>
            <div class="cont_url">
                <input type="text" class="cont_url_det" placeholder="请用一句话描述问题(30字以内)"  maxlength="30"  name="qestitle" required>
            </div>
            <div style="height:30px"></div>

            <div class="cont_des_con">
                {{--<textarea name="content" class="cont_des_con_det" placeholder="请描述您的问题" style="width:803px;height: 260px;border: none;border-top:1px solid #DCDCDC "></textarea>--}}
                <script id="container" name="content" type="text/plain"></script>
            </div>
            <div style="height:10px"></div>

            <div class="cont_top">问题分类</div>
            <div style="height:10px"></div>
            <div class="cont_qustype_con">
                <select name="type"   class="js-example-basic-single" style="width:280px;height:40px;" required>
                    {{--<option value="" selected="selected">全部课程</option>--}}
                </select>
            </div>

            <div style="height: 60px;"></div>
            <div class="cont_xy">
                <input class="deal" type="checkbox" >已阅读并同意<a href="/aboutUs/firmintro/4" target="_blank"><span style="color:#209EEA">《用户协议》</span></a>
            </div>
            <div style="height: 60px;"></div>

            <button class="cont_btn unclick" type="submit" disabled>发布问题</button>
            <div style="height: 60px;"></div>

        </form>
    </div>
@endsection

@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('.js-example-basic-single').select2(
                {
                    minimumResultsForSearch: Infinity,
                    ajax: {
                        url: "/community/getteaSubjects/",
                        type:'get',
                        dataType:'json',
                        processResults: function (data) {
                            console.log(data);
                            return {
                                results: data
                            };
                        }
                    }
                }
        );

    </script>
@endsection
@section('js')
    {{--@if (session('right'))--}}
    {{--<div class="editResInfo dui">* {{session('right')}}</div>--}}
    {{--@elseif(session('wrong'))--}}
    {{--<div class="editResInfo cuo">* {{session('wrong')}}</div>--}}
    {{--@endif--}}
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.config2.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.all.js') }}"></script>
    <script>

        //提交结果显信息提示示框
        window.onload = function(){$(".editResInfo").slideUp(3500)};
        if(commitcg){
            if('{{ \Auth::user()->type }}' == '1' ){
                window.location.href = '/member/studentHomePage/ '+ '{{ \Auth::user()->id }}' +'#myAuditing';
            }else{
                window.location.href = '/member/teacherHomePage/ '+ '{{ \Auth::user()->id }}' +'#myAuditing';
//                window.location.href = '/member/famousTeacher#myQuestion';
            }
        }
        //        http://zaixian.zuren8.com/member/student/1#myAuditing
        //初始化富文本编辑器
        var ue = UE.getEditor('container',{
            initialFrameHeight:280,
            //textarea:'content',
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
