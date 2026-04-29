<?php
use yii\grid\GridView;
?>


<button class="refresh-button" onclick="refreshLogs()">Refresh Logs</button>
<button class="hide-logs-button" onclick="toggleLogs()">
    Hide Logs
    <i class="fa-solid fa-xmark" style="color:#e53935; font-size:30px; vertical-align: middle;" aria-hidden="true"></i>
</button>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table log-table'],
    'columns' => [
        'id',
        'identity',
        'action',
        [
            'attribute' => 'params',
            'contentOptions' => ['class' => 'params-column'],
        ],
        'result',
        [
            'attribute' => 'created_at',
            'format' => ['datetime', 'php:Y-m-d H:i:s'],
        ],
    ],
]) ?>

