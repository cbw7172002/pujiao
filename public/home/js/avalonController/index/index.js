define([], function () {
    //首页最外层模块
    avalon.define({
        $id: 'index',
    });

    //名师介绍模块
    var parter  = avalon.define({
        $id: 'parter',
        datas: [],
        getData:function(){
            $.ajax({
                type: "get",
                url: "/index/getteachers",
                success: function(data){
                    if(data.status){
                        teachers.datas = data.data;
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown){

                }
            });
        }
    });
    parter.getData();
    
    // dplessons.getData();
});