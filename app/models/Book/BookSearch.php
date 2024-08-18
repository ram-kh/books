<?php

namespace app\models\Book;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book\Book;

/**
 * BookSearch represents the model behind the search form of `app\models\Book\Book`.
 */
class BookSearch extends Book
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'year'], 'integer'],
            [['title', 'isbn', 'annotation'], 'safe'],
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
        $query = Book::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
//            'id' => $this->id,
            'year' => $this->year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
//            ->andFilterWhere(['like', 'picture', $this->picture])
//            ->andFilterWhere(['like', 'picture_ext', $this->picture_ext])
            ->andFilterWhere(['like', 'annotation', $this->annotation]);

        return $dataProvider;
    }
}
