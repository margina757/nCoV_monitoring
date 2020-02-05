<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m191203_084307_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->string(64)->unique()->notNull(),
            'order_id' => $this->integer()->unsigned()->notNull()->comment('订单id'),
            'status' => $this->smallInteger()->unsigned()->defaultValue(0)->comment('1:完成,2:退款'),
            'amount' => $this->integer()->unsigned()->notNull()->comment('金额 分'),
            'method' => $this->string(32)->notNull()->comment('支付方式'),
            'finish_at' => $this->dateTime(),
            'created' => $this->dateTime(),
            'updated' => $this->dateTime(),
        ], CREATE_TABLE_OPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment}}');
    }
}
