<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
echo $form->field($searchModel, 'date_from')->input('date', [
    'class' => 'form-control',
]);
echo $form->field($searchModel, 'date_to')->input('date', [
    'class' => 'form-control',
]);
ActiveForm::end();

/*use kartik\date\DatePicker;

$fieldWidth = '100px';
$fontSize = '14px';
$extra_styles = '';
$showAddon = true;

if (isset($size)) {
    if ($size=='small') {
        $fieldWidth = '90px';
        $fontSize = '12px';
        $showAddon = false;
    } elseif ($size=='small2') {
        $fieldWidth = '80px'; 
        $fontSize = '12px';
        $showAddon = false;
        $extra_styles = 'padding:4px 9px';
    }
}

$addon = '';
if ($showAddon)
    $addon = '<span class="input-group-addon">-</span>';

echo

'<div class="input-group">'.
    DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'date_from',
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => [
            'placeholder' => 'Date from',
            'style' => "width: $fieldWidth; font-size: $fontSize; $extra_styles",
        ]
    ]).
    $addon
    .DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'date_to',
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
        'options' => [
            'placeholder' => 'Date to',
            'style' => "width: $fieldWidth; font-size: $fontSize; $extra_styles",
        ],
    ]).
'</div>'
*/
?>