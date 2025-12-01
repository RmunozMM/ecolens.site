<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Colores;

/**
 * ColorSearch represents the model behind the search form of `app\models\Colores`.
 */
class ColorSearch extends Colores
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['col_id', 'created_by', 'updated_by'], 'integer'],
            [['col_nombre', 'col_valor', 'col_descripcion', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Colores::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'col_id' => $this->col_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'col_nombre', $this->col_nombre])
              ->andFilterWhere(['like', 'col_valor', $this->col_valor])
              ->andFilterWhere(['like', 'col_descripcion', $this->col_descripcion]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'col_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'col_valor', $this->globalSearch])
                  ->orFilterWhere(['like', 'col_descripcion', $this->globalSearch])
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
