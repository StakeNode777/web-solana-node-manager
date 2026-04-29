<?php

namespace app\services\snm;


class TransferDTO
{
    public $operation;
    public bool $safe;
    public $identity;
    public $from;
    public $to;

    public function __construct(string $operation, bool $safe, string $identity, string $from, string $to)
    {
        $this->operation = $operation;
        $this->safe = $safe;
        $this->identity = $identity;
        $this->from = $from;
        $this->to = $to;
    }
}