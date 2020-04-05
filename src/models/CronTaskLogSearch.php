<?php

namespace gaxz\crontab\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use gaxz\crontab\models\CronTaskLog;

/**
 * CronTaskLogSearch represents the model behind the search form of `gaxz\crontab\models\CronTaskLog`.
 */
class CronTaskLogSearch extends CronTaskLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cron_task_id', 'exit_code'], 'integer'],
            [['created_at', 'output'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = CronTaskLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cron_task_id' => $this->cron_task_id,
            'exit_code' => $this->exit_code,
        ]);

        $query->andFilterWhere(['like', 'output', $this->output]);

        return $dataProvider;
    }
}
