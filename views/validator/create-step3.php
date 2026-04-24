<?php
// views/validator/create-step3.php

use yii\helpers\Html;

$this->registerCss("
    .form-container {
        margin: 50px;
    }
");

echo '<div class="form-container">';
echo '<h1>Validator was Successfully added</h1>';
echo Html::a('Configure', ['configure', 'id' => $id], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
echo Html::a('Later', ['index'], ['class' => 'btn btn-secondary']);
echo '</div>';