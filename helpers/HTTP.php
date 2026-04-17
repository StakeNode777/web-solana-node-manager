<?php
/**
 * Created by PhpStorm.
 * User: kostecmw
 * Date: 09.10.17
 * Time: 1:09
 */

namespace app\helpers;

use yii\web\HttpException;

class HTTP {

    public static function e422($msg)
    {
        throw new HttpException(422, $msg);
    }
    
    public static function e400($msg)
    {
        throw new HttpException(400, $msg);
    }    
} 