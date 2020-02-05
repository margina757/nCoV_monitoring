<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class PaceAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte';
    public $css = [
        'plugins/pace/pace.min.css',
    ];
    public $js = [
        'plugins/pace/pace.min.js',
    ];
    public $jsOptions = [
        'position' => View::POS_BEGIN,
    ];
    public $depends = [
    ];
}
