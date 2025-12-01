<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Servicio;

/**
 * ServicioSearch representa el modelo detrás de la búsqueda de `app\models\Servicio`.
 */
class ServicioSearch extends Servicio
{
    // Propiedad para búsqueda global
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ser_id', 'ser_cat_id', 'created_by', 'updated_by'], 'integer'],
            [['ser_titulo', 'ser_slug', 'ser_resumen', 'ser_cuerpo', 'ser_publicado', 'ser_destacado', 'ser_imagen', 'ser_icono', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
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
     * Crea una instancia de proveedor de datos con la consulta de búsqueda aplicada.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Servicio::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtros exactos
        $query->andFilterWhere([
            'ser_id' => $this->ser_id,
            'ser_cat_id' => $this->ser_cat_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        // Filtros de texto
        $query->andFilterWhere(['like', 'ser_titulo', $this->ser_titulo])
              ->andFilterWhere(['like', 'ser_slug', $this->ser_slug])
              ->andFilterWhere(['like', 'ser_resumen', $this->ser_resumen])
              ->andFilterWhere(['like', 'ser_cuerpo', $this->ser_cuerpo])
              ->andFilterWhere(['like', 'ser_publicado', $this->ser_publicado])
              ->andFilterWhere(['like', 'ser_destacado', $this->ser_destacado])
              ->andFilterWhere(['like', 'ser_imagen', $this->ser_imagen])
              ->andFilterWhere(['like', 'ser_icono', $this->ser_icono]);

        // Búsqueda global
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'ser_titulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'ser_slug', $this->globalSearch])
                  ->orFilterWhere(['like', 'ser_resumen', $this->globalSearch])
                  ->orFilterWhere(['like', 'ser_cuerpo', $this->globalSearch])
                  ->orFilterWhere(['like', 'ser_publicado', $this->globalSearch])
                  ->orFilterWhere(['like', 'ser_destacado', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
