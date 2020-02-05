<?php

namespace backend\controllers\admin;

use backend\controllers\BaseController;
use backend\models\admin\AdminOperateLog;
use Yii;
use backend\models\admin\AdminOperateLogSearch;

/**
 * AdminOperateLogController implements the CRUD actions for AdminOperateLog model.
 */
class AdminOperateLogController extends BaseController
{
    /**
     * Lists all AdminOperateLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminOperateLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modules = AdminOperateLog::getModuleName(false);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modules' => $modules,
        ]);
    }
}
