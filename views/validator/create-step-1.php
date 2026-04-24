<?php

use app\models\Validator;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Validator $model */

$this->title = 'Add Validator - Step 1: Identity';
$this->params['breadcrumbs'][] = ['label' => 'Validators', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Add Validator (Step 1)';
?>
<div class="validator-create-step1">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'identity')->textInput(['maxlength' => true])->hint('Enter the Solana public key for the validator identity.') ?>

    <?= $form->field($model, 'cluster')->dropDownList([
        Validator::CLUSTER_MAINNET => 'Mainnet',
        Validator::CLUSTER_TESTNET => 'Testnet',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Back To Dashboard', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>