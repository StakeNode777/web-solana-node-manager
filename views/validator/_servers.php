<?php
use yii\grid\GridView;
use yii\helpers\Html;
?>

<div id="serverTableContainer" class="server-table-container">
    <?= GridView::widget([
        'dataProvider' => $serverDataProvider,
        'tableOptions' => ['class' => 'table server-table'],
        'columns' => [
            'name',
            'ip',
            [
                'attribute' => 'is_active',
                'value' => function ($model) {
                    return $model->is_active ? 'Yes' : 'No';
                },
            ],
            'status',
            'status_msg',
        ],
    ]) ?>
</div>