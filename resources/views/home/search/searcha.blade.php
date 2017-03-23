@extends('layouts.layoutHome')

@section('title', '搜索结果')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/search/searcha.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/games/pagination.css')}}">
    <style>
        .select2-container--default .select2-selection--single{
            border:none;
            height: 50px;
            line-height:50px;
            /*font-weight: bold;*/
            font-size:18px;
            text-indent:20px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 50px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px;
        }
        .select2-results__option{
            text-indent:20px;
            height:48px;
            line-height:48px;
            font-size:18px;
            /*font-weight:bold;*/

        }


        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-width: 10px 8px 0 8px;
            height: 0;
            left: 50%;
            margin-left: -8px;
            margin-top: -4px;
            position: absolute;
            top: 50%;
            width: 0;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-width: 0 8px 10px 8px;
            height: 0;
            left: 50%;
            margin-left: -8px;
            margin-top: -4px;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #ffffff;
            color: #209EEA;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px;
            position: absolute;
            top: 1px;
            right: 10px;
            width: 20px;
        }
        .select2-dropdown {
            background-color: white;
            border: 2px solid #ECE8ED;
            border-radius: 4px;
            box-sizing: border-box;
            display: block;
            position: absolute;
            left: -100000px;
            width: 100%;
            z-index: 1051;
        }
    </style>
@endsection

@section('content')
    <div class="con">
        {{--搜索--}}
        <div class="con_top">
            <div class="con_top_con">
                <div class="con_top_con_t">搜索</div>
                <div class="con_top_con_sbar">
                    <form action="{{url('/index/search')}}" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="con_top_con_sbar_l">
                        <select name="type"   class="js-example-basic-single" style="width:171px;height:50px;border: none">
                            {{--<option value="all">全部课程</option>--}}
                            <option selected="selected" value="course">创课课程</option>
                            {{--<option value="review">辅导课程</option>--}}
                        </select>
                    </div>
                    <div style="width:0px;height:30px;border:1px solid #dddddd;float: left;margin-top:10px;margin-left:5px;"></div>
                    <div class="con_top_con_sbar_m">
                        <input class="txt" name="search" type="text" placeholder="请输入您要查找的课程名称" value="{{$search}}">
                    </div>
                    <div class="con_top_con_sbar_r">
                        <button type="submit">搜索</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{--搜索内容--}}
        <div style="height:40px;"></div>
        <div class="con_con">
            <div class="con_con_top">
                <div class="con_con_top_l">和<span class="gl">{{$searchInfo}}</span>有关创课课程</div>
                <div class="con_con_top_r"><span class="selOrd gl" onclick="sel(1)">最新</span> - <span class="selOrd" onclick="sel(2)">热门</span>&nbsp;&nbsp;</div>
            </div>
            <div style="height:40px;"></div>
            <div class="con_con_con">
                {{--专题课程--}}
                <div class="con_con_con_a">
                    {{--<div class="recommend_con_con_li">--}}
                        {{--<div class="contain_lesson_center_data_info_top">--}}
                            {{--<img src="{{asset('home/image/index/3.jpg')}}" width="280" height="180" class="img_big"/>--}}
                        {{--</div>--}}
                        {{--<div class="contain_lesson_center_data_info_bot">--}}
                            {{--<span class="top">钢琴演奏基础教程</span>--}}
                            {{--<div class="center">--}}
                                {{--<span class="left classes">10课时</span><span class="right study">300人学过</span>--}}
                            {{--</div>--}}
                            {{--<span class="bot">￥500.00</span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>

                <div class="clear"></div>
                <div class="page">
                    <div style="display: inline-block"><div id="demo" class="hide"></div></div>
                </div>

                {{--无结果--}}
                <div class="clear"></div>
                <div class="serno nofindaa hide">
                    没有相关内容，换个搜索条件吧
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
@endsection

@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('select').select2(
            {
                minimumResultsForSearch: Infinity
            }
        );
    </script>
@endsection

@section('js')
    <script>
        var searchVal = "{{$search}}";
    </script>
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/search/searcha.js')}}"></script>
@endsection