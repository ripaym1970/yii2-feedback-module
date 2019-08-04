/**
 * Created by User on 04.05.2018.
 */

function registerFeedbackForm(id, url) {
    $('#'+id)
    //.on('beforeValidate',function() {
    //    console.log('url='+url);
    //    $('.send').addClass('hidden');
    //})
    .on('beforeSubmit', function(url) {
        console.log('url='+url);
        let data = $(this).serialize();
        $.ajax({
            //url:      '/feedback-send',
            // Если на сайте используется интернационализация
            url:      url?url:'/'+document.getElementsByTagName('html')[0].getAttribute('lang')+'/feedback-send',
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
