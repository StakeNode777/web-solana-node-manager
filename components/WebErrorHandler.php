<?php

namespace app\components;

use app\models\SiteAlert;

class WebErrorHandler extends \yii\web\ErrorHandler
{
    public function logException($exception)
    {   
        parent::logException($exception);
        if ($exception instanceof \yii\web\HttpException) { 
            //$status_codes = [403, 422]; //403 - access denied, 422 - wrong user input
            $status_codes = [422]; //for first time it could be useful to show access denied
            if (in_array($exception->statusCode, $status_codes)) {
                return;
            }
        }
        SiteAlert::saveNewYiiException($exception);
    }    
}

