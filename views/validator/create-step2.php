<?php
// views/validator/create-step2.php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCss("
    .form-container {
        margin: 50px;
    }
");

echo '<h1>Step 2: Review Validator Details</h1>';
echo '<div class="form-container">';
echo '<p>Identity: ' . Html::encode($data['identity']) . '</p>';
echo '<p>Cluster: ' . Html::encode($data['cluster']) . '</p>';
echo '<p>Vote Account: ' . Html::encode($data['vote_account']) . '</p>';
echo '<p>Name: ' . Html::encode($data['name'] ?: 'Unnamed') . '</p>';
if (!empty($data['img_url'])) {
    echo Html::img($data['img_url'], ['width' => 100, 'alt' => 'Validator Logo']);
}

$form = ActiveForm::begin();
echo Html::submitButton('Add', ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
echo Html::a('Back', ['create-step1'], ['class' => 'btn btn-secondary']);
ActiveForm::end();
echo '</div>';