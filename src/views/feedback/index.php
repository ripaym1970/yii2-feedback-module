<?php

use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
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
                'email',
                [
                    'attribute' => 'email',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return '<a href="" onClick="open_form(\''.$data->email.'\')">'.$data->email.'</a>';
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


<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

<div id="modal" class="modal" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title left fs10"></span>
            </div>
            <div class="modal-body">
                <div id="mail-text" class="mb5">
                    пропропропропропро
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Закрытие сообщений Alert
    setTimeout(function() {
        $(".close").trigger('click');
    }, 3000);

    // Открытие формы с отправкой письма особистості/користувачу
    function open_form(el) {
        $("#map-text").height(450);

        $("#modal").modal('show').on('hide.bs.modal', function() {
            $("#mail-text").text('');
        });
    }

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

</script>
