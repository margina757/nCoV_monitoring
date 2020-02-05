<?php

use common\models\PackageModel;
use common\models\User;
use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                'phone',
                [
                    'attribute' => 'status',
                    'filter' => User::STATUS_MAP,
                    'value' => function (User $m) {
                        return User::STATUS_MAP[$m->status];
                    }
                ],
                [
                    'attribute' => 'product_id',
                    'value' => function (User $m) {
                        return $m->package->name;
                    },
                    'filter' => ArrayHelper::map(PackageModel::PACKAGE_LIST, 'id', 'name'),
                ],
                [
                    'attribute' => 'user_type',
                    'filter' => User::USER_TYPE_MAP,
                    'value' => function (User $m) {
                        return User::USER_TYPE_MAP[$m->user_type];
                    },
                ],
                [
                    'attribute' => 'expired_at',
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'expired_at',
                        'pluginOptions' => [
                            'locale' => [
                                'separator' => '--',
                                'format' => 'Y-m-d',
                            ]
                        ],
                        'convertFormat' => true,
                    ]),
                ],
                [
                    'attribute' => 'created_at',
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'pluginOptions' => [
                            'locale' => [
                                'separator' => '--',
                                'format' => 'Y-m-d',
                            ]
                        ],
                        'convertFormat' => true,
                    ]),
                ],
                [
                    'attribute' => 'updated_at',
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'updated_at',
                        'pluginOptions' => [
                            'locale' => [
                                'separator' => '--',
                                'format' => 'Y-m-d',
                            ]
                        ],
                        'convertFormat' => true,
                    ]),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                ],
            ],
        ]); ?>
    </div>
</div>
