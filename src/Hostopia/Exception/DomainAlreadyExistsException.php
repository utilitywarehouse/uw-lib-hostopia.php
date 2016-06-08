<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception;

class DomainAlreadyExistsException extends HostopiaException
{
    public function __construct($message = "Domain already exists.", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}