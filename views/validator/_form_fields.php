<?php

use yii\helpers\Html;
use app\models\Validator;

/** @var yii\web\View $this */
/** @var app\models\Validator $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php if ($model->img_url): ?>
    <div class="mb-3">
        <?= Html::img($model->img_url, ['alt' => 'Validator Logo', 'class' => 'img-thumbnail', 'width' => '150']) ?>
        
    </div>
<?php endif; ?>

<?= $form->field($model, 'identity')->textInput(['readonly' => true]) ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'vote_account')->textInput(['readonly' => !$model->isNewRecord]) ?>
<?= $form->field($model, 'cluster')->dropDownList(
    [Validator::CLUSTER_MAINNET => 'Mainnet', Validator::CLUSTER_TESTNET => 'Testnet'],
    ['prompt' => 'Select Cluster']
) ?>
<?= $form->field($model, 'snm_server')->textInput(['maxlength' => true, 'placeholder' => 'e.g., 192.168.1.1']) ?>
<?= $form->field($model, 'health')->dropDownList(
    [Validator::HEALTH_OK => 'OK', Validator::HEALTH_DELINQUENT => 'DELINQUENT'],
    ['prompt' => 'Select Health Status']
) ?>
<?= $form->field($model, 'details')->textInput(['maxlength' => true, 'placeholder' => 'https://link-to-details.com']) ?>

<?= $form->field($model, 'img_url')->hiddenInput()->label(false) ?>