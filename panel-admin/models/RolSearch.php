<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rol;

/**
 * RolSearch represents the model behind the search form of `app\models\Roles`.
 */
class RolSearch extends Rol
{
    // Propiedad para búsqueda global
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rol_id', 'created_by', 'updated_by'], 'integer'],
            [['rol_nombre', 'rol_descripcion', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Rol::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // opcional: $query->where('0=1');
            return $dataProvider;
        }

        // Filtros específicos
        $query->andFilterWhere([
            'rol_id' => $this->rol_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'rol_nombre', $this->rol_nombre])
              ->andFilterWhere(['like', 'rol_descripcion', $this->rol_descripcion]);

        // Filtro global: si se asigna globalSearch, buscar en ambos campos
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'rol_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'rol_descripcion', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
