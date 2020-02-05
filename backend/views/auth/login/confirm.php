<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\auth\GoogleAuthenticatorForm */

$this->title = '两步验证';

$fieldOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputOptions' => ['autofocus' => 'autofocus'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><?= Yii::$app->name ?></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form
            ->field($model, 'code', $fieldOptions)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('code')]) ?>

        <div class="text-center">
            <?= Html::submitButton('进入后台', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
        </div>
        <div class="social-auth-links text-center">
            <a href="/" class="text-center">取消</a>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
