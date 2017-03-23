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
                    <a href="{{url('/admin/companyUser/companyUserList')}}">后台用户管理</a>
                </li>
                <li class="active">编辑</li>
            </ul><!-- .breadcrumb -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    后台用户管理
                    <small>
                        <i class="icon-double-angle-right"></i>
                        编辑
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

                    <form action="{{url('admin/companyUser/editscompanyUser')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="space-4"></div>

                        <input type="hidden" name="id"  value="{{$data->id}}"  >


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 用户名 </label>

                            <div class="col-sm-9">
                                <input  disabled="true"  type="text" name="username" id="form-field-1" placeholder="用户名" class="col-xs-10 col-sm-5" value="{{$data->username}}" />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                        {{--<span class="checkusername"  style="position:relative;top:5px;font-size:10px;color:red" >4—16位字母(不区分大小写)汉字/数字/下划线</span>--}}
                                    </label>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 真实姓名 </label>

                            <div class="col-sm-9">
                                <input  type="text" name="realname" id="form-field-1" placeholder="真实姓名" class="col-xs-10 col-sm-5" value="{{$data->realname}}" />
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
                                <input class="col-xs-10 col-sm-4" disabled type="hidden" name="oldPhone" placeholder="Phone" value="{{$data->phone}}"/>
                                <input  type="text" id="phone"  name="phone" id="form-field-1" placeholder="电话" class="col-xs-10 col-sm-5" value="{{$data->phone}}" />
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
                                <input class="col-xs-10 col-sm-4" disabled type="hidden" name="oldEmail" placeholder="Email" value="{{$data->email}}"/>
                                <input  type="text"  id="email" name="email" id="form-field-1" placeholder="邮箱" class="col-xs-10 col-sm-5" value="{{$data->email}}" />
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
                                        {{--<option value="{{$depart->id}}">{{$depart->departName}}</option>--}}
                                        <option value="{{$depart->id}}" @if($data->departId == $depart->id) selected @endif >{{$depart->departName}}</option>
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
                                    @foreach($post as $post)
                                        {{--<option value="{{$depart->id}}">{{$depart->departName}}</option>--}}
                                        <option value="{{$post->id}}" @if($data->postId == $post->id) selected @endif >{{$post->postName}}</option>
                                    @endforeach
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
            var oldEmail = $("input[name='oldEmail']").val();//原邮箱
            if(oldEmail != email){
                if(!isEmail.test(email)){
                    $('#email').val();
                    $('.email_error').html("请正确填写邮箱");
                    return false;
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
            }else{
                $('.email_error').html("可以使用");
            }

        })




        //验证手机号码是否重复
        $('#phone').blur(function(){
            var phone = $("input[name='phone']").val();
            var isphone=/^(?:13\d|15\d)\d{5}(\d{3}|\*{3})$/;
            var isphone1 = /^[1][358][0-9]{9}$/;
            var isphone2 = /^[1][7][07][0-9]{8}$/;
            var oldPhone = $("input[name='oldPhone']").val();//原手机号
            if(oldPhone != phone ){
                if(!isphone1.test(phone) && !isphone2.test(phone) ){
                    $('#phone').val('');
                    $('.phone_error').html("请正确填写电话号码");
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
            }else{
                $('.phone_error').html("可以使用");
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
    {{--$('.email_error').html("请正确填写邮箱格式");--}}
    {{--return false;--}}
    {{--}else{--}}
    {{--$('.email_error').html("可以使用");--}}
    {{--}--}}

    {{--})--}}



    {{--</script>--}}

@endsection