<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Validator $model */
/** @var int $step */

$this->title = 'Add New Validator';
$this->params['breadcrumbs'][] = ['label' => 'Validators', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="validator-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'step' => $step ?? 1,
    ]) ?>

</div>