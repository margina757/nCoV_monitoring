<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_roles}}`.
 */
class m180516_012429_create_admin_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(16)->notNull()->unique()->comment('角色'),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
            'updated' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('修改时间'),
            'status' => "TINYINT(3) NOT NULL DEFAULT 1 COMMENT '状态'",
        ]);

        $this->insert('{{%admin_roles}}', ['name' => '超级管理员']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_roles}}');
    }
}
