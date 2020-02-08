<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_token}}`.
 */
class m200204_073742_create_access_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        \Yii::$app->db->createCommand("CREATE TABLE `access_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(32) NOT NULL COMMENT '姓名',
            `card` tinyint(1) NOT NULL DEFAULT '0' COMMENT '证件类型0身份证1其他证件',
            `card_detail` varchar(128) NOT NULL DEFAULT '' COMMENT '证件内容',
            `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为进入1为外出',
            `area` smallint(6) NOT NULL COMMENT '地区id',
            `unit` varchar(64) NOT NULL COMMENT '单元号|门牌号',
            `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
            `transport` tinyint(1) NOT NULL COMMENT '交通方式0汽车1其他',
            `transport_detail` varchar(32) DEFAULT '' COMMENT '车牌号',
            `reason` smallint(4) NOT NULL DEFAULT '0' COMMENT '出行事由',
            `reason_detail` varchar(128) DEFAULT '' COMMENT '具体原因',
            `is_partner` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为陪同人员0为非陪同',
            `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('access_log');
    }
}
