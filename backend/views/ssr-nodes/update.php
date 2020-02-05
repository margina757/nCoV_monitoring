<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SsrNodes */

$this->title = '编辑节点';
$this->params['breadcrumbs'][] = ['label' => '节点管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="ssr-nodes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
