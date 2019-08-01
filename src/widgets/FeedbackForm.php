<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 8:45
 */

namespace egor260890\feedback\widgets;

use egor260890\feedback\widgets\assets\Assets;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class FeedbackForm extends Widget {

    public $id           = 'feedback-form';
    public $template     = '{name}{company_name}{tel}{email}{message}{button}';
    public $inputTemplate;
    public $fieldsConfig = [];

    private $rules       = [];
    private $formConfig  = [];

    public function init() {
        $this->formConfig['id'] = $this->id;
        if (!$this->inputTemplate) {
            $field = new ActiveField();
            $this->inputTemplate = $field->template;
        }
        Assets::register(\Yii::$app->getView(), \yii\web\View::POS_END);
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run() {
        $model = new \egor260890\feedback\forms\FeedbackForm();
        $model->addRules($this->rules);

        $form = ActiveForm::begin($this->formConfig);
        echo $this->renderFields($model, $form);
        echo '<span id="send_success" class="send pl10 green hidden">'.\Yii::t('app','Відправлено').'</span>';
        echo '<span id="send_error"   class="send pl10 red hidden">'.\Yii::t('app','Ошибка відправки').'</span>';

        ActiveForm::end();

        $this->getView()->registerJs(new JsExpression("registerFeedbackForm(\"$this->id\")"));
    }

    /**
     * @param \egor260890\feedback\forms\FeedbackForm $model
     * @param ActiveForm $form
     *
     * @return string|string[]|null
     */
    protected function renderFields($model, $form) {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model,$form) {
            $name = $matches[1];

            if (!empty($this->fieldsConfig["{$name}"]['value'])) {
                $model->$name = $this->fieldsConfig["{$name}"]['value'];
            }

            switch ($name){
                case 'button':
                    //Tools::myPrintArray($this->fieldsConfig, '');exit;
                    return Html::submitButton(
                        !empty($this->fieldsConfig["{$name}"]['label'])
                            ?$this->fieldsConfig["{$name}"]['label']
                            :'Отправить',
                        [
                            'class' => !empty($this->fieldsConfig["{$name}"]['class'])
                                ?$this->fieldsConfig["{$name}"]['class']
                                :'btn btn-submit-form center-block'
                        ]);
                    break;
                case 'message':
                        $field = $form->field($model, "{$name}", [
                            'template' => !empty($this->fieldsConfig["{$name}"]['template'])
                                ?$this->fieldsConfig["{$name}"]['template']
                                :$this->inputTemplate,
                        ])
                        ->textarea();
                    break;
                default:
                    //Tools::myPrintArray($this->fieldsConfig, '');exit;
                    $field = $form->field($model, "{$name}", [
                            'options' => [
                                'class' => !empty($this->fieldsConfig["{$name}"]['class'])
                                    ?$this->fieldsConfig["{$name}"]['class']
                                    :$this->class,
                                'autocomplete' => 'off',
                            ],
                            'template' => !empty($this->fieldsConfig["{$name}"]['template'])
                                ?$this->fieldsConfig["{$name}"]['template']
                                :$this->inputTemplate,
                        ])
                        ->textInput([
                            'placeholder' => !empty($this->fieldsConfig["{$name}"]['placeholder'])
                                ?$this->fieldsConfig["{$name}"]['placeholder']
                                :null,
                        ]);
                    break;
            }
            if (!empty($this->fieldsConfig["{$name}"]['label'])) {
                $field->label($this->fieldsConfig["{$name}"]['label']);
            }

            return $field;
        }, $this->template);
    }

    public function setFormConfig(array $formConfig) {
        $this->formConfig = array_merge($this->formConfig, $formConfig);
    }

    public function setRules($rules) {
        if ($rules instanceof \Closure) {
            $this->rules = call_user_func($rules);
        } else {
            $this->rules = $rules;
        }
    }
}
