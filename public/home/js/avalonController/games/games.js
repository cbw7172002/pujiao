define([], function () {

    var games = avalon.define({
        $id: 'games',
        datas: [],
        Msg: false,
        Con: false,
        page:false,
        getdata:function(para){
            games.Msg = false;
            games.Con = false;
            $('#page').pagination({
                dataSource: function(done) {
                    $.ajax({
                        type: 'GET',
                        url: '/index/getgames/'+para+'/'+this.pageNumber+'/'+this.pageSize,
                        success: function(response) {
                            if(response.status){
                                var format = [];
                                format['data'] = response.data;
                                format['totalNumber'] = response.count;
                                done(format);
                                // done(response.data);
                                games.Con = true;
                                if(response.count / 7 > 1){
                                    games.page = true;
                                }
                            }else{
                                games.Msg = true;
                            }
                        }
                    });
                },
                getData: function(pageNumber,pageSize) {
                    var self = this;
                    $.ajax({
                        type: 'GET',
                        url: '/index/getgames/'+para+'/'+pageNumber+'/'+pageSize,
                        success: function(response) {
                            self.callback(response.data);
                        }
                    });
                },
                pageSize: 7,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        games.datas = data;
                    }

                }
            })
        }
    });

    games.getdata(1);

});


