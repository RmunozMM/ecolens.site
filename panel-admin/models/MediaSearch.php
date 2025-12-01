<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Media;

/**
 * MediaSearch representa el modelo detrás del formulario de búsqueda de `app\models\Media`.
 */
class MediaSearch extends Media
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['med_id', 'created_by', 'updated_by'], 'integer'],
            [['med_nombre', 'med_ruta', 'med_descripcion', 'med_entidad', 'med_tipo', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // Omitimos la implementación de escenarios en la clase padre
        return Model::scenarios();
    }

    /**
     * Crea una instancia de ActiveDataProvider con la búsqueda aplicada.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Media::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtros estándar
        $query->andFilterWhere([
            'med_id' => $this->med_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'med_nombre', $this->med_nombre])
              ->andFilterWhere(['like', 'med_ruta', $this->med_ruta])
              ->andFilterWhere(['like', 'med_descripcion', $this->med_descripcion])
              ->andFilterWhere(['like', 'med_entidad', $this->med_entidad])
              ->andFilterWhere(['like', 'med_tipo', $this->med_tipo]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'med_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'med_ruta', $this->globalSearch])
                  ->orFilterWhere(['like', 'med_descripcion', $this->globalSearch])
                  ->orFilterWhere(['like', 'med_entidad', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
