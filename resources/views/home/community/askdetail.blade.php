@extends('layouts.layoutHome')

@section('title', '问答详情页')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/community/askdetail.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('home/css/community/question.css') }}">--}}

@endsection

@section('content')

    <div class="cont" ms-controller="askdetail">
        {{--@if (session('right'))--}}
        {{--<div class="editResInfo dui">* {{session('right')}}</div>--}}
        {{--@elseif(session('wrong'))--}}
        {{--<div class="editResInfo cuo">* {{session('wrong')}}</div>--}}
        {{--@endif--}}
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

            <div style="height:30px;background: #F5F5F5"></div>

            {{--回复评论等内容--}}
            <div class="reply_content">
                <div class="ask_name">
                    @if($data->rName)
                        <div>提问者: {{$data->rName}}</div>
                    @else
                        <div>提问者: {{$data->uName}}</div>
                    @endif
                </div>
                <div class="collect_sum">
                    <div class="collect_sum_right hide" ms-class="hide:isfav"  ms-click="qesfav({{$data->id}})" >
                        <div><img src="{{asset('/home/image/community/store.png')}}" alt=""></div>
                        <div>收藏</div>
                    </div>
                    <div class="collect_sum_right hide" ms-class="hide:!isfav" ms-click="qesdefav({{$data->id}})" >
                        <div><img src="{{asset('/home/image/community/hstore.png')}}" alt=""></div>
                        <div>已收藏</div>
                    </div>
                </div>

                <div class="huida">
                    回答
                </div>
                <div style="height:20px"></div>
                <div class="text_edit">
                    {{--<script id="container" name="content" type="text/plain"></script>--}}
                    {{--<textarea id="commentContent" ms-duplex="commentContent" cols="100" rows="9.5"></textarea>--}}
                    {{--<script id="commentContent" name="answerContent"  type="text/plain"></script>--}}

                    <textarea id="commentContent" ms-duplex="commentContent" cols="100" rows="9.5"></textarea>

                </div>
                <div style="height:24px"></div>

                {{--<div class="fabu_img" ms-click="postComment({{$data->id}})">--}}
                <div class="fabu_img" onclick="postcomments({{$data->id}})" >

                    <img src="{{asset('/home/image/community/fabu.png')}}" alt="">
                </div>
                <div style="height:14px"></div>
                {{--回答人数--}}
                <div class="reply_number">
                    {{--3个回答--}}
                </div>
                <div style="height:20px"></div>


                {{--回答内容--}}
                <div class="reply_content_detail" >

                    <div class="reply_content_detail_every" ms-repeat-el="datas">
                        <div style="height:20px"></div>
                        <div class="reply_content_detail_every_top">



                            <div class="every_top_img" ms-if="el.type == 1">
                                <a ms-attr-href="'/member/studentHomePagePublic/'+el.uid"><img ms-attr-src="el.pic"  alt="" width="100%" height="100%"></a>
                            </div>

                            <div class="every_top_img" ms-if="el.type == 2">
                                <a ms-attr-href="'/member/teacherHomePagePublic/'+el.uid"><img ms-attr-src="el.pic"  alt="" width="100%" height="100%"></a>
                            </div>

                            <div class="every_top_name" ms-if="el.realname"  ms-html="el.realname"></div>

                            <div class="every_top_name" ms-if="!el.realname"  ms-html="el.username"></div>

                            {{--是否采纳--}}
                            @if(\Auth::user()->id == $data->uId )
                                {{--<div class="every_top_caina caina " ms-if="!caina" ms-click="cainaclick(el.id,{{$data->id}},el.answerContent)" ></div>--}}
                                {{--<div class="every_top_caina yicaina " ms-if="el.id == caina"></div>--}}
                                <div class="every_top_caina" ms-class-1="caina:!caina" ms-class-2="yicaina:el.id == caina" ms-click="cainaclick(el.id,{{$data->id}},el.answerContent,caina)"></div>
                            @else
                                {{--<div class="every_top_caina caina " ms-if="!caina" ms-click="cainaclick(el.id,{{$data->id}},el.answerContent)" ></div>--}}
                                {{--<div class="every_top_caina yicaina " ms-if="el.id == caina"></div>--}}
                                <div class="every_top_caina yicaina " ms-if="el.id == caina"></div>
                            @endif

                            <div class="every_top_time" ms-html="el.comTime"></div>
                        </div>
                        <div style="height:18px"></div>
                        {{--中间回复内容--}}
                        <div class="reply_content_detail_every_middle" ms-html="el.answerContent">

                        </div>
                        <div style="clear: both"></div>
                        {{--<div style="height:20px"></div>--}}
                        {{--删除点赞等--}}
                        <div class="reply_content_detail_every_buttom">


                            @if(Auth::check())
                                <div class="every_buttom_answer" ms-attr-title="'comment'+el.id" ms-click="anscomment(el.id,el.username)" ms-html="'回复' + el.commentCount"></div>
                                <div class="every_buttom_answer" ms-attr-title="'comments'+el.id" ms-click="anscomments(el.id,el.username)" style="display: none" >收起回复</div>
                            @endif

                            <div class="every_buttom_agree" ms-yizan  ms-click="favcomment(el.id)" ms-if="!el.isthumb" ms-html="'赞同'+ el.thumbNum+''">赞同</div>
                            <div class="every_buttom_agree_yizan" ms-if="el.isthumb" ms-html="'已赞 '+el.thumbNum+''">已赞</div>

                            <div class="every_buttom_delete" ms-click="delcomment(el.id)" ms-if="el.username == el.nowloginUname">删除</div>

                        </div>


                        {{--评论内容--}}
                        <div class="reply_content_comment_div"  ms-repeat-item="el.comment">
                            <div class="reply_content_comment"  ms-attr-title="'ansarea'+el.id" style="display: none"  >
                                <div style="height:10px"></div>
                                <div class="reply_content_comment_every">

                                    <div class="reply_content_comment_every_img" ms-if="item.type == 1">
                                        <a ms-attr-href="'/member/studentHomePagePublic/'+item.uids">
                                            <img ms-attr-src="item.pic" alt="" width="100%" height="100%">
                                        </a>
                                    </div>

                                    <div class="reply_content_comment_every_img" ms-if="item.type == 2">
                                        <a ms-attr-href="'/member/teacherHomePagePublic/'+item.uids">
                                            <img ms-attr-src="item.pic" alt="" width="100%" height="100%">
                                        </a>
                                    </div>

                                    <div class="reply_content_comment_every_name" ms-if="item.realname" ms-html="item.realname"></div>
                                    <div class="reply_content_comment_every_name" ms-if="!item.realname" ms-html="item.username"></div>

                                    <div class="reply_content_comment_every_time" ms-html="item.comTimes"></div>
                                </div>
                                <div style="height:10px"></div>

                                <div class="reply_content_comment_detial" ms-html="item.answerContent">

                                </div>
                            </div>
                            <div class="div_30" style="height:10px;display: none" ms-attr-title="'ansarea'+el.id" ></div>

                        </div>



                        {{--<div class="div_30" style="height:30px;" ms-if="commentshow"></div>--}}
                        {{--评论框--}}
                        <div class="reply_content_frame" ms-repeat-item="el.commentUser" ms-attr-title="'ansarea'+el.id" style="display: none">
                            <div style="height:10px"></div>
                            <div class="reply_content_comment_every">
                                <div class="reply_content_comment_every_img">
                                    <img ms-attr-src="item.pic" alt="" width="100%" height="100%">
                                </div>

                                <div class="reply_content_comment_every_name"  ms-if="item.realname"  ms-html="item.realname"></div>
                                <div class="reply_content_comment_every_name"  ms-if="!item.realname" ms-html="item.username"></div>


                            </div>
                            <div style="height:10px"></div>
                            {{--评论框--}}
                            <div class="reply_content_frame_frame" ms-click="noteShow(el.id)">
                                <textarea id="comment" ms-duplex="comment" class="reply_content_frame_frame_textarea"  cols="30" rows="10"></textarea>
                            </div>
                            <div id="note" class="note" ms-attr-title="'note'+el.id">写下你的回复</div>
                            <div style="height:20px"></div>
                            <div class="huifu" ms-click="comments({{$data->id}})">
                                <img  src="{{asset('/home/image/community/huifu.png')}}" alt="">
                            </div>
                        </div>
                        <div  class="reply_content_frame1" ms-attr-title="'ansarea'+el.id" style="height:120px;display: none"></div>


                    </div>

                </div>



            </div>




        </div>
        {{--右部--}}
        <div class="cont_right">



            @if(Auth::check())
                <a href="/community/question">
                    @else
                        <a href="/index/login">
                            @endif
                            <img src="{{asset('/home/image/community/woyaotiwenslan.png')}}"  width="360" alt="">
                        </a>


                        {{--我要提问--}}
                        {{--<a href="/community/question">--}}
                        {{--<div class="woyaotiwen" style="width:360px;">--}}
                        {{--<img src="{{asset('/home/image/community/woyaotiwen.png')}}" width="360"  alt="">--}}
                        {{--<a ms-attr-href="'/community/question/'+el.id"><div class="ask_question">提问</div></a>--}}
                        {{--</div>--}}
                        {{--</a>--}}


                        <div style="height:20px"></div>


                        {{--问答推荐--}}
                        <div class="cont_right_a">
                            <div class="cont_right_type">问答推荐</div>
                            <div style="height:15px;"></div>
                            <!-- 推荐列表 -->
                            @foreach ($tuijians as $tuijian)
                                <div class="cont_right_a_wdtj_li">
                                    <a href="{{url('community/askDetail/'.$tuijian->id)}}"><div class="cont_right_a_wdtj_li_top">{{$tuijian->qestitle}}</div></a>
                                    <div class="cont_right_a_wdtj_li_bot">

                                        @if($tuijian->teaname->realname)
                                            <div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_l"> {{$tuijian->teaname->realname}} 提问</div>
                                        @else
                                            <div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_l"> {{$tuijian->teaname->username}} 提问</div>
                                        @endif
                                        <div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_m"> {{$tuijian->answer}} 回答</div>
                                        <div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_r">来自 {{$tuijian->type}}</div>
                                    </div>
                                </div>
                            @endforeach
                            {{--<div class="cont_right_a_wdtj_li">--}}
                            {{--<a href=""><div class="cont_right_a_wdtj_li_top">怎么画好手部？</div></a>--}}
                            {{--<div class="cont_right_a_wdtj_li_bot">--}}
                            {{--<div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_l">吴迪 回答</div>--}}
                            {{--<div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_m">3 回复</div>--}}
                            {{--<div class="cont_right_a_wdtj_li_bot_li cont_right_a_wdtj_li_bot_li_r">来自 美术</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>

        </div>

        <div style="clear: both;height:30px;"></div>
    </div>

    <div style="height:180px;"></div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.config2.js') }}"></script>
    <script type="text/javascript" src="{{asset('admin/ueditor/ueditor.all.js') }}"></script>

    <script>
        require(['/community/askdetail'], function (askdetail) {
            askdetail.askstuName = '{{$data->uName}}' || null;
            {{--askdetail.askteaName = '{{$data->bestName}}' || null;--}}
                    askdetail.caina = '{{$data->bestId}}' || null;

            askdetail.getaskstatus({{$data->id}});
            askdetail.getComment({{$data->id}});

            askdetail.$watch('commentContent', function (value) {
                if (value.length < askdetail.toUnamelength) {
                    askdetail.toUname = '';        //清空回复 某人
                    askdetail.comId   = '';        //清空回复对应评论id
                    $('#commentContent').val('');  //清空文本框
                }
            });


            avalon.scan(document.body);
        });
    </script>

    <script>
        //初始化富文本编辑器
        var ue = UE.getEditor('commentContent',{
            initialFrameHeight:280,
            //textarea:'content',
        });

            function postcomments(qesId){
                var uname = '{{$data->uName}}';                   //提问者用户名
                var postdata = {};                                //提交的对象
                var answerContent = ue.getContent();           //获取回复内容
                var content = '{{$data->qestitle}}';
                if (!answerContent || !$.trim(answerContent)) {alert('请输入发布内容！');return false};
                postdata = {qesId:qesId,answerContent:answerContent,uname:uname,content:content};
//                $('.fabu_img').style.display = 'none';
//                $('#commentContent').html('');
                ue.setContent('')
                $.ajax({
                    type: 'POST',
                    url: '/community/qesreply',
                    data:postdata,
                    success: function(response) {
                        if(response.status){
                            window.location.reload();
                        }else{
                            alert('发布失败，请重新尝试');
                        }
                    }
                });
        }


    </script>

@endsection
