<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_operate_log}}`.
 */
class m180516_022858_create_admin_operate_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_operate_log}}', [
            'id' => $this->primaryKey(),
            'admin_id' => $this->integer()->notNull()->comment('管理员id'),
            'ip' => $this->string(39)->notNull()->comment('操作ip'),
            'country' => $this->string(64)->comment('地址'),
            'module' => $this->smallInteger()->notNull()->comment('操作项'),
            'target_id' => $this->integer()->comment('目标对象id'),
            'log' => $this->text()->comment('日志'),
            'reason' => $this->string()->comment('备注'),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('操作时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_operate_log}}');
    }
}
