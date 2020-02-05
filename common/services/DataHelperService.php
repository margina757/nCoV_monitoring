<?php


namespace common\services;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\QueryInterface;

class DataHelperService extends BaseService
{
    public function getDataProvider(Model $model)
    {
        /**
         * @var QueryInterface $query
         */
        $query = $model::find();
        $tableSchema =  $model::getTableSchema();
        $columns = array_keys($tableSchema->columns);
        $dateProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                ],
                'attributes' => $columns,
            ],
        ]);


        foreach ($tableSchema->columns as $attribute => $column) {
            if ($column->phpType === 'string') {
                $query->andFilterWhere(['like', $attribute, $model->getAttribute($attribute)]);
            } else {
                $query->andFilterWhere([$attribute => $model->getAttribute($attribute)]);
            }
        }

        return $dateProvider;
    }
}