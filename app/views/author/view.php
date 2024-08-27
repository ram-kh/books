<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author\Author $model */

$this->title = $model->lastname . ' ' . $model->name . ' ' . $model->surname;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Yii::$app->user->can('createAuthor')) {
            echo Html::a('Добавить', ['create'], ['class' => 'btn btn-primary']);
        }
        ?>
        <?php
        if (Yii::$app->user->can('updateAuthor')) {
            echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        ?>
        <?php
        if (Yii::$app->user->can('deleteAuthor')) {
            echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'lastname',
            'name',
            'surname',
            'created_at',
            'updated_at',
//            'deleted_at',
        ],
    ]) ?>

</div>
