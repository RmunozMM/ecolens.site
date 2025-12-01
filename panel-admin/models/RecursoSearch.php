<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Recurso;

/**
 * RecursoSearch representa el modelo detrás de la búsqueda de `app\models\Recurso`.
 */
class RecursoSearch extends Recurso
{
    public $leccion;
    public $modulo;
    public $curso;
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rec_id', 'created_by', 'updated_by', 'rec_lec_id'], 'integer'],
            [['rec_titulo', 'rec_tipo', 'rec_url', 'rec_descripcion', 'rec_imagen', 'rec_estado', 'rec_icono', 'rec_slug', 'created_at', 'updated_at', 'leccion', 'modulo', 'curso', 'globalSearch'], 'safe'],
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
     * Crea una instancia de proveedor de datos con la consulta de búsqueda aplicada.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Recurso::find()->alias('r')
            ->leftJoin('lecciones l', 'r.rec_lec_id = l.lec_id')
            ->leftJoin('modulos m', 'l.lec_mod_id = m.mod_id')
            ->leftJoin('cursos c', 'm.mod_cur_id = c.cur_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'r.rec_id' => $this->rec_id,
            'r.rec_lec_id' => $this->rec_lec_id,
            'r.created_by' => $this->created_by,
            'r.updated_by' => $this->updated_by,
            'r.created_at' => $this->created_at,
            'r.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'r.rec_titulo', $this->rec_titulo])
            ->andFilterWhere(['like', 'r.rec_tipo', $this->rec_tipo])
            ->andFilterWhere(['like', 'r.rec_url', $this->rec_url])
            ->andFilterWhere(['like', 'r.rec_descripcion', $this->rec_descripcion])
            ->andFilterWhere(['like', 'r.rec_imagen', $this->rec_imagen])
            ->andFilterWhere(['like', 'r.rec_icono', $this->rec_icono])
            ->andFilterWhere(['like', 'r.rec_estado', $this->rec_estado])
            ->andFilterWhere(['like', 'r.rec_slug', $this->rec_slug]);

        $query->andFilterWhere(['like', 'l.lec_titulo', $this->leccion])
            ->andFilterWhere(['like', 'm.mod_titulo', $this->modulo])
            ->andFilterWhere(['like', 'c.cur_titulo', $this->curso]);

        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'r.rec_titulo', $this->globalSearch])
                ->orFilterWhere(['like', 'r.rec_tipo', $this->globalSearch])
                ->orFilterWhere(['like', 'r.rec_url', $this->globalSearch])
                ->orFilterWhere(['like', 'r.rec_descripcion', $this->globalSearch])
                ->orFilterWhere(['like', 'r.rec_estado', $this->globalSearch])
                ->orFilterWhere(['like', 'r.rec_slug', $this->globalSearch])
                ->orFilterWhere(['like', 'r.rec_icono', $this->globalSearch]);
        }

        $query->andFilterWhere(['created_by' => $this->created_by]);
        $query->andFilterWhere(['updated_by' => $this->updated_by]);
        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
