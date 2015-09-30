<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vocabulary;

/**
 * VocabularySearch represents the model behind the search form about `app\models\Vocabulary`.
 */
class VocabularySearch extends Vocabulary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wordId', 'german', 'russian', 'context'], 'safe'],
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
        $query = Vocabulary::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'wordId', $this->wordId])
            ->andFilterWhere(['like', 'german', $this->german])
            ->andFilterWhere(['like', 'russian', $this->russian])
            ->andFilterWhere(['like', 'context', $this->context]);

        return $dataProvider;
    }
}
