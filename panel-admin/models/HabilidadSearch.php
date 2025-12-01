<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Habilidad;

/**
 * HabilidadSearch represents the model behind the search form of `app\models\Habilidad`.
 */
class HabilidadSearch extends Habilidad
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['hab_id', 'hab_nivel', 'created_by', 'updated_by'], 'integer'],
            [['hab_nombre', 'hab_publicada', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Habilidad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'hab_id' => $this->hab_id,
            'hab_nivel' => $this->hab_nivel,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'hab_nombre', $this->hab_nombre])
              ->andFilterWhere(['like', 'hab_publicada', $this->hab_publicada]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'hab_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'hab_publicada', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
