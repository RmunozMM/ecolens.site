<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Newsletter;

/**
 * NewsletterSearch representa el modelo detrás de la búsqueda de `app\models\Newsletter`.
 */
class NewsletterSearch extends Newsletter
{
    // Propiedad para búsqueda global opcional
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['new_id', 'created_by', 'updated_by'], 'integer'],
            [['new_email', 'new_estado', 'new_verificado', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
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
     * Crea una instancia de proveedor de datos con la consulta aplicada
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Newsletter::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'new_id' => $this->new_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'new_email', $this->new_email])
              ->andFilterWhere(['like', 'new_estado', $this->new_estado])
              ->andFilterWhere(['like', 'new_verificado', $this->new_verificado]);

        // Filtro global
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'new_email', $this->globalSearch])
                  ->orFilterWhere(['like', 'new_estado', $this->globalSearch])
                  ->orFilterWhere(['like', 'new_verificado', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
