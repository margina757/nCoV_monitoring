<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ssr_nodes}}`.
 */
class m191204_014416_create_ssr_nodes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ssr_nodes}}', [
            'id' => $this->primaryKey(),
            'protocol' => $this->string(32)->notNull()->defaultValue('')->comment('协议类型'),
            'server_ip' => $this->string(64)->notNull()->comment('ssr服务器IP'),
            'server_port' => $this->smallInteger()->unsigned()->notNull()->comment('ssr服务器端口'),
            'password' => $this->string()->notNull()->comment('ssr服务器连接密码'),
            'confuse_mode' => $this->string(32)->notNull()->defaultValue('')->comment('ssr服务器连接混淆模式'),
            'encrypt_mode' => $this->string(32)->notNull()->comment('ssr服务器连接加密模式'),
            'national_flag' => $this->string()->notNull()->defaultValue('')->comment('服务器所在国图标'),
            'country' => $this->string(32)->notNull()->defaultValue('')->comment('服务器所在国家'),
            'city' => $this->string(32)->notNull()->defaultValue('')->comment('服务器所在城市'),
            'delay' => $this->smallInteger()->notNull()->defaultValue(0)->comment('延时时间(ms)'),
            'forbid_ping' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('节点是否防ping'),
            'token_verify' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('ss节点不需要验vpnServerToken'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ssr_nodes}}');
    }
}
