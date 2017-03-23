@extends('layouts.layoutAdmin')
@section('content')




    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>

                <li>
                    <a href="{{url('/admin/companyUser/companyUserList')}}">后台用户列表</a>
                </li>
                <li class="active">添加</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    后台用户列表
                    <small>
                        <i class="icon-double-angle-right"></i>
                        添加
                    </small>
                </h1>
            </div><!-- /.page-header -->

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <form action="{{url('admin/companyUser/addscompanyUser')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 用户名 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="username" id="username"  placeholder="用户名" class="col-xs-10 col-sm-5" value="{{old('username')}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                        <span class="checkusername"  style="position:relative;top:5px;font-size:10px;color:red" >4—16位字母(不区分大小写)汉字/数字/下划线</span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 真实姓名 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="realname" id="form-field-1" placeholder="真实姓名" class="col-xs-10 col-sm-5" value="{{old('realname')}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 密码 </label>

                            <div class="col-sm-9">
                                <input  type="password" name="password" id="form-field-1" placeholder="密码" class="col-xs-10 col-sm-5" value="" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                        <span style="position:relative;top:5px;font-size:10px;color:red"> *6—16位字母/数字</span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 重复添加密码 </label>

                            <div class="col-sm-9">
                                <input  type="password" name="upassword" id="form-field-1" placeholder="重复添加密码" class="col-xs-10 col-sm-5" value="" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 电话 </label>

                            <div class="col-sm-9">
                                <input  type="text" id="phone"  name="phone" id="form-field-1" placeholder="电话" class="col-xs-10 col-sm-5" value="{{old('phone')}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">

                                    <label class="middle">
                                        <span class="lbl"></span>
                                        <span class="phone_error" style="position:relative;top:5px;font-size:10px;color:red"></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 邮箱 </label>

                            <div class="col-sm-9">
                                <input  type="text"  id="email" name="email" id="form-field-1" placeholder="邮箱" class="col-xs-10 col-sm-5" value="{{old('email')}}" />
                                    {{--<span class="help-inline col-xs-12 col-sm-7">" />--}}
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                        <span class="email_error" style="position:relative;top:5px;font-size:10px;color:red" ></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>





                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  对应部门 </label>
                            <div class="col-sm-9">
                                <select name="departId" id="depart" class="col-xs-10 col-sm-5" >
                                    <option value="" selected>--部门--</option>
                                    @foreach($depart as $depart)
                                        <option value="{{$depart->id}}">{{$depart->departName}}</option>
                                    @endforeach
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>

                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  对应岗位 </label>
                            <div class="col-sm-9">
                                <select name="postId" id="post" class="col-xs-10 col-sm-5" >
                                    <option value="" selected>--岗位--</option>

                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>

                            </div>
                        </div>

                        <div class="space-4"></div>






                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                    确定
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </form>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->




    </div><!-- /.main-content -->
@endsection

@section('js')
    <script language="javascript" type="text/javascript" src="{{asset('DatePicker/WdatePicker.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{asset('admin/js/searchtype.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/admin/js/addSubject.js') }}"></script>


    <script>
        //验证邮箱是否重复
        $('#email').blur(function(){
            var email = $("input[name='email']").val();
            var isEmail = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
            if(!isEmail.test(email)){
                $('#email').val();
                $('.email_error').html("请正确填写邮箱");
//                return false;
            }else{
                $.ajax({
                    type: "post",
                    url: "{{url('admin/users/unique/users/email')}}",
                    data: 'email=' + email,
                    dataType: 'json',
                    success: function (data) {
                        var str = '';
                        if (data.status) {
                            str += '该邮箱已存在!';
                        }else{
                            str += '可以使用!';
                        }
                        $('.email_error').html(str);
                    }
                })
            }
        })


        //验证用户名是否重复
        $('#username').blur(function(){
            var username = $("input[name='username']").val();
            var isUsername = /^[\u4E00-\u9FA5A-Za-z0-9_]{4,16}$/;
            if(!isUsername.test(username)){
                $('#username').val();
                $('.checkusername').html('4—16位字母(不区分大小写)汉字/数字/下划线');
//                return false;
            }else{
                $.ajax({
                    type: "post",
                    url: "{{url('admin/users/unique/users/username')}}",
                    data: 'username=' + username,
                    dataType: 'json',
                    success: function (data) {
                        var str = '';
                        if (data.status) {
                            str += '该用户名已存在!';
                        }else{
                            str += '可以使用!';
                        }
                        $('.checkusername').html(str);
                    }
                })
            }

        })


        //验证手机号码是否重复
        $('#phone').blur(function(){
            var phone = $("input[name='phone']").val();
//            var isphone=/^(?:13\d|15\d)\d{5}(\d{3}|\*{3})$/;
            var isphone1 = /^[1][358][0-9]{9}$/;
            var isphone2 = /^[1][7][07][0-9]{8}$/;
            if(!isphone1.test(phone) && !isphone2.test(phone) ){
                $('#phone').val('');
                $('.phone_error').html("请正确填写电话号码");
//                return false;
            }else{
                $.ajax({
                    type: "post",
                    url: "{{url('admin/users/unique/users/phone')}}",
                    data: 'phone=' + phone,
                    dataType: 'json',
                    success: function (data) {
                        var str = '';
                        if (data.status) {
                            str += '该手机号码已存在!';
                        }else{
                            str += '可以使用!';
                        }
                        $('.phone_error').html(str);
                    }
                })
            }
        })


    </script>


    <script>


        $(function(){
            $('#depart').change(function(){
                var departid = $('#depart').val();
//                alert(departid);
                $.ajax({
                    type:'get',
                    data:{'id':departid},
                    url:'{{url('admin/companyUser/departPost')}}',
                    success:function(data){
//                         alert(data);
                        var str = '<option value="">--岗位--</option>';
                        $.each(data,function(i,val){
                            str += '<option value="'+val.id+'">'+val.postName+'</option>';
                        })
                        $('#post').html(str);
                    },
                    dataType:'json',
                    'async':false
                });
            })
        })


    </script>



    {{--<script>--}}
    {{--$('#phone').blur(function(){--}}
    {{--var tel = $("input[name='phone']").val();--}}
    {{--var isMobile=/^(?:13\d|15\d)\d{5}(\d{3}|\*{3})$/;--}}
    {{--//            var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;--}}
    {{--if(!isMobile.test(tel)){--}}
    {{--$('#phone').val('');--}}
    {{--$('.phone_error').html("请正确填写电话号码");--}}
    {{--return false;--}}
    {{--}else{--}}
    {{--$('.phone_error').html("可以使用");--}}
    {{--}--}}
    {{--})--}}


    {{--$('#email').blur(function(){--}}
    {{--var ema = $("input[name='email']").val();--}}
    {{--var isEmail = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;--}}
    {{--if(!isEmail.test(ema)){--}}
    {{--$('#email').val();--}}
    {{--$('.email_error').html("请正确填写邮箱");--}}
    {{--return false;--}}
    {{--}else{--}}
    {{--$('.email_error').html("可以使用");--}}
    {{--}--}}

    {{--})--}}

    {{--</script>--}}




@endsection