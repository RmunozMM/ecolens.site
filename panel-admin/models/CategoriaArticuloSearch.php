<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CategoriaArticulo;

/**
 * CategoriaArticuloSearch represents the model behind the search form of `app\models\CategoriaArticulo`.
 */
class CategoriaArticuloSearch extends CategoriaArticulo
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['caa_id', 'created_by', 'updated_by'], 'integer'],
            [['caa_nombre', 'caa_estado', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CategoriaArticulo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'caa_id' => $this->caa_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'caa_nombre', $this->caa_nombre])
              ->andFilterWhere(['like', 'caa_estado', $this->caa_estado]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'caa_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'caa_estado', $this->globalSearch])
                  ->orFilterWhere(['like', 'created_at', $this->globalSearch])
                  ->orFilterWhere(['like', 'updated_at', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
