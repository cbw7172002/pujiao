@extends('layouts.layoutHome')

@section('title', '账号管理')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/accountManagerTeacher.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/Jcrop.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/uploadify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/pagination.css')}}">
@endsection

@section('content')
    <div class="container" ms-controller="accountManagerTeacher">
        <div class="h40"></div>
        <div class="center_left">
            <div class="account_manager_top">
                <div class="account_manager_top_left">
                    <img ms-attr-src="'{{$data->pic}}'" alt="" width="65" height="65" onerror="javascript:this.src='/home/image/layout/default.png';"/>
                </div>

                <div class="account_manager_top_right">
                    <div class="account_manager_top_right_top" ms-html="'{{$data->realname}}'"></div>
                    <div class="account_manager_top_right_bottom" ms-html="'老师'"></div>
                </div>
            </div>

            <div class="account_manager" ms-html="'账号管理'"></div>
            <span class="span_hover"></span>
            <div class="account_common" ms-html="'绑定学科'" name="bindSubject" ms-click="changeTab('bindSubject')"></div>
            <span class="span_hover"></span>
            <div class="account_common" ms-html="'添加任课'" name="addCourse" ms-click="changeTab('addCourse')"></div>
            <span class="span_hover"></span>
            <div class="account_common" ms-html="'信息维护'" name="infoUphold" ms-click="changeTab('infoUphold')"></div>
            <span class="span_hover"></span>
            <div class="account_common" ms-html="'密码修改'" name="changePass" ms-click="changeTab('changePass')"></div>
            <span class="span_hover"></span>
            <div class="account_common phone_width_diff" ms-html="'绑定手机号'" name="bindPhone" ms-click="changeTab('bindPhone')"></div>

            <div class="h20"></div>
        </div>


        <!--主体右边   绑定学科开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'bindSubject'" ms-controller="bindSubject">
            <div class="right_subject_top">
                <div class="right_top_bind_subject">绑定学科</div>
                <div class="right_top_bind_button" ms-click="popUpSwitch('bindSubject')">新增绑定</div>
            </div>

            <div class="bind_subject">
                <div class="bind_subject_content" ms-if="bindSubjectInfo.size() > 0 && !loading">
                    <div class="bind_subject_content_top">
                        <ul>
                            <li>编号</li>
                            <li class="bind_subject_top_second">年级</li>
                            <li>学科</li>
                            <li>册别</li>
                            <li>版本</li>
                            <li>操作</li>
                        </ul>
                    </div>
                    <!-- 循环开始 -->
                    <div class="bind_subject_content_repeat" ms-repeat="bindSubjectInfo">
                        <ul>
                            <li class="bind_id" ms-attr-title="el.id" ms-html="$index+1"></li>
                            <li class="bind_subject_repeat_second" ms-html="el.gradeName"></li>
                            <li class="bind_subject_repeat_third" ms-html="el.subjectName"></li>
                            <li class="bind_subject_repeat_forth" ms-html="el.bookName"></li>
                            <li class="bind_subject_repeat_fifth" ms-html="el.editionName"></li>
                            <li class="bind_subject_repeat_sixth">
                                <span ms-click="popUpSwitch('changeSubject', el.id)" onclick="getId(this)">修改</span>
                                {{--<span ms-click="popUpSwitch('changeSubject', el.id)" onclick="getId(this)">修改</span>--}}
                                <span> / </span>
                                <span ms-click="popUpSwitch('deleteSubject', el.id)">删除</span>
                            </li>
                        </ul>
                    </div>
                    <!-- 循环结束 -->

                </div>
                <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="warningMsg" ms-if="bindSubjectMsg && !loading">暂无绑定学科...</div>
            </div>

            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_bind_subject"></div>
                </div>
            </div>
        </div>
        <!--主体右边   绑定学科结束-->

        <!--主体右边   添加任课开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'addCourse'" ms-controller="addCourse">
            <div class="right_subject_top">
                <div class="right_top_bind_subject">添加任课</div>
                <div class="right_top_bind_button" ms-if="!bindSubjects" ms-click="popUpSwitch('resourceMessage')">新增任课</div>
                <div class="right_top_bind_button" ms-if="bindSubjects" ms-click="popUpSwitch('addCourse')">新增任课</div>
            </div>
            <div class="bind_subject">
                <div class="bind_subject_content" ms-if="addCourseInfo.size() && !loading">
                    <div class="add_course_content_top">
                        <ul>
                            <li>编号</li>
                            <li>年级</li>
                            <li>班</li>
                            <li>学科</li>
                            <li>操作</li>
                        </ul>
                    </div>
                    <!-- 循环开始 -->
                    <div class="add_course_content_repeat" ms-repeat="addCourseInfo">
                        <ul>
                            <li ms-html="$index+1" ms-attr-title="el.id" class="addCourseId"></li>
                            <li class="bind_subject_repeat_forth" ms-html="el.gradeName"></li>
                            <li class="bind_subject_repeat_fifth" ms-html="el.className"></li>
                            <li class="bind_subject_repeat_third" ms-html="el.subjectName"></li>
                            <li class="bind_subject_repeat_sixth">
                                <span ms-click="popUpSwitch('changeCourse', el.id)" onclick="getCourse(this)">修改</span>
                                <span> / </span>
                                <span ms-click="popUpSwitch('deleteCourse', el.id)">删除</span>
                            </li>
                        </ul>
                    </div>
                    <!-- 循环结束 -->
                </div>
                <div class="spinner" style="margin: 200px auto;" ms-if="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
                <div class="warningMsg" ms-if="addCourseMsg && !loading">暂无添加任课...</div>
            </div>
            <div ms-if="display" class="pagecon_parent">
                <div class="pagecon">
                    <div id="page_add_course"></div>
                </div>
            </div>
        </div>
        <!--主体右边   添加任课结束-->

        <!--主体右边  信息维护开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'infoUphold'">
            <div class="right_password_top" ms-html="'信息维护'"></div>
            <div class="info_uphold">
                <div class="infoMsg infoMsg_success" ms-visible="infoMsg" ms-html="infoMsg_success"></div>
                <div class="infoMsg infoMsg_error" ms-visible="infoMsg !== null && !infoMsg" ms-html="infoMsg_error"></div>
                <div class="info_uphold_img">
                    <div class="info_uphold_img_left" ms-html="'头像'"></div>
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
                        <input type="text" disabled class="form-control" id="inputUsername" value="{{$data -> username}}">
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label class="form_group_label">学&nbsp; &nbsp; &nbsp; &nbsp;历</label>
                    <div class="form_group_select">
                        <label for="inputEducation1">
                            <select class="js-example-basic-single js-states form-control" id="inputEducation1"
                                    style="width: 78%;">
                                @if(!$data->education)
                                    <option value="">-请选择-</option>
                                @endif
                                <option value="中专" {{$data->education == '中专' ? 'selected' : ''}}>中专</option>
                                <option value="大专" {{$data->education == '大专' ? 'selected' : ''}}>大专</option>
                                <option value="本科" {{$data->education == '本科' ? 'selected' : ''}}>本科</option>
                                <option value="硕士" {{$data->education == '硕士' ? 'selected' : ''}}>硕士</option>
                                <option value="博士" {{$data->education == '博士' ? 'selected' : ''}}>博士</option>
                                <option value="博士后" {{$data->education == '博士后' ? 'selected' : ''}}>博士后</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label for="inputUsername" class="form_group_label">性&nbsp; &nbsp; &nbsp; &nbsp;别</label>
                    <div class="form_radio_input">
                        <label class="radio-inline " for="inlineRadio1">
                            <input type="radio" name="sex" id="inlineRadio1" value="1" {{$data->sex == 1 ? 'checked' : ''}}>男
                        </label>
                        <label class="radio-inline" for="inlineRadio2">
                            <input type="radio" name="sex" id="inlineRadio2" value="2" {{$data->sex == 2 ? 'checked' : ''}}>女
                        </label>
                    </div>
                </div>
                <div class="info_form_message"></div>


                <div class="info_form_group">
                    <label for="inputSchool" class="form_group_label">学&nbsp; &nbsp; &nbsp; &nbsp;校</label>
                    <div class="form_group_input">
                        <input type="text" class="form-control" id="inputSchool" value="{{$data -> school}}">
                    </div>
                </div>
                <div class="info_form_message"></div>

                <div class="info_form_group">
                    <label class="form_group_label">职&nbsp; &nbsp; &nbsp; &nbsp;称</label>
                    <div class="form_group_select">
                        <label for="inputTech">
                            <select class="js-example-basic-single js-states form-control" id="inputTech"
                                    style="width: 78%;">
                                @if(!$data->professional)
                                    <option value="">-请选择-</option>
                                @endif
                                <option value="正高级教师" {{$data->professional == '正高级教师' ? 'selected' : ''}}>正高级教师</option>
                                <option value="高级教师" {{$data->professional == '高级教师' ? 'selected' : ''}}>高级教师</option>
                                <option value="一级教师" {{$data->professional == '一级教师' ? 'selected' : ''}}>一级教师</option>
                                <option value="二级教师" {{$data->professional == '二级教师' ? 'selected' : ''}}>二级教师</option>
                                <option value="三级教师" {{$data->professional == '三级教师' ? 'selected' : ''}}>三级教师</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="info_form_message"></div>
                <div class="info_form_group">
                    <label for="" class="form_group_label">个人简介</label>
                    <div class="form_group_intro">
                        <div class="form_group_intro_textarea">
                            <textarea name="" id="" cols="30" rows="10" ms-duplex-string="intro">{{$data->intro}}</textarea>
                        </div>

                        <div class="form_group_intro_bottom">
                            <span>0</span>/200字&nbsp;
                        </div>

                    </div>
                </div>
                <div class="info_form_message" id="msg_intro"></div>
                <div class="info_form_button" ms-click="holdButton()">保存</div>
            </div>
        </div>
        <!--主体右边   信息维护结束-->

        <!--主体右边   密码修改开始-->
        <div class="center_right hide" ms-visible="tabStatus === 'changePass'">
            <div class="right_password_top">密码修改</div>
            <div class="infoMsg infoMsg_success" ms-visible="infoMsg" ms-html="infoMsg_success"></div>
            <div class="infoMsg infoMsg_error" ms-visible="infoMsg !== null && !infoMsg" ms-html="infoMsg_error"></div>
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
        <!-- 遮罩层 -->
        <div class="shadow hide" id="shadow" ms-popup="popUp" value="close"></div>
        <!--  绑定学科删除  -->
        <div class="delete_subject hide" ms-popup="popUp" value="deleteSubject">
            <div class="top">
                <span>确认删除该绑定？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch('sureDeleteSubject')">确定</span>
            </div>
        </div>
        <!-- 新增任课删除弹出层-->
        <div class="delete_subject hide" ms-popup="popUp" value="deleteCourse">
            <div class="top">
                <span>确认删除该任课？</span>
            </div>
            <div class="bot">
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
                <span class="sure" ms-click="popUpSwitch('sureDeleteCourse')">确定</span>
            </div>
        </div>
        <!---绑定学科弹出层 -->
        <div class="bind_subject_pop hide" ms-popup="popUp" value="bindSubject">
            <div class="top_close">
                <span ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="top">
                <label for="id_label_single" style="width: 100%;">
                    <span style="margin-right:10px;">年级</span>
                    <select class="js-example-basic-single js-states form-control" onChange="getSubject(this.value);" id="id_label_single"
                            style="width: 78%;">
                        <option value="">--年级--</option>
                    </select>
                </label>
                <div class="h10"></div>
                <label for="id_label_single1">
                    <span style="margin-right:10px;">学科</span>
                    <select class="js-example-basic-single js-states form-control"  onChange="getBook(this.value);" id="id_label_single1"
                            style="width: 78%;">
                        <option value="">--学科--</option>
                    </select>
                </label>
                <div class="h10"></div>
                <label for="id_label_single2">
                    <span style="margin-right:10px;">册别</span>
                    <select class="js-example-basic-single js-states form-control" onChange="getEdition(this.value);" id="id_label_single2"
                            style="width: 78%;">
                        <option value="">--册别--</option>
                    </select>
                </label>
                <div class="h10"></div>

                <label for="id_label_single3">
                    <span style="margin-right:10px;">版本</span>
                    <select class="js-example-basic-single js-states form-control" id="id_label_single3"
                            style="width: 78%;">
                        <option value="">--版本--</option>
                    </select>
                </label>
            </div>
            <div class="bot">
                <span class="sure" ms-click="popUpSwitch('sure')">确定</span>
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
            </div>
        </div>
        <!---修改绑定学科弹出层--->
        <div class="change_subject_pop hide" ms-popup="popUp" value="changeSubject">
        {{--<div class="change_subject_pop hide">--}}
            <div class="top_close">
                <span class="close" ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="top">
                <label for="" style="width: 100%;">
                    <span style="margin-right:10px;">年级</span>
                    <select class="js-example-basic-single grade" style="width: 78%;" onChange="getSubject1(this.value);"></select>
                </label>
                <div class="h10"></div>
                <label for="">
                    <span style="margin-right:10px;">学科</span>
                    <select class="js-example-basic-single subject" style="width: 78%;" onChange="getBook1(this.value);"></select>
                </label>
                <div class="h10"></div>
                <label for="">
                    <span style="margin-right:10px;">册别</span>
                    <select class="js-example-basic-single book" style="width: 78%;" onChange="getEdition1(this.value);"></select>
                </label>
                <div class="h10"></div>

                <label for="">
                    <span style="margin-right:10px;">版本</span>
                    <select class="js-example-basic-single edition" style="width: 78%;"></select>
                </label>
            </div>
            <div class="bot">
                <span class="sure" ms-click="popUpSwitch('changeSure')">确定</span>
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
            </div>
        </div>
        <!-- 添加任课弹出层 -->
        <div class="add_course_pop hide" ms-popup="popUp" value="addCourse">
            <div class="top_close">
                <span ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="top">
                <label for="id_label_single4" style="width: 100%;">
                    <span style="margin-right:10px;">年级</span>
                    <select class="js-example-basic-single" id="id_label_single4"
                            style="width: 78%;" onChange="getClass(this.value)">
                        <option value="">--年级--</option>
                    </select>
                </label>

                <div class="h10"></div>

                <label for="id_label_single5">
                    <span style="margin-right:25px;">班</span>
                    <select class="js-example-basic-single" id="id_label_single5" style="width: 78%;">
                        <option value="">--班--</option>
                    </select>
                </label>

                <div class="h10"></div>

                <label for="id_label_single6">
                    <span style="margin-right:10px;">学科</span>
                    <select class="js-example-basic-single" id="id_label_single6"
                            style="width: 78%;">
                        <option value="">--学科--</option>
                    </select>
                </label>
            </div>
            <div class="bot">
                <span class="sure" ms-click="popUpSwitch('sureCourse')">确定</span>
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
            </div>
        </div>
        <!-- 修改任课弹出层 -->
        <div class="change_course_pop hide" ms-popup="popUp" value="changeCourse">
            <div class="top_close">
                <span ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="top">
                <label for="id_label_single4" style="width: 100%;">
                    <span style="margin-right:10px;">年级</span>
                    <select class="js-example-basic-single id_label_single4" id="id_label_single4"
                            style="width: 78%;" onChange="getClass1(this.value)">
                    </select>
                </label>

                <div class="h10"></div>

                <label for="id_label_single5">
                    <span style="margin-right:25px;">班</span>
                    <select class="js-example-basic-single id_label_single5" id="id_label_single5" style="width: 78%;">
                    </select>
                </label>

                <div class="h10"></div>

                <label for="id_label_single6">
                    <span style="margin-right:10px;">学科</span>
                    <select class="js-example-basic-single id_label_single6" id="id_label_single6"
                            style="width: 78%;">
                    </select>
                </label>
            </div>
            <div class="bot">
                <span class="sure" ms-click="popUpSwitch('sureChangeCourse')">确定</span>
                <span class="quit" ms-click="popUpSwitch(false)">取消</span>
            </div>
        </div>
        <!-- 没有绑定学科前添加任课  给出提示-->
        <!--资源转码中提示弹窗-->
        <div class="warning_resource hide" ms-popup="popUp" value="resourceMessage">
            <div class="top_title">
                <div ms-html="'温馨提示'"></div>
                <span ms-click="popUpSwitch(false)"></span>
            </div>
            <div class="middle_content" ms-html="'教师在没有绑定学科前，不能添加任课，请先去绑定学科！'"></div>
            <div class="bot_button">
                <span class="sure" ms-html="'知道了'" ms-click="popUpSwitch(false)"></span>
            </div>
        </div>
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
@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js')}}"></script>
    <script type="text/javascript">
        //点击绑定学科的修改
        function getId (object) {
            var id = $(object).parent().siblings('.bind_id').attr('title');
//            console.log(id);
            $.ajax({
                type: "post",
                url: "/member/getBindSubjects",
                data: { table: 'teachersubject', action: 3, id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $('.grade').children().remove();
                        $('.grade').prepend('<option value="'+ response.data['gradeId'] +'" selected="selected"> ' + response.data['gradeName'] + ' </option>');
                        $('.subject').children().remove();
                        $('.subject').prepend('<option value="'+ response.data['subjectId'] +'" selected="selected"> ' + response.data['subjectName'] + ' </option>');
                        $('.book').children().remove();
                        $('.book').prepend('<option value="'+ response.data['bookId'] +'" selected="selected"> ' + response.data['bookName'] + ' </option>');
                        $('.edition').children().remove();
                        $('.edition').prepend('<option value="'+ response.data['editionId'] +'" selected="selected"> ' + response.data['editionName'] + ' </option>');
                    }

                    if($('.subject').val() && $(".book").val() && $(".edition").val()) {
                        $(".grade").select2({
                            minimumResultsForSearch: Infinity,
                            ajax: {
                                url: "/member/getBindSubjects",
                                type: 'post',
                                data: {table: 'schoolgrade', action: 1, column: 'grade'},
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data.data
                                    };
                                },
                            }
                        });
                        $(".subject").select2({
                            minimumResultsForSearch: Infinity
                        });
                        $(".book").select2({
                            minimumResultsForSearch: Infinity
                        });
                        $(".edition").select2({
                            minimumResultsForSearch: Infinity
                        });

                    }
                }

            });
        }
        //点击新增任课的修改
        function getCourse (object) {
            var id = $(object).parent().siblings('.addCourseId').attr('title');
//            console.log(id)
            $.ajax({
                type: "post",
                url: "/member/bindSubjectsInfo",
                data: { table: 'teacherteach', action: 3, id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $('.id_label_single4').children().remove();
                        $('.id_label_single4').prepend('<option value="'+ response.data['gradeId'] +'" selected="selected"> ' + response.data['gradeName'] + ' </option>');
                        $('.id_label_single5').children().remove();
                        $('.id_label_single5').prepend('<option value="'+ response.data['classId'] +'" selected="selected"> ' + response.data['className'] + ' </option>');
                        $('.id_label_single6').children().remove();
                        $('.id_label_single6').prepend('<option value="'+ response.data['subjectId'] +'" selected="selected"> ' + response.data['subjectName'] + ' </option>');
                    }

                    if($('.id_label_single4').val() && $(".id_label_single5").val() && $(".id_label_single6").val()) {
                        $(".id_label_single4").select2({
                            minimumResultsForSearch: Infinity,
                            ajax: {
                                url: "/member/bindSubjectsInfo",
                                type: 'post',
                                data: {table: 'teachersubject', action: 1, type: 1},
                                dataType: 'json',
                                processResults: function (data) {
                                    return {
                                        results: data.data
                                    };
                                }
                            }
                        });
                        $(".id_label_single5").select2({
                            minimumResultsForSearch: Infinity
                        });
                        $(".id_label_single6").select2({
                            minimumResultsForSearch: Infinity
                        });
                    }
                }

            });
        }
        $(".js-example-basic-single").select2({
            minimumResultsForSearch: Infinity
        });


    </script>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/games/pagination.js')}}"></script>
    <script type="text/javascript">
        require(['/personCenter/directive.js', '/personCenter/accountManagerTeacher.js'], function (directive, accountManagerTeacher) {
            accountManagerTeacher.mineId = '{{$id}}' || null;
            accountManagerTeacher.intro = '{{$data->intro}}' || null;

            if (window.location.hash) {
                accountManagerTeacher.tabStatus = window.location.hash.split('#')[1];
                accountManagerTeacher.changeTab(accountManagerTeacher.tabStatus, accountManagerTeacher.mineId );
            } else {
                accountManagerTeacher.tabStatus = 'infoUphold';
                accountManagerTeacher.changeTab(accountManagerTeacher.tabStatus, accountManagerTeacher.mineId );
            }
            accountManagerTeacher.getData('/member/getCount', 'bindSubjects', {table: 'teachersubject', action: 1, data: { tId: accountManagerTeacher.mineId }}, 'POST');
            //日期过滤器
            avalon.filters.sliceTime = function(str,type){
                return type == 'year' ? str.slice(0,10) : str.slice(11,19);
            };

            avalon.scan();
        });
    </script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/Jcrop.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/jquery.uploadify.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/accountManagerTeacher.js')}}"></script>
    <script>
    //个人信息字数统计
    function countfont(){
        var fontsum =  $('.form_group_intro_textarea textarea').val().length;
        $('.form_group_intro_bottom span').html(fontsum);
    }
    $('.form_group_intro_textarea textarea').keyup(function(){
         countfont();
    })
        countfont();

    </script>
@endsection