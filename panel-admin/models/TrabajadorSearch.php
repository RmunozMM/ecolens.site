<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Trabajador;

/**
 * TrabajadorSearch representa el modelo detrás de la búsqueda de `app\models\Trabajador`.
 */
class TrabajadorSearch extends Trabajador
{
    /**
     * {@inheritdoc}
     */
    public $globalSearch;

    public function rules()
    {
        return [
            [['tra_id', 'created_by', 'updated_by'], 'integer'],
            [['tra_nombre', 'tra_apellido', 'tra_cedula', 'tra_fecha_nacimiento', 'tra_genero',
              'tra_puesto', 'tra_departamento', 'tra_fecha_contratacion', 'tra_email', 'tra_telefono',
              'tra_direccion', 'tra_foto_perfil', 'tra_descripcion', 'tra_facebook', 'tra_instagram',
              'tra_linkedin', 'tra_tiktok', 'tra_twitter', 'tra_whatsapp', 'tra_modalidad_contrato',
              'tra_publicado', 'tra_estado', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['tra_salario'], 'number'],
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
     * Crea un data provider con la búsqueda aplicada.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Trabajador::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tra_id' => $this->tra_id,
            'tra_fecha_nacimiento' => $this->tra_fecha_nacimiento,
            'tra_fecha_contratacion' => $this->tra_fecha_contratacion,
            'tra_salario' => $this->tra_salario,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tra_nombre', $this->tra_nombre])
              ->andFilterWhere(['like', 'tra_apellido', $this->tra_apellido])
              ->andFilterWhere(['like', 'tra_cedula', $this->tra_cedula])
              ->andFilterWhere(['like', 'tra_genero', $this->tra_genero])
              ->andFilterWhere(['like', 'tra_puesto', $this->tra_puesto])
              ->andFilterWhere(['like', 'tra_departamento', $this->tra_departamento])
              ->andFilterWhere(['like', 'tra_email', $this->tra_email])
              ->andFilterWhere(['like', 'tra_telefono', $this->tra_telefono])
              ->andFilterWhere(['like', 'tra_direccion', $this->tra_direccion])
              ->andFilterWhere(['like', 'tra_foto_perfil', $this->tra_foto_perfil])
              ->andFilterWhere(['like', 'tra_descripcion', $this->tra_descripcion])
              ->andFilterWhere(['like', 'tra_facebook', $this->tra_facebook])
              ->andFilterWhere(['like', 'tra_instagram', $this->tra_instagram])
              ->andFilterWhere(['like', 'tra_linkedin', $this->tra_linkedin])
              ->andFilterWhere(['like', 'tra_tiktok', $this->tra_tiktok])
              ->andFilterWhere(['like', 'tra_twitter', $this->tra_twitter])
              ->andFilterWhere(['like', 'tra_whatsapp', $this->tra_whatsapp])
              ->andFilterWhere(['like', 'tra_modalidad_contrato', $this->tra_modalidad_contrato])
              ->andFilterWhere(['like', 'tra_publicado', $this->tra_publicado])
              ->andFilterWhere(['like', 'tra_estado', $this->tra_estado]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'tra_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'tra_apellido', $this->globalSearch])
                  ->orFilterWhere(['like', 'tra_cedula', $this->globalSearch])
                  ->orFilterWhere(['like', 'tra_puesto', $this->globalSearch])
                  ->orFilterWhere(['like', 'tra_departamento', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
