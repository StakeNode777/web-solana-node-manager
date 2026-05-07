<?php
use yii\grid\GridView;
?>


<button class="refresh-button" onclick="refreshLogs()">Refresh Logs</button>
<button class="hide-logs-button" onclick="toggleLogs()">
    Hide Logs
    <i class="fa-solid fa-xmark" style="color:#e53935; font-size:30px; vertical-align: middle;" aria-hidden="true"></i>
</button>

<style>
    .result-column {
        max-width: 400px;
        font-size: 13px;
    }

    .result-msg {
        margin-bottom: 6px;
    }

    .result-log {
        font-size: 11px;
        margin: 0;
        padding-left: 18px;
        color: #666;
    }
</style>

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
        [
            'attribute' => 'result',
            'format' => 'raw',
            'contentOptions' => ['class' => 'result-column'],
            'value' => function ($model) {
                if (empty($model->result)) {
                    return null;
                }

                $data = json_decode($model->result, true);
                $data = json_decode($data, true);
                //var_dump($data);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return "json_last_error(): " . json_last_error(); // or return $model->result if you want fallback
                }

                //return $data['msg'] ?? $model->result;

                $msg = htmlspecialchars($data['msg'] ?? '');
                $log = $data['log'] ?? [];

                $html = "<div class='result-msg'><strong>{$msg}</strong></div>";

                if (!empty($log) && is_array($log)) {
                    $html .= "<ul class='result-log'>";
                    foreach ($log as $line) {
                        $html .= "<li>" . htmlspecialchars($line) . "</li>";
                    }
                    $html .= "</ul>";
                }

                return $html;
            },
        ],
        [
            'attribute' => 'created_at',
            'format' => ['datetime', 'php:Y-m-d H:i:s'],
        ],
    ],
]) ?>

