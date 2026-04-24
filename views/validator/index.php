<?php
// views/validator/index.php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Validator;
use app\services\Helper;

use yii\helpers\BaseUrl;

$baseUrl = BaseUrl::base();

// Register Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', ['position' => \yii\web\View::POS_HEAD]);
?>


<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Logo</th>
                <th><a href="/validator/index?sort=name" data-sort="name">Name</a></th>
                <th><a href="/validator/index?sort=cluster" data-sort="cluster">Cluster</a></th>
                <th><a href="/validator/index?sort=snm_server" data-sort="snm_server">Snm Server</a></th>
                <th><a href="/validator/index?sort=health" data-sort="health">Health</a></th>
                <th class="action-column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($validators as $model): ?>
                <?php $clickHandler = "window.location='{$baseUrl}/validator/view?id={$model->id}';" ?>
                <tr style="cursor: pointer;" data-key="<?= $model->id ?>">
                    <td onclick="<?= $clickHandler ?>">
                        <img src="<?= $model->img_url ? Html::encode($model->img_url) : Yii::getAlias('@web/images/nophoto.png') ?>" width="75" alt="Validator Logo">
                    </td>
                    <td onclick="<?= $clickHandler ?>">
                        <?= Html::encode($model->name ?: 'Unnamed') ?>
                        <br><small style="color: gray;"><?= Html::encode($model->identity) ?></small>
                        <br><small style="color: green;"><?= Html::encode($model->vote_account) ?></small>
                    </td>
                    <td onclick="<?= $clickHandler ?>"><?= Html::encode($model->cluster) ?></td>
                    <td onclick="<?= $clickHandler ?>"><?= Html::encode($model->snm_server) ?></td>
                    <td onclick="<?= $clickHandler ?>">
                        <span style="color: <?= Helper::healthColor($model->health) ?>;">
                            <?= Html::encode($model->health) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?=$baseUrl?>/validator/view?id=<?= $model->id ?>" title="View" aria-label="View" data-pjax="0">
                            <svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/>
                            </svg>
                        </a>
                        <a href="<?=$baseUrl?>/validator/configure?id=<?= $model->id ?>" title="Configure">
                            <i class="fas fa-gear"></i>
                        </a>
                        <a href="<?=$baseUrl?>/validator/delete?id=<?= $model->id ?>" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post">
                            <svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
echo Html::a('Add Validator', ['validator/create-step1'], ['class' => 'btn btn-primary', 'style' => 'margin: 50px;']);
?>

<!-- Bootstrap Icons (if not already included) -->
<?php $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'); ?>

