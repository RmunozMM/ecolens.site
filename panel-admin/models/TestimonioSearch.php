<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Testimonio;

/**
 * TestimonioSearch represents the model behind the search form of `app\models\Testimonio`.
 */
class TestimonioSearch extends Testimonio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tes_id', 'tes_orden', 'created_by', 'updated_by'], 'integer'],
            [['tes_nombre', 'tes_cargo', 'tes_empresa', 'tes_testimonio', 'tes_imagen', 'tes_estado', 'tes_slug', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
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
        $query = Testimonio::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tes_id' => $this->tes_id,
            'tes_orden' => $this->tes_orden,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tes_nombre', $this->tes_nombre])
              ->andFilterWhere(['like', 'tes_cargo', $this->tes_cargo])
              ->andFilterWhere(['like', 'tes_empresa', $this->tes_empresa])
              ->andFilterWhere(['like', 'tes_testimonio', $this->tes_testimonio])
              ->andFilterWhere(['like', 'tes_imagen', $this->tes_imagen])
              ->andFilterWhere(['like', 'tes_estado', $this->tes_estado])
              ->andFilterWhere(['like', 'tes_slug', $this->tes_slug]);

              $query->andFilterWhere(['created_by' => $this->created_by]);
              $query->andFilterWhere(['updated_by' => $this->updated_by]);
              $query->andFilterWhere(['like', 'created_at', $this->created_at]);
              $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}