<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 9:29
 */

namespace egor260890\feedback\widgets\controllers;

use DomainException;
use egor260890\feedback\forms\FeedbackForm;
use egor260890\feedback\services\FeedbackManageService;
use yii\base\Module;
use yii\filters\AjaxFilter;
use yii\web\BadRequestHttpException;

class SendController extends \yii\web\Controller {

    private $service;
    private $request;

    public function __construct($id, Module $module, FeedbackManageService $service, array $config = []) {
        $service->attachMany(\Yii::$app->getModule('feedback-send')->observers ? : null);
        $this->request = \Yii::$app->request;
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    public function behaviors() {
        $behaviors = [
            [
                'class' => AjaxFilter::class,
                'only'  => ['index']
            ],
        ];

        return array_merge($behaviors, parent::behaviors());
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionIndex() {
        \Yii::$app->response->format = 'json';
        $form = new FeedbackForm();
        if (!$form->load($this->request->post())) {
            throw new BadRequestHttpException('Bad request');
        }

        if (!$form->validate()) {
            throw new DomainException('No validate');
        }

        try {
            $this->service->create($form);
        } catch (\Exception $e) {
            \Yii::$app->errorHandler->logException($e);
            return 'Save error (message in ru language) => /frontend/runtime/logs/app.log';
        }

        return 'success22222222';
    }
}
