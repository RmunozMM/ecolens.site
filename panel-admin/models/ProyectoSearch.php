<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proyecto;

/**
 * ProyectoSearch represents the model behind the search form of `app\models\Proyecto`.
 */
class ProyectoSearch extends Proyecto
{
    /**
     * {@inheritdoc}
     */

    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['pro_id', 'pro_ser_id', 'pro_cli_id', 'created_by', 'updated_by'], 'integer'],
            [['pro_titulo', 'pro_descripcion', 'pro_resumen', 'pro_slug', 'pro_estado', 'pro_destacado', 'pro_imagen', 'pro_url', 'pro_fecha_inicio', 'pro_fecha_fin', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = Proyecto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pro_id' => $this->pro_id,
            'pro_ser_id' => $this->pro_ser_id,
            'pro_cli_id' => $this->pro_cli_id,
            'pro_fecha_inicio' => $this->pro_fecha_inicio,
            'pro_fecha_fin' => $this->pro_fecha_fin,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'pro_titulo', $this->pro_titulo])
              ->andFilterWhere(['like', 'pro_descripcion', $this->pro_descripcion])
              ->andFilterWhere(['like', 'pro_resumen', $this->pro_resumen])
              ->andFilterWhere(['like', 'pro_slug', $this->pro_slug])
              ->andFilterWhere(['like', 'pro_estado', $this->pro_estado])
              ->andFilterWhere(['like', 'pro_destacado', $this->pro_destacado])
              ->andFilterWhere(['like', 'pro_imagen', $this->pro_imagen])
              ->andFilterWhere(['like', 'pro_url', $this->pro_url]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'pro_titulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_descripcion', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_resumen', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_slug', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_estado', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_destacado', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_imagen', $this->globalSearch])
                  ->orFilterWhere(['like', 'pro_url', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
