<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Author\Author $model */

$this->title = 'Добавление автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
