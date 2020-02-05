<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\admin\RegisterForm */
/* @var $role_list array */

$this->title = '添加地区';
$this->params['breadcrumbs'][] = '地区管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-success admin-users-create">
    <div class="box-body">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

        <?= $form->field($model, 'name')->textInput()->hint('地区名称') ?>

        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-3">
                <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.box-body -->
</div>
