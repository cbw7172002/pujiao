$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //$(".course_back").hover(
    //    function () {
    //        var str1 = $(this).attr('class');
    //        var str2 = 'intro'
    //        console.log(str1.indexOf(str2) > -1); //true
    //        if(str1.indexOf(str2) > -1){
    //            $(this).addClass("active_hover").prev('span').addClass('span_active');
    //        }
    //    },
    //    function () {
    //        $(this).removeClass("active_hover").prev('span').removeClass('span_active');
    //    }
    //);



    $(".course_back").hover(
        function () {
            $(this).addClass("active_hover").prev('span').addClass('span_active');
        },
        function () {
            $(this).removeClass("active_hover").prev('span').removeClass('span_active');
        }
    );

    $(".mycourse").click(function() {
        $(".content_right_mycourse").show().siblings(".content_right_reccourse").hide().siblings(".content_right_allcourse").hide().siblings(".content_right_newcourse").hide()
        $('.mycourse').addClass('span_active').siblings().removeClass('span_active')
    })
    $(".reccourse").click(function(){
        $(".content_right_reccourse").show().siblings(".content_right_mycourse").hide().siblings(".content_right_allcourse").hide().siblings(".content_right_newcourse").hide()
        $('.reccourse').addClass('span_active').siblings().removeClass('span_active')
    })
    $(".allcourse").click(function(){
        $(".content_right_allcourse").show().siblings(".content_right_mycourse").hide().siblings(".content_right_reccourse").hide().siblings(".content_right_newcourse").hide()
        $('.allcourse').addClass('span_active').siblings().removeClass('span_active')
    })
    $(".newcourse").click(function(){
        $(".content_right_newcourse").show().siblings(".content_right_reccourse").hide().siblings(".content_right_allcourse").hide().siblings(".content_right_mycourse").hide()
        $('.newcourse').addClass('span_active').siblings().removeClass('span_active')
    })

    $('.release').click(function(){
        $(this).removeClass('color_gray').addClass('color_blue').siblings().removeClass('color_blue').addClass('color_gray')
    })

    $('.reccourse').click(function(){
        //$('.mycourse_select').addClass('color_blue')
    })


    //更多选项
    $('.more_option').click(function () {
        $('.version').show();
        $('.more_option').hide();
        $('.stop_option').show();
    })

    $('.stop_option').click(function () {
        $('.version').hide();
        $('.stop_option').hide();
        $('.more_option').show();
    })

    //热门 -- 最新
    $('.hot_new_hot').click(function(){
        $('.hot_new_hot').addClass('hot_new_blue')
        $('.hot_new_new').removeClass('hot_new_blue')
    })
    $('.hot_new_new').click(function(){
        $('.hot_new_new').addClass('hot_new_blue')
        $('.hot_new_hot').removeClass('hot_new_blue')
    })


    $('.mycourse_select .release :first').addClass('color_blue')

    //年级-学科-册别-版本 颜色变化

    $('.all_grade').click(function(){
        $('.all_grade').addClass('hot_new_blue').siblings().removeClass('hot_new_blue')
    })

    $('.all_subject').click(function(){
        $('.all_subject').addClass('hot_new_blue').siblings().removeClass('hot_new_blue')
    })

    $('.all_book').click(function(){
        $('.all_book').addClass('hot_new_blue').siblings().removeClass('hot_new_blue')
    })

    $('.all_edition').click(function(){
        $('.all_edition').addClass('hot_new_blue').siblings().removeClass('hot_new_blue')
    })


    $('.mycourse_select').children('div:nth-child(2)').addClass('color_blue')


    //头像上传
    var uploadify_onSelectError = function(file, errorCode, errorMsg) {
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

    //创客封面
    $('#file_upload_coursepic').uploadify({
        'swf'      : '/home/css/personCenter/uploadify.swf',
        'uploader' : '/member/addImg',
        'buttonText' : '选择图片',
        'post_params' : {
            '_token' : $('meta[name="csrf-token"]').attr('content')
        },
        'file_size_limit' : '5MB',
        'file_types' : " *.jpg;*.png;*.jpeg;*.bmp;*.pdf;*.ico;*.gif ",
        'overrideEvents'  : ['onSelectError'],
        'onSelectError' : uploadify_onSelectError,
        'onUploadSuccess' : function(file, data, response) {
            //alert(data);
            //return false;
            var data = eval("("+data+")");
            imgsrc = data.src;
            $('.content_right_newcourse_design_barb_r_img').html("<img class=\"center_right_comment_fabu_a_con_pic_r_l_pic\" src='"+data.src+"' width='120' height='90'>");
        }
    });

})




