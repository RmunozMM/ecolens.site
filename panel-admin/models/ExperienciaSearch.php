<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Experiencia;

/**
 * ExperienciaSearch represents the model behind the search form of `app\models\Experiencia`.
 */
class ExperienciaSearch extends Experiencia
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['exp_id', 'exp_mod_id', 'created_by', 'updated_by'], 'integer'],
            [['exp_cargo', 'exp_empresa', 'exp_fecha_inicio', 'exp_fecha_fin', 'exp_descripcion', 'exp_logros', 'exp_publicada', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Experiencia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'exp_id' => $this->exp_id,
            'exp_fecha_inicio' => $this->exp_fecha_inicio,
            'exp_fecha_fin' => $this->exp_fecha_fin,
            'exp_mod_id' => $this->exp_mod_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'exp_cargo', $this->exp_cargo])
              ->andFilterWhere(['like', 'exp_empresa', $this->exp_empresa])
              ->andFilterWhere(['like', 'exp_descripcion', $this->exp_descripcion])
              ->andFilterWhere(['like', 'exp_logros', $this->exp_logros])
              ->andFilterWhere(['like', 'exp_publicada', $this->exp_publicada]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'exp_cargo', $this->globalSearch])
                  ->orFilterWhere(['like', 'exp_empresa', $this->globalSearch])
                  ->orFilterWhere(['like', 'exp_descripcion', $this->globalSearch]);
        }
        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}