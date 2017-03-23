define([], function() {
	

	avalon.directive('follow', {
		update: function(value) {
			var element = this.element, className = '#' + avalon(element).attr('id');
			avalon(element).attr('class', 'introduce_left_top_right_right');
			value || avalon(element).addClass('active');
			value ? $(className).html('已关注') : $(className).html('关 注');
			$(className).unbind();
			$(className).hover(function () {
				value ? avalon(element).addClass('isFollow') : avalon(element).addClass('noFollow');
			}, function () {
				value ? avalon(element).removeClass('isFollow') : avalon(element).removeClass('noFollow');
			});
		}
	});

	avalon.directive('tabstatus', {
		update: function(value) {
			var element = this.element, status = avalon(element).attr('value');
			avalon(element).attr('class', '');
			$('div[id=tabs][value='+ avalon(element).attr('value') +']').unbind();
			if (value == status) {
				avalon(element).attr('class', 'tab_active');
			} else {
				$('div[id=tabs][value='+ avalon(element).attr('value') +']').hover(function() {
					avalon(element).addClass('tab_hover');
				}, function() {
					avalon(element).removeClass('tab_hover');
				});
			}
		}
	});

	avalon.directive('popup', {
		update: function(value) {
			var element = this.element, popUpType = avalon(element).attr('value');
	        if (!value) {
	            avalon(element).css('display', 'none');
	            return;
	        };
	        if (value == popUpType || popUpType == 'close') {
	            avalon(element).css('display', 'block');
	        } else {
	        	avalon(element).css('display', 'none');
	        };
		}
	});

	avalon.directive("sel", {
		init: function (a) {
			$('.middle_subject_item').click(function () { $(this).addClass('subject_font_diff').siblings().removeClass('subject_font_diff') });
		}
	});
});