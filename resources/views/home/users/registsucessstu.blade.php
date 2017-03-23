<!DOCTYPE html>
<html>
<head>
    <title>完善个人信息</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/registsucessstu.css')}}">
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
        <form method="post" action="{{url('index/infostu')}}">
            {!! csrf_field() !!}
            <div class="center_con_con">

            {{--性别--}}
            <div style="height:20px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">性别</div>
                <div class="center_con_r_bar_m">
                    <input  type="radio" name="sex" value="1" checked>男 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input  type="radio" name="sex" value="2">女
                </div>
            </div>

            {{--当前等级--}}
            <div style="height:25px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">当前等级</div>
                <div class="center_con_r_bar_m">
                    <select name="pianoGrade"  class="js-example-basic-single">
                        <option selected="selected">等级</option>
                        <option value="钢琴一级">钢琴一级</option>
                        <option value="钢琴二级">钢琴二级</option>
                        <option value="钢琴三级">钢琴三级</option>
                        <option value="钢琴四级">钢琴四级</option>
                        <option value="钢琴五级">钢琴五级</option>
                        <option value="钢琴六级">钢琴六级</option>
                        <option value="钢琴七级">钢琴七级</option>
                        <option value="钢琴八级">钢琴八级</option>
                        <option value="钢琴九级">钢琴九级</option>
                        <option value="钢琴十级">钢琴十级</option>
                    </select>
                </div>
            </div>

            {{--日期--}}
            <div style="height:25px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">开始学琴时间</div>
                <div class="center_con_r_bar_m">
                    <select name="learnYear"  class="js-example-basic-single sel_year">
                        <option selected="selected">年份</option>
                        {{--<option value="1" selected="selected">2016</option>--}}
                    </select >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                    <select name="learnMonth"  class="js-example-basic-single sel_month">
                        <option selected="selected">月份</option>
                        {{--<option value="1" selected="selected">1</option>--}}
                    </select>
                </div>
            </div>
            {{--居住地--}}
            <div style="height:25px;"></div>
            <div class="center_con_r_bar">
                <div class="center_con_r_bar_ll">居住地</div>
                <div class="center_con_r_bar_m">
                    <select name="provinceId"  class="js-example-basic-single province" onChange="getCity(this.value)">
                        <option selected="selected">省</option>
                        {{--<option value="120000" selected="selected">天津市</option>--}}
                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <select name="cityId"  class="js-example-basic-single city">
                        <option selected="selected">市</option>
                    </select>
                </div>
            </div>
            </div>
            <div class="btntj">
                <button type="submit" class="wc">完成</button>
            </div>
        </form>
    </div>
</body>
<script type="text/javascript" src="{{asset('home/js/layout/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/jquery.bday-picker.js') }}"></script>
<script type="text/javascript" src="{{asset('home/js/users/registsucessstu.js') }}"></script>
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

    $(function () {
        $.ms_DatePicker({
            YearSelector: ".sel_year",
            MonthSelector: ".sel_month",
//            DaySelector: ".sel_day"
        });
    });
</script>
</html>