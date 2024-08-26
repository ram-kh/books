<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;

class SoftDeleteQuery extends ActiveQuery
{
    public function __construct($modelClass, $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->onlyDeleted();
    }

    private function onlyDeleted(): void
    {
        [, $tableAlias] = $this->getTableNameAndAlias();

        $this->andWhere(["$tableAlias.[[deleted_at]]" => null]);
    }
}
