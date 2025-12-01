<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Layouts;

/**
 * LayoutSearch represents the model behind the search form of `app\models\Layouts`.
 */
class LayoutSearch extends Layouts
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['lay_id', 'created_by', 'updated_by'], 'integer'],
            [['lay_nombre', 'lay_ruta_imagenes', 'lay_estado', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

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
        $query = Layouts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'lay_id' => $this->lay_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'lay_nombre', $this->lay_nombre])
              ->andFilterWhere(['like', 'lay_ruta_imagenes', $this->lay_ruta_imagenes])
              ->andFilterWhere(['like', 'lay_estado', $this->lay_estado]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'lay_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'lay_ruta_imagenes', $this->globalSearch])
                  ->orFilterWhere(['like', 'lay_estado', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}