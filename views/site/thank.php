<?php
use yii\helpers\Html;
?>


<div class="container thank">

    <div class="row">
        <div class="col-xs-12 text-center">
            <?= Html::img('@web/images/handshake.png', ['alt' => "check"]) ?>
        </div>
        <div class="col-xs-12 text-center header">
            Thank you!
        </div>
        <div class="col-xs-12 text-center subtitle">
            For joining to Stake Node 777 project!
        </div>
    </div>

</div>

<hr>

<div class="container thank">

    <div class="row">
        <div class="col-xs-12 text-center question">
            Having questions or trouble? <?= Html::a('Contact us', ['/contact']) ?>
        </div>
    </div>

</div>