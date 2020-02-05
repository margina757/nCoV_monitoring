<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\admin\AdminRoles */

$this->title = '添加角色';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-success lw-admin-roles-create">
    <div class="box-body">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-3">
                <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.box-body -->
</div>
