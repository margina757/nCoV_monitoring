<?php

namespace backend\models\admin;

use backend\models\auth\AdminRolePermission;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%admin_roles}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property string $updated
 * @property integer $status
 *
 * @property AdminUsers $adminUsers
 */
class AdminRoles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_roles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name'], 'string', 'max' => 16],
            [['name'], 'unique', 'message' => '该角色已存在'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色',
            'created' => '创建时间',
            'updated' => '修改时间',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return AdminRolesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminRolesQuery(get_called_class());
    }


    /**
     * 获取用户组列表
     *
     * @param $role_id
     * @return array
     */
    public static function getAdminRolesList($role_id = null)
    {
        return ArrayHelper::map(self::find()
            ->select(['id', 'name'])
            ->where($role_id === null ? [] : ['id' => $role_id])
            ->asArray()
            ->all(), 'id', 'name');
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        if ($this->id === 1) {
            $this->addError('id', '不能删除超级管理员');
        }
        if (AdminUsers::find()->where(['role_id' => $this->id])->active()->count() > 0) {
            $this->addError('id', '该用户组成员不为空');
        }
        if (!$this->hasErrors()) {
            AdminRolePermission::deleteAll(['role_id' => $this->id]);
            return parent::delete();
        }
        return false;
    }

    /**
     * Relation with AdminUsers
     */
    public function getAdminUsers()
    {
        return $this->hasMany(AdminUsers::class, ['role_id' => 'id']);
    }
}

/**
 * This is the ActiveQuery class for [[AdminRoles]].
 *
 * @see AdminRoles
 */
class AdminRolesQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return AdminRoles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminRoles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
