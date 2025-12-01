<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Modelo;

/**
 * ModeloSearch represents the model behind the search form of `app\models\Modelo`.
 */
class ModeloSearch extends Modelo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_id'], 'integer'],
            [['mod_nombre', 'mod_version', 'mod_archivo', 'mod_dataset', 'mod_fecha_entrenamiento', 'mod_estado', 'mod_notas', 'mod_tipo'], 'safe'],
            [['mod_precision_val'], 'number'],
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
        $query = Modelo::find();

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
            'mod_id' => $this->mod_id,
            'mod_precision_val' => $this->mod_precision_val,
            'mod_fecha_entrenamiento' => $this->mod_fecha_entrenamiento,
        ]);

        $query->andFilterWhere(['like', 'mod_nombre', $this->mod_nombre])
            ->andFilterWhere(['like', 'mod_version', $this->mod_version])
            ->andFilterWhere(['like', 'mod_archivo', $this->mod_archivo])
            ->andFilterWhere(['like', 'mod_dataset', $this->mod_dataset])
            ->andFilterWhere(['like', 'mod_estado', $this->mod_estado])
            ->andFilterWhere(['like', 'mod_notas', $this->mod_notas])
            ->andFilterWhere(['like', 'mod_tipo', $this->mod_tipo]);

        return $dataProvider;
    }
}
