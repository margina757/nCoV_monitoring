<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\auth\GoogleAuthenticatorForm */

$this->title = '重新绑定';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;

$fieldOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

?>

<div class="row">
    <div class="col-sm-4">
        <div class="box box-warning">
            <!-- /.login-logo -->
            <div class="box-body">
                <div class="text-center">
                    <img src="<?=Url::to(['auth/login/render-secret', 'secret' => $model->secret]) ?>">
                </div>

                <p class="login-box-msg">
                    密钥：<?= $model->secret ?>
                </p>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'secret')->hiddenInput()->label(false) ?>

                <?= $form
                    ->field($model, 'code', $fieldOptions)
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('code')]) ?>

                <p class="text-left">
                    请使用身份验证器扫描二维码或输入对应密钥进行绑定，绑定成功后输入生成的动态口令完成设置。二维码5分钟内有效）
                </p>

                <div class="text-center">
                    <?= Html::submitButton('立刻绑定', ['class' => 'btn btn-warning btn-block btn-flat', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
