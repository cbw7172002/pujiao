<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0, user-scalable=0"/>
    <title>关于我们</title>
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/aboutus/appAbouts.css') }}">
</head>
<body>
<div class="background">
    <div class="main" ms-controller="aboutus">

        <!-- 右侧内容 -->
        <div class="main_right" ms-visible="isshowbox">


            <div class="main_right_content" ms-visible="currentIndex==1" ms-repeat="aboutus1">
                <!-- 公司介绍 -->
                <div class="main_right_content_intro1" ms-html="el.content">

                </div>
            </div>


            {{--联系我们--}}
            <div class="main_right_content" ms-visible="currentIndex==2" ms-repeat="aboutus2">

                <div style="height:32px"></div>
                <!-- 图片 -->
                <div class="main_right_content_img">
                    <div style="width:100%;height:100%;border:#ccc solid 1px;" id="dituContent" ms-baiduditu></div>
                </div>
                {{--<div style="height:63px"></div>--}}
                <div class="main_right_content_intro1" ms-html="el.content">

                </div>
            </div>


            {{--常见问题--}}
            <div class="main_right_content" ms-visible="currentIndex==3" ms-repeat="aboutus3">

                <div style="height:32px"></div>
                <!-- 图片 -->
                {{--<div class="main_right_content_img" id="">--}}
                {{--<img src="{{asset('home/image/aboutus/gangqin.png')}}" alt="" />--}}
                {{--</div>--}}
                {{--<div style="height:63px"></div>--}}
                <div class="main_right_content_intro1" ms-html="el.content">

                </div>
            </div>

            {{--用户协议--}}
            {{--<div class="main_right_content"   ms-visible="currentIndex==4" ms-repeat="aboutus4" >--}}

            {{--<div class="main_right_content_name" ms-html="el.title" >--}}

            {{--</div>--}}
            {{--<div style="height:32px"></div>--}}

            {{--<div class="main_right_content_intro1"  ms-html="el.content"  >--}}

            {{--</div>--}}
            {{--</div>--}}


            {{--友情链接--}}
            {{--<div class="main_right_content"   ms-visible="currentIndex==5"  >--}}

            {{--<div class="main_right_content_name"  >--}}
            {{--友情链接--}}
            {{--</div>--}}
            {{--<div style="height:19px"></div>--}}

            {{--<div class="friendlink_content">--}}

            {{--<div class="friendlink_content_each"  ms-repeat="aboutus5">--}}
            {{--<div class="friendlink_content_each_img"  >--}}
            {{--<a ms-attr-href=" linkurl+ el.url"><img  ms-attr-src="el.path"  width="200px" height="110px" alt="" /></a>--}}
            {{--<a ms-attr-href=" linkurl+ el.url"><img  ms-attr-src="el.path"  width="200px" height="110px" alt="" /></a>--}}

            {{--</div>--}}
            {{--<a ms-attr-href="linkurl+ el.url"><div class="friendlink_content_each_font" ms-html="el.title" ></div></a>--}}
            {{--</div>--}}
            {{--</div>--}}


            {{--<div id="page"></div>--}}

            {{--</div>--}}

        </div>
    </div>
</div>
<div class="clear"></div>
<div style="height:220px"></div>

<div class="screen1200"></div>

</body>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('avalon/avalon.js')}}"></script>
<script type="text/javascript" src="{{asset('home/js/layout/avalonConfig.js')}}"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>
<script>
    require(['/aboutus/firmintro'], function (model) {
        model.currentIndex = '{{$type}}' || null;

        if (window.location.hash) {
            model.currentIndex = window.location.hash.split('#')[1];
        }

        if (model.currentIndex == 1) {
            model.getData1();
            $('.us1').addClass('intro').siblings().removeClass('intro');
        } else if (model.currentIndex == 2) {
            model.getData2();
            $('.us2').addClass('intro').siblings().removeClass('intro');
        } else if (model.currentIndex == 3) {
            model.getData3();
            $('.us3').addClass('intro').siblings().removeClass('intro');
        } else if (model.currentIndex == 4) {
            model.getData4();
            $('.us4').addClass('intro').siblings().removeClass('intro');
        } else if (model.currentIndex == 5) {
            model.getData5();
            $('.us5').addClass('intro').siblings().removeClass('intro');
        }

        avalon.scan();
    });
</script>
</html>


