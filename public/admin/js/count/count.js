// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main'));

function fetchData(cb) {

    //$.get('userCount','',function (data){
    //    cb({
    //        categories: data.categories,
    //        data:data.data
    //    });
    //})
    $('.searchtype').change(function(){
        var time = $(this).val();
        console.log(time);
        $.ajax({
            type: "get",
            url: "/admin/count/userCount/" + time,
            dataType: 'json',
            success: function (data) {
                cb({
                    categories: data.categories,
                    data:data.data
                });
                $('.totalCount').html(data.totalCount);
                $('.nowMonthCount').html(data.nowMonthCount);
                $("input[name='excels']").val(JSON.stringify(data));

            }
        });
    })
    $.ajax({
        type: "get",
        url: "/admin/count/userCount/null",
        dataType: 'json',
        success: function (data) {
            cb({
                categories: data.categories,
                data:data.data
            });
            console.log(JSON.stringify(data));
            $('.totalCount').html(data.totalCount);
            $('.nowMonthCount').html(data.nowMonthCount);
            $("input[name='excels']").val(JSON.stringify(data));
        }
    });

//            setTimeout(function () {
//                cb({
//                    categories: ["16","17","18","19","20","21"],
//                    data: [5, 20, 36, 10, 10, 20]
//                });
//            }, 3000);
}

// 指定图表的配置项和数据
option = {
    title: {
        text: '注册用户数'
    },
    tooltip: {},
    legend: {
        data:['用户数']
    },
    xAxis: {
        name:'日期',
        type:'category',
        data: []
    },
    yAxis: {
        name:'人数'
    },
    series: [{
        name: '用户数',
        type: 'bar',
        data: []
    }]
};

// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);

myChart.showLoading();
fetchData(function (data) {
    myChart.hideLoading();
    myChart.setOption({
        xAxis: {
            data: data.categories
        },
        series: [{
            // 根据名字对应到相应的系列
            name: '用户数',
            data: data.data
        }]
    });
});



//搜索条件筛选
//$('.searchtype').change(function(){
//    var a = $(this).val();
//    console.log(a);
//})