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
        var orders = 'orders';
        console.log(time);
        $.ajax({
            type: "get",
            url: "/admin/count/userCount/" + time + '/' + orders,
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
        url: "/admin/count/userCount/null/orders",
        dataType: 'json',
        success: function (data) {
            cb({
                categories: data.categories,
                data:data.data
            });
            console.log(data);
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
        text: '订单数'
    },
    tooltip: {},
    legend: {
        data:['订单数']
    },
    xAxis: {
        name:'日期',
        type:'category',
        data: []
    },
    yAxis: {
        name:'订单数'
    },
    series: [{
        name: '订单数',
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
            name: '人数',
            data: data.data
        }]
    });
});
