<?php

use yii\helpers\Html;
use backend\components\widgets\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\admin\AdminRolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '角色管理';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lw-admin-roles-index box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'buttons' => Html::a('添加角色', ['create'], ['class' => 'btn btn-success']),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                [
                    'label' => '账号数量',
                    'content' => function($model) {
                        return count($model->adminUsers);
                    }
                ],
                [
                    'label' => '账号',
                    'content' => function($model) {
                        return Html::encode(implode(' ', ArrayHelper::getColumn($model->adminUsers, 'real_name')));
                    }
                ],

                [
                    'content' => function($model) {
                        // 超级管理员不能编辑
                        if ($model->id == 1) {
                            return Html::button('系统默认', [
                                'class' => 'btn btn-default btn-sm',
                                'disabled' => 'disabled',
                            ]);
                        }
                        $return = '<div class="btn-group">';
                        $return .= Html::a('权限', Url::to(['admin/role-auth/index', 'role_id' => $model->id]), [
                            'class' => 'btn btn-primary btn-sm',
                        ]);
                        $return .= Html::button('删除', [
                            'class' => 'btn btn-danger btn-sm delete-button',
                            'data-id' => $model->id,
                            'data-name' => $model->name,
                        ]);
                        $return .= '</div>';
                        return $return;
                    }
                ],
            ],
        ]); ?>
    </div>
</div>

<script>
    // 删除角色
    $('.delete-button').on('click', function (event) {
        let id = $(this).data('id');
        let name = $(this).data('name');
        swal({
            title: '确认删除',
            text: "即将删除<code>" + name + "</code>群组<br>该操作将不可撤销，请确认您的操作。",
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
                url: '<?= Url::to(['admin/admin-roles/delete']) ?>',
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