<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book\Book $model */

$this->title = 'Добавить книгу';
$this->params['breadcrumbs'][] = ['label' => 'Каталог книг', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
