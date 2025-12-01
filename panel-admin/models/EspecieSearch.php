<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Especie;

/**
 * EspecieSearch represents the model behind the search form of `app\models\Especie`.
 */
class EspecieSearch extends Especie
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['esp_id', 'esp_tax_id', 'created_by', 'updated_by'], 'integer'],
            [['esp_nombre_cientifico', 'esp_nombre_comun', 'esp_descripcion', 'esp_imagen', 'esp_estado', 'created_at', 'updated_at'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Especie::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'esp_id' => $this->esp_id,
            'esp_tax_id' => $this->esp_tax_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'esp_nombre_cientifico', $this->esp_nombre_cientifico])
            ->andFilterWhere(['like', 'esp_nombre_comun', $this->esp_nombre_comun])
            ->andFilterWhere(['like', 'esp_descripcion', $this->esp_descripcion])
            ->andFilterWhere(['like', 'esp_imagen', $this->esp_imagen])
            ->andFilterWhere(['like', 'esp_estado', $this->esp_estado]);

        return $dataProvider;
    }
}
