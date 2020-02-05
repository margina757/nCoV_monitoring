<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_login_log}}`.
 */
class m180516_021849_create_admin_login_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_login_log}}', [
            'id' => $this->primaryKey(),
            'admin_id' => $this->integer()->notNull()->comment('管理员id'),
            'role_id' => $this->integer()->notNull()->comment('用户组'),
            'ip' => $this->string(39)->notNull()->comment('登录ip'),
            'address' => $this->string(64)->comment('地址'),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('登录时间'),
            'duration' => $this->integer()->comment('保持时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_login_log}}');
    }
}
