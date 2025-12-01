<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Modulo;

/**
 * ModuloSearch representa el modelo detrás de la búsqueda de `app\models\Modulo`.
 */
class ModuloSearch extends Modulo
{
    public $curso; // Campo para filtrar por curso
    public $globalSearch; // Búsqueda global

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mod_id', 'mod_orden', 'mod_cur_id', 'created_by', 'updated_by'], 'integer'],
            [['mod_titulo', 'mod_estado', 'mod_slug', 'mod_imagen', 'mod_descripcion', 'curso', 'created_at', 'updated_at', 'globalSearch'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // Saltamos la implementación de escenarios en la clase padre
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
        $query = Modulo::find()->alias('m')
            ->leftJoin('cursos c', 'm.mod_cur_id = c.cur_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtros exactos
        $query->andFilterWhere([
            'm.mod_id' => $this->mod_id,
            'm.mod_orden' => $this->mod_orden,
            'm.mod_cur_id' => $this->mod_cur_id,
            'm.created_by' => $this->created_by,
            'm.updated_by' => $this->updated_by,
            'm.created_at' => $this->created_at,
            'm.updated_at' => $this->updated_at,
        ]);

        // Filtros tipo like
        $query->andFilterWhere(['like', 'm.mod_titulo', $this->mod_titulo])
              ->andFilterWhere(['like', 'm.mod_descripcion', $this->mod_descripcion])
              ->andFilterWhere(['like', 'm.mod_estado', $this->mod_estado])
              ->andFilterWhere(['like', 'm.mod_slug', $this->mod_slug])
              ->andFilterWhere(['like', 'm.mod_imagen', $this->mod_imagen])
              ->andFilterWhere(['like', 'c.cur_titulo', $this->curso]);

        // Filtro global
        if (!empty($this->globalSearch)) {
            $query->orFilterWhere(['like', 'm.mod_titulo', $this->globalSearch])
                  ->orFilterWhere(['like', 'm.mod_descripcion', $this->globalSearch])
                  ->orFilterWhere(['like', 'm.mod_estado', $this->globalSearch])
                  ->

                  $query->andFilterWhere(['created_by' => $this->created_by]);
                  $query->andFilterWhere(['updated_by' => $this->updated_by]);
                  $query->andFilterWhere(['like', 'created_at', $this->created_at]);
                  $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);
        }

        return $dataProvider;
    }
}
