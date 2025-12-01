<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Redes;

/**
 * RedSearch represents the model behind the search form of `app\models\Redes`.
 */
class RedSearch extends Redes
{
    /**
     * {@inheritdoc}
     */

    // Propiedad para búsqueda global
    public $globalSearch;
    public function rules()
    {
        return [
            [['red_id', 'created_by', 'updated_by'], 'integer'],
            [['red_nombre', 'red_enlace', 'red_publicada', 'red_categoria', 'red_perfil', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
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
        $query = Redes::find();

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
            'red_id' => $this->red_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'red_nombre', $this->red_nombre])
            ->andFilterWhere(['like', 'red_enlace', $this->red_enlace])
            ->andFilterWhere(['like', 'red_categoria', $this->red_categoria])
            ->andFilterWhere(['like', 'red_perfil', $this->red_perfil])
            ->andFilterWhere(['like', 'red_publicada', $this->red_publicada]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'red_nombre', $this->globalSearch])
                    ->orFilterWhere(['like', 'red_enlace', $this->globalSearch])
                    ->orFilterWhere(['like', 'red_categoria', $this->globalSearch])
                    ->orFilterWhere(['like', 'red_perfil', $this->globalSearch])
                    ->orFilterWhere(['like', 'red_publicada', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
