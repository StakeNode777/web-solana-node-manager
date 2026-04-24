<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var array $data */

$this->title = 'Add Validator - Step 2: Confirmation';
$this->params['breadcrumbs'][] = ['label' => 'Validators', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Add Validator (Step 2)';
?>
<div class="validator-create-step2">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please confirm the details retrieved from the API.</p>

    <?php if (!empty($data['img_url'])): ?>
        <?= Html::img($data['img_url'], ['alt' => 'Validator Logo', 'style' => 'max-width: 100px; border-radius: 5px; margin-bottom: 15px;']) ?>
    <?php endif; ?>

    <table class="table table-bordered">
        <tr>
            <th>Identity</th>
            <td><?= Html::encode($data['identity']) ?></td>
        </tr>
        <tr>
            <th>Cluster</th>
            <td><?= Html::encode($data['cluster']) ?></td>
        </tr>
         <tr>
            <th>Validator Name</th>
            <td><?= Html::encode($data['name'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Vote Account</th>
            <td><?= Html::encode($data['vote_account'] ?? 'N/A') ?></td>
        </tr>
    </table>
    
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton('Add Validator', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['create-step1'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>