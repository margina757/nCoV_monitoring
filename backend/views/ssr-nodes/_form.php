<?php

use common\models\SsrNodes;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SsrNodes */
/* @var $form kartik\form\ActiveForm */

$format = <<< SCRIPT
function format(state) {
    if (!state.id) return state.text; // optgroup
    return '<img class="flag" src="' + state.id + '"/> ' + state.text;
}
SCRIPT;
$escape = new JsExpression("function(m) { return m; }");
$this->registerJs($format, \yii\web\View::POS_HEAD);
'<label class="control-label">Provinces</label>';
?>
<style>
    .flag {
        max-height: 25px;
        max-width: 35px;
    }
</style>
<div class="ssr-nodes-form box box-primary">
    <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
    ]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'protocol')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'server_ip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'server_port')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'confuse_mode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'encrypt_mode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'national_flag')->widget(Select2::class, [
            'attribute' => 'national_flag',
            'data' => \common\services\CountryFlagService::getFlagList(),
            'options' => ['placeholder' => '选择国旗'],
            'pluginOptions' => [
                'templateResult' => new JsExpression('format'),
                'templateSelection' => new JsExpression('format'),
                'escapeMarkup' => $escape,
                'allowClear' => true
            ],
        ]) ?>

        <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'delay')->textInput() ?>

        <?= $form->field($model, 'forbid_ping')->dropDownList(SsrNodes::FORBID_PING_MAP) ?>

        <?= $form->field($model, 'token_verify')->dropDownList(SsrNodes::TOKEN_VERIFY_MAP) ?>

    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
