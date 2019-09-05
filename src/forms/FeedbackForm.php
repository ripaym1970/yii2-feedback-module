<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.11.2017
 * Time: 22:43
 */

namespace egor260890\feedback\forms;

use egor260890\feedback\entities\Feedback;
//use egor260890\feedback\validators\PhoneValidator;
use Yii;
use yii\base\Model;

class FeedbackForm extends Model {

    private $name;
    private $tel;
    private $status;
    private $email;
    private $message;
    private $company_name;

    private $rules = [
        [['tel', 'company_name'], 'string'],
        //['tel', PhoneValidator::class],
        [['name', 'email'], 'string', 'max' => 100],
        [['message'], 'string', 'max' => 1000],
        [['email'], 'email', 'message' => 'Невалідний E-mail'],
    ];

    public function __construct(Feedback $feedback=null, $config=[]) {
        if ($feedback) {
            $this->name         = $feedback->name;
            $this->tel          = $feedback->tel;
            $this->email        = $feedback->email;
            $this->message      = $feedback->message;
            $this->company_name = $feedback->company_name;
            $this->status       = $feedback->status;
        } else {
            $this->status = Feedback::STATUS_UNREVIEWED;
        }
        parent::__construct($config);
    }

    public function rules() {
        return $this->rules;
        //return array_merge($this->rules, ['email', 'email', 'message' => Yii::t('app','Невалідний E-mail')]);
    }

    public function addRules(array $rule) {
        $this->rules = array_merge($this->rules, $rule);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            //'name'         => Yii::t('app','Ваше ім’я:'),
            //'company_name' => Yii::t('app','Компанія:'),
            //'tel'          => Yii::t('app','Телефон:'),
            //'email'        => Yii::t('app','E-mail:'),
            //'message'      => Yii::t('app','Заявка:'),
            'name'         => 'Ваше ім’я:',
            'company_name' => 'Компанія:',
            'tel'          => 'Телефон:',
            'email'        => 'E-mail:',
            'message'      => 'Заявка:',
        ];
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getTel() {
        return $this->tel;
    }

    public function setTel($tel) {
        $this->tel = $tel;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getMessage(){
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getCompany_name(){
        return $this->company_name;
    }

    public function setCompany_name($company_name) {
        $this->company_name = $company_name;
    }
}
