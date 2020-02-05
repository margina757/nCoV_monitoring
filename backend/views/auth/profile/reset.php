<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\admin\ResetPasswordForm */

$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-warning">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'id' => $model->formName(),
                    'fieldConfig' => [
                        'hintOptions' => ['class' => 'help-block col-sm-6 col-sm-offset-3'],
                    ],
                ]); ?>

                <?= $form->field($model, 'password')->passwordInput()->hint('密码至少16位，且包含一个数字、一个大写字母、一个小写字母和一个英文半角符号') ?>

                <?= $form->field($model, 'confirm_password')->passwordInput() ?>

                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-3">
                        <?= Html::button('修改', ['class' => 'btn btn-warning', 'id' => 'submit-reset']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>

<script type="application/javascript">
    // 提交重置验证
    $('#submit-reset').on('click', function () {
        let data = {
            password: getModalInputVal('#<?= $model->formName() ?>', '<?= $model->formName() ?>[password]'),
            confirm_password: getModalInputVal('#<?= $model->formName() ?>', '<?= $model->formName() ?>[confirm_password]'),
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(data),
            url: '<?= Url::to(['auth/user/reset-password']) ?>',
            contentType: 'application/json',
            success : function (res) {
                if (res.code == 0) {
                    swal({title: "成功", text: "请重新登录", type: "success"}, function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });
                } else {
                    swal({title: "失败", text: res.data.error, type: "error"});
                }
            }
        })
    });
</script>
