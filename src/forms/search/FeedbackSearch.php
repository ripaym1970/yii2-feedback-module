<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.02.2018
 * Time: 10:17
 */

namespace egor260890\feedback\forms\search;

use egor260890\feedback\entities\Feedback;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FeedbackSearch extends Model {

    private $id;
    private $name;
    private $tel;
    private $email;
    private $created_date;
    private $status;
    private $is_send;
    private $company_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status'], 'integer'],
            [['is_send'], 'boolean'],
            [['created_date', 'email', 'company_name'], 'string'],
            [['name', 'tel', 'created_date', 'status', 'email'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Feedback::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            //$query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'      => $this->id,
            'status'  => $this->status,
            'is_send' => $this->is_send,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'company_name', $this->company_name]);

        $query->andFilterWhere(['>=', 'created_date', $this->created_date ? strtotime($this->created_date . ' 00:00:00') : null]);
        $query->andFilterWhere(['<=', 'created_date', $this->created_date ? strtotime($this->created_date . ' 23:59:59') : null]);

        return $dataProvider;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
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

    public function getCreated_date() {
        return $this->created_date;
    }

    public function setCreated_date($created_date) {
        $this->created_date = $created_date;
    }
    public function getIs_send() {
        return $this->is_send;
    }

    public function setIs_send($is_send) {
        $this->is_send = $is_send;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getCompany_name() {
        return $this->company_name;
    }

    public function setCompany_name($company_name) {
        $this->company_name = $company_name;
    }
}
