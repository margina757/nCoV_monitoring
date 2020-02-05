<?php

use common\models\SsrNodes;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SsrNodesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '节点管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssr-nodes-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('添加节点', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                'protocol',
                'server_ip',
                'server_port',
                'password',
                'confuse_mode',
                'encrypt_mode',
                'country',
                'city',
                'delay',
                [
                    'attribute' => 'forbid_ping',
                    'filter' => SsrNodes::FORBID_PING_MAP,
                    'value' => function (SsrNodes $model) {
                        return SsrNodes::FORBID_PING_MAP[$model->forbid_ping];
                    },
                ],
                [
                    'attribute' => 'token_verify',
                    'filter' => SsrNodes::TOKEN_VERIFY_MAP,
                    'value' => function (SsrNodes $model) {
                        return SsrNodes::TOKEN_VERIFY_MAP[$model->token_verify];
                    },
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'options' => ['class' => 'col-md-1'],
                ],
            ],
        ]); ?>
    </div>
</div>
