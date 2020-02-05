<?php

use yii\helpers\Html;
use backend\components\widgets\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use common\models\Area;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\admin\AdminUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $role_list array */
/* @var $status_list array */
/* @var $resetModel \backend\models\admin\ResetPasswordForm */

$this->title = '出入信息';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;

$cardArr = ['身份证', '其他'];
$typeArr = ['进', '出'];
$areaMap = Area::getIdNameMap();
$transportArr = ['汽车', '其他'];
$reasonArr = ['采购', '工作','其他'];
$isPartnerArr = ['是', '否']
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $dynamicModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'attribute' => 'card_detail',
            'value' => function($model) use ($cardArr) {
                return $cardArr[$model->card]."|".$model->card_detail;
            },
        ],
        [
            'attribute' => 'type',
            'value' => function($model) use ($typeArr) {
                return $typeArr[$model->type];
            },
            'filter' => $typeArr,
        ],
        [
            'attribute' => 'area',
            'value' => function($model) use ($areaMap) {
                 return $areaMap[$model->area]."|".$model->unit;
            },
            'filter' => $areaMap,
        ],
        'phone',
        [
            'attribute' => 'transport_detail',
            'value' => function($model) use ($transportArr) {
                if ($model->transport == 0) {
                    return $transportArr[$model->transport]."|".$model->transport_detail;
                }
                return $transportArr[$model->transport];

            },
        ],
        [
            'attribute' => 'reason_detail',
            'value' => function($model) use ($reasonArr) {
                 return $reasonArr[$model->reason].'|'.$model->reason_detail;
            },
            'filter' => false
        ],
        [
            'attribute' => 'is_partner',
            'value' => function($model) {
                return $model->is_partner == 0 ? '否' : '是';
            }
        ],
        [
            'attribute' => 'created',
            'filter' => DateRangePicker::widget([
                'model' => $dynamicModel,
                'attribute' => 'created',
                'pluginOptions' => [
                    'timePicker' => true,
                    'timePickerIncrement' => 10,
                    'locale' => [
                        'format'=>'YYYY-MM-DD HH:mm:ss',
                    ],
                    'maxDate' => date('Y-m-d H:i:s'),
                ]
            ]),
        ],
        [
            'content' => function($model) {
                $return = '<div class="btn-group">';
                // $return .= Html::button('编辑', [
                //     'class' => 'btn btn-primary btn-sm',
                //     'data-toggle' => 'modal',
                //     'data-target' => '#edit-modal',
                //     'data-id' => $model->id,
                //     'data-name' => $model->name,

                // ]);
                $return .= Html::button('删除', [
                    'class' => 'btn btn-danger btn-sm delete-button',
                    'data-id' => $model->id,
                    'data-name' => $model->name,

                ]);
                $return .= '</div>';
                return $return;
            }
        ]
    ],
]) ?>

<script>
    $('.delete-button').on('click', function (event) {
        let id = $(this).data('id');
        let name = $(this).data('name');
        swal({
            title: '确认删除',
            text: "即将删除<code>" + name + "</code><br>该操作将<b>不可撤销</b>，请确认您的操作",
            type: "warning",
            html: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: true,
            showLoaderOnConfirm: true,
        },function(){
            let data = {
                id: id,
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(data),
                url: '<?= Url::to(['access/delete']) ?>',
                contentType: 'application/json',
                success : function (res) {
                    if (res.code == 0) {
                        swal({title: "成功", text: "", type: "success"}, function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal({title: "失败", text: res.data.error, type: "error"});
                    }
                }
            })
        });
    });
</script>