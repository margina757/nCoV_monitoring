<?php

use backend\components\widgets\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\admin\AdminOperateLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modules array */

$this->title = '操作日志';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box lw-admin-users-operate-log-index">
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'created',
                    'options' => ['class' => 'col-sm-2'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created',
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 10,
                            'locale' => [
                                'format'=>'YYYY-MM-DD HH:mm:ss',
                            ],
                            'maxDate' => date('Y-m-d 23:59:59'),
                        ]
                    ]),
                ],
                [
                    'attribute' => 'module',
                    'options' => ['class' => 'col-sm-1'],
                    'value' => function($model) use ($modules) {
                        return $modules[$model->module] ?? $model->module;
                    },
                    'filter' => $modules,
                ],
                'log:raw',
                [
                    'attribute' => 'admin_id',
                    'label' => '操作用户',
                    'options' => ['class' => 'col-sm-1'],
                    'content' => function($model) {
                        return $model->adminUser->real_name . '<br>' . $model->adminUser->username;
                    }
                ],
                [
                    'attribute' => 'ip',
                    'options' => ['class' => 'col-sm-1'],
                    'content' => function($model) {
                        return $model->ip . ' ' . $model->country;
                    }
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
