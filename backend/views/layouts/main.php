<?php

use dmstr\helpers\AdminLteHelper;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\AppAsset::register($this);
\dmstr\web\AdminLteAsset::register($this);
\backend\assets\PaceAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="/favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition <?= AdminLteHelper::skinClass() ?> sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render('header.php') ?>

    <?= $this->render('left.php') ?>

    <?= $this->render('content.php', ['content' => $content]) ?>

</div>

<?php $this->endBody() ?>
</body>

<script>
    $(document).ajaxStart(function() {
        Pace.restart();
    });
</script>
</html>
<?php $this->endPage() ?>
