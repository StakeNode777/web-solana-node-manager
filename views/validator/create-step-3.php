<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $id */

$this->title = 'Validator Added Successfully';
?>
<div class="validator-create-step3 text-center">

    <div class="jumbotron">
      <h1><span class="glyphicon glyphicon-ok-circle" style="color: green;"></span> Success!</h1>
      <p class="lead">The validator has been successfully added to the dashboard.</p>
      <p>You can now configure the server details or do it later from the dashboard.</p>
      <p>
        <?= Html::a('Configure Now', ['configure', 'id' => $id], ['class' => 'btn btn-lg btn-primary']) ?>
        <?= Html::a('Later', ['index'], ['class' => 'btn btn-lg btn-default']) ?>
      </p>
    </div>

</div>