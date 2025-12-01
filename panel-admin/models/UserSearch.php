<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UserSearch represents the model behind the search form of `app\models\Users`.
 */
class UserSearch extends Users
{
    // Propiedad para búsqueda global
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usu_id'], 'integer'],
            [['usu_username', 'usu_email', 'usu_password', 'usu_authKey', 'usu_accessToken', 'usu_email_verificado', 'globalSearch'], 'safe'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Users::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtros específicos
        $query->andFilterWhere([
            'usu_id' => $this->usu_id,
        ]);

        $query->andFilterWhere(['like', 'usu_username', $this->usu_username])
              ->andFilterWhere(['like', 'usu_email', $this->usu_email])
              ->andFilterWhere(['like', 'usu_email_verificado', $this->usu_email_verificado])
              ->andFilterWhere(['like', 'usu_password', $this->usu_password])
              ->andFilterWhere(['like', 'usu_authKey', $this->usu_authKey])
              ->andFilterWhere(['like', 'usu_accessToken', $this->usu_accessToken]);

        // Filtro global: si globalSearch tiene un valor, se filtra en varios campos
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'usu_username', $this->globalSearch])
                  ->orFilterWhere(['like', 'usu_email', $this->globalSearch])
                  ->orFilterWhere(['like', 'usu_email_verificado', $this->globalSearch])
                  ->orFilterWhere(['like', 'usu_password', $this->globalSearch])
                  ->orFilterWhere(['like', 'usu_authKey', $this->globalSearch])
                  ->orFilterWhere(['like', 'usu_accessToken', $this->globalSearch]);
        }

        return $dataProvider;
    }
}