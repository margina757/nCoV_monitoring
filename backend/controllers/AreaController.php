<?php


namespace backend\controllers;

use Yii;
use common\models\Area;
use common\services\DataHelperService;
use yii\base\DynamicModel;

class AreaController extends BaseController
{
    public function actionIndex()
    {
        $dynamicModel = new DynamicModel(['name']);
        $dynamicModel->addRule(['name'], 'string');
        $model = new Area();
        $model->load(\Yii::$app->request->get(), $dynamicModel->formName());
        $dynamicModel->load(\Yii::$app->request->get());

        $attribute2Label = $model->attributeLabels();
        $column = array_keys($attribute2Label);
        $dataProvider = DataHelperService::getIns()->getDataProvider($model);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dynamicModel' => $dynamicModel,
            'column' => $column
        ]);
    }

    public function actionCreate()
    {
        $model = new Area();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                $this->successFlash('添加成功');
            } else {
                $error = $model->getFirstErrors();
                $this->failedFlash(reset($error));
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionPost()
    {
        $model = new Area();   
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
        $model = Area::findOne($id);
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