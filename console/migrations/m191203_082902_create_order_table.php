<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m191203_082902_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'trade_no' => $this->string(64)->unique()->comment('订单唯一标识符'),
            'out_trade_no' => $this->string(64)->comment('外部订单'),
            'uid' => $this->integer()->unsigned()->notNull(),
            'amount' => $this->integer()->unsigned()->comment('金额 单位（分）'),
            'status' => $this->smallInteger()->unsigned()->defaultValue(0)->comment('支付状态 0:未支付 1:已支付 2:退款'),
            'days' => $this->integer()->unsigned()->comment('天数'),
            'package_id' => $this->integer()->unsigned()->defaultValue(0)->comment('套餐id'),
            'version' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'finish_at' => $this->dateTime(),
            'created' => $this->dateTime(),
            'updated' => $this->dateTime(),
        ], CREATE_TABLE_OPTION);

        $this->createIndex('out_trade_no', \common\models\Order::tableName(), 'out_trade_no');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
    }
}
