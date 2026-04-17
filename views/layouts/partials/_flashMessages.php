<?php

//Get all flash messages and loop through them
//https://www.yiiframework.com/extension/diecoding/yii2-toastr

use diecoding\toastr\ToastrFlash;

$this->registerCss(<<<CSS
    .toast-success {
        background-color: #28a745 !important;
        border-color: #1e7e34 !important;
    }
    .toast-error {
        background-color: #dc3545 !important;
        border-color: #bd2130 !important;
    }
    .toast-info {
        background-color: #17a2b8 !important;
        border-color: #117a8b !important;
    }
    .toast-warning {
        background-color: #ffc107 !important;
        border-color: #d39e00 !important;
    }
CSS
);

ToastrFlash::widget();
?>
