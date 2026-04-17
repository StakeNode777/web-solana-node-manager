<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SiteAlert */

$this->title = 'Alert id = ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Site Alerts', 'url' => ['/site-alert']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container site-alert-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= Html::beginForm('', 'post', ['class'=>'form-inline', 'id'=>'comment_form']) ?>
        <div class="form-group">
            <label for="comment">Comment</label>
            <?= Html::activeTextarea($model, 'comment', ['class' => 'form-control', 'id'=>"comment", 'cols'=>"70", 'rows'=>"2"]) ?>
        </div>
        <div class="form-group">
            <label for="checkbox_status">Done</label>
            <?= Html::activeCheckbox($model, 'status', ['class' => 'form-control', 'id'=>"checkbox_status", 'label' => null]) ?>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger del_alert',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    <?= Html::endForm() ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Type',
                'value' => $model->typeLabel
            ],
            'message',
            [
                'label' => 'Name',
                'value' => $model->name
            ],
            [
                'label' => 'File',
                'value' => $model->file
            ],
            [
                'label' => 'Line',
                'value' => $model->line
            ],
            'created',
            [
                'label' => 'User',
                'value' => $model->userInfo,
                'format'=>'raw',
            ],
            [
                'label' => $model->isConsole() ? 'Cmd' : 'Url',
                'value' => $model->isConsole() ? $model->url : "<a href=\"{$model->url}\">{$model->url}</a>",
                'format' => 'html'
            ],
            'referrer:url',
            [
                'label' => 'Post',
                'value' => $model->post ? "<pre class='post'>".print_r($model->post,1)."</pre>" : '<span class="not-set">(not set)</span>',
                'format' => 'html',
            ],
            'trace:ntext',
        ],
    ]) ?>

</div>