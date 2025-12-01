<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Modalidad;

/**
 * ModalidadSearch represents the model behind the search form of `app\models\Modalidad`.
 */
class ModalidadSearch extends Modalidad
{
    // Propiedad para búsqueda global
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_id','created_by', 'updated_by'], 'integer'],
            [['mod_nombre', 'mod_publicado', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Modalidad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtros estándar
        $query->andFilterWhere([
            'mod_id' => $this->mod_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mod_nombre', $this->mod_nombre])
              ->andFilterWhere(['like', 'mod_publicado', $this->mod_publicado]);

        // Filtro global
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'mod_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'mod_publicado', $this->globalSearch])
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
