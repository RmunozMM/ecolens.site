<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pagina;

class PaginaSearch extends Pagina
{
    // Atributos para búsqueda adicional
    public $globalSearch;
    public $pag_css_programador;
    public $pag_plantilla;
    public $pag_fuente_contenido;
    public $pag_menu_principal_tipo;
    public $pag_menu_secundario_tipo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pag_id', 'pag_autor_id', 'created_by', 'updated_by'], 'integer'],
            [[
                'pag_titulo',
                'pag_slug',
                'pag_modo_contenido',
                'pag_fuente_contenido',
                'pag_estado',
                'pag_mostrar_menu',
                'pag_menu_principal_tipo',
                'pag_mostrar_menu_secundario',
                'pag_menu_secundario_tipo',
                'pag_label',
                'created_at',
                'updated_at',
                'globalSearch',
                'pag_css_programador',
                'pag_plantilla',
            ], 'safe'],
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
     * Crea el data provider con la búsqueda aplicada
     */
    public function search($params)
    {
        $query = Pagina::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['pag_posicion' => SORT_ASC],
            ],
        ]);

        // Cargar parámetros y validar
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filtros exactos
        $query->andFilterWhere([
            'pag_id'        => $this->pag_id,
            'pag_autor_id'  => $this->pag_autor_id,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'created_by'    => $this->created_by,
            'updated_by'    => $this->updated_by,
        ]);

        // Filtros con LIKE
        $query->andFilterWhere(['like', 'pag_titulo', $this->pag_titulo])
              ->andFilterWhere(['like', 'pag_slug', $this->pag_slug])
              ->andFilterWhere(['like', 'pag_modo_contenido', $this->pag_modo_contenido])
              ->andFilterWhere(['like', 'pag_fuente_contenido', $this->pag_fuente_contenido])
              ->andFilterWhere(['like', 'pag_estado', $this->pag_estado])
              ->andFilterWhere(['like', 'pag_mostrar_menu', $this->pag_mostrar_menu])
              ->andFilterWhere(['like', 'pag_menu_principal_tipo', $this->pag_menu_principal_tipo])
              ->andFilterWhere(['like', 'pag_mostrar_menu_secundario', $this->pag_mostrar_menu_secundario])
              ->andFilterWhere(['like', 'pag_menu_secundario_tipo', $this->pag_menu_secundario_tipo])
              ->andFilterWhere(['like', 'pag_label', $this->pag_label])
              ->andFilterWhere(['like', 'pag_css_programador', $this->pag_css_programador])
              ->andFilterWhere(['like', 'pag_plantilla', $this->pag_plantilla]);

        // 🔍 Búsqueda global (opcional)
        if (!empty($this->globalSearch)) {
            $query->andFilterWhere(['or',
                ['like', 'pag_titulo', $this->globalSearch],
                ['like', 'pag_slug', $this->globalSearch],
                ['like', 'pag_label', $this->globalSearch],
                ['like', 'pag_estado', $this->globalSearch],
                ['like', 'pag_css_programador', $this->globalSearch],
                ['like', 'pag_plantilla', $this->globalSearch],
                ['like', 'pag_fuente_contenido', $this->globalSearch],
                ['like', 'pag_menu_principal_tipo', $this->globalSearch],
                ['like', 'pag_menu_secundario_tipo', $this->globalSearch],
            ]);
        }

        return $dataProvider;
    }
}