<!DOCTYPE html>
<html>
<head>
    <title>完善个人信息</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/registsucesstea.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
</head>
<body>
    <div class="center">
        <div class="teacher_con_top">
            <div class="teacher_con_top_l" ><div style="height:60px;"></div><hr></div>
            <div class="teacher_con_top_t">注册成功</div>
            <div class="teacher_con_top_r" ><div style="height:60px;"></div><hr></div>
        </div>
        <div class="ts">
            为了带给您更好的服务，请您完善以下账户信息
        </div>
        <div style="height:50px;"></div>
        <form method="post" action="{{url('index/infotea')}}">
            {!! csrf_field() !!}

            <div class="center_con_con">
            {{--真实姓名--}}
            <div style="height:20px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">真实姓名</div>
                <div class="center_con_r_bar_m"><input class="txt" name="realname" type="text"></div>
            </div>
            {{--性别--}}
            <div style="height:20px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">性别</div>
                <div class="center_con_r_bar_m">
                    <input  type="radio" name="sex" value="1" checked>男 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input  type="radio" name="sex" value="2" >女
                </div>
            </div>
            {{--所在单位--}}
            <div style="height:25px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">所在学校</div>
                <div class="center_con_r_bar_m"><input class="txt" name="school" type="text"></div>
            </div>
            {{--毕业院校--}}
            <div style="height:25px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">所属专业</div>
                <div class="center_con_r_bar_m"><input class="txt" name="stuMajor" type="text"></div>
            </div>
            {{--学历--}}
            {{--<div style="height:25px;"></div>--}}
            {{--<div class="center_con_r_bar">--}}
                {{--<div class="center_con_r_bar_ll">学历</div>--}}
                {{--<div class="center_con_r_bar_m">--}}
                    {{--<select name="education"  class="js-example-basic-single">--}}
                        {{--<option selected="selected">学历</option>--}}
                        {{--<option value="专科">专科</option>--}}
                        {{--<option value="本科">本科</option>--}}
                        {{--<option value="研究生">研究生</option>--}}
                        {{--<option value="博士">博士</option>--}}
                    {{--</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}

                    {{--<span style="font-weight: bold">职称</span>&nbsp;&nbsp;&nbsp;--}}

                    {{--<select name="professional"  class="js-example-basic-single">--}}
                        {{--<option selected="selected">职称</option>--}}
                        {{--<option value="助教">助教</option>--}}
                        {{--<option value="讲师">讲师</option>--}}
                        {{--<option value="副教授">副教授</option>--}}
                        {{--<option value="教授">教授</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--居住地--}}
            {{--<div style="height:25px;"></div>--}}
            {{--<div class="center_con_r_bar">--}}
                {{--<div class="center_con_r_bar_ll">居住地</div>--}}
                {{--<div class="center_con_r_bar_m">--}}
                    {{--<select name="provinceId"  class="js-example-basic-single province" onChange="getCity(this.value)">--}}
                        {{--<option selected="selected">省</option>--}}
                    {{--</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}

                    {{--<select name="cityId"  class="js-example-basic-single city">--}}
                        {{--<option selected="selected">市</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
            {{--</div>--}}

            </div>
            <div class="btntj">
                <button type="submit" class="wc">完成</button>
            </div>
        </form>
    </div>
</body>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/registsucesstea.js') }}"></script>
<script type="text/javascript">
    $('select').select2(
        {
            minimumResultsForSearch: Infinity
        }
    );

    $(".province").select2({
        ajax: {
            url: "/index/getProvince",
            type:'get',
            dataType:'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
//            cache: true
        },

    });
</script>
</html>