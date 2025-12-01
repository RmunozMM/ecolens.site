<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Asunto;

class AsuntoSearch extends Asunto
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['asu_id', 'created_by', 'updated_by'], 'integer'],
            [['asu_nombre', 'asu_publicado', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],

        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Asunto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'asu_id' => $this->asu_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        // Filtros estándar
        $query->andFilterWhere(['like', 'asu_nombre', $this->asu_nombre])
              ->andFilterWhere(['like', 'asu_publicado', $this->asu_publicado]);

        // Filtro global
        if (!empty($this->globalSearch)) {
            $query->andFilterWhere(['or',
                ['like', 'asu_nombre', $this->globalSearch],
                ['like', 'asu_publicado', $this->globalSearch],
                ['like', 'created_at', $this->globalSearch],
                ['like', 'updated_at', $this->globalSearch],
            ]);
            $query->andFilterWhere(['created_by' => $this->created_by]);
            $query->andFilterWhere(['updated_by' => $this->updated_by]);
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);
            $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        }

        return $dataProvider;
    }
}
