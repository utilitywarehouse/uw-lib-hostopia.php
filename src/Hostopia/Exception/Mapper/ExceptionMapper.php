<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception\Mapper;

use UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistsException;
use UtilityWarehouse\SDK\Hostopia\Exception\EmailAlreadyExistsException;
use UtilityWarehouse\SDK\Hostopia\Exception\NonExistentDomainException;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Exception\UnknownException;

class ExceptionMapper implements MapperInterface
{
    private $exceptionMap = [
        '14100110' => DomainAlreadyExistsException::class,
        '80100080' => NonExistentDomainException::class,
        '40160020' => EmailAlreadyExistsException::class
    ];

    public function fromSoapException(SoapException $exception)
    {
        if (array_key_exists($exception->getFaultCode(), $this->exceptionMap)) {
            $className = $this->exceptionMap[$exception->getFaultCode()];

            return new $className(null, 0, $exception);
        }

        return new UnknownException($exception->getMessage(), 0, $exception);
    }
}