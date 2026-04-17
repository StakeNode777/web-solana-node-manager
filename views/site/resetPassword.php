<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'New password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container site-reset-password">
    <h2><?= Html::encode($this->title) ?></h2>

    <p>Please enter your new password:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
