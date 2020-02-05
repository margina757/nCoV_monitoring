<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \backend\models\admin\AdminUsers */
/* @var $login_history \backend\models\admin\AdminLoginLog[] */
/* @var $operate_log \backend\models\admin\AdminOperateLog[] */
/* @var $operate_log_label array */

$this->title = '个人中心';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <h3 class="profile-username text-center"><?= $model->real_name ?></h3>
                <p class="text-muted text-center"><?= $model->role->name ?></p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>账号：</b> <?= $model->username ?> <a class="pull-right" href="<?= Url::to(['auth/profile/reset-password']) ?>">修改密码</a>
                    </li>
                    <li class="list-group-item">
                        <b>邮箱：</b> <?= $model->mail ?>
                    </li>
                    <li class="list-group-item">
                        <b>手机：</b> <?= $model->phone ?>
                    </li>
                </ul>
            </div>
        </div>
        <?php if ($model->role_id == 1): // 超级管理员 ?>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">高级功能</h3>
            </div>
            <div class="box-body">
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-sm-8">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#login-log" data-toggle="tab" aria-expanded="true">登录日志</a></li>
                <li class=""><a href="#opeate-log" data-toggle="tab" aria-expanded="false">操作日志</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="login-log">
                    <ul class="timeline">
                        <?php
                        $date = '';
                        foreach ($login_history as $item):
                            if ($date != date('Y-m-d', strtotime($item['created']))):
                                $date = date('Y-m-d', strtotime($item['created']));

                                $during = (time() - strtotime($date)) / (3600 * 24);
                                if ($during < 3):
                                    $color = 'purple';
                                elseif ($during < 7):
                                    $color = 'blue';
                                elseif ($during < 30):
                                    $color = 'aqua';
                                else:
                                    $color = 'yellow';
                                endif;
                        ?>
                        <li class="time-label">
                            <span class="bg-<?= $color ?>"><?= $date ?></span>
                        </li>
                        <?php endif; ?>
                        <li>
                            <i class="fa fa-user bg-<?= (strpos($item['address'], '河南') !== false || !filter_var($item['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) ? 'green' : 'red' ?>"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i><?= date('H:i:s', strtotime($item['created'])) ?></span>

                                <h3 class="timeline-header no-border">登录于 <?= $item['ip'] ?> <small><?= $item['address'] ?></small></h3>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                </div>
                <!-- /登录日志 -->
                <div class="tab-pane" id="opeate-log">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>操作时间</th>
                            <th>操作项</th>
                            <th>日志</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($operate_log as $item): ?>
                            <tr>
                                <td><?= $item->created ?></td>
                                <td><?= $operate_log_label[$item->module] ?></td>
                                <td><?= $item->log ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /操作日志 -->
            </div>
        </div>
    </div>
</div>