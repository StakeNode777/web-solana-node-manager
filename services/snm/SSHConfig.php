<?php

namespace app\services\snm;

use phpseclib3\Net\SFTP;
use Yii;
use yii\helpers\ArrayHelper;

class SSHConfig
{
    public $host;
    public $username;
    public $password;
    public $remotePath;

    public function __construct(string $host, string $username, string $password, string $remotePath = null)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;

        $this->remotePath = $remotePath ?? ArrayHelper::getValue(Yii::$app->params, "snm.remote_path");

        if (!$this->remotePath) {
            throw new \Exception("Empty remote path!");
        }
    }

    // public static function default(): SSHConfig
    // {
    //     $cfg = Yii::$app->params['snm'] ?? [];
    //     if (empty($cfg)) {
    //         throw new \Exception("snm config doesnt exist!");
    //     }

    //     return new self(
    //         $cfg['host'],
    //         $cfg['username'],
    //         $cfg['password'],
    //     );
    // }
}