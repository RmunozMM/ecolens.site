<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class CategoriaServicioSearch extends CategoriaServicio
{
    public function rules()
    {
        return [
            [['cas_id', 'created_by', 'updated_by'], 'integer'],
            [['cas_nombre', 'cas_publicada', ], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = CategoriaServicio::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cas_id' => $this->cas_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'cas_nombre', $this->cas_nombre])
              ->andFilterWhere(['like', 'cas_publicada', $this->cas_publicada])
              ->andFilterWhere(['like', 'created_at', $this->created_at])
              ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}