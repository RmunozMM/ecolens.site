<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DeteccionSearch representa el modelo de búsqueda para `app\models\Deteccion`.
 * Permite filtrar y listar registros de detecciones en el panel administrativo.
 */
class DeteccionSearch extends Deteccion
{
    /** Campos virtuales (para filtros por texto en relaciones) */
    public $esp_nombre_cientifico;
    public $tax_nombre;
    public $obs_nombre;

    public function rules()
    {
        return [
            [
                [
                    'det_id',
                    'det_modelo_router_id',
                    'det_modelo_experto_id',
                    'det_tax_id',
                    'det_esp_id',
                    'det_obs_id',
                    'det_validado_por',
                    'det_tiempo_router_ms',
                    'det_tiempo_experto_ms',
                ],
                'integer'
            ],
            [
                [
                    'det_imagen',
                    'det_origen_archivo',
                    'det_ubicacion_textual',
                    'det_ip_cliente',
                    'det_fuente',
                    'det_estado',
                    'det_revision_estado',
                    'det_dispositivo_tipo',
                    'det_sistema_operativo',
                    'det_navegador',
                    'det_observaciones',
                    'det_fecha',
                    'det_validacion_fecha',
                    'created_at',
                    'updated_at',
                    'esp_nombre_cientifico',
                    'tax_nombre',
                    'obs_nombre',
                ],
                'safe'
            ],
            [
                [
                    'det_confianza_router',
                    'det_confianza_experto',
                    'det_latitud',
                    'det_longitud'
                ],
                'number'
            ],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        // JOIN con alias explícitos para evitar conflictos
        $query = Deteccion::find()
            ->alias('d')
            ->joinWith(['especie e'])     // alias 'e' para especies
            ->joinWith(['taxonomia t'])   // alias 't' para taxonomias
            ->joinWith(['observador o']); // alias 'o' para observadores

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        // === ORDENAMIENTO (sort attributes) ===
        $dataProvider->sort->attributes['esp_nombre_cientifico'] = [
            'asc'  => ['e.esp_nombre_cientifico' => SORT_ASC],
            'desc' => ['e.esp_nombre_cientifico' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['tax_nombre'] = [
            'asc'  => ['t.tax_nombre' => SORT_ASC],
            'desc' => ['t.tax_nombre' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['obs_nombre'] = [
            'asc'  => ['o.obs_nombre' => SORT_ASC],
            'desc' => ['o.obs_nombre' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // === FILTROS EXACTOS / NUMÉRICOS ===
        $query->andFilterWhere([
            'd.det_id'                => $this->det_id,
            'd.det_confianza_router'  => $this->det_confianza_router,
            'd.det_confianza_experto' => $this->det_confianza_experto,
            'd.det_modelo_router_id'  => $this->det_modelo_router_id,
            'd.det_modelo_experto_id' => $this->det_modelo_experto_id,
            'd.det_tax_id'            => $this->det_tax_id,
            'd.det_esp_id'            => $this->det_esp_id,
            'd.det_obs_id'            => $this->det_obs_id,
            'd.det_validado_por'      => $this->det_validado_por,
            'd.det_tiempo_router_ms'  => $this->det_tiempo_router_ms,
            'd.det_tiempo_experto_ms' => $this->det_tiempo_experto_ms,
            'd.det_latitud'           => $this->det_latitud,
            'd.det_longitud'          => $this->det_longitud,
            'd.det_fecha'             => $this->det_fecha,
            'd.det_validacion_fecha'  => $this->det_validacion_fecha,
        ]);

        // === FILTROS TEXTUALES ===
        $query->andFilterWhere(['like', 'd.det_imagen', $this->det_imagen])
              ->andFilterWhere(['like', 'd.det_origen_archivo', $this->det_origen_archivo])
              ->andFilterWhere(['like', 'd.det_ubicacion_textual', $this->det_ubicacion_textual])
              ->andFilterWhere(['like', 'd.det_ip_cliente', $this->det_ip_cliente])
              ->andFilterWhere(['like', 'd.det_fuente', $this->det_fuente])
              ->andFilterWhere(['like', 'd.det_estado', $this->det_estado])
              ->andFilterWhere(['like', 'd.det_revision_estado', $this->det_revision_estado])
              ->andFilterWhere(['like', 'd.det_dispositivo_tipo', $this->det_dispositivo_tipo])
              ->andFilterWhere(['like', 'd.det_sistema_operativo', $this->det_sistema_operativo])
              ->andFilterWhere(['like', 'd.det_navegador', $this->det_navegador])
              ->andFilterWhere(['like', 'd.det_observaciones', $this->det_observaciones])
              ->andFilterWhere(['like', 'd.created_at', $this->created_at])
              ->andFilterWhere(['like', 'd.updated_at', $this->updated_at]);

        // === FILTROS POR RELACIONES ===
        $query->andFilterWhere(['like', 'e.esp_nombre_cientifico', $this->esp_nombre_cientifico])
              ->andFilterWhere(['like', 't.tax_nombre', $this->tax_nombre])
              ->andFilterWhere(['like', 'o.obs_nombre', $this->obs_nombre]);

        return $dataProvider;
    }
}