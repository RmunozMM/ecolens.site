<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Curso;

/**
 * CursoSearch representa el modelo detrás de la búsqueda de `app\models\Curso`.
 */
class CursoSearch extends Curso
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['cur_id', 'created_by', 'updated_by'], 'integer'],
            [['cur_titulo', 'cur_descripcion', 'cur_imagen', 'cur_icono', 'cur_estado', 'cur_slug', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Curso::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cur_id' => $this->cur_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'cur_titulo', $this->cur_titulo])
              ->andFilterWhere(['like', 'cur_descripcion', $this->cur_descripcion])
              ->andFilterWhere(['like', 'cur_estado', $this->cur_estado])
              ->andFilterWhere(['like', 'cur_slug', $this->cur_slug])
              ->andFilterWhere(['like', 'cur_icono', $this->cur_icono])
              ->andFilterWhere(['like', 'cur_imagen', $this->cur_imagen]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'cur_titulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'cur_descripcion', $this->globalSearch])
                  ->orFilterWhere(['like', 'cur_estado', $this->globalSearch])
                  ->orFilterWhere(['like', 'cur_slug', $this->globalSearch])
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