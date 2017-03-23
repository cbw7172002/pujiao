$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
// 导航栏
    //鼠标悬停
	$('.head_con_li_con').hover(
		function(){
			$(this).parent().addClass('hov')
		},
		function(){
			$('.head_con_li').removeClass('hov')
		}
	)
	//鼠标点击
	// $('.head_con_li_con').click(function(){
	// 	$(this).addClass('sel');
	// })

// 头像个人信息
// $('.touxiang').mousemove(function(){
// 	$(".persapce").slideDown("fast");
// })
// $(".persapce").mousemove(function(){
// 	$(this).stop().show();
// }).mouseout(function(){
// 	$(this).stop().slideUp("fast");
// })

$('.showperspace').hover(function () {
	$('.persapce').stop().show();
},function(){
	$('.persapce').stop().hide();
})



// 咨询
// $('.qq').hover(function(){
// 	$(this).children(".mmsg").removeClass('hide');
// },function(){
// 	$('.mmsg').addClass('hide');
// })
// $('.wx').hover(function(){
// 	$(this).children(".mmsg").removeClass('hide');
// },function(){
// 	$('.mmsg').addClass('hide');
// })
// $('.tel').hover(function(){
// 	$(this).children(".mmsg").removeClass('hide');
// },function(){
// 	$('.mmsg').addClass('hide');
// })
// $('.eml').hover(function(){
// 	$(this).children(".mmsg").removeClass('hide');
// },function(){
// 	$('.mmsg').addClass('hide');
// })
//
//
// $('.mmsg').mouseover(function(){
// 	$(this).removeClass('hide');
// })
// $('.mmsg').mouseout(function(){
// 	$(this).addClass('hide');
// })

$('.onlines_wx').hover(function(){
	$(this).children(".onlines_wx_con").removeClass('hide');
},function(){
	$(this).children(".onlines_wx_con").addClass('hide');
})

$('.onlines_fk').hover(function(){
	$(".onlines_fk_con").removeClass('hide');
},function(){
	$(".onlines_fk_con").addClass('hide');
})

$('.onlines_db').hover(function(){
	$(".onlines_db_con").removeClass('hide');
},function(){
	$(".onlines_db_con").addClass('hide');
})


// 底部联系方式
$('.lxlogoa').hover(function(){
	$('.lxfsa').removeClass('hide');
},function(){
	$('.lxfsa').addClass('hide');
})
$('.lxlogob').hover(function(){
	$('.lxfsb').removeClass('hide');
},function(){
	$('.lxfsb').addClass('hide');
})

var url = window.location.href.split('/');
switch (url[3]) {
	case 'member':
		url = 'member';
		break;
	case 'resource':
		url = 'resource';
		break;
	case 'studentCourse':
		url = 'studentCourse';
		break;
    case 'teacherCourse':
        url = 'teacherCourse';
        break;
	case 'community':
		url = 'community';
		break;
	case 'evaluateManageTea':
		url = 'evaluateManageTea';
		break;
	case 'evaluateManageStu':
		url = 'evaluateManageStu';
		break;
}
$('.'+url).addClass('sels');
$('.'+url).addClass('selss');
//
document.getElementById("shadow").addEventListener("wheel", function(e) {
	e.preventDefault();
});

