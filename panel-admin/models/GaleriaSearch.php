<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Galerias;

/**
 * GaleriaSearch represents the model behind the search form of `app\models\Galerias`.
 */
class GaleriaSearch extends Galerias
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['gal_id', 'gal_id_registro', 'created_by', 'updated_by'], 'integer'],
            [['gal_tipo_registro', 'gal_descripcion', 'gal_estado', 'gal_titulo', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Galerias::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'gal_id' => $this->gal_id,
            'gal_id_registro' => $this->gal_id_registro,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gal_tipo_registro', $this->gal_tipo_registro])
              ->andFilterWhere(['like', 'gal_descripcion', $this->gal_descripcion])
              ->andFilterWhere(['like', 'gal_estado', $this->gal_estado])
              ->andFilterWhere(['like', 'gal_titulo', $this->gal_titulo]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'gal_tipo_registro', $this->globalSearch])
                  ->orFilterWhere(['like', 'gal_descripcion', $this->globalSearch])
                  ->orFilterWhere(['like', 'gal_estado', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
