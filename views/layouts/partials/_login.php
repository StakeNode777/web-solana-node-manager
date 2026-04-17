<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

$mode = isset($mode) ? $mode : 0;
?>

<div class="site-login container-fluid">
    <div class="row">
        <div class="col-md-12 main-col">
            <?php /*
            <?= Html::a('<span class="fa fa-facebook fa-lg icon-facebok"></span> Login with Facebook', ['/user/federated/facebook/1'], ['class'=>'btn btn-block btn-primary']) ?>
            <?= Html::a('<span class="fa fa-google fa-lg icon-google"></span> Login with Google', ['/user/federated/google/1'], ['class'=>'btn btn-block btn-default google-btn']) ?>

            <div class="popup-hr-legend">
                <span class="title">
                    or with email
                </span>
            </div>
            */?>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form-' . $mode,
                'action' => Url::to(['/site/login']),
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => false,
                'validateOnSubmit' => true,
            ]); ?>

                <?= $form->field($model, 'email')->textInput([]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('Log In', ['class' => 'btn btn-block btn-md popup-login-btn font-weight-bold', 'name' => 'login-button']) ?>
                    <?= Html::a('Forgot password?', ['site/request-password-reset'], ['class'=>'popup-forgot-link']) ?>
                </div>

                <?php
                /* LIVE: 
                <div class="row-center reg-acc" style="margin-top:30px;">
                    <h4><?= Html::a('Register an Account', ['site/register']) ?></h4>
                </div>
                 */
                ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>