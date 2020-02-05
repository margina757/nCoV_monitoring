<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\admin\AdminOperateLog;

/**
 * AdminOperateLogSearch represents the model behind the search form about `backend\models\admin\AdminOperateLog`.
 */
class AdminOperateLogSearch extends AdminOperateLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'module'], 'integer'],
            [['admin_id', 'ip', 'country', 'log', 'reason', 'created'], 'safe'],
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
        $query = AdminOperateLog::find();

        // add conditions that should always apply here
        $query->where(['>=', 'module', 200]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_DESC,
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'module' => $this->module,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'log', $this->log])
            ->andFilterWhere(['like', 'reason', $this->reason]);

        if ($this->created) {
            $between = explode(' - ', $this->created);
            $query->andFilterWhere(['between', AdminOperateLog::tableName() . '.created', $between[0], $between[1]]);
        }

        if ($this->admin_id) {
            $query->joinWith('adminUser')
                ->andWhere(['or',
                    ['like', 'username', $this->admin_id],
                    ['like', 'real_name', $this->admin_id],
                ]);
        }

        return $dataProvider;
    }
}
