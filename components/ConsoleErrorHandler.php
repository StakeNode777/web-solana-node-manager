<?php

namespace app\components;

use app\models\SiteAlert;

class ConsoleErrorHandler extends \yii\console\ErrorHandler
{
    public function logException($exception)
    {
        parent::logException($exception);
        SiteAlert::saveNewYiiException($exception);
    }    
}

