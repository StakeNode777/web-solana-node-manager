<?php

namespace app\services\snm;


class ActivateDTO
{
    public $operation;
    public bool $safe;
    public $identity;
    public $to;

    public function __construct(string $operation, bool $safe, string $identity, string $to)
    {
        $this->operation = $operation;
        $this->safe = $safe;
        $this->identity = $identity;
        $this->to = $to;
    }
}