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
                let el = $('#message_status');
                el.removeClass('hidden');
                if (res === 'success') {
                    console.log('Отправлено');
                    el.html('Отправлено');
                } else {
                    console.log('Ошибка отправки');
                    el.html('Ошибка отправки');
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
