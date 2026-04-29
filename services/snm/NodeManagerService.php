<?php

namespace app\services\snm;

use Ramsey\Uuid\Uuid;
use app\services\snm\RPCProcessor;
use Yii;


class NodeManagerService
{
   public static function process()
   {
        return new RPCProcessor();
   }
}