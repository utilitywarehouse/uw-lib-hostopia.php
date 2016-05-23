<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception\Mapper;

use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;

interface MapperInterface
{
    public function fromSoapException(SoapException $exception);
}