<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dispositivo;

/**
 * DispositivoSearch represents the model behind the search form of `app\models\Dispositivo`.
 */
class DispositivoSearch extends Dispositivo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dis_id', 'dis_usuario_id'], 'integer'],
            [['dis_tipo', 'dis_sistema_operativo', 'dis_navegador', 'dis_user_agent', 'dis_ip_origen', 'created_at'], 'safe'],
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
        $query = Dispositivo::find();

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
            'dis_id' => $this->dis_id,
            'dis_usuario_id' => $this->dis_usuario_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'dis_tipo', $this->dis_tipo])
            ->andFilterWhere(['like', 'dis_sistema_operativo', $this->dis_sistema_operativo])
            ->andFilterWhere(['like', 'dis_navegador', $this->dis_navegador])
            ->andFilterWhere(['like', 'dis_user_agent', $this->dis_user_agent])
            ->andFilterWhere(['like', 'dis_ip_origen', $this->dis_ip_origen]);

        return $dataProvider;
    }
}
