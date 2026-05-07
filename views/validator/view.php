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

<!-- Show logs Button -->
    <?= Html::a('Show logs', null, [
        'class' => 'toggle-button',
        'id' => 'show-logs-btn',
        'onclick' => "toggleLogs()",
    ]) ?>
</div>

<div class="table table-striped view-container extra-tables">
    <span class="server-title">Servers</span>
    <button id="refresh-server-button-id" class="refresh-server-button" onclick="refreshServers(this)">
        <span class="icon">⟳</span>
    </button>
    <span id="last-updated-span" class="last-updated">Last Updated: <?= $lastUpdated ?></span>
    <?php echo $this->render('_servers', ['serverDataProvider' => $serverDataProvider]); ?>


    <div id="logMainContainer" class="action-log-index">
        <div id="logTableContainer" class="log-table-container">

            <?php require __DIR__ . '/_logs_table.php'; ?>

        </div>
    </div>
</div>

<script>
function toggleLogs() {
    const container = document.getElementById('logTableContainer');
    const button = document.querySelector('.toggle-button');
    container.classList.toggle('active');
    button.textContent = container.classList.contains('active') ? 'Hide Logs' : 'Show Logs';

    // scroll down
    sleep(400);
    if (container.classList.contains('active')) {
        var table = $('#logTableContainer');
        table.slideDown('fast', function() {
            // Smooth scroll to table
            $('html, body').animate({
                scrollTop: table.offset().top
            }, 600);
        });
    }
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

function refreshLogs() {
    fetch('?r=log&id=<?=$model->id?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        const container = document.getElementById('logTableContainer');
        container.innerHTML = data;
        container.classList.add('active');
        document.querySelector('.toggle-button').textContent = 'Hide Logs';
    })
    .catch(error => console.error('Error refreshing logs:', error));
}

function refreshServers(button) {
    // Disable button immediately
    button.disabled = true;
    button.classList.add('loading');

    fetch('?r=log&type=servers&id=<?=$model->id?>', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('serverTableContainer');
        const lastUpdated = document.getElementById('last-updated-span');
        container.innerHTML = data.html;
        lastUpdated.innerHTML = "Last Updated: " + data.lastUpdated;
    })
    .catch(error => console.error('Error refreshing servers:', error))
    .finally(() => {
        // Always re-enable button
        button.disabled = false;
        button.classList.remove('loading');
    });
}

function refreshModalLog(logs) {
    var csrfToken = document.querySelector("meta[name=\"csrf-token\"]");
    
    fetch('?r=log&type=modal-log&id=<?=$model->id?>', {
        method: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            "Content-Type": "application/json",
            'X-CSRF-Token': csrfToken.getAttribute("content"),
        },
        body: JSON.stringify({
            logs: logs,
        }),
    })
    .then(response => response.text())
    .then(data => {
        const container = document.getElementById('modal-log');
        container.innerHTML = data;
    })
    .catch(error => console.error('Error refreshing servers:', error));
}
</script>



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
                'activate' => 'Activation'
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
        
        <!-- Loader (hidden by default) -->
        <div id="loader" class="loader ms-2" style="display: none;">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="ms-1">Processing...</span>
        </div>
    </div>

    <!-- Modal Log (hidden by default) -->
    <div id="modal-log" class="modal-log-container"> 
         <?php require __DIR__ . '/_modal_log.php'; ?>
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

/*** logs table ***/

.log-table {
    font-size: 12px;
    font-family: "Courier New", Courier, monospace;
    background-color: #f8f9fa;
}
.log-table th, .log-table td {
    padding: 6px;
    border: 1px solid #dee2e6;
}
.log-table tr:nth-child(even) {
    background-color: #e9ecef;
}
.log-table .params-column {
    max-width: 300px;
    word-wrap: break-word;
}
.log-table-container {
    display: none;
}
.log-table-container.active {
    display: block;
}
.toggle-button, .refresh-server-button {
    margin-bottom: 10px;
    padding: 8px 16px;
    background-color: #ffffffff;
    color: grey;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.toggle-button:hover, .refresh-server-button:hover {
    background-color: #f1f4f8ff;
}

.toggle-button:disabled, .refresh-server-button:disabled {
    cursor: not-allowed;
}

.hide-logs-button {
    float: right;
    margin-right: 200px;
}

.refresh-server-button {
    padding: 8px;
    font-size: 14px;
}

.refresh-server-button.loading .icon {
    display: inline-block;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
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

<!-- JavaScript -->
<?php $this->registerJs('
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll("[data-bs-toggle=\"tooltip\"]"));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Get modal element
    var transferModal = document.getElementById("transfer-modal");
    var modal = new bootstrap.Modal(transferModal);

    var defaultDisplayMode = document.getElementById("input-server-from").style.display;
    
    // Handle dropdown change
    document.getElementById("transfer-options").addEventListener("change", function() {
        var selectedValue = this.value;
        var buttonText = selectedValue === "activate" ? "Activate" : "Transfer";
        document.getElementById("action-btn").textContent = buttonText;

        var serverFrom = document.getElementById("input-server-from");
        if (selectedValue === "activate") {
            serverFrom.style.display = "none";
        } else {
            serverFrom.style.display = defaultDisplayMode;
        }

    });
    
    // Handle action button click
    document.getElementById("action-btn").addEventListener("click", function() {
        var selectedOption = document.getElementById("transfer-options").value;
        var safeFlag = document.getElementById("safe-flag").checked;

        var validatorID = document.getElementById("validator-id").value;
        var serverFrom = document.getElementById("server-from").value;
        var serverTo = document.getElementById("server-to").value;

        var actionBtn = this;
        var loader = document.getElementById("loader");
        
        // Show loader and disable button
        loader.style.display = "inline-flex";
        actionBtn.disabled = true;
        
        // Prepare API data
        var formData = new FormData();
        formData.append("option", selectedOption);
        formData.append("safe", safeFlag ? "true" : "false");
        formData.append("validatorID", validatorID);
        formData.append("serverFrom", serverFrom);
        formData.append("serverTo", serverTo);
        
        // Add CSRF token
        var csrfToken = document.querySelector("meta[name=\"csrf-token\"]");
        if (csrfToken) {
            formData.append("_csrf", csrfToken.getAttribute("content"));
        }
        
        // Make API call
        fetch("' . \yii\helpers\Url::to(['validator/transfer-api']) . '", {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loader and enable button
            loader.style.display = "none";
            actionBtn.disabled = false;
            
            if (data.success) {
                // Close modal on success
                //modal.hide();
                
                // Show success message
                showMessage("Operation completed successfully!", "success");
            } else {
                // Show error message
                //var errorMsg = data.message || "Operation failed. Please try again.";
                //showMessage(errorMsg, "error");
            }

            if (data.transferred) {
                //console.log("transferred");
                //console.log(data.log);

                refreshModalLog(data.log);
            }
        })
        .then(() => {
            const button = document.getElementById("refresh-server-button-id");
            refreshServers(button);
        })
        .catch(error => {
            // Hide loader and enable button
            loader.style.display = "none";
            actionBtn.disabled = false;
            
            // Show error message
            showMessage("Network error occurred. Please try again.", "error");
        });
    });
    
    // Reset modal state when closed
    transferModal.addEventListener("hidden.bs.modal", function() {
        document.getElementById("transfer-options").value = "transfer";
        document.getElementById("action-btn").textContent = "Transfer";
        document.getElementById("safe-flag").checked = false;
        document.getElementById("loader").style.display = "none";
        document.getElementById("action-btn").disabled = false;
    });
    
    // Helper function to show messages
    function showMessage(message, type) {
        // Check if Toastr is available
        if (typeof toastr !== "undefined") {
            if (type === "success") {
                toastr.success(message);
            } else {
                toastr.error(message);
            }
        } else {
            // Fallback to Bootstrap alert
            var alertClass = type === "success" ? "alert-success" : "alert-danger";
            var alertDiv = document.createElement("div");
            alertDiv.className = "alert " + alertClass + " alert-dismissible fade show mt-3";
            alertDiv.innerHTML = message + 
                "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>";
            
            var modalBody = document.querySelector("#transfer-modal .modal-body");
            modalBody.insertBefore(alertDiv, modalBody.firstChild);
            
            // Auto-remove alert after 5 seconds
            setTimeout(function() {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    }
});
', View::POS_END); ?>