<?php

namespace backend\components\widgets;

use kartik\base\Widget;
use kartik\growl\Growl;
use Yii;
use yii\bootstrap\Alert as BootstrapAlert;

class Alert extends Widget
{
    public $growlTypes = [
        'growl-success' => [
            'type' => Growl::TYPE_SUCCESS,
            'icon' => 'glyphicon glyphicon-ok-sign',
        ],
        'growl-info' => [
            'type' => Growl::TYPE_INFO,
            'icon' => 'glyphicon glyphicon-info-sign',
        ],
        'growl-warning' => [
            'type' => Growl::TYPE_WARNING,
            'icon' => 'glyphicon glyphicon-exclamation-sign',
        ],
        'growl-danger' => [
            'type' => Growl::TYPE_DANGER,
            'icon' => 'glyphicon glyphicon-remove-sign',
        ],
    ];

    public $growlOptions = [
        'showSeparator' => true,
        'delay' => 0,
        'pluginOptions' => [
            'showProgressbar' => true,
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ];

    public $alertTypes = [
        'alert-danger' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'alert-success' => [
            'class' => 'alert-success',
            'icon' => '<i class="icon fa fa-check"></i>',
        ],
        'alert-info' => [
            'class' => 'alert-info',
            'icon' => '<i class="icon fa fa-info"></i>',
        ],
        'alert-warning' => [
            'class' => 'alert-warning',
            'icon' => '<i class="icon fa fa-warning"></i>',
        ],
    ];

    /**
     * @var boolean whether to removed flash messages during AJAX requests
     */
    public $isAjaxRemoveFlash = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            $data = (array)$data;
            // 右上角悬浮通知
            if (isset($this->growlTypes[$type])) {
                $config = array_merge($this->growlOptions, $this->growlTypes[$type]);

                foreach ($data as $message) {
                    $config['body'] = $message;
                    echo Growl::widget($config);
                }

                if ($this->isAjaxRemoveFlash && !Yii::$app->request->isAjax) {
                    $session->removeFlash($type);
                }
            }
            // 顶部通知
            elseif (isset($this->alertTypes[$type])) {
                foreach ($data as $message) {

                    $this->options['class'] = $this->alertTypes[$type]['class'];
                    $this->options['id'] = $this->getId() . '-' . $type;

                    echo BootstrapAlert::widget([
                        'body' => $this->alertTypes[$type]['icon'] . $message,
                        'closeButton' => [],
                        'options' => $this->options,
                    ]);
                }

                if ($this->isAjaxRemoveFlash && !\Yii::$app->request->isAjax) {
                    $session->removeFlash($type);
                }
            }
        }
    }
}