define([], function(){
    var newId = location.href.split('/').pop();

    var newdetail = avalon.define({
        $id: 'newdetail',

        newdetails :[],
        getData:function(newId){
            $.ajax({
                url : '/community/getnewdetail/'+ newId,
                type : 'get',
                dataType : 'json',
                success: function(response){
                    if(response.statuss){
                        newdetail.newdetails = response.data;
                    }
                },

            })
        },



        //newdetail: {
        //    id : '1',
        //    title: '乐和音乐工作室',
        //    content:'在1995年以Personal Home Page Tools (PHP Tools) 开始对外发表第一个版本，Lerdorf写了一些介绍此程序的文档。并且发布了PHP1.0！在这的版本中，提供了访客留言本、访客计数器等简单的功能。以后越来越多的网站使用了PHP，并且强烈要求增加一些特性。比如循环语句和数组变量等等；在新的成员加入开发行列之后，Rasmus Lerdorf 在1995年6月8日将 PHP/FI 公开发布，希望可以透过社群来加速程序开发与寻找错误。这个发布的版本命名为 PHP 2，已经有 PHP 的一些雏型，像是类似 Perl的变量命名方式、表单处理功能、以及嵌入到 HTML 中执行的能力。程序语法上也类似 Perl，有较多的限制，不过更简单、更有弹性。PHP/FI加入了对MySQL的支持，从此建立了PHP在动态网页开发上的地位。到了1996年底，有15000个网站使用 PHP/FI',
        //    time: '2016-05-23',
        //},


    });
    newdetail.getData(newId);
    return newdetail;
});