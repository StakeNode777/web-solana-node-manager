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
use app\models\User;
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
class UserController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionAddAdminUser($email, $password = null)
    {
        $user = User::find()->where(['email' => $email])->one();
        if (empty($user)) {
            $user = new User();
            $user->email = $email;
        }

        if (empty($password)) {
            $password = \Yii::$app->security->generateRandomString(16);
        }
        
        echo "creating add-admin-user: $email ...\n";

        $user->roles = User::ROLE_ROOT;
        $user->setPassword($password);
        $user->generateAuthKey();

        if($user->save()){            
            //$user->sendEmailThanksForRegistration();
            echo "add-admin-user: $email CREATED!\n";
        }
        else{
            echo "add-admin-user: $email CREATION FAILED!\n";
        }

        return ExitCode::OK;
    }
}
