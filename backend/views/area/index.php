<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\base\DynamicModel;
use backend\components\widgets\grid\GridView;


$this->title = '地区管理';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $dataProvider
 * @var $dynamicModel
 * @var $column
 */
?>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $dynamicModel,
    'buttons' => Html::a('<i class="fa fa-user-plus"></i> 添加地区', ['create'], ['class' => 'btn btn-success']),
    'columns' => array_merge($column, [[
        'content' => function($model) {
                $return = '<div class="btn-group">';
                $return .= Html::button('编辑', [
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'modal',
                    'data-target' => '#edit-modal',
                    'data-id' => $model->id,
                    'data-name' => $model->name,

                ]);
                $return .= Html::button('删除', [
                    'class' => 'btn btn-danger btn-sm delete-button',
                    'data-id' => $model->id,
                    'data-name' => $model->name,

                ]);
                $return .= '</div>';
                return $return;
        }
    ]]),
]) ?>

<div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="form-edit">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">编辑</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="inputId" name="id">

                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">地区名称</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="inputName" name="name">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit-edit">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /编辑账号 -->

<script>
$('#edit-modal').on('shown.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        $('#inputId').val(button.data('id'));
        $('#inputName').val(button.data('name'));
    });
    // 提交编辑账号
    $('#submit-edit').on('click', function () {
        swal({
            title: '确认修改',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "提交",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            closeOnCancel: true,
            showLoaderOnConfirm: true,
        },function(){
            let data = {
                id: $('#inputId').val(),
                name: $('#inputName').val(),
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(data),
                url: '<?= Url::to(['area/update']) ?>',
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
                url: '<?= Url::to(['area/delete']) ?>',
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