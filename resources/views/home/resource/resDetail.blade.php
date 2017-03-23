@extends('layouts.layoutHome')

@section('title', '资源详情')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/resource/resDetail.css') }}">
@endsection

@section('content')
    <div class="contain_resource" ms-controller="resDetailcontroller">
        <div class="contain_resource_location"><span>资源中心</span> > 资源详情 <div class="whole" ms-if="showWholeBtn"><a ms-href="info.resourcePath" target="_blank">全屏查看</a></div></div>
        <div class="contain_resource_left">
            {{--资源播放器--}}
            <div class="contain_resource_left_player">
                <div id="myplayer" ms-if="showVideo"></div>
                <iframe ms-if="!showVideo" ms-attr-src="info.resourcePath" width="100%" height="100%" ></iframe>
                <div id="onlinePlay" ms-if="isOnline"></div>
            </div>
            <div style="height:40px;"></div>
            {{--资源信息及评论--}}
            <div class="contain_resource_left_resinfo">
                <div class="contain_resource_left_resinfo_top">
                    <div class="contain_resource_left_resinfo_top_li sel" ms-click="tabs('introduce')">资源介绍</div>
                    <div class="contain_resource_left_resinfo_top_li" ms-click="tabs('comment')">用户评论</div>
                </div>
                {{--资源介绍--}}
                <div class="contain_resource_left_resinfo_intruction" ms-if="introduce">
                    <div class="contain_resource_left_resinfo_intruction_t">资源说明</div>
                    <div class="contain_resource_left_resinfo_intruction_con">
                        <div class="contain_resource_left_resinfo_intruction_restitle" ms-html="info.resourceTitle"> </div>
                        <div class="contain_resource_left_resinfo_intruction_li">
                            <span ms-html="info.gradeName"></span>
                            <span ms-html="info.subjectName"></span>
                            <span ms-html="info.editionName"></span>
                            <span ms-html="info.bookName"></span>
                        </div>
                        <div class="contain_resource_left_resinfo_intruction_li">
                            <span>上传时间：<span ms-html="info.created_at"></span></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>上传者：<span ms-html="info.username"></span></span>
                        </div>
                        <div class="contain_resource_left_resinfo_intruction_li">
                            <div class="contain_resource_left_resinfo_intruction_li_l">浏览 <span ms-html="info.resourceView"></span></div>
                            <div class="contain_resource_left_resinfo_intruction_li_l">下载 <span ms-html="info.resourceDownload"></span></div>
                            <div class="contain_resource_left_resinfo_intruction_li_l">收藏 <span ms-html="info.resourceFav"></span></div>

                            <div class="contain_resource_left_resinfo_intruction_li_r havea" ms-class="haveb:info.isCollection" ms-click="addCollection(info.id)">收藏资源</div>
                            @if($detailId != 318 && $detailId != 291 && $detailId != 290 && $detailId != 289)
                                <div class="contain_resource_left_resinfo_intruction_li_r download" ms-click="getDown({{$detailId}})">下载资源</div>
                            @endif
                        </div>
                    </div>
                    <div style="height:20px;"></div>
                    <div class="contain_resource_left_resinfo_intruction_t">资源描述</div>
                    <div class="contain_resource_left_resinfo_intruction_con">
                        <div class="contain_resource_left_resinfo_intruction_con_des" ms-html="info.resourceIntro" ms-if="!resdescribe">

                        </div>
                        <div ms-if="resdescribe" class="resdescribe_msg">暂无资源描述内容...</div>
                    </div>
                </div>
                {{--用户评论--}}
                <div name="comment" ms-if="comment">
                    @if(Auth::check() && Auth::user() -> type != 3)
                        <div class="contain_lessonDetail_bot_left_comment">
                            <textarea ms-duplex="commentContent" id="commentContent"></textarea>
                            <span ms-on-mouseout="descriptionSwitch('replyWarning', false)" ms-click="publishComment(info.id,commentContent);">发布</span>
                            <div class="teacherHomepage_detail_content_applyTip" ms-visible="replyWarning">请输入评论内容</div>
                        </div>
                        <div class="comment_button" style="background: none;"></div>
                    @else
                        <div class="comment_textarea comment_textarea_nologin" style="line-height: 150px;"><a href="/index/login" style="color: #3BA3FE;text-decoration: none;">请登录后发表评论</a></div>
                        <div class="comment_button" style="background: none;"></div>
                    @endif
                    <div class="clear" style="width: 100%;height: 20px;"></div>
                    <div class="first_not">
                        {{--评论列表--}}
                        <div class="contain_lessonDetail_bot_left_comment_content" ms-repeat="commentinfo">
                            <div class="photo">
                                <img ms-attr-src="el.comPic"  width="70" height="70" alt=""/>
                            </div>
                            <div class="right">
                                <div class="top">
                                    <span ms-html="el.username"></span><span class="time" ms-html="el.created_at">一月前</span>
                                </div>
                                <div class="center">
                                    <div class="content">
                                        <span class="touser" ms-html="'@' + el.tousername + '&nbsp;&nbsp;&nbsp;'" ms-if="el.tousername"></span><span ms-text="el.commentContent"></span>
                                        {{--<span class="touser">@小亮&nbsp;&nbsp;&nbsp;</span><span ms-html="el.commentContent"> </span>--}}
                                    </div>
                                </div>
                                <div class="bot">
                                    @if(Auth::check() && Auth::user() -> type != 3)
                                        {{--<a href="#input_content" ms-if="(!el.isSelf && detailInfo.isBuy) || (!el.isSelf && detailInfo.isAuthor) || (detailInfo.isTeacher && !el.isSelf) || (detailInfo.isFree && !el.isSelf)" ms-click="replyComment(el.username,el.id);">--}}
                                        <a href="#input_content" ms-if="!el.isSelf" ms-click="replyComment(el.username,el.id);">
                                            <span class="first">回复</span>
                                        </a>
                                        <span class="third" ms-if="el.isSelf" ms-click="deleteComment(el.id,info.id)">删除</span>

                                        <span class="second" ms-click="addLike(el);" ms-if="!el.isLike">点赞（[-- el.likeTotal || 0--] )</span>
                                        <span class="no_hover" ms-if="el.isLike">已赞（[-- el.likeTotal || 0--] )</span>
                                    @else
                                        <a href="/index/login"><span class="second">点赞（ 0 )</span></a>
                                    @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                    </div>
                    <div ms-visible="commentMsg" class="comment_msg">暂无相关评论...</div>
                </div>
            </div>
        </div>
        {{--相关资源推荐--}}
        <div class="contain_resource_right">
            <div class="contain_resource_right_t">相关资源推荐</div>
            <div class="contain_resource_right_li" ms-repeat="relationinfo">
                <div class="contain_resource_right_li_pic">
                    <a ms-attr-href="'/resource/resDetail/' + el.id"><img ms-attr-src="el.resourcePic" alt="" style="width:100%;height:100%;"></a>
                </div>
                <div class="contain_resource_right_li_info">
                    <a ms-attr-href="'/resource/resDetail/' + el.id"><div class="contain_resource_right_li_info_top" ms-html="el.resourceTitle"></div></a>
                    <div class="contain_resource_right_li_info_fot" ms-html="el.created_at"></div>
                </div>
            </div>
            <div ms-visible="relationmsg" class="relation_msg">暂无相关推荐...</div>
        </div>
        <div style="height:60px;clear: both"></div>

        <!-- 遮罩层 -->
        <div class="shadow hide" ms-visible="popUp"></div>
        <!-- 删除评论弹出层 -->
        <div class="delete_comment hide" ms-visible="'deleteComment' == popUp">
            <div class="top">
                <span>确认删除该评论？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch(false, 'delComment');">确定</span>
            </div>
        </div>

    </div>
@endsection


@section('js')
    <script type="text/javascript" src="{{asset('home/jplayer/jwplayer.js')}}"></script>
	<!--[if gt IE 9]>
	   <script src="/home/jplayer/jwplayer.html5.js" type="text/javascript"></script>  
    <![endif]-->
    <script type="application/x-javascript">
        require(['/resource/resDetail'], function (vm) {
            vm.detailId = {{$detailId}} || null;
            vm.$watch('commentContent', function (value) {
                if (value.length < vm.commentContentLength) {
                    vm.commentContentLength = '';
                    vm.commentContent = '';
                    vm.tousername = '';
                    vm.parentId = '';
                }
            });
//            console.log(vm.detailId);return false;
            vm.getDetail(vm.detailId);
            vm.getCommentInfo(vm.detailId);
            vm.getRealtion(vm.detailId);
            avalon.scan(document.body);
        });
    </script>
@endsection