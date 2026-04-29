<?php

namespace app\services\snm;

use phpseclib3\Net\SFTP;
use Yii;

class RequestFileSender
{
    private SSHConfig $ssh;
    private $sftp;


    public function __construct(SSHConfig $ssh)
    {
        $this->ssh = $ssh;
        $this->sftp = new SFTP($ssh->host);
        $this->_loginToSsh();
    }

    public function sendFile(string $localFile, string $remoteFile): void
    {
        if (!$this->sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
            throw new \Exception('File send failed');
        }
    }

    private function _loginToSsh()
    {
        if (!$this->sftp->login($this->ssh->username, $this->ssh->password)) {
            throw new \Exception('Login failed for receive server');
        }
    }
}