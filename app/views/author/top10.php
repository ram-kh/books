<?php

use app\models\Author\Author;
use yii\bootstrap5\Dropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var int $year */
/** @var array $years */
/** @var yii\data\ArrayDataProvider $provider */

$this->title = "Топ 10 Авторов в {$year} году";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="dropdown">
        <a href="#" data-bs-toggle="dropdown" class="dropdown-toggle">
            Год издания <b class="caret"></b>
        </a>

        <?= Dropdown::widget(
            [
                'items' => array_map(
                    fn($yearItem) => [
                        'label' => $yearItem,
                        'active' => $yearItem === $year,
                        'url' => Url::to(['author/top10', 'year' => $yearItem]),
                    ],
                    $years,
                ),
            ],
        ); ?>
    </div>
    <br>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            [
                'format' => 'raw',
                'label' => 'ФИО автора',
                'value' => fn($model) => "{$model['lastname']} {$model['name']} {$model['surname']}",
            ],
            [
                'attribute' => 'rating',
                'label' => 'Рейтинг',
            ],
        ],
    ]); ?>
</div>
