<?php

/** @var yii\web\View $this */

/** @var yii\bootstrap5\ActiveForm $form */

/** @var \models\User\SubscribeForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Подписка на рассылку';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (Yii::$app->session->hasFlash('subscribeFormSubmitted')): ?>

        <div class="alert alert-success">
            Спасибо за подписку. Вам придет СМС уведомление о поступлении книги на подписанного автора.
        </div>

    <?php
    else: ?>

        <p>
            Если вы хотите подписаться на рассылку по поступлению книги интересующего вас автора, заполните следующую
            форму.
            Спасибо.
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php
                $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'authorId')->dropDownList($authors ?? []) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'phone')->widget(
                    \yii\widgets\MaskedInput::class,
                    [
                        'mask' => '+7 (999) 999 99 99',
                    ],
                )
                ?>

                <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php
                ActiveForm::end(); ?>

            </div>
        </div>

    <?php
    endif; ?>
</div>
