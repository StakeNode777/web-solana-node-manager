<?php

namespace app\services\snm;

use Ramsey\Uuid\Uuid;
use Yii;
use app\models\Validator;
use app\models\Server;
use yii\helpers\ArrayHelper;

class RPCBatchProcessor
{
    public function GetInfo()
    {
        $validators = Validator::find()
            ->all();
        
        foreach ($validators as $validator) {
            $ssh = new SSHConfig(
                $validator->snm_server,
                $validator->snm_ssh_login,
                $validator->getSshPassword(),
            );

            try {
                RPCProcessor::sendAndSaveResult_GetInfo($ssh, $validator); 
            } catch (\Exception $e) {
                \Yii::error('[RPCBatchProcessor::GetInfo]' . $e->getMessage());
            }

        }
    }

    public function DoTransfer()
    {
        // not needed yet
    }
}