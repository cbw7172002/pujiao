@extends('layouts.layoutAdmin')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/Jcrop.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/personCenter/uploadify.css')}}">

    <style>
        /*-- --- 上传头像弹出层 --- --*/
        .headImg {
            width: 630px;
            height: 470px;
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 1001;
            margin-top: -325px;
            margin-left: -400px;
            background: #F2F8FC;

            /*border-radius: 5px;*/
            /*-moz-border-radius: 5px;*/
            /*-webkit-border-radius: 5px;*/
        }

        .headImg_tit {
            width: 100%;
            height: 30px;
            background: #B1584C;
        }

        .headImg_tit_l {
            width: 100px;
            height: 30px;
            float: left;
            line-height: 30px;
            text-indent: 7px;
            font-size: 13px;
            color: #ffffff;
        }

        .headImg_tit_r {
            width: 24px;
            height: 30px;
            float: right;
            cursor: pointer;
        }

        .headImg_tit_r img {
            margin-top: 4px;
        }

        .headImg_con {
            width: 100%;
            height: 400px;
        }

        .headImg_con_l {
            width: 490px;
            height: 400px;
            float: left;
            /*background:#dddddd;                       !*tmp*!*/
        }

        .headImg_con_r {
            width: 140px;
            height: 400px;
            /*background:#ffffff;*/
            float: right;
        }

        .headImg_con_r_yl {
            width: 100%;
            height: 20px;
            line-height: 20px;
            font-size: 13px;
            text-indent: 18px;
        }

        .headImg_con_r_preview_s {
            width: 140px;
            height: 60px;
            padding-left: 40px;
        }

        .headImg_con_r_preview_s2 {
            width: 140px;
            height: 100px;
            padding-left: 20px;
        }

        .headImg_con_r_preview_s_info {
            width: 100%;
            height: 40px;
            line-height: 40px;
            text-align: center;
            font-size: 12px;
        }

        #imgs {
            width: 480px;
            height: 330px;
            margin-left: 5px;
            overflow: hidden;
            text-align: center;
            /*background:#dddddd;                        !*tmp*!*/
            background: #ffffff;
        }

        .headImg_foot {
            width: 100%;
            height: 40px;
            background: #F5EBE6;
        }

        .headImg_foot_selImg {
            width: 330px;
            height: 40px;
            float: left;
            margin-left: 50px;
        }

        .headImg_foot_cutImg {
            width: 125px;
            height: 40px;
            float: left;
        }

        .sel_btn {
            width: 120px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            background: #F4F4F4;
            margin-top: 7px;
            cursor: pointer;
            border: 1px solid #A09A96;

            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
        }

        #file_upload {
            position: relative;
            top: -30px;
            opacity: 0;
            filter: alpha(opacity=0);
        }

        #SWFUpload_0_0 {
            /*margin-top: 10px;*/
            position: relative;
            top: -130px;
        }

        #SWFUpload_0_1 {
            /*margin-top: 10px;*/
            position: relative;
            top: -130px;
        }

        #SWFUpload_0_2 {
            /*margin-top: 10px;*/
            position: relative;
            top: -130px;
        }

        #SWFUpload_0_3 {
            /*margin-top: 10px;*/
            position: relative;
            top: -130px;
        }

        /*-- --- 上传头像弹出层 结束 --- --*/

        .right_head_img_upload {
            width: 100px;
            height: 30px;
            line-height: 30px;
            background: #209EEA;
            margin-top: 20px;
            text-align: center;
            color: #fff;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {
                }
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>

                <li>
                    <a href="{{url('/admin/users/userList')}}">用户管理</a>
                </li>
                <li class="active">用户管理列表</li>
            </ul><!-- .breadcrumb -->
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="page-content">
            <div class="page-header">
                <h1>
                    用户管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        添加用户
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" role="form" method="post" action="{{url('admin/users/insert')}}"
                          onsubmit=" return postcheck();">
                        {{csrf_field()}}
                        <input type="hidden" name="pic" id="pic"/>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">用户名</label>

                            <div class="col-sm-9">
                                <input type="text" id="form-field-1" name="username" placeholder="Username"
                                       class="col-xs-10 col-sm-4" value="{{old('username')}}" required/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                            </div>
                        </div>


                        {{--上传头像--}}
                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 用户头像 </label>
                            {{--<input type="hidden" value="" name="headImg"/>--}}
                            <div class="col-sm-9">
                                <img id="showImg" src="{{asset('home/image/layout/default.png')}}" width="100"
                                     height="100" alt="">
                                <div class="right_head_img_upload">上传头像</div>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">手机号</label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-4" type="text" name="phone" id="form-field-4"
                                       placeholder="Phone" value="{{old('phone')}}"/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">真实姓名</label>

                            <div class="col-sm-9">
                                <input type="text" id="form-field-6" name="realname" placeholder="Realname"
                                       class="col-xs-10 col-sm-4"
                                       value="{{old('realname')}}"/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">性别</label>

                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="sex" id="inlineRadio1" value="1">男
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sex" id="inlineRadio2" value="2">女
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">所在学校</label>

                            <div class="col-sm-9">
                                <input type="text" name="school" placeholder="school"
                                       class="col-xs-10 col-sm-4"
                                       value="{{old('school')}}" required/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="change_type_select">
                                用户身份 </label>

                            <div class="col-sm-9">

                                <select id="change_type_select" class="col-xs-10 col-sm-4" name="type" required>
                                    <option value="">-请选择-</option>
                                    <option value="1">学生</option>
                                    <option value="2">教师</option>
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:red;">*必选</span>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                            </div>
                        </div>


                        <div class="form-group hide hide_teacher">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">学历</label>

                            <div class="col-sm-9">
                                <select id="form-field-7" class="col-xs-10 col-sm-4" name="education">
                                    <option value="">-请选择-</option>
                                    <option value="中专">中专</option>
                                    <option value="大专">大专</option>
                                    <option value="本科">本科</option>
                                    <option value="硕士">硕士</option>
                                    <option value="博士">博士</option>
                                    <option value="博士后">博士后</option>
                                </select>
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <span class="middle"></span>
                                    </span>
                            </div>
                        </div>

                        <div class="form-group hide  hide_teacher">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">职称</label>

                            <div class="col-sm-9">
                                <select class="col-xs-10 col-sm-4" name="professional">
                                    <option value="">-请选择-</option>
                                    <option value="正高级教师">正高级教师</option>
                                    <option value="高级教师">高级教师</option>
                                    <option value="一级教师">一级教师</option>
                                    <option value="二级教师">二级教师</option>
                                    <option value="三级教师">三级教师</option>
                                </select>
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <span class="middle"></span>
                                    </span>
                            </div>
                        </div>



                        <div class="form-group hide  hide_teacher">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">个人简介</label>

                            <div class="col-sm-9" style="width:415px;border:1px solid silver;margin-left:12px;padding:0">
                                <div style="width:413px;height:170px;padding: 3px;">
                                    <textarea class="col-xs-10 col-sm-5" style="resize:none;border:none;padding:2px 4px;width:407px;-ms-word-break: break-all;word-break: break-all;: break-all" name="intro" id="textarea" cols="20"
                                              rows="10">{{old('intro')}}</textarea>
                                </div>
                                <div style="width:410px;height:25px;line-height:25px;text-align: right;font-size: 12px;">
                                    <span>0</span>/200字&nbsp;
                                </div>
                            </div>
                            <div style="display: block;height:30px;line-height: 30px;color:brown;"></div>
                        </div>

                        <div class="form-group hide hide_student">
                            <label class="col-sm-3 control-label no-padding-right" for="sno">学号</label>

                            <div class="col-sm-9">
                                <input type="text" id="sno" name="sno" placeholder="Sno(学号)"
                                       class="col-xs-10 col-sm-4"
                                       value="{{old('sno')}}"/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                            </div>
                        </div>
                        <div class="form-group hide hide_student">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">所属学级</label>

                            <div class="col-sm-9">
                                <select name="schoolYear" id="form-field-2" class="col-xs-10 col-sm-4">
                                    @foreach($schoolYear as $year)
                                        <option value="{{$year.'级'}}">{{$year.'级'}}</option>
                                    @endforeach
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>

                        <div class="form-group hide hide_student">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">所在年级</label>

                            <div class="col-sm-9">
                                <select name="gradeId" id="student_gradeId" class="col-xs-10 col-sm-4">
                                    <option value="">-所在年级-</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}">{{$grade->gradeName}}</option>
                                    @endforeach
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>

                        <div class="form-group hide hide_student">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">所在班级</label>

                            <div class="col-sm-9">
                                <select name="classId" id="student_classId" class="col-xs-10 col-sm-4">
                                    <option value="">-所在班级-</option>
                                </select>
                                <span style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                    提交
                                </button>


                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->

        {{--头像切换弹出层--}}
        <div class="headImg hide">
            <div class="headImg_tit">
                <div class="headImg_tit_l">更换头像</div>
                <div class="headImg_tit_r"><img src="{{asset('home/image/personCenter/close.png')}}" alt=""></div>
            </div>
            <div class="headImg_con">
                <div class="headImg_con_l">
                    <div style="height:20px;"></div>
                    <div id="imgs">
                        <div style="height:50px;"></div>
                        <img id="imghead" style="" src="{{asset('home/image/personCenter/unload.png')}}">
                    </div>
                </div>
                <div class="headImg_con_r">
                    <div style="height:20px;"></div>
                    <div class="headImg_con_r_yl">预览：</div>
                    <div style="height:30px;"></div>
                    <div class="headImg_con_r_preview_s">
                        <div id="imgsb" style="width:60px;height:60px;overflow: hidden;">
                            {{--<img style="width: 100%;height:100%" src="{{asset(\Auth::user()->pic)}}" alt="">--}}
                            <img style="width: 100%;height:100%" src="{{asset('home/image/personCenter/unload.png')}}"
                                 alt="">
                        </div>
                    </div>
                    <div class="headImg_con_r_preview_s_info">60*60</div>
                    <div style="height:20px;"></div>
                    <div class="headImg_con_r_preview_s2">
                        <div id="imgsc" style="width:100px;height:100px;overflow: hidden;">
                            {{--<img style="width: 100%;height:100%" src="{{asset(\Auth::user()->pic)}}" alt="">--}}
                            <img style="width: 100%;height:100%" src="{{asset('home/image/personCenter/unload.png')}}"
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

        <!-- 实例化编辑器 -->
        <script type="text/javascript">

            //初始化验证变量
            var checkUsername = false;
            var checkPhone = false;
            var checkRealname = false;
            if($("#change_type_select").val() == 2) {
                var checkTextarea = false;
            } else {
                var checkTextarea = true;
            }

            //onSubmit
            function postcheck() {
                review();
                if (checkPhone && checkUsername && checkRealname && checkTextarea) {
                    return true;
                } else {
                    return false;
                }
            }
            //再次检查添加信息
            function review() {
                $("input[name='username']").trigger('blur');
                $("input[name='phone']").trigger('blur');
                $("input[name='realname']").trigger('blur');
                if($("#change_type_select").val() == 2) $("#textarea").trigger('blur');
            }


            $(function () {
                //Ajax提交，发送_token
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                });

                $("#change_type_select").change(function () {
                    if ($(this).val() == '') {
                        $('.hide_student,.hide_teacher').addClass('hide');
                    } else if ($(this).val() == 1) {
                        $('.hide_student').removeClass('hide');
                        $('.hide_teacher').addClass('hide');
                    } else if ($(this).val() == 2) {
                        $('.hide_teacher').removeClass('hide');
                        $('.hide_student').addClass('hide');
                    }
                });


                //验证用户名唯一
                $("input[name='username']").blur(function () {
                    var obj = $(this);
                    var username = obj.val();

                    if (!username.match(/^[\u4E00-\u9FA5A-Za-z0-9_]{2,8}$/)) {
                        //格式错误
                        obj.next('span').html('* 2—8位字母(不区分大小写)汉字/数字/下划线');
                        checkUsername = false;
                        return false;
                    } else {//验证唯一性
                        $.ajax({
                            type: "post",
                            url: "{{url('admin/users/unique/users/username')}}",
                            data: 'username=' + username,
                            dataType: 'json',

                            success: function (data) {
                                var str = '';
                                if (data.status) {
                                    str += '* 该用户名已存在!';
                                    checkUsername = false;
//                                    return false;
                                } else {
                                    checkUsername = true;
                                }
                                obj.next('span').html(str);
                            }
                        })
                    }
                })

                //验证用户名唯一
                $("input[name='realname']").blur(function () {
                    var obj = $(this);
                    var realname = obj.val();

                    if (!realname.match(/^[\u4E00-\u9FA5A-Za-z0-9_]{2,8}$/)) {
                        //格式错误
                        obj.next('span').html('* 2—8位字母(不区分大小写)汉字/数字/下划线');
                        checkRealname = false;
//                        return false;
                    } else {//
                        obj.next('span').html(' ');
                        checkRealname = true;
                    }

                })

                //验证手机号唯一
                $("input[name='phone']").blur(function () {
                    var obj = $(this);
                    var phone = obj.val();

                    if(!phone) {  //可为空
                        obj.next('span').html(' ');
                        checkPhone = true;
                        return true;
                    } else {
                        if (!phone.match(/^[1][358][0-9]{9}$/) && !phone.match(/^[1][7][07][0-9]{8}$/)) {//格式错误
                            obj.next('span').html('* 手机号格式错误');
                            checkPhone = false;
                            return false;
                        } else {//验证唯一性
                            $.ajax({
                                type: "post",
                                url: "{{url('admin/users/unique/users/phone')}}",
                                data: 'phone=' + phone,
                                dataType: 'json',

                                success: function (data) {
                                    var str = '';
                                    if (data.status) {
                                        str += '* 该手机号码已存在!';
                                        checkPhone = false;
//                                    return false;
                                    } else {
                                        checkPhone = true;
                                    }
                                    obj.next('span').html(str);
                                }
                            })
                        }
                    }


                });
                //验证文本域字数
                $("#textarea").blur(function () {
                    var textLength = $(this).val().length;
                    if (textLength > 200) {
                        //格式错误
                        $(this).parent().parent().next('div').html('* 您输入的字数已超出限制！');
                        checkTextarea = false;
//                        return false;
                    } else {//
                        $(this).parent().parent().next('div').html(' ');
                        checkTextarea = true;
                    }

                })


                //头像编辑框 显示
                $('.right_head_img_upload').click(function () {
                    $('.headImg').removeClass('hide');
                });

                $('.headImg_tit_r').click(function () {
                    $('.headImg').addClass('hide');
                })
                //头像上传
                var uploadify_onSelectError = function (file, errorCode, errorMsg) {
                    var msgText = "上传失败\n";
                    switch (errorCode) {
                        case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                            //this.queueData.errorMsg = "每次最多上传 " + this.settings.queueSizeLimit + "个文件";
                            msgText += "每次最多上传 " + this.settings.queueSizeLimit + "个文件";
                            break;
                        case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                            msgText += "文件大小超过限制( " + this.settings.file_size_limit + " )";
                            break;
                        case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                            msgText += "文件大小为0";
                            break;
                        case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                            msgText += "文件格式不正确，仅限 " + this.settings.file_types;
                            break;
                        default:
                            msgText += "错误代码：" + errorCode + "\n" + errorMsg;
                    }
                    alert(msgText);
                };


                //上传头像
                var img = '';
                var imgb = '';
                var imgc = '';

                var x = 0;
                var y = 0;
                var w = 0;
                var h = 0;

                var imgsrc = '';

                $('#file_upload').uploadify({
                    'swf': '/home/css/personCenter/uploadify.swf',
                    'uploader': '/member/addImg',
                    'buttonText': '选择图片',
                    'post_params': {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    'file_size_limit': '5MB',
                    'file_types': " *.jpg;*.png;*.jpeg;*.bmp;*.pdf;*.ico;*.gif ",
                    'overrideEvents': ['onSelectError'],
                    'onSelectError': uploadify_onSelectError,
                    'onUploadSuccess': function (file, data, response) {
                        var data = eval("(" + data + ")");
                        imgsrc = data.src;
                        img = "<img id=\"imghead\" src='" + data.src + "'>";
                        imgb = "<img id=\"preview\" src='" + data.src + "'>";
                        imgc = "<img id=\"preview2\" src='" + data.src + "'>";
                        $('#imgs').html(img);
                        $('#imgsb').html(imgb);
                        $('#imgsc').html(imgc);

                        w = 0;
                        cutImg(data.width, data.height);
                    }
                });

                function cutImg(boundx, boundy) {

                    jQuery('#imghead').Jcrop({
                        aspectRatio: 1,
                        onSelect: showPreview, //选中区域时执行对应的回调函数
                        onChange: showPreview, //选择区域变化时执行对应的回调函数
                    });

                    function showPreview(coords) {
                        var rx = 60 / coords.w;
                        var ry = 60 / coords.h;
                        var rx2 = 100 / coords.w;
                        var ry2 = 100 / coords.h;

                        $('#preview').css({
                            width: Math.round(rx * boundx) + 'px',
                            height: Math.round(ry * boundy) + 'px',
                            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                            marginTop: '-' + Math.round(ry * coords.y) + 'px'
                        });

                        $('#preview2').css({
                            width: Math.round(rx2 * boundx) + 'px',
                            height: Math.round(ry2 * boundy) + 'px',
                            marginLeft: '-' + Math.round(rx2 * coords.x) + 'px',
                            marginTop: '-' + Math.round(ry2 * coords.y) + 'px'
                        });

                        //jQuery('#x').val(coords.x); //选中区域左上角横
                        //jQuery('#y').val(coords.y); //选中区域左上角纵坐标
                        //jQuery("#x2").val(coords.x2); //选中区域右下角横坐标
                        //jQuery("#y2").val(coords.y2); //选中区域右下角纵坐标
                        //jQuery('#w').val(coords.w); //选中区域的宽度
                        //jQuery('#h').val(coords.h); //选中区域的高度

                        x = coords.x;
                        y = coords.y;
                        w = coords.w;
                        h = coords.h;

                    }


                }

                function checkCoords() {
                    if (w > 0) return true;
                    alert('请选择需要裁切的图片区域.');
                    return false;
                }

                $('.saveImg').click(function () {
                    if (checkCoords()) {
                        $.ajax({
                            type: "post",
                            url: "/member/trimImg",
                            data: {imgsrc: imgsrc, x: x, y: y, w: w, h: h},
                            async: false,
                            success: function (data) {
                                $("#pic").val(data);
                                $("#showImg").attr('src', data);
                                $('#imgs').html("<div style=\"height:50px;\"></div><img id=\"imghead\" style=\"\" src=\"{{asset('home/image/personCenter/unload.png')}}\">");
                                $('.headImg').addClass('hide');
                            }
                        });
                    }
                });

                //获取所属班级
                $("#student_gradeId").change(function () {
                    $('#student_classId').html('<option value="">-班级-</option>');
                    var gradeId = $("#student_gradeId").val();
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/users/getClass')}}",
                        data: {parentId: gradeId},
                        dataType: 'json',

                        success: function (result) {
                            var str = '';
                            //学校
                            if (result.status == true) {
                                $.each(result.data, function (i, val) {
                                    str += '<option value="' + val.id + '">' + val.className + '</option>';
                                })
                                $('#student_classId').html(str);
                            }
                        }
                    });
                })
            });
        </script>
        <script>
            //个人信息字数统计
            function countfont(){
                var fontsum =  $('#textarea').val().length;
                $('#textarea').parent().siblings('div').children('span').html(fontsum);
            }
            $('#textarea').keyup(function(){
                countfont();
            })
            countfont();
        </script>
    </div><!-- /.main-content -->
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('home/js/personCenter/Jcrop.js')}}"></script>
    <script type="text/javascript" src="{{asset('home/js/personCenter/jquery.uploadify.js')}}"></script>
@endsection