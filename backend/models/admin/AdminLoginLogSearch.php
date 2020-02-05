<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\admin\AdminLoginLog;

/**
 * AdminLoginLogSearch represents the model behind the search form about `backend\models\admin\AdminLoginLog`.
 */
class AdminLoginLogSearch extends AdminLoginLog
{
    public $adminUser;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admin_id', 'role_id', 'duration'], 'integer'],
            [['ip', 'address', 'created', 'adminUser'], 'safe'],
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
        $query = AdminLoginLog::find();

        // add conditions that should always apply here
        $query->with('adminUser');

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
            'admin_id' => $this->admin_id,
            'role_id' => $this->role_id,
            'duration' => $this->duration,
        ]);

        if ($this->created) {
            $between = explode(' - ', $this->created);
            $query->andFilterWhere(['between', 'created', $between[0], $between[1]]);
        }

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'address', $this->address]);

        if ($this->adminUser) {
            $query->joinWith('adminUser')
                ->andWhere(['or',
                    ['like', 'username', $this->adminUser],
                    ['like', 'real_name', $this->adminUser],
                ]);
        }

        return $dataProvider;
    }
}
