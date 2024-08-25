<?php

use app\models\Book\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Book\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Yii::$app->user->can('createBook')) {
            echo Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'authorsAsString',
            'title',
            'year',
            'isbn',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => static function (Book $model) {
                    return Html::img(
                        $model->file?->getUrl(),
                        [
                            'alt' => $model->file?->name,
                            'height' => '100px',
                        ],
                    );
                },


            ],
            'annotation:ntext',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {

                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'visibleButtons' => [
                    'update' => Yii::$app->user->can('updateBook'),
                    'delete' => Yii::$app->user->can('deleteBook'),
                ],
            ],
        ],
    ]); ?>


</div>
