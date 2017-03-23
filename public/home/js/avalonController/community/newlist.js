 define([], function () {

 	var model = avalon.define({
 		$id : 'newlist',
 		lujing: '/community/newdetail/',
        page: false,
        newlist :[],
         getDatas:function(){
            model.page = false,
            $('#demo').pagination({
                dataSource: function(done) {
                     $.ajax({
                         type: 'GET',
                         url : '/community/getnewlist/'+this.pageNumber+'/'+this.pageSize,
                         //dataType:'json',
                         success: function(response) {
                             if(response.statuss){
                                 var format = [];
                                 format['data'] = response.data;
                                 format['totalNumber'] = response.count;
                                 done(format);
                                 if(response.count / 10 > 1){
                                     model.page = true;
                                 }
                                 //console.log(format['data'])
                                 //console.log(format['totalNumber'])
                             }
                         }
                     });
                },

                getData: function(pageNumber,pageSize) {
                     var self = this;
                     $.ajax({
                         type: 'GET',
                         url: '/community/getnewlist/'+pageNumber+'/'+pageSize,
                         //dataType:'json',
                         success: function(response) {
                             self.callback(response.data);
                         }
                     });
                },


                pageSize: 10,
                pageNumber :1,
                totalNumber :1,
                className:"paginationjs-theme-blue",
                showGoInput: true,
                showGoButton: true,
                callback: function(data) {
                    if(data){
                        model.newlist = data;
                    }

                }
             })

         },

 	});
     model.getDatas();
 	return model;
 });