<?php

namespace backend\controllers;


use common\models\OnlineUserModel;
use yii\data\ArrayDataProvider;

class OnlineUserController extends BaseController
{

    public function actionIndex()
    {
        $models = OnlineUserModel::getAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}