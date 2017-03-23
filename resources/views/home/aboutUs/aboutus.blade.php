@extends('layouts.layoutHome')

@section('title', '关于我们')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/aboutus/aboutus.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/games/pagination.css')}}">


    <style type="text/css">
        #page{
           margin-top: -20px;
            margin-left: -24px;
        }

    </style>

@endsection

@section('content')


    <div class="background"  >


        <div style="height:30px"></div>

        <div class="main" ms-controller="aboutus">

            <!-- 左侧链接 -->
            <div class="main_left">

                <span class="span_hover"></span>
                <div class="us1 us"   ms-click="tabs(1);">公司介绍</div>
                <span class="span_hover"></span>
                <div class="us3 us" ms-click="tabs(3);">常见问题</div>
                <span class="span_hover"></span>
                <div class="us2 us" ms-click="tabs(2);">联系我们</div>
                <span class="span_hover"></span>
                <div class="us4 us" ms-click="tabs(4);">用户协议</div>
                <span class="span_hover"></span>
                <div  class="us5 us" ms-click="tabs(5);">友情链接</div>

            </div>

            <!-- 右侧内容 -->
            <div class="main_right" ms-visible="isshowbox">


                <div class="main_right_content" ms-visible="currentIndex==1" ms-repeat="aboutus1" >
                    <!-- 公司介绍 -->
                    <div class="main_right_content_name" ms-html="el.title" >

                    </div>
                    <div style="height:32px"></div>
                    <!-- 图片 -->
                    {{--<div class="main_right_content_img">--}}
                        {{--<img src="{{asset('home/image/aboutus/gangqin.png')}}" alt="" />--}}
                    {{--</div>--}}
                    {{--<div style="height:63px"></div>--}}
                    <div class="main_right_content_intro1"  ms-html="el.content" >

                    </div>
                </div>


                {{--联系我们--}}
                <div class="main_right_content"  ms-visible="currentIndex==2" ms-repeat="aboutus2"  >

                    <div class="main_right_content_name"  ms-html="el.title"  >

                    </div>
                    <div style="height:32px"></div>
                    <!-- 图片 -->
                    <div class="main_right_content_img">
                        <div style="width:627px;height:306px;border:#ccc solid 1px;" id="dituContent" ms-baiduditu ></div>
                    </div>
                    {{--<div style="height:63px"></div>--}}
                    <div class="main_right_content_intro1" ms-html="el.content"  >

                    </div>
                </div>



                {{--常见问题--}}
                <div class="main_right_content"   ms-visible="currentIndex==3" ms-repeat="aboutus3" >

                    <div class="main_right_content_name" ms-html="el.title" >

                    </div>
                    <div style="height:32px"></div>
                    <!-- 图片 -->
                    {{--<div class="main_right_content_img" id="">--}}
                        {{--<img src="{{asset('home/image/aboutus/gangqin.png')}}" alt="" />--}}
                    {{--</div>--}}
                    {{--<div style="height:63px"></div>--}}
                    <div class="main_right_content_intro1"  ms-html="el.content"  >

                    </div>
                </div>

                {{--用户协议--}}
                <div class="main_right_content"   ms-visible="currentIndex==4" ms-repeat="aboutus4" >

                    <div class="main_right_content_name" ms-html="el.title" >

                    </div>
                    <div style="height:32px"></div>

                    <div class="main_right_content_intro1"  ms-html="el.content"  >

                    </div>
                </div>


                {{--友情链接--}}
                <div class="main_right_content"   ms-visible="currentIndex==5"  >

                    <div class="main_right_content_name"  >
                        {{--友情链接--}}
                    </div>
                    <div style="height:19px"></div>

                    <div class="friendlink_content">

                        <div class="friendlink_content_each"  ms-repeat="aboutus5">
                            <div class="friendlink_content_each_img"  >
                                {{--<a ms-attr-href=" linkurl+ el.url"><img  ms-attr-src="el.path"  width="200px" height="110px" alt="" /></a>--}}
                                <a ms-attr-href=" linkurl+ el.url"><img  ms-attr-src="el.path"  width="200px" height="110px" alt="" /></a>

                            </div>
                            <a ms-attr-href="linkurl+ el.url"><div class="friendlink_content_each_font" ms-html="el.title" ></div></a>
                        </div>
                    </div>


                        {{--<div id="page"></div>--}}

                </div>


            </div>







        </div>



    </div>



    <div class="clear"></div>
    <div style="height:220px"></div>

    <div class="screen1200"></div>



@endsection

@section('js')
    <script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>

    <script>

            $(".us").hover(
                    function () {
                        $(this).addClass("active_hover").prev('span').addClass('span_active');
                    },
                    function () {
                        $(this).removeClass("active_hover").prev('span').removeClass('span_active');
                        ;
                    }
            );

//            $(".intro").hover(function(){
//                $(this).removeClass("active_hover");
//            });

    </script>

    <script>
        require(['/aboutus/firmintro'], function (model) {
            model.currentIndex = {{$type}} || null;

            if(window.location.hash){
                model.currentIndex = window.location.hash.split('#')[1];
            }

            if(model.currentIndex == 1){
                model.getData1();
                $('.us1').addClass('intro').siblings().removeClass('intro');
            }else if(model.currentIndex == 2){
                model.getData2();
                $('.us2').addClass('intro').siblings().removeClass('intro');
            }else if(model.currentIndex == 3){
                model.getData3();
                $('.us3').addClass('intro').siblings().removeClass('intro');
            }else if(model.currentIndex == 4){
                model.getData4();
                $('.us4').addClass('intro').siblings().removeClass('intro');
            }else if(model.currentIndex == 5){
                model.getData5();
                $('.us5').addClass('intro').siblings().removeClass('intro');
            }

            avalon.scan();
        });
  </script>




@endsection


