<?php
//use \Yii;

$this->title = "Log In";
?>

<div class="container site-login-static">
    <div class="row">
        <div class="login-block col-lg-4 col-md-5 col-sm-6 col-xs-10 col-md-offset-4 col-sm-offset-3 col-xs-offset-1">

                <h2 class="text-center">Login</h2>

                <?=Yii::$app->controller->renderPartial('/layouts/partials/_login', ['model' => $model, 'mode' => 1])?>

        </div>
    </div>
</div>