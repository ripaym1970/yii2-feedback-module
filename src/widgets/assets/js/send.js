/**
 * Created by User on 04.05.2018.
 */

function registerFeedbackForm(id) {
    $('#'+id)
    .on('beforeValidate',function() {
        $('.send').addClass('hidden');
    })
    .on('beforeSubmit',function() {
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
                //console.log(res);
                //if (res === 'success') {
                //    $('#send_success').removeClass('hidden');
                //} else {
                //    $('#send_error').removeClass('hidden');
                //}
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
