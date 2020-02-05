<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Access;


class AccessSearch extends Access
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card', 'type', 'area', 'transport', 'reason', 'is_partner'], 'integer'],
            [['created'], 'safe'],
            [['name', 'transport_detail'], 'string', 'max' => 32],
            [['card_detail', 'reason_detail'], 'string', 'max' => 128],
            [['unit'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Access::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $tableSchema =  Access::getTableSchema();
        $columns = array_keys($tableSchema->columns);

        foreach ($tableSchema->columns as $attribute => $column) {
            if ($column->phpType === 'string') {
                $query->andFilterWhere(['like', $attribute, $this->getAttribute($attribute)]);
            } else {
                $query->andFilterWhere([$attribute => $this->getAttribute($attribute)]);
            }
        }

        return $dataProvider;
    }
}
