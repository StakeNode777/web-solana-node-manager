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

echo '<h2 style="margin-bottom: 0.5rem">Configure Validator Profile</h2>';
echo '<h6 style="margin-bottom: 1.5rem">Connect to Solana Node Manager to manage identity. 
        <a src="https://github.com/StakeNode777/solana-node-manager/blob/main/README.md#snm-external-interface-optional">Learn more</a></h6>';

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