<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Taxonomia;

/**
 * TaxonomiaSearch representa el modelo detrás del formulario de búsqueda para `app\models\Taxonomia`.
 */
class TaxonomiaSearch extends Taxonomia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_id', 'created_by', 'updated_by'], 'integer'],
            [['tax_nombre', 'tax_nombre_comun', 'tax_descripcion', 'tax_imagen', 'tax_estado', 'created_at', 'updated_at'], 'safe'],
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
     * Crea una instancia de ActiveDataProvider con la consulta aplicada.
     *
     * @param array $params
     * @param string|null $formName Nombre del formulario a usar en ->load().
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Taxonomia::find();

        // condiciones iniciales
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // Descomenta si no quieres devolver registros cuando falla la validación
            // $query->where('0=1');
            return $dataProvider;
        }

        // condiciones de filtrado
        $query->andFilterWhere([
            'tax_id' => $this->tax_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'tax_nombre', $this->tax_nombre])
            ->andFilterWhere(['like', 'tax_nombre_comun', $this->tax_nombre_comun])
            ->andFilterWhere(['like', 'tax_descripcion', $this->tax_descripcion])
            ->andFilterWhere(['like', 'tax_imagen', $this->tax_imagen])
            ->andFilterWhere(['like', 'tax_estado', $this->tax_estado]);

        return $dataProvider;
    }
}