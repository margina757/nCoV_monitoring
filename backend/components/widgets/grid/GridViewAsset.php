<?php

namespace backend\components\widgets\grid;

class GridViewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components/datatables.net-bs';

    public $css = [
        'css/dataTables.bootstrap.min.css',
    ];

    public $js = [];

    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}