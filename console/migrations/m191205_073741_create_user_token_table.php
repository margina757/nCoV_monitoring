<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_token}}`.
 */
class m191205_073741_create_user_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_token}}', [
            'id' => $this->bigPrimaryKey(),
            'api_token' => $this->string(55)->notNull()->unique(),
            'user_id' => $this->integer()->comment('用户id')->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('user_token_user_id', '{{%user_token}}', ['user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_token}}');
    }
}
