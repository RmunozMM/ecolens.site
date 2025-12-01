<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class LeccionSearch extends Leccion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lec_id', 'lec_orden', 'lec_mod_id', 'created_by', 'updated_by'], 'integer'],
            [['lec_titulo', 'lec_contenido', 'lec_tipo', 'lec_estado', 'lec_slug', 'lec_imagen', 'lec_icono', 'created_at', 'updated_at'], 'safe'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Leccion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtering conditions
        $query->andFilterWhere([
            'lec_id' => $this->lec_id,
            'lec_orden' => $this->lec_orden,
            'lec_mod_id' => $this->lec_mod_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'lec_titulo', $this->lec_titulo])
              ->andFilterWhere(['like', 'lec_contenido', $this->lec_contenido])
              ->andFilterWhere(['like', 'lec_tipo', $this->lec_tipo])
              ->andFilterWhere(['like', 'lec_estado', $this->lec_estado])
              ->andFilterWhere(['like', 'lec_slug', $this->lec_slug])
              ->andFilterWhere(['like', 'lec_imagen', $this->lec_imagen])
              ->andFilterWhere(['like', 'lec_icono', $this->lec_icono])
              ->andFilterWhere(['like', 'created_at', $this->created_at])
              ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}