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
                    <a href="{{url('/admin/notice/noticeTemList')}}">通知管理</a>
                </li>
                <li class="active">发送通知</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    发送通知
                    <small>
                        <i class="icon-double-angle-right"></i>
                        发送通知
                    </small>
                </h1>
            </div>
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
                    <form action="{{url('admin/notice/doAddNotice')}}" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="text-align: right"> 针对对象 </label>
                            <div class="col-sm-9">
                                <select id="form-field-1" class="col-xs-10 col-sm-5" name="pointAt">
                                    <option value="0" style="text-indent: 10px;">学生</option>
                                    <option value="1" style="text-indent: 10px;">专家</option>
                                    <option value="2" style="text-indent: 10px;">教师</option>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="text-align: right"> 接收用户 </label>
                            <div class="col-sm-9">

                                <div id="form-field-1" name="student" class="col-xs-10 col-sm-5">
                                    <div style="width: 100%;float: left;margin-top: 5px;">
                                        <input type="checkbox" name="selectAll"/><span style="margin-left: 8px;">全选</span>
                                    </div>
                                    @foreach($student as $value)
                                        <div style="width: 150px;float: left;margin-top: 5px;">
                                            <input type="checkbox" onclick="allCheck(this);" name="username[]" value="{{$value->username}}" /> <span style="margin-left: 8px;">{{$value->username}}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="form-field-1" name="teacher" class="col-xs-10 col-sm-5 hide">
                                    <div style="width: 100%;float: left;margin-top: 5px;">
                                        <input type="checkbox" name="selectAll"/><span style="margin-left: 8px;">全选</span>
                                    </div>
                                    @foreach($teacher as $value)
                                        <div style="width: 150px;float: left;margin-top: 5px;">
                                            <input type="checkbox" onclick="allCheck(this);" name="username[]" value="{{$value->username}}" /> <span style="margin-left: 8px;">{{$value->username}}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="form-field-1" name="famous" class="col-xs-10 col-sm-5 hide">
                                    <div style="width: 100%;float: left;margin-top: 5px;">
                                        <input type="checkbox" name="selectAll"/><span style="margin-left: 8px;">全选</span>
                                    </div>
                                    @foreach($famous as $value)
                                        <div style="width: 150px;float: left;margin-top: 5px;">
                                            <input type="checkbox" onclick="allCheck(this);" name="username[]" value="{{$value->username}}" /> <span style="margin-left: 8px;">{{$value->username}}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 选择模板 </label>
                            <div class="col-sm-9">
                                <select id="studentTem" class="col-xs-6 col-sm-5" name="stuTempId">
                                    @foreach($studentTem as $value)
                                        <option value="{{$value -> id}}" style="text-indent: 10px;">{{$value->tempName}}</option>
                                    @endforeach
                                </select>
                                <select id="famousTem" class="col-xs-6 col-sm-5 hide" name="famTempId">
                                    @foreach($famousTem as $value)
                                        <option value="{{$value -> id}}" style="text-indent: 10px;">{{$value->tempName}}</option>
                                    @endforeach
                                </select>
                                <select id="teacherTem" class="col-xs-6 col-sm-5 hide" name="teaTempId">
                                    @foreach($teacherTem as $value)
                                        <option value="{{$value -> id}}" style="text-indent: 10px;">{{$value->tempName}}</option>
                                    @endforeach
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 通知内容 </label>

                            <div class="col-sm-9">
                                <textarea name="content" placeholder="通知内容" id="form-field-1" class="col-xs-10 col-sm-5" cols="30" rows="10" style="resize: none"></textarea>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <label class="middle">
                                        <span class="lbl"></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="icon-ok bigger-110"></i>Submit</button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset"><i class="icon-undo bigger-110"></i>Reset</button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="userType" value="0"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function allCheck(obj) {
            var num = $(obj).parent().siblings().size();
            var num_checked = $(obj).parent('div').siblings('div').children('input:checked').size() + 1; //选中个数
            if (num == num_checked) {
                $("input[name='selectAll']").prop("checked","checked");
            } else {
                $("input[name='selectAll']").removeAttr("checked");
            }
        }
        $(function(){
            $("select[name='pointAt']").change(function(){
                var obj = $("select[name='pointAt']").val();
                $("input[name='selectAll']").removeAttr("checked").parent('div').siblings('div').children('input').removeAttr("checked");
                if(obj == '0'){
                    $("div[name='student']").removeClass('hide').siblings('div').addClass('hide');
                    $('#studentTem').removeClass('hide').siblings('select').addClass('hide');
                    $("input[name='userType']").val('0');
                }else if(obj == '1'){
                    $("div[name='famous']").removeClass('hide').siblings('div').addClass('hide');
                    $('#famousTem').removeClass('hide').siblings('select').addClass('hide');
                    $("input[name='userType']").val('1');
                }else{
                    $("div[name='teacher']").removeClass('hide').siblings('div').addClass('hide');
                    $('#teacherTem').removeClass('hide').siblings('select').addClass('hide');
                    $("input[name='userType']").val('2');
                }
            })
            $("input[name='selectAll']").click(function(){
                if(this.checked){
                    $(this).parent('div').siblings('div').children('input').prop("checked","checked");
                }else{
                    $(this).parent('div').siblings('div').children('input').removeAttr("checked");
                }
            })
        })

    </script>
@endsection