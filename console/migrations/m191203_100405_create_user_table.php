<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m191203_100405_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(20)->notNull()->unique()->comment('手机号'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('状态0 禁用， 1 启用'),
            'api_token' => $this->string(55)->unique()->notNull(),
            'product_id' => $this->integer()->notNull()->defaultValue(2)->comment('已购买套餐'),
            'user_type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('用户类型1 未付费， 2 已付费'),
            'version' => $this->bigInteger()->defaultValue(0),
            'expired_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('套餐到期时间'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('注册时间'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('更新时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
