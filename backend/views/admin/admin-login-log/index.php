<?php

use backend\components\widgets\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\admin\AdminLoginLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '登录日志';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box lw-admin-users-login-log-index">
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'created',
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
                    'attribute' => 'adminUser',
                    'label' => '登录用户',
                    'content' => function($model) {
                        return $model->adminUser->real_name . '<br>' . $model->adminUser->username;
                    }
                ],
                'admin_id',
                'ip',
                [
                    'attribute' => 'address',
                    'label' => '归属地',
                ],
                // 'duration',
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
