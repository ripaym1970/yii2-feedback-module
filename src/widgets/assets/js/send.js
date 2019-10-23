/**
 * Created by User on 04.05.2018.
 */

//function registerFeedbackForm(id) {
//    $('#'+id)
//    //.on('beforeValidate',function() {
//    //    console.log('url='+url);
//    //    $('.send').addClass('hidden');
//    //})
//    .on('beforeSubmit', function() {
//        let data = $(this).serialize();
//        $.ajax({
//            //url:      '/feedback-send',
//            // Если на сайте используется интернационализация
//            url:      '/'+document.getElementsByTagName('html')[0].getAttribute('lang')+'/feedback-send',
//            type:     'POST',
//            context:  this,
//            dateType: 'json',
//            data:     data,
//            // Для обработки ответа надо на странице сделать подписку на
//            // $(document).ajaxSuccess(function(event, xhr, settings) {...});
//            success: function(res){
//                //console.log(res);
//                $(this)[0].reset();
//                $('#'+id).trigger('send.feedback');
//            },
//            // Для обработки ошибки надо на странице сделать подписку на
//            // $(document).ajaxError(function(event, xhr, settings) {...});
//            error: function(err){
//                console.log(err);
//            }
//        });
//
//        return false;
//    });
//}

function registerFeedbackForm(id, url) {
    //console.log('url='+url);
    if (url) {
        $('#'+id)
        .on('beforeSubmit', function() {
            let data = $(this).serialize();
            $.ajax({
                // Если на сайте используется интернационализация
                url:      url,
                type:     'POST',
                //context:  this,
                dateType: 'json',
                data:     data,
                // Для обработки ответа надо на странице сделать подписку на
                // $(document).ajaxSuccess(function(event, xhr, settings) {...});
                success: function(res){
                    console.log(res);
                    //let btn = document.getElementById('btn-submit');
                    ////console.log('btn=',btn);
                    //let newSpan = document.createElement('span');
                    //
                    //if (res === 'success') {
                    //    newSpan.className = 'send pt20 pl10 bold green';
                    //    newSpan.innerHTML = 'Відправлено';
                    //} else {
                    //    newSpan.className = 'send pt20 pl10 bold red';
                    //    newSpan.innerHTML = 'Ошибка відправки';
                    //}
                    //
                    //btn.after(newSpan);
                },
                // Для обработки ошибки надо на странице сделать подписку на
                // $(document).ajaxError(function(event, xhr, settings) {...});
                error: function(err){
                    console.log(err);
                }
            });

            return false;
        });
    } else {
        $('#'+id)
        //.on('beforeValidate',function() {
        //    console.log('url='+url);
        //    $('.send').addClass('hidden');
        //})
        .on('beforeSubmit', function() {
            let data = $(this).serialize();
            $.ajax({
                //url:      '/feedback-send',
                // Если на сайте используется интернационализация
                url:      '/'+document.getElementsByTagName('html')[0].getAttribute('lang')+'/feedback-send',
                type:     'POST',
                context:  this,
                dateType: 'json',
                data:     data,
                // Для обработки ответа надо на странице сделать подписку на
                // $(document).ajaxSuccess(function(event, xhr, settings) {...});
                success: function(res){
                    //console.log(res);
                    $(this)[0].reset();
                    $('#'+id).trigger('send.feedback');
                },
                // Для обработки ошибки надо на странице сделать подписку на
                // $(document).ajaxError(function(event, xhr, settings) {...});
                error: function(err){
                    console.log(err);
                }
            });

            return false;
        });
    }
}
