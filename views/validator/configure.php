<?php
// views/validator/configure.php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use himiklab\yii2\recaptcha\ReCaptcha2;

$this->registerCss("
    .form-container {
        margin: 50px;
    }
");

$form = ActiveForm::begin(['options' => ['class' => 'form-container']]);
echo $form->field($model, 'snm_server')->textInput();
echo $form->field($model, 'snm_ssh_login')->textInput();
echo $form->field($model, 'snm_ssh_password')->passwordInput();

if (!empty(\Yii::$app->params['reCaptcha.siteKey'])) {
    echo $form->field($model, 'reCaptcha')->widget(
        ReCaptcha2::class,
        ['siteKey' => \Yii::$app->params['reCaptcha.siteKey']]
    );    
}
                
echo Html::submitButton('Save', ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
echo Html::a('Later', ['index'], ['class' => 'btn btn-secondary']);
ActiveForm::end();