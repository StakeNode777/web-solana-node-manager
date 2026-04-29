<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Validator;
use yii\helpers\ArrayHelper;
use app\services\ValidatorsSyncService;
use app\services\snm\RPCProcessor;
use app\services\snm\NodeManagerService;
use app\services\snm\SyncService;
use app\services\snm\SSHConfig;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ValidatorController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionUpdateHealthes($cluster = "mainnet")
    {
        $service = new ValidatorsSyncService($cluster);
        $service->updateHealthes();
        $service->updateIPs();

        return ExitCode::OK;
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $cluster refers to the cluster from which the data is being fetched.
     * @return int Exit code
     */
    public function actionGetInfo($validatorId, $cluster = "mainnet")
    {
        //NodeManagerService::process()->GetInfo(SSHConfig::default());
        $validator = Validator::findOne($validatorId);
        if (!$validatorId) return;

        $ssh = new SSHConfig(
                $validator->snm_server,
                $validator->snm_ssh_login,
                $validator->getSshPassword(),
            );

        echo json_encode(
            (new SyncService())->GetInfo($ssh)
        );

        return ExitCode::OK;
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $cluster refers to the cluster from which the data is being fetched.
     * @return int Exit code
     */
    public function actionGetInfoBatch()
    {
        (new SyncService())->updateAll();
        return ExitCode::OK;
    }
}
