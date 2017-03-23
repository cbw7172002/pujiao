
var getCity = function(code){
    $(".city").select2({
        ajax: {
            url: "/index/getCity/"+code,
            type:'get',
            dataType:'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        },

    });
}