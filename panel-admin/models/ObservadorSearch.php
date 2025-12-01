<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Observador;

/**
 * ObservadorSearch represents the model behind the search form of `app\models\Observador`.
 */
class ObservadorSearch extends Observador
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['obs_id'], 'integer'],
            [[
                'obs_nombre',
                'obs_usuario',               // NUEVO
                'obs_email',
                'obs_institucion',
                'obs_experiencia',
                'obs_pais',
                'obs_ciudad',
                'obs_estado',
                'obs_fecha_registro',
                'obs_foto',
                'obs_act_token_hash',        // NUEVO
                'obs_act_expires',           // NUEVO (datetime)
                'obs_email_verificado_at',   // NUEVO (datetime)
                'created_at',
                'updated_at'
            ], 'safe'],
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
        $query = Observador::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions (exact matches para fechas si vienes con igualdad)
        $query->andFilterWhere([
            'obs_id'               => $this->obs_id,
            'obs_fecha_registro'   => $this->obs_fecha_registro,
            'obs_act_expires'      => $this->obs_act_expires,         // NUEVO
            'obs_email_verificado_at' => $this->obs_email_verificado_at, // NUEVO
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ]);

        // like-based for strings
        $query->andFilterWhere(['like', 'obs_nombre', $this->obs_nombre])
            ->andFilterWhere(['like', 'obs_usuario', $this->obs_usuario])               // NUEVO
            ->andFilterWhere(['like', 'obs_email', $this->obs_email])
            ->andFilterWhere(['like', 'obs_institucion', $this->obs_institucion])
            ->andFilterWhere(['like', 'obs_experiencia', $this->obs_experiencia])
            ->andFilterWhere(['like', 'obs_pais', $this->obs_pais])
            ->andFilterWhere(['like', 'obs_ciudad', $this->obs_ciudad])
            ->andFilterWhere(['like', 'obs_estado', $this->obs_estado])
            ->andFilterWhere(['like', 'obs_foto', $this->obs_foto])
            ->andFilterWhere(['like', 'obs_act_token_hash', $this->obs_act_token_hash]); // NUEVO

        return $dataProvider;
    }
}