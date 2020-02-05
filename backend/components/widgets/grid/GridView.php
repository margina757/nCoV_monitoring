<?php

namespace backend\components\widgets\grid;

use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    public $buttons;

    /**
     * @inheritdoc
     */
    public $dataColumnClass = 'backend\components\widgets\grid\DataColumn';

    /**
     * @inheritdoc
     */
    public $tableOptions = ['class' => 'table dataTable table-bordered table-condensed table-striped table-hover'];

    public $layout = <<< HTML
<div class="row">
    <div class="col-sm-6">
        {page_size}
    </div>
    <div class="col-sm-6">
        {buttons}
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        {items}
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        {summary}
    </div>
        <div class="col-sm-7">
        {pager}
    </div>
</div>
HTML;

    /**
     * @inheritdoc
     */
    public function run()
    {
        GridViewAsset::register($this->getView());
        parent::run();
    }

    /**
     * @inheritdoc
     */
    public $summaryOptions = ['class' => 'dataTables_info'];

    /**
     * @inheritdoc
     */
    public function renderPager()
    {
        return Html::tag('div', parent::renderPager(), ['class' => 'dataTables_paginate paging_simple_numbers']);
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{page_size}':
                return $this->renderPageSize();
            case '{buttons}':
                return $this->renderButtons();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderPageSize()
    {
        return '<div class="dataTables_length"></div>';
    }

    public function renderButtons()
    {
        if ($this->buttons) {
            return '<div class="dataTables_filter pull-right">' . $this->buttons . '</div>';
        }
        return '';
    }

}