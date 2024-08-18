<?php

namespace app\models;

use Carbon\Carbon;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class ModelAR extends ActiveRecord
{
    public function behaviors(): array
    {
        $behaviors = [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => Carbon::now()->toDateTimeString(),
            ],
        ];

        return $behaviors;
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere(['deleted_at' => null]);
    }
}