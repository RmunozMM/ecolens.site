<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Articulo;

use app\components\traits\AuditoriaSearchTrait;

/**
 * ArticuloSearch represents the model behind the search form of `app\models\Articulo`.
 */
class ArticuloSearch extends Articulo
{
    /**
     * Propiedad para búsqueda global
     */
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['art_id', 'art_vistas', 'art_likes', 'art_categoria_id'], 'integer'],
            [['art_titulo', 'art_contenido', 'art_resumen', 'art_etiquetas', 'art_fecha_publicacion', 'art_destacado', 'art_comentarios_habilitados', 'art_palabras_clave', 'art_meta_descripcion', 'art_slug', 'art_estado', 'art_notificacion', 'art_imagen'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'], // ✅ agregado

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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Articulo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'art_id' => $this->art_id,
            'art_fecha_publicacion' => $this->art_fecha_publicacion,
            'art_vistas' => $this->art_vistas,
            'art_likes' => $this->art_likes,
            'art_categoria_id' => $this->art_categoria_id,
        ]);

        $query->andFilterWhere(['like', 'art_titulo', $this->art_titulo])
            ->andFilterWhere(['like', 'art_contenido', $this->art_contenido])
            ->andFilterWhere(['like', 'art_resumen', $this->art_resumen])
            ->andFilterWhere(['like', 'art_etiquetas', $this->art_etiquetas])
            ->andFilterWhere(['like', 'art_destacado', $this->art_destacado])
            ->andFilterWhere(['like', 'art_comentarios_habilitados', $this->art_comentarios_habilitados])
            ->andFilterWhere(['like', 'art_palabras_clave', $this->art_palabras_clave])
            ->andFilterWhere(['like', 'art_meta_descripcion', $this->art_meta_descripcion])
            ->andFilterWhere(['like', 'art_slug', $this->art_slug])
            ->andFilterWhere(['like', 'art_estado', $this->art_estado])
            ->andFilterWhere(['like', 'art_notificacion', $this->art_notificacion])
            ->andFilterWhere(['like', 'art_imagen', $this->art_imagen]);


            $query->andFilterWhere(['created_by' => $this->created_by]);
            $query->andFilterWhere(['updated_by' => $this->updated_by]);
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);
            $query->andFilterWhere(['like', 'updated_at', $this->updated_at]);


        return $dataProvider;
    }
}
