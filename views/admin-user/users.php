<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container client-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php
        $gridColumns = [
            [
                'attribute' => 'id',
                'filterOptions' => ['style' => 'width:60px;'],
            ],
            [
                'attribute' => 'roles',
                'label' => 'Role',
                'filter' => \app\models\User::getRoleLabels(),
                'value' => 'roleName', //work getter - User::getRoleName()
            ],
            [
                'attribute' => 'email',
                'filterOptions' => ['style' => 'min-width:160px;'],
            ], 
            [
                'attribute' => 'status',
                'filter' => \app\models\User::getStatusLabels(),
                'filterOptions' => ['style' => 'min-width:120px;'],
                'value' => 'statusName'
            ],               
            [
                //'filter' => $this->render('/layouts/partials/_myDateRangePicker', ['searchModel' => $searchModel]),
                'attribute' => 'created_at',
                //'filterOptions' => ['style' => 'max-width:240px;'],
                'label' => 'Created',
                'format' =>  ['date', 'Y-MM-dd - HH:mm:ss'],
            ],
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showOnEmpty' => true,
        'emptyText' => 'Not found.',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'columns' => $gridColumns,
    ]);?>

</div>
