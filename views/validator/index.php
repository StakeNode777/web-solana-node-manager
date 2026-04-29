<?php
// views/validator/index.php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Validator;
use app\services\Helper;

$this->registerCss("
    .dashboard-table {
        margin: 50px;
    }
");

// Register Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', ['position' => \yii\web\View::POS_HEAD]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-striped dashboard-table'],
    'rowOptions' => function ($model, $key, $index, $grid) {
        return [
            'onclick' => "window.location='" . \yii\helpers\Url::to(['view', 'id' => $model->id]) . "';",
            'style'   => 'cursor: pointer;',
        ];
    },
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'logo',
            'format' => 'raw',
            'value' => function ($model) {
                $url = $model->img_url
                    ? $model->img_url
                    : Yii::getAlias('@web/images/nophoto.png');   // fallback default image
                return Html::img($url, ['width' => 75, 'alt' => 'Validator Logo']);
            },
        ],
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function ($model) {
                $html = Html::encode($model->name ?: 'Unnamed');
                $html .= '<br><small style="color: gray;">' . Html::encode($model->identity) . '</small>';
                $html .= '<br><small style="color: green;">' . Html::encode($model->vote_account) . '</small>';
                return $html;
            },
        ],
        'cluster',
        'snm_server',
        [
            'attribute' => 'health',
            'format' => 'raw',
            'value' => function ($model) { 
                return '<span style="color: ' . Helper::healthColor($model->health) . ';">' . Html::encode($model->health) . '</span>';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {configure} {delete}',
            'buttons' => [
                'configure' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-gear"></i>', ['validator/configure', 'id' => $model->id], ['title' => 'Configure']);
                },
                // 'view' => function ($url, $model, $key) {
                //     return Html::a('<i class="fas fa-pen"></i>', ['validator/view', 'id' => $model->id], ['title' => 'View']);
                // },
            ],
        ],
    ],
]);

echo Html::a('Add Validator', ['validator/create-step1'], ['class' => 'btn btn-primary', 'style' => 'margin: 50px;']);
?>