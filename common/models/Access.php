<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "access_log".
 *
 * @property int $id
 * @property string $name 姓名
 * @property int $card 证件类型0身份证1其他证件
 * @property string $card_detail 证件内容
 * @property int $type 0为进入1为外出
 * @property int $area 地区id
 * @property string $unit 单元号|门牌号
 * @property string $phone 手机号
 * @property int $transport 交通方式0汽车1其他
 * @property string $transport_detail 车牌号
 * @property int $reason 出行事由
 * @property string $reason_detail 具体原因
 * @property int $is_partner 是否为陪同人员0为非陪同
 * @property string $created 创建时间
 */
class Access extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'access_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'area', 'unit', 'transport'], 'required'],
            [['card', 'type', 'area', 'transport', 'reason', 'is_partner'], 'integer'],
            [['created'], 'safe'],
            [['name', 'transport_detail'], 'string', 'max' => 32],
            [['card_detail', 'reason_detail'], 'string', 'max' => 128],
            [['unit'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'card' => '证件类型',//0身份证1其他证件
            'card_detail' => '证件内容',
            'type' => '进出', //0为进入1为外出
            'area' => '地区',
            'unit' => '单元号|门牌号',
            'phone' => '手机号',
            'transport' => '交通方式',//0汽车1其他
            'transport_detail' => '车牌号',
            'reason' => '出行事由',
            'reason_detail' => '具体原因',
            'is_partner' => '是否为陪同人员', //0为非陪同
            'created' => '创建时间',
        ];
    }
}
