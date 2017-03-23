@extends('layouts.layoutHome')

@section('title', '发布资源')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/users/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/resource/uploadRes.css') }}">
@endsection

@section('content')
    <div class="contain_resource" ms-controller="upRescontroller">
        <div style="height: 40px;"></div>
        {{--资源类型选择--}}
        <div class="contain_resource_top">
            <div class="contain_resource_top_t">发布资源</div>
            <div class="contain_resource_top_con">
                <div class="contain_resource_top_con_li">
                    <div class="contain_resource_top_con_li_l">拓展资源</div>
                    <div class="contain_resource_top_con_li_r">
                        <input type="radio" name="isexpand" value="1" checked ms-click="selType(1)">&nbsp;&nbsp;  NO  &nbsp;&nbsp;<input type="radio" name="isexpand" value="2" ms-click="selType(2)">&nbsp;&nbsp;  YES
                    </div>
                </div>
                <div class="contain_resource_top_con_li">
                    <div class="contain_resource_top_con_li_l">资源类型</div>
                    <div class="contain_resource_top_con_li_r">
                        <select name=""  class="js-example-basic-single restype" style="width: 120px;">
                            <option selected="selected" value="">请选择类型</option>
                        </select>
                    </div>
                </div>
                <div class="contain_resource_top_con_li">
                    <div class="contain_resource_top_con_li_l">资源标签</div>
                    <div class="contain_resource_top_con_li_r">
                        <select name=""  class="js-example-basic-single resgrade" style="width: 120px;">
                            <option selected="selected" value="">请选择年级</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div style="display: inline-block" ms-if="isexpand == 1">
                        <select name=""  class="js-example-basic-single ressubject" style="width: 120px;">
                            <option selected="selected" value="">请选择学科</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <select name=""  class="js-example-basic-single resedition" style="width: 120px;">
                            <option selected="selected" value="">请选择版本</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <select name=""  class="js-example-basic-single resbook" style="width: 120px;">
                            <option selected="selected" value="">请选择册别</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <div class="contain_resource_top_con_li" ms-if="isexpand == 1">
                    <div class="contain_resource_top_con_li_l">章&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;节</div>
                    <div class="contain_resource_top_con_li_r">
                        <select name=""  class="js-example-basic-single reschapter" style="width: 120px;">
                            <option selected="selected" value="">请选择章节</option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
        {{--提示 及 按钮--}}
        <div class="contain_resource_mid">
            <div class="contain_resource_mid_l">提示</div>
            <div class="contain_resource_mid_m">
                <div class="contain_resource_mid_m_li" style="line-height:65px;">从我的电脑选择要上传的文档：按住CTRL可以上传多个资源，最多上传10个</div>
                <div class="contain_resource_mid_m_li">支持word、ppt、excel、pdf、png、jpg、mp4、flv、avi、rmvb、wmv、mkv格式资源</div>
            </div>
            <div class="contain_resource_mid_btn xzzy" ms-slectziliao>选择资源</div>
            <input id="ziliaosource" class="ziliaosource" name="ziliaosource" type="file"  multiple   style="display: none">
            <div class="contain_resource_mid_btn qrtj" ms-click="commitResData()">确认提交</div>
            <input type="text" value="" class="md5container" id="md5container" style="display: none">{{--MD5容器--}}
        </div>
        {{--待添加资源列表--}}
        <div class="contain_resource_con hide" ms-class="hide:!resData.length">
            {{--待提交资源循环--}}
            <div class="contain_resource_con_li" ms-repeat="resData">
                <div class="contain_resource_con_li_num" ms-html="$index+1">1</div>
                <div class="contain_resource_con_li_con">
                    {{--资源名称--}}
                    <div class="contain_resource_con_li_con_li">
                        <div class="contain_resource_con_li_con_li_l">资源名称：</div>
                        <div class="contain_resource_con_li_con_li_m">
                            <div style="height:15px;"></div>
                            <div class="contain_resource_con_li_con_li_m_t"><input type="text" name="" class="resourceName" ms-class="inputgetfocus:el.unwind" ms-duplex="el.resourceTitle"></div>
                            {{--上传成功提示框--}}
                            <div class="contain_resource_con_li_con_li_m_success" ms-if=" el.jdmsg == '上传成功！' ">资源上传成功</div>
                            {{--进度条--}}
                            <div class="contain_resource_con_li_con_li_m_progress " ms-if="el.showjdbar">
                                <div style="height: 10px;"></div>
                                <div class="chapter_progress_bar" ms-if="el.showjdbar">
                                    <div class="chapter_progress_bar_a">                 <!-- 进度条底层 -->
                                        <div class="chapter_progress_bar_b" ms-css-width="[--el.progressBara--]%">             <!-- 读取进度条 -->
                                            <div class="chapter_progress_bar_c" ms-css-width="[--el.progressBarb--]%"></div>   <!-- 上传进度条 -->
                                        </div>
                                    </div>
                                    <div class="chapter_progress_bar_msg">
                                        <div class="chapter_progress_bar_msg_l" ms-html="el.jdmsg">文件上传中...</div>
                                        {{--<div class="chapter_progress_bar_msg_r hide" ms-click="stopupload(el)">取消上传</div>--}}
                                        <div class="chapter_progress_bar_msg_r" ms-if=" el.jdmsg == '读取中...' " ms-html="el.progressBara+'%'"></div>
                                        <div class="chapter_progress_bar_msg_r" ms-if=" el.progressBarb > 0 " ms-html="el.progressBarb+'%'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="contain_resource_con_li_con_li_r">
                            <div class="contain_resource_con_li_con_li_r_morebbtn down" ms-if="!el.unwind" ms-click="tounwind(el)">展开</div>
                            <div class="contain_resource_con_li_con_li_r_morebbtn up" ms-if="el.unwind" ms-click="tounwind(el)">收起</div>
                            <div class="contain_resource_con_li_con_li_r_delbtn" ms-click="stopUploadRes(el,$index)">删除</div>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    {{--资源描述--}}
                    <div class="contain_resource_con_li_con_li"  ms-if="el.unwind">
                        <div class="contain_resource_con_li_con_li_l" style="line-height:30px;">资源描述：</div>
                        <div class="contain_resource_con_li_con_li_m">
                            <div class="contain_resource_con_li_con_li_m_text">
                                <textarea name="" placeholder="为您的资源添加描述（选填）" ms-duplex="el.resourceIntro"  maxlength="200"></textarea>
                                <div class="contain_resource_con_li_con_li_m_text_count" ms-html="el.resourceIntro.length+'/200'">0/200</div>
                            </div>
                        </div>
                    </div>
                    <div style="height:20px;"></div>
                    {{--资源封面--}}
                    <div class="contain_resource_con_li_con_li"  ms-if="el.unwind">
                        <div class="contain_resource_con_li_con_li_l" style="line-height:30px;">资源封面：</div>
                        <div class="contain_resource_con_li_con_li_m" style="height:100px;">
                            <img style="width:120px;height:90px;" ms-attr-src="el.resourcePic" alt="">
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div style="clear: both"></div>
            </div>
            <div style="height: 20px;"></div>
        </div>
    </div>

@endsection

@section('selectjs')
    <script type="text/javascript" src="{{asset('home/js/users/select2.min.js') }}"></script>
    <script>
        $('select').select2(
                { minimumResultsForSearch: Infinity }
        );
    </script>
@endsection
@section('js')
    <script>
        require(['/resource/uploadRes'], function (vm) {
            vm.userId = '{{$userId}}' || '';
            vm.getType('restype',1);
            vm.getType('resgrade',2);

            //选择文件
            avalon.directive("slectziliao", {
                init: function (binding) {
                    var elem = binding.element;
                    avalon(elem).unbind();
                    avalon(elem).bind("click",function () {
                        var inputf = $(this).next();    //input 选择文件框
                        inputf.bind('change',function(e){
                            var obj = document.getElementById("ziliaosource").files;  //文件对象集合（多选）
                            document.getElementById("ziliaosource").outerHTML = document.getElementById("ziliaosource").outerHTML; //清空input存储文件对象
                            var objlength = obj.length;
                            if((objlength + vm.resData.length) > 10){ alert('上传文件最多不超过'+vm.fileLimit+'个'); return false;}
                            for(var i=0;i<objlength;i++){//格式验证
                                var suffix = obj[i].name.substring(obj[i].name.lastIndexOf('.') + 1);
                                if(!suffix.match(/(xls|xlsx|doc|docx|ppt|pdf|png|jpg|mov|mp4|flv|avi|rmvb|wmv|mkv|swf)/i)){
                                    alert('文件格式错误');return false;
                                }
                                if(!vm.countsize(obj[i].name,obj[i].size)){
                                    alert(obj[i].name+'文件大小超过1G');return false;
                                }
                            }
                            vm.getDefaultPic(obj,objlength);
                            //console.log(obj);
                            vm.uploadziliao(obj,objlength); //执行上传
                        });
                        inputf.click();
                    })

                }
            });

            avalon.scan(document.body);
        });
    </script>
@endsection