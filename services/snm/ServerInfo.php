<?php

namespace app\services\snm;

class ServerInfo
{
    public $name;
    public $ip;
    public $status;
    public $status_msg;
    public $is_active;

    public function validate(): bool
    {
        if (empty($this->name)) {
            return false;
        }

        if (empty($this->ip)) {
            return false;
        }

        if (empty($this->status)) {
            return false;
        }

        return true;
    }
}