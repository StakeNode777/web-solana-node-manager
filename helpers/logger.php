<?php

if (!function_exists('info')) {
    /**
     * Записывает сообщение в кастомный лог.
     *
     * @param string $message Сообщение для записи в лог.
     */
    function info($message)
    {
        Yii::info($message, 'custom');
    }
}
