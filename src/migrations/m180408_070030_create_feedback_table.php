<?php

use yii\db\Migration;

/**
 * Handles the creation of table `feedback`
 */
class m180408_070030_create_feedback_table extends Migration {

    public $newTableName = 'feedback';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            //ALTER TABLE feedback CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        }

        $newTableName = $this->newTableName;
        if ($this->db->driverName === 'pgsql') {
            $newTableName = Yii::$app->params['schema'] . '.' . $newTableName;
        }

        $this->createTable($newTableName, [
            'id' => $this->primaryKey(),

            'name'         => $this->string(),
            'company_name' => $this->string(),
            'tel'          => $this->string(),
            'email'        => $this->string(100),
            'message'      => 'MEDIUMTEXT',
            'status'       => $this->tinyInteger()->defaultValue(0),
            'created_date' => $this->integer(),
        ], $tableOptions);
    }

    public function safeDown() {
        $newTableName = $this->newTableName;
        if ($this->db->driverName === 'pgsql') {
            $newTableName = Yii::$app->params['schema'] . '.'. $newTableName;
        }

        $this->dropTable($newTableName);
    }
}
