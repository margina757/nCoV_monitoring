<?php

namespace backend\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class HeaderMenu extends \dmstr\widgets\Menu
{
    public $itemOptions = ['class' => 'dropdown messages-menu'];
    public $submenuTemplate = "\n<ul class='dropdown-menu'><li><ul class='menu'>\n{items}\n</ul></li></ul>\n";
    public $hideEmptyItems = false;

    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        if(isset($item['items'])) {
            if (empty($item['items'])) {
                $linkTemplate = '<a href="{url}" target="_blank">{icon} <span class="hidden-sm hidden-xs">{label}</span></a>';
            } else {
                $linkTemplate = '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown">{icon} <span class="hidden-sm hidden-xs">{label}</span></a>';
            }
        } else {
            $linkTemplate = '<a href="{url}">{icon} {label}</a>';
        }

        $template = ArrayHelper::getValue($item, 'template', $linkTemplate);
        $replace = !empty($item['icon']) ? [
            '{url}' => Url::to($item['url']),
            '{label}' => '<span>'.$item['label'].'</span>',
            '{icon}' => '<i class="' . self::$iconClassPrefix . $item['icon'] . '"></i> '
        ] : [
            '{url}' => Url::to($item['url']),
            '{label}' => '<span>'.$item['label'].'</span>',
            '{icon}' => $this->defaultIconHtml,
        ];
        return strtr($template, $replace);
    }

    /**
     * @inheritdoc
     */
    protected function isItemActive($item)
    {
        if (isset($item['items']) && strpos(Yii::$app->controller->module->id, $item['prefix']) === 0) {
            return true;
        }
        return false;
    }
}