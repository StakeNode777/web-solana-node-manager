<?php
    use app\models\LoginForm;
    use yii\widgets\Breadcrumbs;
    $breadcrumbs = isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [];
?>

<?php require __DIR__ . '/partials/_top.php'; ?>

    <?php if ($breadcrumbs) : ?>
        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $breadcrumbs,
        ]) ?>
        </div>
    <?php endif ?>

    <?=$content ?>

<?php if (!Yii::$app->user->getId()) : ?>
    <?php
        $loginForm = new LoginForm();
        yii\bootstrap5\Modal::begin([
            'title' => '<h2 class="text-center">Log In</h2>',
            'id' => 'modal-login',
            'size' => 'modal-md',
        ]);
    ?>
    <div id="modal-login-content">
        <?=Yii::$app->controller->renderPartial('/layouts/partials/_login', ['model' => $loginForm, 'mode' => 0])?>
    </div>
    <?php yii\bootstrap5\Modal::end(); ?>
<?php endif ?>

<?=Yii::$app->controller->renderPartial('//layouts/partials/_loader', ['model' => []])?>

<?php require __DIR__ . '/partials/_bottom.php'; ?>