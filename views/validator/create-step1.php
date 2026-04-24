<?php
// views/validator/create-step1.php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerCss("
    .form-container {
        margin: 50px;
    }
");

$form = ActiveForm::begin(['options' => ['class' => 'form-container']]);
echo $form->field($model, 'identity')->textInput();
echo $form->field($model, 'cluster')->dropDownList(['Testnet' => 'Testnet', 'Mainnet' => 'Mainnet']);
echo Html::submitButton('Next', ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
echo Html::a('Back To Dashboard', ['index'], ['class' => 'btn btn-secondary']);
ActiveForm::end();