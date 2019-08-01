/**
 * Created by User on 04.05.2018.
 */

function registerFeedbackForm(id) {
    $('#'+id).on('beforeSubmit',function() {
        $('#message_status').addClass('hidden');
        let data = $(this).serialize();
        $.ajax({
            //url:      '/feedback-send',
            // Если на сайте используется интернационализация
            url:      '/'+document.getElementsByTagName('html')[0].getAttribute('lang')+'/feedback-send',
            type:     'POST',
            context:  this,
            dateType: 'json',
            data:     data,
            success: function(res){
                console.log(res);
                if (res === 'success') {
                    $('#message_status').html('Отправлено').removeClass('hidden');
                } else {
                    $('#message_status').html('Ошибка отправки').removeClass('hidden');
                }
                $(this)[0].reset();
                $('#'+id).trigger('send.feedback');
            },
            error: function(err){
                console.log(err);
            }
        });

        return false;
    });
}
