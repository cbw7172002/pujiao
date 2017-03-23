define([], function() {
    var detail = avalon.define({
        $id: "searchSelect",
        grades: [],
        subjects:[],
        editions:[],
        books:[],
        chapter:[],
        resourceType:[],


        defaultGrade:0, //默认选中的值
        defaultSubject:0,
        defaultEdition:0,
        defaultBook:0,
        defaultChapter:0,
        defaultType:0,
        getGrade:function(){
            $.ajax({
                type: "get",
                url: "/admin/resource/getGrade",

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    detail.grades = res;
                }
            });
        },

        getSubject:function(){
            $.ajax({
                type: "get",
                url: "/admin/resource/getSubject",

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    detail.subjects = res;
                }
            });
        },

        getEdition:function(){
            $.ajax({
                type: "get",
                url: "/admin/resource/getEdition",

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    detail.editions = res;
                }
            });
        },

        getBook:function(){
            $.ajax({
                type: "get",
                url: "/admin/resource/getBook",

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    detail.books = res;
                }
            });
        },

        getChapter:function(type){
            var gradeId = null;
            var subjectId = null;
            var editionId = null;
            var bookId = null;
            if(type == 1){
                gradeId = $(this).val();
                subjectId = $('#subject').val();
                editionId = $('#edition').val();
                bookId = $('#book').val();
            }else if(type == 2){
                subjectId = $(this).val();
                gradeId = $('#grade').val();
                editionId = $('#edition').val();
                bookId = $('#book').val();
            }else if(type == 3){
                editionId = $(this).val();
                gradeId = $('#grade').val();
                subjectId = $('#subject').val();
                bookId = $('#book').val();
            }else if(type == 4){
                bookId = $(this).val();
                gradeId = $('#grade').val();
                subjectId = $('#subject').val();
                editionId = $('#edition').val();
            }else{
                gradeId = detail.defaultGrade;
                subjectId = detail.defaultSubject;
                editionId = detail.defaultEdition;
                bookId = detail.defaultBook;
            }

            //console.log('===');
            //console.log(gradeId);
            //console.log(subjectId);
            //console.log(editionId);
            //console.log(bookId);
            //return;
            $.ajax({
                type: "get",
                url: "/admin/resource/getChapter/" + gradeId + '/' + subjectId + '/' + editionId + '/' + bookId,

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    detail.chapter = res;
                }
            });
        },

        getResourceType:function(){
            $.ajax({
                type: "get",
                url: "/admin/resource/getResourceType",

                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    detail.resourceType = res;
                }
            });
        }
    });
    detail.getGrade();//年级
    detail.getSubject();//科目
    detail.getEdition();//版本
    detail.getBook();//册别
    detail.getResourceType();//资源类型

    return detail;
})