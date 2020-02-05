<?php


namespace backend\controllers;

use Yii;
use common\models\Access;
use common\services\DataHelperService;
use yii\base\DynamicModel;
use backend\models\AccessSearch;

class AccessController extends BaseController
{
    public function actionIndex()
    {
        $search = new AccessSearch();
        $dataProvider = $search->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dynamicModel' => $search,
        ]);
    }

    public function actionCreate()
    {
    }

    public function actionUpdate()
    {
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $model = Area::findOne($id);
        $model->name = Yii::$app->request->post('name');

        if ($model && $model->save()) {
            return [
                'code' => 0,
            ];
        } else {
            return [
                'code' => 1,
                'data' => [
                    'error' => '修改失败'
                ]
            ];
        }
    }

    public function actionDelete()
    {
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        $model = Access::findOne($id);
        if ($model && $model->delete()) {
            return [
                'code' => 0,
            ];
        } else {
            return [
                'code' => 1,
                'data' => [
                    'error' => '删除失败'
                ]
            ];
        }
    }
}