<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception;

class NonExistentDomainException extends HostopiaException
{
    public function __construct($message = "Domain does not exist.", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
{

}