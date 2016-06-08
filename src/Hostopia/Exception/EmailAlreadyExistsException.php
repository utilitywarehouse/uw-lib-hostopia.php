<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception;

class EmailAlreadyExistsException extends HostopiaException
{
    public function __construct($message = "Email already exists.", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}