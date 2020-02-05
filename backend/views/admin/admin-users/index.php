<?php

use yii\helpers\Html;
use backend\components\widgets\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\admin\AdminUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $role_list array */
/* @var $status_list array */
/* @var $resetModel \backend\models\admin\ResetPasswordForm */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lw-admin-users-index box">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'buttons' => Html::a('<i class="fa fa-user-plus"></i> 添加账号', ['create'], ['class' => 'btn btn-success']),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'real_name',
                'username',
                [
                    'attribute' => 'role_id',
                    'value' => function($model) use ($role_list) {
                        return $role_list[$model->role_id];
                    },
                    'filter' => $role_list,
                ],
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
                            'maxDate' => date('Y-m-d H:i:s'),
                        ]
                    ]),
                ],

                [
                    'content' => function($model) {
                        if ($model->id == 1) {
                            return Html::button('系统账号', [
                                'class' => 'btn btn-default btn-sm',
                                'disabled' => 'disabled',
                            ]);
                        } else {
                            $return = '<div class="btn-group">';
                            $return .= Html::button('编辑', [
                                'class' => 'btn btn-primary btn-sm',
                                'data-toggle' => 'modal',
                                'data-target' => '#edit-modal',
                                'data-uid' => $model->id,
                                'data-real_name' => Html::encode($model->real_name),
                                'data-username' => Html::encode($model->username),
                                'data-role_id' => $model->role_id,
                                'data-mail' => $model->mail,
                                'data-phone' => $model->phone,
                            ]);
                            $return .= Html::button('修改密码', [
                                'class' => 'btn btn-warning btn-sm',
                                'data-toggle' => 'modal',
                                'data-target' => '#reset-modal',
                                'data-uid' => $model->id,
                            ]);
                            $return .= Html::button('删除', [
                                'class' => 'btn btn-danger btn-sm delete-button',
                                'data-uid' => $model->id,
                                'data-username' => Html::encode($model->username),
                                'data-real_name' => Html::encode($model->real_name),
                            ]);
                            $return .= '</div>';
                            return $return;
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>

<!-- 编辑账号 -->
<div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="form-edit">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">编辑</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="inputUid" name="uid">

                    <div class="form-group">
                        <label for="inputRealName" class="col-sm-2 control-label">真实姓名</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="inputRealName"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputUsername" class="col-sm-2 control-label">账号</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="inputUsername"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputRole" class="col-sm-2 control-label">角色</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="inputRole" name="role_id">
                                <?php foreach ($role_list as $role_id => $role_name): ?>
                                    <option value="<?= $role_id ?>"><?= $role_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputMail" class="col-sm-2 control-label">邮箱</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="inputMail" name="mail">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPhone" class="col-sm-2 control-label">手机</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="inputPhone" name="phone">
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

<!-- 重置验证 -->
<div class="modal fade" tabindex="-1" role="dialog" id="reset-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'id' => $resetModel->formName(),
                'fieldConfig' => [
                    'hintOptions' => ['class' => 'help-block col-sm-6 col-sm-offset-3'],
                ],
            ]); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">修改密码</h4>
                </div>
                <div class="modal-body">

                    <?= $form->field($resetModel, 'uid')->hiddenInput()->label(false) ?>

                    <?= $form->field($resetModel, 'password')->passwordInput()->hint('密码至少16位，且包含一个数字、一个大写字母、一个小写字母和一个英文半角符号') ?>

                    <?= $form->field($resetModel, 'confirm_password')->passwordInput() ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="submit-reset">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!-- /重置验证 -->

<script type="application/javascript">
    // 编辑账号
    $('#edit-modal').on('shown.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        $('#inputUid').val(button.data('uid'));
        $('#inputRole').val(button.data('role_id'));
        $('#inputMail').val(button.data('mail'));
        $('#inputPhone').val(button.data('phone'));
        $('#inputRealName').html(button.data('real_name'));
        $('#inputUsername').html(button.data('username'));
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
                uid: getModalInputVal('#form-edit', 'uid'),
                role_id: getModalSelectVal('#form-edit', 'role_id'),
                mail: getModalInputVal('#form-edit', 'mail'),
                phone: getModalInputVal('#form-edit', 'phone'),
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(data),
                url: '<?= Url::to(['admin/admin-users/edit']) ?>',
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

    // 重置验证
    $('#reset-modal').on('shown.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let uid = button.data('uid');
        $('#resetpasswordform-uid').val(uid);
    });
    // 提交重置验证
    $('#submit-reset').on('click', function () {
        // todo
        if ($('#<?= $resetModel->formName() ?>').yiiActiveForm('validate', true)) {
            return;
        }

        let data = {
            uid: getModalInputVal('#<?= $resetModel->formName() ?>', '<?= $resetModel->formName() ?>[uid]'),
            password: getModalInputVal('#<?= $resetModel->formName() ?>', '<?= $resetModel->formName() ?>[password]'),
            confirm_password: getModalInputVal('#<?= $resetModel->formName() ?>', '<?= $resetModel->formName() ?>[confirm_password]'),
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(data),
            url: '<?= Url::to(['admin/admin-users/reset']) ?>',
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

    // 删除用户
    $('.delete-button').on('click', function (event) {
        let uid = $(this).data('uid');
        let username = $(this).data('username');
        let real_name = $(this).data('real_name');
        swal({
            title: '确认删除',
            text: "即将删除<code>" + real_name + "</code>的账号<code>" + username + "</code><br>该操作将<b>不可撤销</b>，请确认您的操作",
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
                uid: uid,
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(data),
                url: '<?= Url::to(['admin/admin-users/delete']) ?>',
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
