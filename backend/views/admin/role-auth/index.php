<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View;
 * @var $permission_modules array
 * @var $roles array
 * @var $permissions array
 * @var $role_permission array
 */

$this->title = '权限管理';
$this->params['breadcrumbs'][] = '后台管理';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-primary">
    <div class="box-body">
        <div class="panel panel-default">
            <table class="table table-hover table-bordered table-responsive">
                <thead>
                <tr>
                    <th>权限</th>
                    <?php
                    foreach ($roles as $role_id => $role):
                        // 超级管理员权限不能编辑
                        if ($role_id == 1) continue;
                    ?>
                        <th data-id=<?= $role_id ?>><?= $role ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($permission_modules as $module): ?>
                    <tr class="info">
                        <td><?= $module ?></td>
                    </tr>
                    <?php foreach ($permissions as $permission): ?>
                        <?php if ($module == $permission['module']): ?>
                            <tr data-id="<?= $permission['key'] ?>">
                                <td><?= $permission['permission'] ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="panel-footer">
                <button id="j-btn-save" class="btn btn-primary" type="button">保存修改</button>
            </div>
    </div>
</div>

<script type="text/javascript">
    var role_permission = <?= Json::encode($role_permission) ?>;

    $(function() {
        (function() {
            var identity = {
                init: function() {
                    var that = this;
                    var count = $('.table th').length;    //获取要循环的列数
                    $('.table tbody tr').each(function(index, ele) {
                        var id = $(this).attr('data-id');

                        for(var i = 1; i < count; i++) {
                            that._createNodes(id).appendTo($(this));
                        }
                    });

                    that._query();
                },
                //获取需要选中的项
                _query: function() {
                    var that = this;
                    for(var attr in role_permission) {
                        var index = $('th[data-id="'+ attr + '"]').index();  //用户在表格中的列号
                        that._alreadyChecked(index, role_permission[attr]);
                    }
                },
                //生成表格中的input
                _createNodes: function(id) {
                    var $td = $('<td>', {'data-id': id});
                    var $input = $('<input/>', {'type': 'checkbox'});

                    $td.append($input);

                    return $td;
                },
                //标记已有的权限
                _alreadyChecked: function(pos, data) {
                    //第pos列 下的 tr的data-id = data
                    $.each(data, function(i, val) {
                        $('.table tbody tr[data-id="' + val + '"]').find('td').eq(pos)
                            .find('input').prop('checked', true);
                    });

                },
                multipleCheck: function($obj, index) {
                    var status = $obj.find('input').prop('checked');     //选中状态
                    var allChecks = $obj.parent('tr').nextUntil('.info')
                        .find('td:eq(' + index + ')').find('input');

                    allChecks.prop('checked', status);
                },
                getAllCheckbox: function() {
                    var json = {};
                    var len = $('table th[data-id]').length;
                    var length = $('table tbody tr:not(".info")').length;

                    for (var i = 0; i < len; i++) {     //第i列
                        var data = {};

                        for (var j = 0; j < length; j++) {  //第j行
                            var $obj = $('table tbody tr:not(".info")').eq(j).find('td:gt(0)').eq(i);
                            var status = $obj.children('input').prop('checked');

                            if (status) {
                                data[$obj.attr('data-id')] = 1;
                            } else {
                                data[$obj.attr('data-id')] = 0;
                            }
                        }

                        if(data) {
                            var id = $('table th[data-id]').eq(i).attr('data-id');
                            json[id] = data;
                        }
                    }

                    return json;
                }
            };
            //页面初始化
            identity.init();
            //批量选取
            $('.info').on('change', 'td', function() {
                var index = $(this).index();
                identity.multipleCheck($(this), index);

            });
            //保存
            $('#j-btn-save').on('click', function() {
                let json = identity.getAllCheckbox();
                console.log(json);
                swal({
                    title: '确认修改',
                    text: "修改后相应人员将立刻无法访问",
                    type: "warning",
                    html: true,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    cancelButtonText: "取消",
                    closeOnConfirm: false,
                    closeOnCancel: true,
                    showLoaderOnConfirm: true,
                },function(){
                    $.ajax({
                        type: 'POST',
                        data: JSON.stringify({data: json}),
                        url: '<?= Url::to(['admin/role-auth']) ?>',
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
        })();
    });

</script>