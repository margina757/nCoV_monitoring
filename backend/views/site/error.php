<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = Yii::$app->name;
?>

<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-<?= $exception->getCode() ? 'red' : 'yellow' ?>"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                <?= nl2br(Html::encode($message)) ?>
            </p>

            <?php if ($exception->getCode()): ?>
                <p>
                    访问页面时发生错误，请联系管理员并提供以下信息：
                </p>

                <pre><?= Html::encode('Error: ' . $exception->getMessage() . PHP_EOL .
                        'Location: ' . $exception->getFile() . ':' . $exception->getLine() . PHP_EOL .
                        $exception->getTraceAsString() . PHP_EOL .
                        date('Y-m-d H:i:s')) ?></pre>
            <?php endif; ?>

            <a href='<?= Yii::$app->homeUrl ?>'>返回首页</a>
        </div>
    </div>

</section>