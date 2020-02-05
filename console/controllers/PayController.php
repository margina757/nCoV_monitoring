<?php

namespace console\controllers;


use common\services\PayService;
use yii\console\Controller;

class  PayController extends Controller
{
    public function actionIndex()
    {
        $param  = [
            'gmt_create' => '2019-12-05 14:07:18',
            'charset' => 'utf-8',
            'gmt_payment' => '2019-12-05 14:07:25',
            'notify_time' => '2019-12-05 14:07:26',
            'subject' => '月卡',
            'sign' => 'mNq7HNDU+Ig0ZvTXwad6jKGd7WoXoaVKZcsK9B5xwEQDquAHmeSwt8sdVOoyrRNIKKBMAju3DvLK+OQWaf8vBcZ7wRjGxMfLPSrr4ExFZwGzAUNsKzjrq6Y7zuAWPCftRTs7JDmlxFwekM976N7bkJcYBjxrN4uj4FHz4bI0hVDbeo1OjkX6wvVTFF4itBMYKMRsE5ab3bpUYqIxH4UmaQz94imxopZYdtQj6iisqkW9ElQm5na3ZgUY0pZeTcxBo4mR6wgjyL7dQtdgql4WD/9m9YzjlzMc5bUo0gCyf0dzfAoryvDbpnu+MMH+jYw6RgvhFxj1kPD7rsfNvy6YuA==',
            'buyer_id' => '2088802858857564',
            'body' => '购买套餐',
            'invoice_amount' => '0.01',
            'version' => '1.0',
            'notify_id' => '2019120500222140725057560527152413',
            'fund_bill_list' => '[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]',
            'notify_type' => 'trade_status_sync',
            'out_trade_no' => '20191205060712660672',
            'total_amount' => '0.01',
            'trade_status' => 'TRADE_SUCCESS',
            'trade_no' => '2019120522001457560561631741',
            'auth_app_id' => '2019082966720190',
            'receipt_amount' => '0.01',
            'point_amount' => '0.00',
            'buyer_pay_amount' => '0.01',
            'app_id' => '2019082966720190',
            'sign_type' => 'RSA2',
            'seller_id' => '2088631077289681',
        ];
        echo PayService::getIns()->finish($param);
    }


}