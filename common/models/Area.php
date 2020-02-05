<?php
namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $name
 */
class Area extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'area';
    }

    public function rules()
    {
        return [
            [['name'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '地区名称',
        ];
    }

    public static function getIdNameMap()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }
}