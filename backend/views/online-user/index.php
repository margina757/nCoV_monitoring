<?php
use common\models\User;
use kartik\grid\GridView;
$this->title="在线用户";
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'uid',
            'label' => 'uid',
        ],
        [
            'label' => '手机号',
            'value' => function($model) {
                return $model->user->phone;
            },
        ],
        [
            'label' => '用户卡品',
            'value' => function($model) {
                return $model->user->package->name;
            },
        ],
        [
            'label' => '用户类型',
            'value' => function($model) {
                return $model->user->user_type == 1 ? '免费' : '付费';
            },
        ],
        [
            'label' => '到期时间',
            'value' => function($model) {
                return $model->user->expired_at;
            },
        ],
        [
            'label' => '上次心跳时间',
            'value' => function($model) {
                return $model->reportDate ? : '';
            },
        ],
        [
            'label' => '使用线路',
            'value' => function($model) {
                return $model->nodeName;
            },
        ],
    ],
]) ?>