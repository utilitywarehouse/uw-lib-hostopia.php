<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception\Mapper;

use UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException;
use UtilityWarehouse\SDK\Hostopia\Exception\NonExistentDomainException;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Exception\UnknownException;

class ExceptionMapper implements MapperInterface
{
    const DOMAIN_NAME_EXCEPTION_CLASS_MAP = [
        '14100110' => DomainAlreadyExistException::class,
        '80100080' => NonExistentDomainException::class,
    ];

    public function fromSoapException(SoapException $exception)
    {
        if (array_key_exists($exception->getFaultCode(), self::DOMAIN_NAME_EXCEPTION_CLASS_MAP)) {
            $className = self::DOMAIN_NAME_EXCEPTION_CLASS_MAP[$exception->getFaultCode()];

            return new $className(null, 0, $exception);
        }

        return new UnknownException($exception->getMessage(), 0, $exception);
    }
}