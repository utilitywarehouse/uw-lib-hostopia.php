<?php

namespace spec\UtilityWarehouse\SDK\Hostopia;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UtilityWarehouse\SDK\Hostopia\Client;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
use UtilityWarehouse\SDK\Hostopia\Request\DomainInfo;
use UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo;
use UtilityWarehouse\SDK\Hostopia\Response\ResponseInterface;
use UtilityWarehouse\SDK\Hostopia\Response\ReturnCode;
use UtilityWarehouse\SDK\Hostopia\Service;

class ServiceSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client, 'username', 'password');
    }

    function it_creates_new_domain(Client $client)
    {
        $primaryInfo = new PrimaryInfo('username', 'password');
        $domain = "000000@uwclub.net";
        $password = 'password';
        $domainInfo = new DomainInfo(Service::PACKAGE, $password);

        $client->makeCall('newDomain', $primaryInfo, $domain, $domainInfo)
            ->willReturn(new ReturnCode(true, "OK:Domain added"));

        $domainName = new DomainName($domain);

        $this->createNewDomain($domainName, $password)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }

    function it_deletes_existing_domain(Client $client)
    {
        $primaryInfo = new PrimaryInfo('username', 'password');
        $domain = "000000@uwclub.net";

        $client->makeCall('delDomain', $primaryInfo, $domain)
            ->willReturn(new ReturnCode(true, "OK:Domain deleted"));

        $domainName = new DomainName($domain);

        $this->deleteDomain($domainName)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }
}
