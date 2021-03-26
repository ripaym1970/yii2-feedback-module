<?php

namespace egor260890\feedback\controllers;

use egor260890\feedback\entities\Feedback;
use nickdenry\grid\toggle\actions\ToggleAction;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use egor260890\feedback\forms\search\FeedbackSearch;
use egor260890\feedback\services\FeedbackManageService;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;

class FeedbackController extends Controller {

    private $feedbackManageService;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            [
                'class' => AjaxFilter::class,
                'only'  => [
                    'delete-multiple',
                    'unreviewed-multiple',
                    'viewed-multiple',
                    'send-reply',
                ]
            ],
        ];

        return array_merge($behaviors, parent::behaviors());
    }

    /**
     * FeedbackController constructor
     *
     * @param int                   $id
     * @param Module                $module
     * @param array                 $config
     * @param FeedbackManageService $feedbackManageService
     */
    public function __construct($id, Module $module, array $config=[], FeedbackManageService $feedbackManageService) {
        $this->feedbackManageService = $feedbackManageService;
        parent::__construct($id, $module, $config);
    }

    public function actions() {
        return [
            'toggle' => [
                'class' => ToggleAction::class,
                'modelClass' => 'egor260890\feedback\entities\Feedback', // Your model class
            ],
        ];
    }

    /**
     * Lists all Feedback models
     *
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FeedbackSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Feedback model
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        $feedback = $this->feedbackManageService->findModel($id);
        $this->feedbackManageService->viewed($feedback->id);

        return $this->render('view', [
            'model' => $feedback,
        ]);
    }

    /**
     * Deletes an existing Feedback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        try {
            $this->feedbackManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionDeleteMultiple() {
        if (Yii::$app->request->isAjax) {
            $keys = Yii::$app->request->post('keys');
            $this->feedbackManageService->removeMultiple($keys);

            return 'success';
        }

        throw new BadRequestHttpException('Bad request');
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionUnreviewedMultiple() {
        if (Yii::$app->request->isAjax) {
            $keys = Yii::$app->request->post('keys');
            $this->feedbackManageService->unreviewedMultiple($keys);

            return 'success';
        }

        throw new BadRequestHttpException('Bad request');
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionViewedMultiple() {
        if (Yii::$app->request->isAjax) {
            $keys = Yii::$app->request->post('keys');
            $this->feedbackManageService->viewedMultiple($keys);

            return 'success';
        }

        throw new BadRequestHttpException('Bad request');
    }

    public function actionTest() {
        return 'test';
    }

    /**
     * Отправляет ответ на вопрос сообщением или на почту если нет зарегистрированного с такой почтой
     *
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionSendReply() {
        if (Yii::$app->request->isAjax) {
            $requestPost = Yii::$app->request->post();

            $isSend = Yii::$app->mailer
                ->compose()
                ->setFrom('no-reply@dzygamdb.com')
                //->setTo('editor@dzygamdb.com')
                ->setTo($requestPost['FeedbackForm']['email'])
                ->setSubject('DzygaMDB.com: Відповідь на ваше питання')
                ->setHtmlBody(
$requestPost['FeedbackForm']['name'].', дякуємо за звернення.<br><br>
Повідомляємо, що:<br>'
. $requestPost['FeedbackForm']['message']
. '<br><br>Модератор'
)
                ->send();

            if ($isSend) {
                $feedback = Feedback::findOne(['id' => $requestPost['FeedbackForm']['id']]);
                if ($feedback) {
                    $feedback->is_send = true;
                    $feedback->save(false);
                }
            }

            return $isSend?'success':'error';
        }

        throw new BadRequestHttpException('Bad request');
    }
}
