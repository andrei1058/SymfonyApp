<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting {

    private $logger;
    private $msg;

    public function __construct(LoggerInterface $logger, string $msg)
    {
        $this->logger = $logger;
        $this->msg = $msg;
    }

    public function greet(string $name) : string{
        return "{$this->msg} $name";
    }
}