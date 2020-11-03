<?php

use nickdenry\grid\toggle\components\RoundSwitchColumn;
use egor260890\feedback\widgets\FeedbackForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Html;
use egor260890\feedback\helpers\FeedbackHelper;
use egor260890\feedback\entities\Feedback;

/* @var $this View */
/* @var $searchModel egor260890\feedback\forms\search\FeedbackSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = 'Обратная связь';

?>

<div class="feedback-index">
    <?php
    Pjax::begin(['id' => 'pjax-content']);
    ?>
        <?php
        echo GridView::widget([
            'id' => 'w0',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function(Feedback $model) {
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
                        return '<a href="javascript:void(0)" onClick="open_form(\''.$data->name.'\',\''.$data->email.'\')">'.$data->email.'</a>';
                    }
                ],
                [
                    'class' => RoundSwitchColumn::class,
                    'attribute' => 'is_send',
                    /* other column options, i.e. */
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'is_send',
                        ['' => 'Всі', 0 => 'Ні', 1 => 'Так'],
                        ['class' => 'form-control', 'style' => 'padding:6px 2px;width:70px;']
                    ),
                    'headerOptions' => ['width' => '70px'],
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
    Pjax::end();
    ?>
</div>

<button id="activate-btn-feedback" class="btn btn-success" data-url="<?=Yii::$app->urlManager->createUrl('/feedback/feedback/viewed-multiple')?>">В просмотренные</button>
<button id="draft-btn-feedback" class="btn btn-primary" data-url="<?=Yii::$app->urlManager->createUrl('/feedback/feedback/unreviewed-multiple')?>">В непросмотренные</button>
<button id="del-btn-feedback" class="btn btn-danger fl-r" data-url="<?=Yii::$app->urlManager->createUrl('/feedback/feedback/delete-multiple')?>">Удалить</button>


<!--<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>-->

<div id="modal" class="modal" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button id="btn-close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
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
                            'id' => 'btn-submit',
                        ],
                    ],
                    // Куда отправлять запрос
                    'url' => '/feedback/feedback/send-reply',
                    'formConfig' => [
                        'action' => '/feedback/feedback/send-reply',
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
    // Подписываемся на jQuery-событие beforeValidate валидации формы
    $('#feedback-form').on('beforeValidate',function() {
        $('.send').addClass('hidden');
    });

    // Подписываемся на jQuery-событие success отправки сообщения
    $(document).ajaxSuccess(function(event, xhr, settings) {
        //console.log(settings.url);
        if (settings.url === '/feedback/feedback/send-reply') {
            let res = xhr.responseText;
            let btn = document.getElementById('btn-submit');
            let newSpan = document.createElement('span');

            if (res === 'success') {
                newSpan.className = 'send pt20 pl10 bold green';
                newSpan.innerHTML = 'Відправлено';
                document.getElementById('feedbackform-message').value = '';
            } else {
                newSpan.className = 'send pt20 pl10 bold red';
                newSpan.innerHTML = 'Ошибка відправки';
            }

            btn.after(newSpan);
        } else if (settings.url === '/'+document.getElementsByTagName('html')[0].getAttribute('lang')+'/feedback-send') {
            let res = JSON.parse(xhr.responseText);
            let btn = document.getElementById('btn-submit');
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
        //console.log('open_form');
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
