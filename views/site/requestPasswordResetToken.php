<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use himiklab\yii2\recaptcha\ReCaptcha2;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container site-request-password-reset">
    <h2><?= Html::encode($this->title) ?></h2>

    <p>Enter your email address and we will send you a link to reset your password..</p>

    <div class="row">
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
            ]); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?php 
                    if (!empty(\Yii::$app->params['reCaptcha.siteKey'])) {
                        echo $form->field($model, 'reCaptcha')->widget(
                            ReCaptcha2::class,
                            ['siteKey' => \Yii::$app->params['reCaptcha.siteKey']]
                        );    
                    }
                ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
