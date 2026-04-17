<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<ul class="nav nav-tabs account-tabs">
    <?php foreach($items as $item): ?>
        <?php
            $liClass = '';
            if (strcasecmp(Url::to($item['url']), $_SERVER['REQUEST_URI']) == 0)
                $liClass = "class='active'";
        ?>
        <li <?= $liClass ?>>
            <?= Html::a($item['label'], $item['url']) ?>
        </li>
    <?php endforeach; ?>
</ul>