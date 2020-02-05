<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_users}}`.
 */
class m180516_012529_create_admin_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull()->unique()->comment('账号'),
            'real_name' => $this->string(16)->notNull()->comment('真实姓名'),
            'password' => $this->string(60)->notNull()->comment('密码'),
            'secret' => $this->string(16)->comment('两步验证秘钥'),
            'auth_key' => $this->string(32)->comment('认证密钥'),
            'role_id' => $this->integer()->notNull()->comment('角色'),
            'mail' => $this->string()->comment('邮箱'),
            'phone' => $this->string(11)->comment('手机'),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
            'updated' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('修改时间'),
            'status' => "TINYINT(3) NOT NULL DEFAULT 1 COMMENT '用户状态'",
        ]);

        $this->addForeignKey('fk_admin_users_admin_roles', '{{%admin_users}}', 'role_id', '{{%admin_roles}}', 'id', 'NO ACTION');

        $this->insert('{{%admin_users}}', [
            'username' => 'system@admin.com',
            'real_name' => '系统',
            'password' => Yii::$app->security->generatePasswordHash(Yii::$app->db->password),
            'role_id' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_users}}');
    }
}
