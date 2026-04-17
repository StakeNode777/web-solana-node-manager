<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use himiklab\yii2\recaptcha\ReCaptcha2;


$this->title = 'Registration';
?>

<div class="container site-signup">
    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <?php $form = ActiveForm::begin(
            [
                'id' => 'form-register',
                'fieldConfig' => ['options' => ['class' => 'form-group form-group-lg']]
            ]);?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?php 
                    if (!empty(\Yii::$app->params['reCaptcha.siteKey'])) {
                        echo $form->field($model, 'reCaptcha')->widget(
                            ReCaptcha2::class,
                            ['siteKey' => \Yii::$app->params['reCaptcha.siteKey']]
                        );    
                    }
                ?>

                <?= $form->field($model, 'agree')->checkBox()->label('<span> I agree to the ' . Html::a('terms and conditions', ['page/terms-and-conditions']) . '</span>') ?>

               <div class="form-group">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-lg btn-primary reg-btn', 'name' => 'signup-button']) ?>
                    <?php /*<span class="or_indent">or</span>
                    <?php // https://lipis.github.io/bootstrap-social/ - EXAMPLE FOR BTN-SOCIAL ?>
                    <?= Html::a('<span class="fa fa-facebook"></span> Sign up with Facebook', ['/user/federated/facebook/1'], ['class'=>'btn btn-social btn-facebook pull-right']) ?>
                    <?= Html::a('<span class="fa fa-google"></span> Sign up with Google', ['/user/federated/google/1'], ['class'=>'btn btn-social btn-google pull-right']) ?>
                    */?>
                </div>
                
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <p class="text-right">Already have an account? <?=Html::a('Sign In', ['site/login'], ['class' => 'modal-login-btn'])?></p>
        </div>
    </div>
</div>
