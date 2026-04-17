<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\BaseUrl;

use app\assets\AppAsset;
use app\models\User;

use diecoding\toastr\ToastrFlash;

$baseUrl = BaseUrl::base();

AppAsset::register($this);

$includeStats = 1;
$staff = User::ROLE_ROOT + User::ROLE_EDITOR;
$identity = Yii::$app->user->identity;
if ($identity) {
    $is_staff = $staff & $identity->roles;
    if ($is_staff) {
        $includeStats = 0;
    }
}

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="<?=$baseUrl?>/images/icons/favicon.png" />
    <link rel="apple-touch-icon" href="<?=$baseUrl?>/images/icons/apple-touch-icon.png" />
    <?= Html::csrfMetaTags() ?>
    <?= $this->render('//layouts/partials/_jsErrorScript'); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4 CSS (if not already included) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
</head>
<body>
<script type="text/javascript">
    var baseUrl = '<?=BaseUrl::base();?>';
</script>

<?php $this->beginBody() ?>

<?= $this->render('partials/_flashMessages')
//$this->render('partials/_flashMessagesStd') 
?>

<div class="wrap">
<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => app\helpers\NavbarHelper::getItems(),/*[
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]*/
    ]);
    NavBar::end();
    ?>
</header>
