<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ImagenesGaleria;

/**
 * ImagenGaleriaSearch represents the model behind the search form of `app\models\ImagenesGaleria`.
 */
class ImagenGaleriaSearch extends ImagenesGaleria
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['img_id', 'img_gal_id', 'created_by', 'updated_by'], 'integer'],
            [['img_ruta', 'img_descripcion', 'img_estado', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ImagenesGaleria::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'img_id' => $this->img_id,
            'img_gal_id' => $this->img_gal_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'img_ruta', $this->img_ruta])
              ->andFilterWhere(['like', 'img_descripcion', $this->img_descripcion])
              ->andFilterWhere(['like', 'img_estado', $this->img_estado]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'img_ruta', $this->globalSearch])
                  ->orFilterWhere(['like', 'img_descripcion', $this->globalSearch])
                  ->orFilterWhere(['like', 'img_estado', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}