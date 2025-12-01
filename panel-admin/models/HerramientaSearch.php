<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Herramienta;

/**
 * HerramientaSearch represents the model behind the search form of `app\models\Herramientas`.
 */
class HerramientaSearch extends Herramienta
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['her_id', 'her_nivel', 'created_by', 'updated_by'], 'integer'],
            [['her_nombre', 'her_publicada', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Herramienta::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'her_id' => $this->her_id,
            'her_nivel' => $this->her_nivel,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'her_nombre', $this->her_nombre])
              ->andFilterWhere(['like', 'her_publicada', $this->her_publicada]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'her_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'her_publicada', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
