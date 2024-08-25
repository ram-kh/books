<?php

use app\models\Author\Author;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php
    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'authors')->listBox(
        ArrayHelper::map(Author::findAllasArray(), 'id', 'fio'),
        ['multiple' => true],
    ) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?php
    if ($model->picture_id): ?>
        <p><?= $model->attributeLabels()['picture'] ?>
        <br>
            <?php
            echo Html::img(
                $model->file?->getUrl(),
                [
                    'alt' => $model->file?->name,
                    'height' => '100px',
                ],
            ); ?>
        </p>
    <?php
    endif; ?>

    <?= $form->field($model, 'picture')->fileInput()->label('Загрузить новое фото обложки') ?>

    <?= $form->field($model, 'annotation')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php
    ActiveForm::end(); ?>

</div>
