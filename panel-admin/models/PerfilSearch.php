<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Perfil;

class PerfilSearch extends Perfil
{
    public $globalSearch;

    public function rules()
    {
        return [
            [['per_id', 'singleton', 'created_by', 'updated_by'], 'integer'],
            [['per_tipo', 'per_nombre', 'per_fecha_nacimiento', 'per_lugar_nacimiento_fundacion', 'per_ubicacion', 'per_nacionalidad',
               'per_correo', 'per_telefono', 'per_direccion', 'per_linkedin', 'per_sitio_web', 'per_sector', 'per_idiomas',
              'per_fecha_creacion', 'per_imagen', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
              [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Perfil::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'per_id' => $this->per_id,
            'per_fecha_nacimiento' => $this->per_fecha_nacimiento,
            'per_fecha_creacion' => $this->per_fecha_creacion,
            'singleton' => $this->singleton,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'per_tipo', $this->per_tipo])
            ->andFilterWhere(['like', 'per_nombre', $this->per_nombre])
            ->andFilterWhere(['like', 'per_lugar_nacimiento_fundacion', $this->per_lugar_nacimiento_fundacion])
            ->andFilterWhere(['like', 'per_ubicacion', $this->per_ubicacion])
            ->andFilterWhere(['like', 'per_nacionalidad', $this->per_nacionalidad])
            ->andFilterWhere(['like', 'per_correo', $this->per_correo])
            ->andFilterWhere(['like', 'per_telefono', $this->per_telefono])
            ->andFilterWhere(['like', 'per_direccion', $this->per_direccion])
            ->andFilterWhere(['like', 'per_linkedin', $this->per_linkedin])
            ->andFilterWhere(['like', 'per_sitio_web', $this->per_sitio_web])
            ->andFilterWhere(['like', 'per_sector', $this->per_sector])
            ->andFilterWhere(['like', 'per_idiomas', $this->per_idiomas])
            ->andFilterWhere(['like', 'per_imagen', $this->per_imagen]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'per_tipo', $this->globalSearch])
                ->orFilterWhere(['like', 'per_nombre', $this->globalSearch])
                ->orFilterWhere(['like', 'per_nacionalidad', $this->globalSearch])
                ->orFilterWhere(['like', 'per_correo', $this->globalSearch])
                ->orFilterWhere(['like', 'per_sector', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
