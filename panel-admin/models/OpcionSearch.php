<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Opcion;

/**
 * OpcionSearch represents the model behind the search form of `app\models\Opcion`.
 */
class OpcionSearch extends Opcion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['opc_id', 'opc_cat_id', 'opc_rol_id', 'created_by', 'updated_by'], 'integer'],
            [['opc_nombre', 'opc_valor', 'opc_tipo', 'opc_descripcion', 'created_at', 'updated_at'], 'safe'],
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
        $query = Opcion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'opc_id' => $this->opc_id,
            'opc_cat_id' => $this->opc_cat_id,
            'opc_rol_id' => $this->opc_rol_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'opc_nombre', $this->opc_nombre])
            ->andFilterWhere(['like', 'opc_valor', $this->opc_valor])
            ->andFilterWhere(['like', 'opc_tipo', $this->opc_tipo])
            ->andFilterWhere(['like', 'opc_descripcion', $this->opc_descripcion]);

        return $dataProvider;
    }
}
