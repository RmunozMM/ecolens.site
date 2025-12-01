<?php

namespace app\helpers;

use yii\helpers\ArrayHelper;
use app\models\Users;
use Yii;

class AuditoriaGridColumns
{
    public static function createdBy()
    {
        return [
            'attribute' => 'created_by',
            'label' => 'Creado por',
            'format' => 'text',
            'filter' => ArrayHelper::map(Users::find()->all(), 'usu_id', 'usu_username'),
            'value' => fn($model) => $model->createdByUser->usu_username ?? '—',
        ];
    }

    public static function updatedBy()
    {
        return [
            'attribute' => 'updated_by',
            'label' => 'Modificado por',
            'format' => 'text',
            'filter' => ArrayHelper::map(Users::find()->all(), 'usu_id', 'usu_username'),
            'value' => fn($model) => $model->updatedByUser->usu_username ?? '—',
        ];
    }

    public static function createdAt()
    {
        return [
            'attribute' => 'created_at',
            'label' => 'Fecha creación',
            'format' => ['date', 'php:d-m-Y H:i'],
            'filter' => true,
        ];
    }

    public static function updatedAt()
    {
        return [
            'attribute' => 'updated_at',
            'label' => 'Fecha modificación',
            'format' => ['date', 'php:d-m-Y H:i'],
            'filter' => true,
        ];
    }

    public static function todas()
    {
        return [
            self::createdBy(),
            self::createdAt(),
            self::updatedBy(),
            self::updatedAt(),
        ];
    }

    public static function getAuditoriaAttributes($model)
{
    return [
        [
            'label' => 'Creado por',
            'value' => $model->createdByUser->usu_username ?? 'Desconocido',
        ],
        [
            'label' => 'Modificado por',
            'value' => $model->updatedByUser->usu_username ?? 'Desconocido',
        ],
        [
            'label' => 'Fecha de creación',
            'value' => Yii::$app->formatter->asDatetime($model->created_at),
        ],
        [
            'label' => 'Fecha de modificación',
            'value' => Yii::$app->formatter->asDatetime($model->updated_at),
        ],
    ];
}
}