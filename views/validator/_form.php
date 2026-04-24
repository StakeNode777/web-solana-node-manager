<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Validator;

/** @var yii\web\View $this */
/** @var app\models\Validator $model */
/** @var yii\widgets\ActiveForm $form */
/** @var int|null $step */

// If $step is not defined, it means we are in the 'update' action.
$isUpdate = !isset($step);
?>

<div class="validator-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // --- CREATE WIZARD --- ?>
    <?php if (!$isUpdate): ?>

        <?php if ($step == 1): ?>
            <h3>Step 1: Provide Validator Identity</h3>
            <p>Enter the Solana validator's identity public key to fetch its details.</p>
            <?= $form->field($model, 'identity')->textInput(['maxlength' => true, 'placeholder' => 'Enter Solana Pubkey']) ?>
            <?= Html::hiddenInput('step', 1) ?>
            <div class="form-group mt-3">
                <?= Html::submitButton('Next <i class="fas fa-arrow-right"></i>', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Back to Dashboard', ['index'], ['class' => 'btn btn-link']) ?>
            </div>
        <?php endif; ?>

        <?php if ($step == 2): ?>
            <h3>Step 2: Confirm and Complete Details</h3>
            <p>We've fetched some information for you. Please review and fill in the remaining fields.</p>
            <?php include('_form_fields.php'); ?>
            <?= Html::hiddenInput('step', 2) ?>
            <div class="form-group mt-3">
                <?= Html::submitButton('Save Validator', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back to Dashboard', ['index'], ['class' => 'btn btn-link']) ?>
            </div>
        <?php endif; ?>

    <?php // --- UPDATE FORM --- ?>
    <?php else: ?>
        <h3>Update Validator Details</h3>
        <?php include('_form_fields.php'); ?>
        <div class="form-group mt-3">
            <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancel', ['view', 'id' => $model->id], ['class' => 'btn btn-link']) ?>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>