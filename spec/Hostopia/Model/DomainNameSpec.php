<?php

namespace spec\UtilityWarehouse\SDK\Hostopia\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UtilityWarehouse\SDK\Hostopia\Exception\ClientException;

class DomainNameSpec extends ObjectBehavior
{
    function it_validates_if_domain_name_is_given()
    {
        $this->shouldThrow(ClientException::class)->during('__construct', [null]);
    }

    function it_validates_if_valid_domain_name_is_given()
    {
        $this->shouldThrow(ClientException::class)->during('__construct', ['testdomain.com']);
    }

    function it_can_be_printed()
    {
        $this->beConstructedWith('0000001@uwclub.net');
        $this->__toString()->shouldBe("0000001@uwclub.net");
    }
}
