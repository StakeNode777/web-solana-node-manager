<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\SiteAlert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SiteAlertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Site Alerts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container site-alert-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showOnEmpty' => true,
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            [
                'attribute' => 'id',
                'filterOptions' => ['style' => 'width:60px;'],
            ],
            [
                'attribute' => 'type',
                'filter' =>SiteAlert::getTypeLabels(),
                'value' => 'typeLabel', //work getter - User::getRoleName()
                'filterOptions' => ['style' => 'width:160px;'],
            ],
            [
                'attribute' => 'status',
                'filter' => SiteAlert::getStatusLabels(),
                'format' => 'html',
                'value'=>function($data){
                    if($data->status == SiteAlert::STATUS_DONE)
                        return '<span class="done">Done</span>';
                    elseif ($data->status == SiteAlert::STATUS_SYSTEM_DONE)
                        return '<span class="done">Auto Done</span>';
                    return '<span class="new">New</span>';
                },
                'filterOptions' => ['style' => 'width:120px;'],
            ],
            [
                'attribute'=>'message',
                'format'=>'html',
                'value'=>function($data) {
                    return $this->render('/site-alert/_alertInfo', ['data' => $data]);
                },
            ],
            [
                'filter' => $this->render('/layouts/partials/_myDateRangePicker', ['searchModel' => $searchModel, 'size' => 'small']),
                'attribute' => 'created',
                'filterOptions' => ['style' => 'width:200px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'filterOptions' => ['style' => 'width:25px;'],
            ],
        ],
    ]); ?>
</div>
