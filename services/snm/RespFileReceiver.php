<?php

namespace app\services\snm;

use phpseclib3\Net\SFTP;
use Yii;

class RespFileReceiver
{
    private SSHConfig $ssh;
    private $sftp;

    public function __construct(SSHConfig $ssh)
    {
        $this->ssh = $ssh;
        $this->sftp = new SFTP($ssh->host);
        $this->_loginToSsh();
    }

    public function checkFileAvailable(string $remoteFile): bool
    {
        return $this->sftp->file_exists($remoteFile) && $this->sftp->size($remoteFile) > 0;
    }

    public function get(string $remoteFile): string|bool
    {
        return $this->sftp->get($remoteFile);
    }

    private function _loginToSsh()
    {
        if (!$this->sftp->login($this->ssh->username, $this->ssh->password)) {
            throw new \Exception('Login failed for receive server');
        }
    }
}