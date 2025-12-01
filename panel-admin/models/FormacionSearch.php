<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Formacion;

/**
 * FormacionSearch represents the model behind the search form of `app\models\Formacion`.
 */
class FormacionSearch extends Formacion
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['for_id', 'created_by', 'updated_by'], 'integer'],
            [['for_institucion', 'for_grado_titulo', 'for_fecha_inicio', 'for_fecha_fin', 'for_logros_principales', 'for_tipo_logro', 'for_categoria', 'for_publicada', 'for_codigo_validacion', 'for_certificado', 'for_mostrar_certificado', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Formacion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'for_id' => $this->for_id,
            'for_fecha_inicio' => $this->for_fecha_inicio,
            'for_fecha_fin' => $this->for_fecha_fin,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'for_institucion', $this->for_institucion])
              ->andFilterWhere(['like', 'for_grado_titulo', $this->for_grado_titulo])
              ->andFilterWhere(['like', 'for_logros_principales', $this->for_logros_principales])
              ->andFilterWhere(['like', 'for_tipo_logro', $this->for_tipo_logro])
              ->andFilterWhere(['like', 'for_categoria', $this->for_categoria])
              ->andFilterWhere(['like', 'for_publicada', $this->for_publicada])
              ->andFilterWhere(['like', 'for_codigo_validacion', $this->for_codigo_validacion])
              ->andFilterWhere(['like', 'for_certificado', $this->for_certificado])
              ->andFilterWhere(['like', 'for_mostrar_certificado', $this->for_mostrar_certificado]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'for_institucion', $this->globalSearch])
                  ->orFilterWhere(['like', 'for_grado_titulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'for_logros_principales', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
