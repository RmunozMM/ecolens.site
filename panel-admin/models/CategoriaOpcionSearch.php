<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CategoriaOpcion;

/**
 * CategoriaOpcionSearch represents the model behind the search form of `app\models\CategoriaOpcion`.
 */
class CategoriaOpcionSearch extends CategoriaOpcion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_id', 'cat_orden', 'created_by', 'updated_by'], 'integer'],
            [['cat_nombre', 'cat_descripcion', 'cat_icono', 'created_at', 'updated_at'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = CategoriaOpcion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'cat_id' => $this->cat_id,
            'cat_orden' => $this->cat_orden,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'cat_nombre', $this->cat_nombre])
            ->andFilterWhere(['like', 'cat_descripcion', $this->cat_descripcion])
            ->andFilterWhere(['like', 'cat_icono', $this->cat_icono]);

        return $dataProvider;
    }
}
