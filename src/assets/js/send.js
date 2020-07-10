$(document).on('pjax:complete', function() {
    $(function() {
        $("#del-btn-feedback, #draft-btn-feedback, #activate-btn-feedback").on('click', function() {
            let url = $(this).data('url');
            if ($(this).attr('id') === 'del-btn-feedback' && !confirm('Подтвердите удаление')) {
                return;
            }
            ajaxSend(url);

            return false;
        });
    });
});

$(function () {
    $("#del-btn-feedback, #draft-btn-feedback, #activate-btn-feedback").on('click', function() {
        let url = $(this).data('url');
        if ($(this).attr('id') === 'del-btn-feedback' && !confirm('Подтвердите удаление')) {
            return;
        }
        ajaxSend(url);

        return false;
    });
});

function ajaxSend(url) {
    let keys = $('#w0').yiiGridView('getSelectedRows');
    if (keys.length < 1) {
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        dateType: 'json',
        data:{
            keys:keys
        },
        success: function(res){
            //dataLayer.push({'event': 'Svyajutes_s_nami'});

            $.pjax.reload({
                container: '#pjax-content'
            });
        },
        error: function(err){
            console.log(err);
        }
    });
}
