<?php

use egor260890\feedback\widgets\FeedbackForm;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use egor260890\feedback\helpers\FeedbackHelper;
use egor260890\feedback\entities\Feedback;

/* @var $this yii\web\View */
/* @var $searchModel egor260890\feedback\forms\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = 'Обратная связь';

?>

<div class="feedback-index">
    <?php
    Pjax::begin(['id' => 'pjax-content']); ?>
        <?php
        echo GridView::widget([
            'id' => 'w0',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function(Feedback $model){
                if ($model->isUnreviewed()){
                    return ['class' => 'danger'];
                }
            },
            'columns' => [
                [
                    'class' => '\yii\grid\CheckboxColumn',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}'
                ],
                'id',
                'name',
                'tel',
                [
                    'attribute' => 'email',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return '<a href="" onClick="open_form(\''.$data->name.'\',\''.$data->email.'\')">'.$data->email.'</a>';
                    }
                ],
                [
                    'attribute' => 'created_date',
                    'value' => function($model){
                        return Yii::$app->formatter->asDatetime($model->created_date,'dd.MM.yyyy HH:mm:ss');
                    },
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_date',
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true
                        ]
                    ])
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'filter' => FeedbackHelper::statusList(),
                    'value' => function($model){
                        return FeedbackHelper::statusLabel($model->status);
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}'
                ],
            ],
        ]);
        ?>
    <?php
    Pjax::end(); ?>
</div>

<button id="activate-btn-feedback" class="btn btn-success" data-url="<?=Yii::$app->urlManager->createUrl('/feedback/feedback/viewed-multiple')?>">В просмотренные</button>
<button id="draft-btn-feedback" class="btn btn-primary" data-url="<?=Yii::$app->urlManager->createUrl('/feedback/feedback/unreviewed-multiple')?>">В непросмотренные</button>
<button id="del-btn-feedback" class="btn btn-danger" data-url="<?=Yii::$app->urlManager->createUrl('/feedback/feedback/delete-multiple')?>">Удалить</button>


<!--<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>-->

<div id="modal" class="modal" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modal').hide();">
                    <span>×</span>
                </button>
                <span class="modal-title left fs10">Відповідь на питання</span>
            </div>
            <div class="modal-body">
                <?php
                echo FeedbackForm::widget([
                    // Все поля из формы
                    //'template' => '{name}{company_name}{tel}{email}{message}{button}',
                    // Используемые поля из формы
                    'template' => '{name}{email}{message}{button}',
                    // Можно задать правила валидации
                    'rules' => function(){
                        return [
                            [['name'],    'required', 'message' => Yii::t('app','Необхідно заповнити'). ' "'.Yii::t('app','Ім’я').'"'],
                            [['email'],   'required', 'message' => Yii::t('app','Необхідно заповнити'). ' "'.Yii::t('app','E-mail').'"'],
                            [['message'], 'required', 'message' => Yii::t('app','Необхідно заповнити'). ' "'.Yii::t('app','Відповідь').'"'],
                        ];
                    },
                    'fieldsConfig' => [ // Настройка полей
                        'name' => [
                            'label' => Yii::t('app','Ім’я'),
                            'class' => 'input-wrapper',
                            'value' => '',
                        ],
                        'email' => [
                            'label' => Yii::t('app','E-mail'),
                            'class' => 'input-wrapper',
                            'value' => '',
                        ],
                        'message' => [
                            'label' => Yii::t('app','Відповідь'),
                            //'placeholder' => 'please',
                            'class' => 'input-wrapper pb20',
                        ],
                        'button' => [
                            'label' => Yii::t('app','Відправити'),
                            'class' => 'btn btn-warning',
                        ],
                    ],
                    // Куда отправлять запрос
                    'url' => true, //'/feedback/feedback/send_reply',
                    'formConfig' => [
                        //'enableAjaxValidation'   => false,
                        //'enableClientValidation' => true,
                        //'validationUrl' => '/'.Yii::$app->language.'/site/validate-feedback',
                        'options' => [
                            'class' => 'form-contact-us',
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    //// Закрытие сообщений Alert
    //setTimeout(function() {
    //    $(".close").trigger('click');
    //}, 3000);

    // Подписываемся на jQuery-событие beforeValidate валидации формы
    $('#feedback-form').on('beforeValidate',function() {
        $('.send').addClass('hidden');
    });

    // Подписываемся на jQuery-событие success отправки сообщения
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url === '/'+document.getElementsByTagName('html')[0].getAttribute('lang')+'/feedback-send') {
            let res = JSON.parse(xhr.responseText);
            let btn = document.getElementById('btn-submit');
            //console.log('btn=',btn);
            let newSpan = document.createElement('span');

            if (res === 'success') {
                newSpan.className = 'send pt20 pl10 bold green';
                newSpan.innerHTML = 'Відправлено';
            } else {
                newSpan.className = 'send pt20 pl10 bold red';
                newSpan.innerHTML = 'Ошибка відправки';
            }

            btn.after(newSpan);
        }
    });

    // Открытие формы с отправкой письма особистості/користувачу
    function open_form(name, email) {
        console.log('open_form');
        $("#modal").modal('show').on('hide.bs.modal', function() {
            console.log('on.hide.bs.modal');
        });

        $("#feedbackform-name").val(name).attr('value', name);
        $("#feedbackform-email").val(email).attr('value', email);
    }

    //$('body').on('hidden.bs.modal', '.modal', function () {
    //    $(this).removeData('bs.modal');
    //});
</script>
