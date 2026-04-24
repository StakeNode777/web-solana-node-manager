<?php
// views/validator/view.php

use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\web\View;
use yii\helpers\Url;
use app\models\Validator;
use app\services\Helper;

$this->registerCss("
    .view-container {
        padding: 3rem;
    }
    .identity-span {
        color: gray;
    }
    .vote-acc-span {
        color: green;
    }
");

// Register Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', ['position' => \yii\web\View::POS_HEAD]);

// echo DetailView::widget([
//     'model' => $model,
//     'options' => ['class' => 'table table-striped view-container'],
//     'attributes' => [
//         [
//             'label' => Html::img($model->img_url, ['style' => 'max-height:150px; margin-right:10px;']),
//             'format' => 'raw',
//             'value' => function ($model) {
//                 $html = '<h4>' . Html::encode($model->name ?: 'Unnamed') . '</h4>';
//                 $html .= '<small class="identity-span">identity: ' . Html::encode($model->identity) . '</small>';
//                 $html .= '<br><small class="vote-acc-span">vote_acc: ' . Html::encode($model->vote_account) . '</small>';
//                 $html .= '<br><br>Health: <span style="color: ' . Html::encode(Helper::healthColor($model->health)) . ';">' . Html::encode($model->health) . '</span>';
//                 $html .= '<br>Active Server: ' . (!empty($model->snm_server) ? Html::encode($model->snm_server) : '<span class="not-set">(not set)</span>');
//                 return $html;
//             },
//         ],
//         'cluster',
//         //'snm_server',
//         //'health',
//         //'identity',
//         //'vote_account',
//         //'configured:boolean',
//         //'snm_ssh_login',
//         [
//             'label' => 'Configured',
//             'format' => 'raw',
//             'value' => function ($model) {
//                 return Html::encode(Helper::yesNoHelper($model->configured))
//                     . ' '
//                     . Html::a('<i class="fas fa-gear"></i>', ['validator/configure', 'id' => $model->id], ['title' => 'Configure']);
//             },
//         ],
//     ],
// ]);

/** @var yii\web\View $this */

$validator = $model;
?>

<div class="container-fluid view-container">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="w0" class="table table-striped view-container">
                    <tr>
                        <th><img src="<?= Html::encode($validator->img_url) ?>" alt="" style="max-height:150px; margin-right:10px;"></th>
                        <td>
                            <h4><?= Html::encode($validator->name ?: 'Unnamed') ?></h4>
                            <small class="identity-span">identity: <?= Html::encode($validator->identity) ?></small><br>
                            <small class="vote-acc-span">vote_acc: <?= Html::encode($validator->vote_account) ?></small><br><br>
                            Health: <span style="color: <?= Html::encode(Helper::healthColor($validator->health)) ?>;"><?= Html::encode($validator->health) ?></span><br>
                            Active Server: <?= !empty($validator->snm_server) ? Html::encode($validator->snm_server) : '<span class="not-set">(not set)</span>' ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Cluster</th>
                        <td><?= Html::encode($validator->cluster) ?></td>
                    </tr>
                    <tr>
                        <th>Configured</th>
                        <td>
                            <?= Html::encode(Helper::yesNoHelper($validator->configured)) ?>
                            <?= Html::a('<i class="fas fa-gear"></i>', ['validator/configure', 'id' => $validator->id], ['title' => 'Configure']) ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="table table-striped view-container">
    <!-- Transfer Button -->
    <?= Html::button('Transfer', [
        'class' => 'btn btn-primary btn-lg',
        'id' => 'transfer-btn',
        'data-bs-toggle' => 'modal',
        'data-bs-target' => '#transfer-modal'
    ]) ?>

</div>

<div class="table table-striped view-container extra-tables">
    <span class="server-title">Servers</span>
    <?php echo $this->render('_servers', ['serverDataProvider' => $serverDataProvider]); ?>

</div>

<!-- Transfer Modal -->
<?php Modal::begin([
    'id' => 'transfer-modal',
    'title' => 'Transfer Options',
    'size' => Modal::SIZE_DEFAULT,
    'closeButton' => [
        'label' => '&times;',
        'class' => 'btn-close',
        'encode' => false
    ],
]); ?>

<div class="transfer-modal-content">
    <!-- Transfer Options Dropdown -->
    <div class="mb-3">
        <label for="transfer-options" class="form-label">Transfer options</label>
        <div class="input-group">
            <?= Html::dropDownList('transfer-options', 'transfer', [
                'transfer' => 'Transfer',
                'activation' => 'Activation'
            ], [
                'class' => 'form-select',
                'id' => 'transfer-options'
            ]) ?>
            <span class="input-group-text">
                <i class="bi bi-info-circle info-icon" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="top" 
                   title="Select Transfer to move items between locations, or Activation to enable features."
                   id="transfer-options-info"></i>
            </span>
        </div>
    </div>

    <!-- Safe Flag Checkbox -->
    <div class="mb-3">
        <div class="form-check">
            <?= Html::checkbox('safe-flag', true, [
                'id' => 'safe-flag',
                'class' => 'form-check-input'
            ]) ?>
            <label class="form-check-label" for="safe-flag">
                Safe
                <i class="bi bi-info-circle info-icon" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="top" 
                   title="Enable safe mode to perform operations with additional verification steps."
                   id="safe-flag-info"></i>
            </label>
        </div>
    </div>

    <?php 
        $_servers = [];
        foreach ($serversData as $key=>$v) {
            $_servers[$v->name] = "{$v->name} ({$v->ip})";
        }
    ?>

    <div class="mb-3">
        <label for="servers-from" class="form-label">Servers</label>
        <div id="input-server-from" class="input-group">
            <span class="input-group-text">
                From
            </span>
            <?= Html::dropDownList('transfer-server-from', 'transfer', 
                $_servers, [
                'class' => 'form-select',
                'id' => 'server-from'
            ]) ?>
            <span class="input-group-text">
                <i class="bi bi-info-circle info-icon" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="top" 
                   title="Select servers"
                   id="servers-from-info"></i>
            </span>
        </div>
        <div class="input-group">
            <span class="input-group-text">
                To
            </span>
            <?= Html::dropDownList('transfer-server-to', 'transfer', 
                $_servers, [
                'class' => 'form-select',
                'id' => 'server-to'
            ]) ?>
            <span class="input-group-text">
                <i class="bi bi-info-circle info-icon" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="top" 
                   title="Select servers"
                   id="servers-to-info"></i>
            </span>
        </div>
    </div>

    <!-- hidden field -->
    <div class="mb-3">
        <?= Html::input('text', 'validator-id', $model->id, [
                'class' => 'modal-hidden',
                'id' => 'validator-id',
            ]) ?>
    </div>

    <!-- Action Button -->
    <div class="mb-3 d-flex align-items-center">
        <?= Html::button('Transfer', [
            'class' => 'btn btn-success',
            'id' => 'action-btn'
        ]) ?>
    </div>

    </div>
</div>

<?php Modal::end(); ?>

<!-- Custom CSS -->
<?php $this->registerCss('
.info-icon {
    color: var(--bs-primary);
    cursor: help;
    margin-left: 5px;
}

.info-icon:hover {
    color: var(--bs-primary-dark, #0d6efd);
    opacity: 0.8;
}

.loader {
    color: var(--bs-primary);
}

.transfer-modal-content .mb-3:last-child {
    margin-bottom: 0 !important;
}

.input-group-text {
    background-color: transparent;
    border-left: none;
}

.form-select:focus + .input-group-text {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-check-label {
    cursor: pointer;
}

.modal-hidden {
    display: none;
}

.server-table-container {
    margin-left: 15px;
}

.server-table-container .table td, 
.server-table-container .table th,
.server-table-container .summary {
    /*margin-bottom: 20px;*/
    font-size: 0.8rem;
    padding: .2rem;
}

.extra-tables .server-title,
.extra-tables button {
    font-size: 1.2rem;
}

.last-updated {
    font-size: 12px;
    font-family: "Courier New", Courier, monospace;
    background-color: #fce0dcff;
}
    
'); ?>

<!-- Bootstrap Icons (if not already included) -->
<?php $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'); ?>
