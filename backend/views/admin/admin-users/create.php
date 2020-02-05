<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\admin\RegisterForm */
/* @var $role_list array */

$this->title = '添加账号';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = ['label' => '管理员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-success admin-users-create">
    <div class="box-body">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

        <?= $form->field($model, 'username')->textInput()->hint('请填写公司邮箱') ?>

        <?= $form->field($model, 'real_name')->textInput() ?>

        <?= $form->field($model, 'mail')->textInput() ?>

        <?= $form->field($model, 'phone')->textInput() ?>

        <?= $form->field($model, 'role_id')->dropDownList($role_list) ?>

        <?= $form->field($model, 'password')->passwordInput()->hint('密码至少16位，且包含一个数字、一个大写字母、一个小写字母和一个英文半角符号') ?>

        <?= $form->field($model, 'confirm_password')->passwordInput() ?>

        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-3">
                <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.box-body -->
</div>
