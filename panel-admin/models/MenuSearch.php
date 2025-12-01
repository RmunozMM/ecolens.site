<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Menu;

/**
 * MenuSearch represents the model behind the search form of `app\models\Menu`.
 */
class MenuSearch extends Menu
{
    // Propiedad para búsqueda global
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['men_id', 'men_rol_id', 'men_padre_id', 'created_by', 'updated_by'], 'integer'],
            [['men_nombre', 'men_mostrar', 'men_url', 'men_etiqueta', 'men_nivel', 'men_link_options', 'men_target', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Menu::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'men_id' => $this->men_id,
            'men_rol_id' => $this->men_rol_id,
            'men_padre_id' => $this->men_padre_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'men_nombre', $this->men_nombre])
              ->andFilterWhere(['like', 'men_url', $this->men_url])
              ->andFilterWhere(['like', 'men_etiqueta', $this->men_etiqueta])
              ->andFilterWhere(['like', 'men_nivel', $this->men_nivel])
              ->andFilterWhere(['like', 'men_mostrar', $this->men_mostrar])
              ->andFilterWhere(['like', 'men_link_options', $this->men_link_options])
              ->andFilterWhere(['like', 'men_target', $this->men_target]);

        // Filtro global: si se asigna globalSearch, se busca en varios campos
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'men_nombre', $this->globalSearch])
                  ->orFilterWhere(['like', 'men_url', $this->globalSearch])
                  ->orFilterWhere(['like', 'men_etiqueta', $this->globalSearch])
                  ->orFilterWhere(['like', 'men_nivel', $this->globalSearch])
                  ->orFilterWhere(['like', 'men_mostrar', $this->globalSearch])
                  ->orFilterWhere(['like', 'men_link_options', $this->globalSearch])
                  ->orFilterWhere(['like', 'men_target', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
