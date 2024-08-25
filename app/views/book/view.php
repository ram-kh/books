<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Каталог книг', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Yii::$app->user->can('updateBook')) {
            echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        ?>
        <?php
        if (Yii::$app->user->can('deleteBook')) {
            echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены что хотите удалить эту книгу?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'authorsAsString',
            'title',
            'year',
            'isbn',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => Html::img(
                    $model->file?->getUrl(),
                    [
                        'alt' => $model->file?->name,
                        'height' => '200px',
                    ],
                ),
            ],
            'annotation:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
