<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
class ClienteSearch extends Cliente
{
    // Propiedad para búsqueda global
    public $globalSearch;

    public function rules()
    {
        return [
            [['cli_id', 'created_by', 'updated_by'], 'integer'],
            [['cli_nombre', 'cli_email', 'cli_telefono', 'cli_direccion', 'cli_estado', 'cli_logo', 'cli_publicado', 'cli_destacado', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cliente::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cli_id' => $this->cli_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'cli_nombre', $this->cli_nombre])
              ->andFilterWhere(['like', 'cli_email', $this->cli_email])
              ->andFilterWhere(['like', 'cli_telefono', $this->cli_telefono])
              ->andFilterWhere(['like', 'cli_direccion', $this->cli_direccion])
              ->andFilterWhere(['like', 'cli_estado', $this->cli_estado])
              ->andFilterWhere(['like', 'cli_logo', $this->cli_logo])
              ->andFilterWhere(['like', 'cli_publicado', $this->cli_publicado])
              ->andFilterWhere(['like', 'cli_destacado', $this->cli_destacado]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'cli_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'cli_email', $this->globalSearch])
                  ->orFilterWhere(['like', 'cli_telefono', $this->globalSearch])
                  ->orFilterWhere(['like', 'cli_direccion', $this->globalSearch])
                  ->orFilterWhere(['like', 'cli_estado', $this->globalSearch])
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
