<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Curriculum;

/**
 * CurriculumSearch represents the model behind the search form of `app\models\Curriculum`.
 */
class CurriculumSearch extends Curriculum
{
    public $globalSearch;

    public function rules()
    {
        return [
            [['cur_id', 'cur_per_id', 'created_by', 'updated_by'], 'integer'],
            [['cur_titulo', 'cur_subtitulo', 'cur_resumen_profesional', 'cur_estilos', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Curriculum::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cur_id' => $this->cur_id,
            'cur_per_id' => $this->cur_per_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'cur_titulo', $this->cur_titulo])
              ->andFilterWhere(['like', 'cur_subtitulo', $this->cur_subtitulo])
              ->andFilterWhere(['like', 'cur_resumen_profesional', $this->cur_resumen_profesional])
              ->andFilterWhere(['like', 'cur_estilos', $this->cur_estilos]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'cur_titulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'cur_subtitulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'cur_resumen_profesional', $this->globalSearch])
                  ->orFilterWhere(['like', 'cur_estilos', $this->globalSearch])
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
