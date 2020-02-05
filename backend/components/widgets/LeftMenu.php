<?php

namespace backend\components\widgets;

use Yii;
use yii\helpers\Html;

class LeftMenu extends \dmstr\widgets\Menu
{
    /**
     * @inheritdoc
     */
    public function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            } else {
                if (isset($item['url']) && !empty($item['url']) && $item['url'] != '#' && !Yii::$app->user->can('!', ['url' => $item['url'][0]])) {
                    unset($items[$i]);
                    continue;
                }
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url']) || $item['url'] == '#') {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        return array_values($items);
    }

    /**
     * @inheritdoc
     */
    public function isItemActive($item)
    {
        return parent::isItemActive($item) || isset($item['contains']) && in_array($this->route, $item['contains']);
    }
}