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
                        管理员信息
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" role="form" method="post" action="{{url('admin/users/updatePersonDetail/'.$data->id)}}"
                          onsubmit=" return postcheck();">
                        {{csrf_field()}}

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">管理员ID</label>

                            <div class="col-sm-9">
                                <input type="text" class="col-xs-10 col-sm-4" value="{{ $data -> id }}" disabled />
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">用户名</label>

                            <div class="col-sm-9">
                                <input type="text" class="col-xs-10 col-sm-4" value="{{ $data -> username }}" disabled/>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">姓名</label>

                            <div class="col-sm-9">
                                <input type="text" id="form-field-6" class="col-xs-10 col-sm-4" value="{{ $data -> realname }}" disabled/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">部门</label>

                            <div class="col-sm-9">
                                <input type="text" id="form-field-6" class="col-xs-10 col-sm-4" value="{{ $data -> departName ? $data -> departName : '无' }}" disabled/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-6">岗位</label>

                            <div class="col-sm-9">
                                <input type="text" id="form-field-6" class="col-xs-10 col-sm-4" value="{{ $data -> postName ? $data -> postName : '无'}}" disabled/>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">电话</label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-4" disabled type="hidden" name="oldPhone" value="{{$data->phone}}"/>
                                <input class="col-xs-10 col-sm-4" type="text" name="phone" id="form-field-4"
                                       placeholder="Phone" value="{{ $data -> phone }}"/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">邮箱</label>

                            <div class="col-sm-9">
                                <input class="col-xs-10 col-sm-4" disabled type="hidden" name="oldEmail" value="{{$data->email}}"/>
                                <input class="col-xs-10 col-sm-4" type="text" name="email"
                                       placeholder="email" value="{{ $data -> email }}"/><span
                                        style="display: block;height:30px;line-height: 30px;color:brown;"></span>
                                <div class="space-2"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-4">账户状态</label>

                            <div class="col-sm-9">
                                <input type="text" class="col-xs-10 col-sm-4" value=" {{ $data -> checks ? '锁定' : '激活'}}" disabled/>
                            </div>
                        </div>





                        <div class="space-4"></div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                    保存
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


        <script type="text/javascript">

            //初始化验证变量
            var checkEmail = false;
            var checkPhone = false;

            //onSubmit
            function postcheck() {
                review();
                if (checkPhone && checkEmail) {
                    return true;
                } else {
                    return false;
                }
            }
            //再次检查添加信息
            function review() {
                $("input[name='email']").trigger('blur');
                $("input[name='phone']").trigger('blur');
            }


            $(function () {
                //Ajax提交，发送_token
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                });

                //验证用户名唯一
                $("input[name='email']").blur(function () {
                    var obj = $(this);
                    var username = obj.val();
                    var oldEmail = $("input[name='oldEmail']").val();//获取原来的手机号
                    if(oldEmail != username){
                        if (!username.match(/^([a-zA-Z0-9_\.\-])+@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                            //格式错误
                            obj.next('span').html('* 请输入正确的邮箱地址 ');
                            checkEmail = false;
                            return false;
                        } else {//验证唯一性
                            $.ajax({
                                type: "post",
                                url: "{{url('admin/users/unique/users/email')}}",
                                data: 'email=' + username,
                                dataType: 'json',

                                success: function (data) {
                                    var str = '';
                                    if (data.status) {
                                        str += '* 该邮箱地址已存在!';
                                        checkEmail = false;
//                                    return false;
                                    } else {
                                        checkEmail = true;
                                    }
                                    obj.next('span').html(str);
                                }
                            })
                        }
                    }else{
                        obj.next('span').html('');
                        checkEmail = true;
                    }
                })

                //验证手机号唯一
                $("input[name='phone']").blur(function () {
                    var obj = $(this);
                    var phone = obj.val();

                    var original = $("input[name='oldPhone']").val();//获取原来的手机号
                    if(original != phone){
                        if(!phone.match(/^[1][358][0-9]{9}$/) && !phone.match(/^[1][7][07][0-9]{8}$/)){//格式错误
                            obj.next('span').html('* 手机号格式错误');
                            checkPhone = false;
                        }else{//验证唯一性
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
                                    }else{
                                        checkPhone = true;
                                    }
                                    obj.next('span').html(str);
                                }
                            })
                        }
                    }else{
                        obj.next('span').html('');
                        checkPhone = true;
                    }

                })
            });
        </script>
    </div><!-- /.main-content -->
@endsection