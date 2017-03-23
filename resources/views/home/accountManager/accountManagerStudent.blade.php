@extends('layouts.layoutHome')

@section('title', '账号管理')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/accountManagerStudent.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/Jcrop.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/uploadify.css')}}">
@endsection

@section('content')
    <div class="container" ms-controller="accountManagerStudent">
        <div class="h40"></div>
        <div class="center_left">
            <div class="account_manager_top">
                <div class="account_manager_top_left">
                    <img ms-attr-src="'{{$data->pic}}'" alt="" width="65" height="65" onerror="javascript:this.src='/home/image/layout/default.png';"/>
                </div>

                <div class="account_manager_top_right">
                    <div class="account_manager_top_right_top" ms-html="'{{$data->realname}}'"></div>
                    <div class="account_manager_top_right_bottom" ms-html="'学生'"></div>
                </div>
            </div>

            <div class="account_manager" ms-html="'账号管理'"></div>
            <span class="span_hover"></span>
            <div class="account_common blue_common" ms-html="'信息维护'" name="infoUphold" ms-click="changeTab('infoUphold')"></div>
            <span class="span_hover"></span>
            <div class="account_common" ms-html="'密码修改'" name="changePass" ms-click="changeTab('changePass')"></div>
            <span class="span_hover"></span>
            <div class="account_common phone_width_diff" ms-html="'绑定手机号'" name="bindPhone" ms-click="changeTab('bindPhone')"></div>

            <div class="h20"></div>
        </div>


        <!--主体右边  信息维护开始-->
        <div class="center_right hide" value="infoUphold" ms-visible="tabStatus === 'infoUphold' ">
            <div class="right_password_top">信息维护</div>
            <div class="info_uphold">
                <div class="info_uphold_img">
                    <div class="info_uphold_img_left">头像</div>
                    <div class="info_uphold_img_right">
                        <div class="img_right_image">
                            <img ms-attr-src="'{{$data->pic}}'" alt="" width="48" height="48" onerror="javascript:this.src='/home/image/layout/default.png';">
                        </div>

                        <div class="upload_img_text">上传头像</div>
                    </div>
                </div>

                <div class="info_form_group">
                    <label for="inputUsername" class="form_group_label">用&nbsp; 户&nbsp; 名</label>
                    <div class="form_group_input">
                        <span ms-html="'{{$data->username}}'"></span>
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label for="inputUsername" class="form_group_label">学&nbsp; &nbsp; &nbsp; &nbsp;号</label>
                    <div class="form_group_input">
                        <span ms-html="'{{$data->sno}}' || '无'"></span>
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label for="inputUsername" class="form_group_label">性&nbsp; &nbsp; &nbsp; &nbsp;别</label>
                    <div class="form_group_input">
                        <span ms-html="'{{$data->sex == 1 ? '男' : '女'}}'"></span>
                    </div>
                </div>
                <div class="info_form_message"></div>


                <div class="info_form_group">
                    <label for="inputSchool" class="form_group_label">学&nbsp; &nbsp; &nbsp; &nbsp;校</label>
                    <div class="form_group_input">
                        <span ms-html="'{{ $data->school }}'"></span>
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label for="inputUsername" class="form_group_label">所属学级</label>
                    <div class="form_group_input">
                        <span ms-html="'{{$data->schoolYear ? $data->schoolYear : '暂无'}}'"></span>
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label for="inputUsername" class="form_group_label">所属班级</label>
                    <div class="form_group_class">
                        <span ms-html="'{{$data->gradeId.$data->classId ? $data->gradeId.$data->classId : '暂无'}}'"></span>
                    </div>
                </div>
                <div class="info_form_message"></div>


            </div>
        </div>
        <!--主体右边   信息维护结束-->

        <!--主体右边   密码修改开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'changePass'">
            <div class="right_password_top">密码修改</div>
            <div class="infoMsg infoMsg_success" ms-visible="infoMsg" ms-html="infoMsg_success"></div>
            <div class="infoMsg infoMsg_error" ms-visible="infoMsg !== '' && !infoMsg" ms-html="infoMsg_error"></div>
            <div class="right_password_content">
                <div class="password_content">
                    <div class="password_content_input">
                        <input type="password" name="currentPass" ms-keyup="validateCurrent(currentPass)" placeholder="  当前密码" ms-duplex="currentPass">
                    </div>
                    <div class="password_content_message"></div>
                </div>

                <div class="password_content">
                    <div class="password_content_input">
                        <input type="password" placeholder="  新密码" ms-keyup="validateNew(newPass)" name="newPass" ms-duplex="newPass" />
                    </div>
                    <div class="password_content_message"></div>
                </div>

                <div class="password_content">
                    <div class="password_content_input">
                        <input type="password" placeholder="  确认新密码" ms-keyup="validateSure(sureNewPass)" name="sureNewPass" ms-duplex="sureNewPass" />
                    </div>
                    <div class="password_content_message"></div>
                </div>

                <button type="button" ms-attr-disabled="passMsg || newPassMsg || surePassMsg" ms-css-cursor="(passMsg || newPassMsg || surePassMsg) ? 'not-allowed' : 'pointer'" ms-click="changePassButton(currentPass, newPass, sureNewPass, mineId)">确定</button>
            </div>
        </div>
        <!--主体右边   密码修改结束-->

        <!--主体右边   绑定手机号开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'bindPhone'">
            <div class="right_password_top" ms-html="'绑定手机号'"></div>
            <div class="right_phone_content">
                <div class="change_phone_part1 hide" value="changePhone" ms-visible="phoneIndex === 'changePhone'">
                    <label class="phone_number">
                        <span ms-html="'手机号'"></span>
                        <span ms-html="'{{$data->phone}}' || ''" title="{{$data->phone}}"></span>
                    </label>
                    @if($data->phone)
                        <div class="phone_button" ms-click="changePhone('getCode')">修改</div>
                    @else
                        <div class="phone_button" ms-click="changePhone('getCode1')">修改</div>
                    @endif
                    <div class="phone_message">提示 : 绑定手机号后,您将可及时获取课程审核的短信通知。</div>
                </div>

                <div class="change_phone_part2 hide" ms-visible="phoneIndex === 'getCode'">
                    <div class="change_phone_part2_switch">
                        <div class="phone_part2_switch_top">
                            <div class="part2_switch_top_item blue_common">
                                <span>1</span>
                                <span>验证身份</span>
                            </div>

                            <div class="part2_switch_top_item ">
                                <span>2</span>
                                <span>绑定手机号</span>
                            </div>

                            <div class="part2_switch_top_item ">
                                <span>3</span>
                                <span>绑定成功</span>
                            </div>
                        </div>
                    </div>
                    <!-- part2 切换1-->
                    <div class="phone_part2_content hide" ms-visible="next === 'getCode'">
                        <div class="phone_part2_content_top">
                            <input type="text" placeholder="请输入原绑定的手机号" ms-duplex="phone">
                        </div>
                        <div class="phone_part2_content_message msgAa"></div>
                        <div class="phone_part2_content_middle get_old_code">
                            <label>
                                <input type="text" placeholder="请输入验证码" ms-duplex="code">
                                <span ms-click="getCode('code')">获取验证码</span>
                            </label>
                        </div>
                        <div class="phone_part2_content_message msgBb"></div>
                        <div class="phone_next" ms-click="changeNext('getCode1')">下一步</div>
                    </div>
                    <!-- part2 切换2-->
                    <div class="phone_part2_content hide" ms-visible="next === 'getCode1'">
                        <div class="phone_part2_content_top">
                            <input type="text" placeholder="请输入绑定的手机号" ms-duplex="newPhone" >
                        </div>
                        <div class="phone_part2_content_message msgCc"></div>
                        <div class="phone_part2_content_middle get_new_code">
                            <label>
                                <input type="text" placeholder="请输入验证码" ms-duplex="newCode">
                                <span ms-click="getCode('newCode')">获取验证码</span>
                            </label>
                        </div>
                        <div class="phone_part2_content_message msgDd"></div>
                        <div class="phone_next" ms-click="changeNext('getCode2')">下一步</div>
                    </div>
                    <!-- part2 切换3-->
                    <div class="phone_part2_content hide" ms-visible="next === 'getCode2'">
                        <div class="phone_part2_bind_success">恭喜你,手机号修改成功!</div>
                    </div>

                </div>
                <div class="change_phone_part2 hide" ms-visible="phoneIndex === 'getCode1'">
                    <div class="change_phone_part2_switch">
                        <div class="phone_part2_switch_top">

                            <div class="part2_switch_top_item blue_common" style="margin-left:128px;">
                                <span>1</span>
                                <span>绑定手机号</span>
                            </div>

                            <div class="part2_switch_top_item ">
                                <span>2</span>
                                <span>绑定成功</span>
                            </div>
                        </div>
                    </div>
                    <!-- part2 切换2-->
                    <div class="phone_part2_content hide" ms-visible="next === 'getCode'">
                        <div class="phone_part2_content_top">
                            <input type="text" placeholder="请输入绑定的手机号" ms-duplex="newPhone" >
                        </div>
                        <div class="phone_part2_content_message msgCc"></div>
                        <div class="phone_part2_content_middle get_new_code">
                            <label>
                                <input type="text" placeholder="请输入验证码" ms-duplex="newCode">
                                <span ms-click="getCode('newCode')">获取验证码</span>
                            </label>
                        </div>
                        <div class="phone_part2_content_message msgDd"></div>
                        <div class="phone_next" ms-click="changeNext('getCode2')">下一步</div>
                    </div>
                    <!-- part2 切换3-->
                    <div class="phone_part2_content hide" ms-visible="next === 'getCode2'">
                        <div class="phone_part2_bind_success">恭喜你,手机号修改成功!</div>
                    </div>

                </div>


            </div>
        </div>
        <!--主体右边   绑定手机号结束-->
        {{--头像切换弹出层--}}
        <div class="headImg hide">
            <div class="headImg_tit">
                <div class="headImg_tit_l">更换头像</div>
                {{--<div class="headImg_tit_r"><img src="{{asset('home/image/personCenter/close.png')}}" alt=""></div>--}}
                <div class="headImg_tit_r">×</div>
            </div>
            <div class="headImg_con">
                <div class="headImg_con_l">
                    <div style="height:20px;"></div>
                    <div id="imgs">
                        <div style="height:50px;"></div>
                        <img id="imghead" style="" src="{{asset('home/image/personCenter/default.png')}}">
                    </div>
                </div>
                <div class="headImg_con_r">
                    <div style="height:20px;"></div>
                    <div class="headImg_con_r_yl">预览：</div>
                    <div style="height:30px;"></div>
                    <div class="headImg_con_r_preview_s">
                        <div id="imgsb" style="width:60px;height:60px;overflow: hidden;">
                            {{--<img style="width: 100%;height:100%" src="{{asset(\Auth::user()->pic)}}" alt="">--}}
                            <img style="width: 100%;height:100%" src="{{asset('home/image/personCenter/default.png')}}"
                                 alt="">
                        </div>
                    </div>
                    <div class="headImg_con_r_preview_s_info">60*60</div>
                    <div style="height:20px;"></div>
                    <div class="headImg_con_r_preview_s2">
                        <div id="imgsc" style="width:100px;height:100px;overflow: hidden;">
                            {{--<img style="width: 100%;height:100%" src="{{asset(\Auth::user()->pic)}}" alt="">--}}
                            <img style="width: 100%;height:100%" src="{{asset('home/image/personCenter/default.png')}}"
                                 alt="">
                        </div>
                    </div>
                    <div class="headImg_con_r_preview_s_info">100*100</div>
                </div>
            </div>
            <div class="headImg_foot">

                <div class="headImg_foot_selImg">
                    <div class="sel_btn">选择图片</div>
                    <input id="file_upload" name="file_upload" type="file" multiple="false" value=""/>
                </div>
                <div class="headImg_foot_cutImg">
                    <div class="sel_btn saveImg">保存头像</div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="h5"></div>
    <div class="h5"></div>
    <div class="h20"></div>

@endsection
@section('js')
    <script type="text/javascript">
        require(['/personCenter/accountManagerStudent.js'], function (accountManagerStudent) {
            accountManagerStudent.mineId = '{{$id}}' || null;
            if (window.location.hash) {
                accountManagerStudent.tabStatus = window.location.hash.split('#')[1];
            } else {
                accountManagerStudent.tabStatus = 'infoUphold';
            }
            avalon.scan();
        });
    </script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/Jcrop.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/jquery.uploadify.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/accountManagerStudent.js')}}"></script>
@endsection