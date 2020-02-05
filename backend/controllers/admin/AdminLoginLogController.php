<?php

namespace backend\controllers\admin;

use backend\controllers\BaseController;
use backend\models\admin\AdminLoginLogSearch;
use Yii;

/**
 * AdminLoginLogController implements the CRUD actions for AdminLoginLog model.
 */
class AdminLoginLogController extends BaseController
{
    /**
     * Lists all AdminLoginLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminLoginLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
