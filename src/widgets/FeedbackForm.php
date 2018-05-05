<?php
namespace egor260890\feedback\widgets;
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 8:45
 */
use egor260890\feedback\widgets\assets\Assets;
use yii\base\Widget;
use yii\helpers\Html;
use yii\validators\Validator;
use yii\web\JsExpression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;


class FeedbackForm extends Widget
{

    public $id='feedback-form';
    public $template='{name}{company_name}{tel}{email}{message}{button}';
    public $inputTemplate;
    public $fieldsConfig=[];
    private $rules=[];
    private $formConfig=[];

    public function init()
    {
        $this->formConfig['id']=$this->id;
        if (!$this->inputTemplate) {
            $field = new ActiveField();
            $this->inputTemplate = $field->template;
        }
        Assets::register(\Yii::$app->getView(),\yii\web\View::POS_END);
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run(){
        $model=new \egor260890\feedback\forms\FeedbackForm();
        $model->addRules($this->rules);
        $form=ActiveForm::begin($this->formConfig);
        echo $this->renderFields($model,$form);
        ActiveForm::end();
        $this->getView()->registerJs(new JsExpression("registerFeedbackForm(\"$this->id\")"));
    }

    protected function renderFields($model,$form)
    {

        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model,$form) {
            $name = $matches[1];
            switch ($name){
                case 'button': return Html::submitButton($this->fieldsConfig["{$name}"]['label']?:'Отправить', ['class' =>$this->fieldsConfig["{$name}"]['class']?:'btn btn-submit-form  center-block']);
                    break;
                case 'message': $field=$form->field($model,"{$name}",['template'=>$this->fieldsConfig["{$name}"]['template']?:$this->inputTemplate])->textarea();

                    break;
                default: $field=$form->field($model,"{$name}",['template'=>$this->fieldsConfig["{$name}"]['template']?:$this->inputTemplate])->textInput(['placeholder'=>$this->fieldsConfig["{$name}"]['placeholder']?:null]);
                    break;
            }
            if (isset($this->fieldsConfig["{$name}"]['label'])){
                $field->label($this->fieldsConfig["{$name}"]['label']);
            }
            return $field;
        }, $this->template);
    }

    public function setFormConfig(array $formConfig){
        $this->formConfig=array_merge($this->formConfig,$formConfig);
    }

    public function setRules($rules){
        if ($rules instanceof \Closure){
            $this->rules=call_user_func($rules);
        }else {
            $this->rules = $rules;
        }
    }



}