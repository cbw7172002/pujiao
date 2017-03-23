avalon.directive('theteacheryincang', {
    update: function (value) {
        // 超出部分隐藏
        $('.content_introduce div').each(function () {
            var maxwidth = 130;
            if ($(this).html().length > maxwidth) {
                $($(this)).html($($(this)).html().substring(0, maxwidth));
                $($(this)).html($($(this)).html() + '…');
            }
        });
    }
});


define([], function () {
    var model = avalon.define({
        $id: 'theteacher',
        //名师主页路由
        teacherhomepage: '/lessonComment/teacher/',
        Yes: false,
        No: false,
        page: false,
        //名师
        theteacherlist: [],
        gettheteacher: function (type) {
            model.Yes = false;
            model.No = false;
            model.page = false,
            $('#page').pagination({
                dataSource: function (done) {
                    $.ajax({
                        type: 'GET',
                        url: '/community/gettheteacher/' + type + '/' + this.pageNumber + '/' + this.pageSize,
                        dataType : 'json',
                        success: function (response) {
                            if (response.statuss) {
                                var format = [];
                                //format['data'] = {'A':[{1:1},{1:1},{1:1}],'B':[{1:2}],'C':[{1:2}],'D':[{1:3},{1:3},{1:3}]};
                                //format['data'] = [[{1:1},{1:1},{1:1}],[{1:2}],[{1:2}],[{1:3},{1:3},{1:3}]];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                                model.Yes = true;
                                if(response.count / 8 > 1){
                                    model.page = true;
                                }
                            } else {
                                model.No = true;
                            }
                        }
                    });
                },
                getData: function (pageNumber, pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/community/gettheteacher/' + type + '/' + pageNumber + '/' + pageSize,
                        success: function (response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 8,
                pageNumber: 1,
                totalNumber: 1,
                className: "paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function (data) {
                    if (data) {
                        model.theteacherlist = data;
                    }
                }
            })

        },


        //26字母接口
        firstletter: [],
        getfirstletter: function () {
            $.ajax({
                url: '/community/getfirstletter/',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    //console.log(response.firstletter)
                    if (response.statuss) {
                        model.firstletter = response.firstletter;
                    }
                },
            })
        },


        //点击变色
        tabs: function (type) {
            if (type == 'tabss') {
                model.gettheteacher(0);
                $('.tabss').addClass('bluebackground').siblings().removeClass('bluebackground');
            } else if(type){
                model.gettheteacher(type);
                $('.'+ type).addClass('bluebackground').siblings().removeClass('bluebackground');
            }
        }


    });

    model.gettheteacher(0);
    model.getfirstletter();
    return model;
});