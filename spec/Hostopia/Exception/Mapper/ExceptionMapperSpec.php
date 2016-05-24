<?php

namespace spec\UtilityWarehouse\SDK\Hostopia\Exception\Mapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException;
use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\ExceptionMapper;
use UtilityWarehouse\SDK\Hostopia\Exception\NonExistentDomainException;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Exception\UnknownException;

/**
 * @mixin ExceptionMapper
 */
class ExceptionMapperSpec extends ObjectBehavior
{
    function it_maps_SoapException_to_DomainAlreadyExistException_when_domain_already_exists(SoapException $soapException)
    {
        $soapException->getFaultCode()->willReturn('14100110');

        $this->fromSoapException($soapException)->shouldReturnAnInstanceOf(DomainAlreadyExistException::class);
    }

    function it_maps_SoapException_to_NonExistentDomainException_when_domain_does_not_exist(SoapException $soapException)
    {
        $soapException->getFaultCode()->willReturn('80100080');

        $this->fromSoapException($soapException)->shouldReturnAnInstanceOf(NonExistentDomainException::class);
    }
    
    function it_maps_SoapException_to_UnknownException_for_uknown_exception_code(SoapException $soapException)
    {
        $soapException->getFaultCode()->willReturn('666');

        $this->fromSoapException($soapException)->shouldReturnAnInstanceOf(UnknownException::class);
    }
}
