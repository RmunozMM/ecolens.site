<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Correo;

/**
 * CorreoSearch represents the model behind the search form of `app\models\Correo`.
 */
class CorreoSearch extends Correo
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['cor_id', 'created_by', 'updated_by'], 'integer'],
            [['cor_nombre', 'cor_correo', 'cor_asunto', 'cor_mensaje', 'cor_fecha_consulta', 'cor_fecha_respuesta', 'cor_estado', 'cor_respuesta', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Correo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cor_id' => $this->cor_id,
            'cor_fecha_consulta' => $this->cor_fecha_consulta,
            'cor_fecha_respuesta' => $this->cor_fecha_respuesta,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'cor_nombre', $this->cor_nombre])
              ->andFilterWhere(['like', 'cor_correo', $this->cor_correo])
              ->andFilterWhere(['like', 'cor_asunto', $this->cor_asunto])
              ->andFilterWhere(['like', 'cor_mensaje', $this->cor_mensaje])
              ->andFilterWhere(['like', 'cor_estado', $this->cor_estado])
              ->andFilterWhere(['like', 'cor_respuesta', $this->cor_respuesta]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'cor_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'cor_correo', $this->globalSearch])
                  ->orFilterWhere(['like', 'cor_asunto', $this->globalSearch])
                  ->orFilterWhere(['like', 'cor_mensaje', $this->globalSearch])
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