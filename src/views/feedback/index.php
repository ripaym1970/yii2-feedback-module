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
                        $link = Html::a($data->email, ['view', 'id' => $data->id], [
                            'title' => $data->email,
                            'data' => [
                                'target' => '#myModal',
                                'toggle' => 'modal',
                                'backdrop' => 'static',
                            ]
                        ]);
                        return $link;
                    },
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


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

<script>
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
</script>
