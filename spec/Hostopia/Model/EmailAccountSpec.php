<?php

namespace spec\UtilityWarehouse\SDK\Hostopia\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UtilityWarehouse\SDK\Hostopia\Exception\HostopiaException;
use UtilityWarehouse\SDK\Hostopia\Model\EmailAccount;

/**
 * @mixin EmailAccount
 */
class EmailAccountSpec extends ObjectBehavior
{
    function it_validates_if_email_is_given()
    {
        $this->shouldThrow(HostopiaException::class)->during('__construct', [null, null]);
    }

    function it_validates_if_valid_email_is_given()
    {
        $this->shouldThrow(HostopiaException::class)->during('__construct', ['testdomain.com', 'password']);
    }

    function it_validates_if_valid_password_is_given()
    {
        $this->shouldThrow(HostopiaException::class)->during('__construct', ['testdomain.com', '11']);
    }
    
    function it_can_be_printed()
    {
        $this->beConstructedWith('john.doe@uwclub.net', 'password');
        $this->__toString()->shouldBe("john.doe@uwclub.net");
    }
}
