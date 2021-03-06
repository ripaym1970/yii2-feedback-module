<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.02.2018
 * Time: 14:19
 */

namespace egor260890\feedback\entities;

use yii\db\ActiveRecord;



/**
 * Class Feedback
 * @package core\entities
 *
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property string $message
 * @property string $company_name
 * @property string $created_date
 * @property integer $status
 *
 */
class Feedback extends ActiveRecord implements FeedbackInterface
{

    const STATUS_UNREVIEWED=0;
    const STATUS_VIEWED=1;

    public static function create($name,$tel,$email,$message,$company_name):self
    {
        $feedback=new static();
        $feedback->name=$name;
        $feedback->tel=$tel;
        $feedback->email=$email;
        $feedback->message=$message;
        $feedback->company_name=$company_name;
        $feedback->status=self::STATUS_UNREVIEWED;
        $feedback->created_date=date('U');
        return $feedback;
    }

    public function edit($name,$tel,$email,$message,$company_name){
        $this->name=$name;
        $this->tel=$tel;
        $this->email=$email;
        $this->message=$message;
        $this->company_name=$company_name;
    }

    public function isViewed():bool
    {
        return $this->status==self::STATUS_VIEWED;
    }

    public function isUnreviewed():bool
    {
        return $this->status==self::STATUS_UNREVIEWED;
    }

    public function viewed(){
        $this->status=self::STATUS_VIEWED;
    }

    public function unreviewed(){
        $this->status=self::STATUS_UNREVIEWED;
    }

    public function attributeLabels()
    {
        return [
            'id'=>'id',
            'name'=>'Имя',
            'company_name'=>'Название компании',
            'tel'=>'Телефон',
            'status'=>'Статус',
            'created_date'=>'Дата создания',
            'email'=>'E-mail',
            'message'=>'Сообщение'
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_DELETE,
            self::SCENARIO_DEFAULT=>self::OP_INSERT
        ];
    }

    public static function tableName():string
    {
        return '{{%feedback}}'; // TODO: Change the autogenerated stub
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getTel(): string
    {
        return $this->tel;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCompany_name(): string
    {
        return $this->company_name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

}