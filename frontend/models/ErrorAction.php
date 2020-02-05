<?php

namespace frontend\models;

use Yii;

class ErrorAction extends \yii\web\ErrorAction
{
    public function run()
    {
        Yii::$app->getResponse()->setStatusCodeByException($this->exception);
        /** @var \frontend\controllers\BaseController $controller */
        $controller = Yii::$app->controller;
        return $controller->failed($this->renderAjaxResponse());
    }
}