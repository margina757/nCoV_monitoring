<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_role_permission}}`.
 */
class m180516_020215_create_admin_role_permission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_role_permission}}', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull()->comment('角色id'),
            'permission' => $this->string(128)->notNull()->comment('权限'),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
        ]);

        $this->createIndex('index_role_permission', '{{%admin_role_permission}}', ['role_id', 'permission'], true);
        $this->addForeignKey('fk_admin_role_permission_admin_role', '{{%admin_role_permission}}', 'role_id', '{{%admin_roles}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_role_permission}}');
    }
}
