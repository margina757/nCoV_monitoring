<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SsrNodes */

$this->title = '添加节点';
$this->params['breadcrumbs'][] = ['label' => '节点管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssr-nodes-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
