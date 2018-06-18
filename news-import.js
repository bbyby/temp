$("document").ready(function(){
    $("#importStart").on('click', function(){
        var ru_options = $("[name='ru-options[]']");
        var en_options = $("[name='en-options[]']");
        var url="news_import.php";
        var result = $("#result");
        
        //предпологаем что статей на ру всегда больше или равно
        $.each(ru_options,function(key, value){
            quer_data = {
                ru_id: value.dataset.val,
                en_id: en_options[key].dataset.val
            };

            $.ajax({
                type: 'post',
                async: false,
                dataType: 'json',
                data: quer_data,
                url: url,
                success: function( res_data ){
                    var old_res = parseInt( $("#result").html() );
                    //по-умолчанию блок пустой
                    if( isNaN( old_res) ){
                        old_res = 0;
                    }
                    $("#result").html(old_res + parseInt(res_data.status) );
                }
            });
            //return false;
        });
        //TODO: сделать цикл не зависящий от ру статей
    });
	
	$("#startTwins").on('click', function(){
        var ru_options = $("[name='ru-options[]']");
        var en_options = $("[name='en-options[]']");
        var url="news_twins.php";
        var result = $("#result");
        
        //предпологаем что статей на ру всегда больше или равно
        $.each(ru_options,function(key, value){
            quer_data = {
                ru_id: value.dataset.val,
                en_id: en_options[key].dataset.val
            };

            $.ajax({
                type: 'post',
                async: false,
                dataType: 'json',
                data: quer_data,
                url: url,
                success: function( res_data ){
                    var old_res = parseInt( $("#result").html() );
                    //по-умолчанию блок пустой
                    if( isNaN( old_res) ){
                        old_res = 0;
                    }
                    $("#result").html(old_res + parseInt(res_data.status) );
                }
            });
            //return false;
        });
        //TODO: сделать цикл не зависящий от ру статей
    });
});