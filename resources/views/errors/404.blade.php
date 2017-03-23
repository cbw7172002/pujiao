<!DOCTYPE html>
<html>
    <head>
        <title>404</title>
        <meta charset="utf-8">
        <style>
            html{
                overflow-x: hidden;
                overflow-y: auto;
            }
            .container{
                width:1920px;
                height:650px;
                /*background: #dddddd;              !*tmp*!*/
                position:absolute;
                top:50%;
                left:50%;
                margin-top:-325px;
                margin-left:-960px;
            }
            .container_top{
                width:1920px;
                height:504px;
            }
            .container_foot{
                width:550px;
                height:50px;
                margin: 0 auto;
                /*background:gray;                 !*tmp*!*/
            }
            .container_foot_l{
                width:150px;
                height:50px;
                float:left;
            }
            .container_foot_r{
                width:150px;
                height:50px;
                float:right;
            }
            button{
                width:150px;
                height:50px;
                line-height: 50px;
                text-align: center;
                font-size:20px;
                color:#ffffff;
                background:#00A0EA;
                font-weight: bold;
                border:none;
                border-radius:5px;
                -moz-border-radius:5px;
                -webkit-border-radius:5px;
                cursor:pointer;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="container_top">
                <img src="{{asset('home/image/errors/error.png')}}" alt="">
            </div>
            <div style="height:90px;"></div>
            <div class="container_foot">
                <div class="container_foot_l"><a href="{{url('/')}}"><button>去首页</button></a></div>
                <div class="container_foot_r"><button onclick="history.go(-1)">返回上一页</button></div>
            </div>
        </div>
    </body>
</html>
