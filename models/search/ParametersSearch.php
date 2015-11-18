<?php

namespace cubiclab\store\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cubiclab\store\models\Parameters;

/**
 * ParametersSearch represents the model behind the search form about `app\models\Parameters`.
 */
class ParametersSearch extends Parameters
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'digit', 'status', 'order'], 'integer'],
            [['name', 'description', 'units', 'is_range', 'icon'], 'safe'],
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
        $query = Parameters::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'digit' => $this->digit,
            'status' => $this->status,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'units', $this->units])
            ->andFilterWhere(['like', 'is_range', $this->is_range])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}
