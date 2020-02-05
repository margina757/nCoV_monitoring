<?php


namespace frontend\controllers;

use yii\web\Controller;
use common\models\Area;
use common\models\Access;
use yii\web\Response;
use Yii;
use yii\helpers\Url;


/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableRbacValidation = false;

    /**
     * @inheritdoc
     */

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }


    /*
     * 地区接口
     */
    public function actionArea(){
        $areaList = Area::find()->all();
	foreach($areaList as $area){
		$data[$area->id] = $area->name;
	}
	Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'data' => $data,
            'message' => 'success',
            'code' => 0,
        ];

    }
	
	
    public function actionData(){
	    $name = Yii::$app->request->post('name');
	    $card =  Yii::$app->request->post('card');
        $type =  Yii::$app->request->post('type');
        $card_detail =  Yii::$app->request->post('card_detail');
        $address =  Yii::$app->request->post('address');
        $unit =  Yii::$app->request->post('unit');
        $phone =  Yii::$app->request->post('phone');
        $transport =  Yii::$app->request->post('transport');
        $transport_detail = Yii::$app->request->post('transport_detail');
        $reason =  Yii::$app->request->post('reason');
        $reason_detail =  Yii::$app->request->post('reason_detail');
        $partners = Yii::$app->request->post('partners');

        if(count($partners) >= 3){
            return self::failedAjax([], '同行人员不能超过3人');
        }

        if(!$name){
            return self::failedAjax([], '姓名不能为空');
        }

        if(!$card_detail){
            return self::failedAjax([], '证件号码不能为空');
        }

        if(!$card_detail){
            return self::failedAjax([], '小区地址不能为空');
        }

        if(!$unit){
            return self::failedAjax([], '单元号码不能为空');
        }

        if($transport === 0 && !$transport_detail){
            return self::failedAjax([], '车牌不能为空');
        }

        $access = new Access();
        $access->name = $name;
        $access->card = $card;
        $access->card_detail = $card_detail;
        $access->type = $type;
        $access->area = $address;
        $access->unit = $unit;
        $access->phone = $phone;
        $access->transport = $transport;
        $access->transport_detail = $transport_detail;
        $access->reason = $reason;
        $access->reason_detail = $reason_detail;
        $access->is_partner = 0;
        $access->created = date('Y-m-d H:i:s');

        foreach ($partners as $p){
            $partners = new Access();
            $partners->name = $p['name'];
            $partners->card = $card;
            $partners->card_detail = $card_detail;
            $partners->type = $type;
            $partners->area = $address;
            $partners->unit = $unit;
            $partners->phone = $p['phone'];
            $partners->transport = $transport;
            $partners->transport_detail = $transport_detail;
            $partners->reason = $reason;
            $partners->reason_detail = $reason_detail;
            $partners->is_partner = 1;
            $partners->created = date('Y-m-d H:i:s');
            $partners->save();
        }


        if($access->save()){
            return self::successAjax([],'提交成功，请出示此页面给统计人员核实');
        }else{
            return self::failedAjax([],$access->getErrors());
        }

    }


    /**
     * json返回
     *
     * @param $code
     * @param $message
     * @param $data
     * @return array
     */
    public function replyAjax($data, $message, $code)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    /**
     * 成功返回
     *
     * @param array $data
     * @param string $message
     * @param int $code
     * @return array
     */
    public function successAjax($data = [], $message = 'success', $code = 0)
    {
        return $this->replyAjax($data, $message, $code);
    }

    /**
     * 失败返回
     *
     * @param array $data
     * @param string $message
     * @param int $code
     * @return array
     */
    public function failedAjax($data = [], $message = 'failed', $code = 1)
    {
        return $this->replyAjax($data, $message, $code);
    }




}

