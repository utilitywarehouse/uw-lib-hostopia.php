<?php

namespace spec\UtilityWarehouse\SDK\Hostopia;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UtilityWarehouse\SDK\Hostopia\Client;
use UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException;
use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\MapperInterface;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
use UtilityWarehouse\SDK\Hostopia\Model\EmailAccount;
use UtilityWarehouse\SDK\Hostopia\Request\DomainInfo;
use UtilityWarehouse\SDK\Hostopia\Request\MailInfo;
use UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo;
use UtilityWarehouse\SDK\Hostopia\Response\ResponseInterface;
use UtilityWarehouse\SDK\Hostopia\Response\ReturnCode;
use UtilityWarehouse\SDK\Hostopia\Service;

/**
 * @mixin Service
 */
class ServiceSpec extends ObjectBehavior
{
    function let(Client $client, MapperInterface $mapper)
    {
        $this->beConstructedWith($client, $mapper, 'username', 'password');
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

    function it_throws_DomainAlreadyExistException_when_adding_domain_which_already_exists(Client $client, MapperInterface $mapper)
    {
        $primaryInfo = new PrimaryInfo('username', 'password');
        $domain = "000000@uwclub.net";
        $password = 'password';
        $domainInfo = new DomainInfo(Service::PACKAGE, $password);


        $fault = new \SoapFault('14100110', 'ERR:Domain \'000000@uwclub.net\' already exists in database');
        $soapException = new SoapException('Domain already exists.', 0, $fault);

        $client->makeCall('newDomain', $primaryInfo, $domain, $domainInfo)
            ->willThrow($soapException);

        $mapper->fromSoapException($soapException)->willReturn(new DomainAlreadyExistException('Domain already exists.', 0, $soapException));

        $domainName = new DomainName($domain);

        $this->shouldThrow('UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException')
            ->during('createNewDomain', [$domainName, $password]);
    }

    function it_creates_new_email_account(Client $client)
    {
        $primaryInfo = new PrimaryInfo('username', 'password');

        $domain = "000000@uwclub.net";

        $email = "john.doe@uwclub.net";
        $password = 'password';

        $mailInfo = new MailInfo($email, $password);

        $client->makeCall('mailAdd', $primaryInfo, $domain, $mailInfo)
            ->willReturn(new ReturnCode(true, "OK:Mail account added"));

        $this->createMailAccount(new EmailAccount($email, $password), new DomainName($domain))
            ->shouldReturnAnInstanceOf(ResponseInterface::class);
    }

    function it_deletes_email_account_which_already_exists(Client $client)
    {
        $primaryInfo = new PrimaryInfo('username', 'password');

        $domain = "000000@uwclub.net";

        $email = "john.doe@uwclub.net";

        $client->makeCall('mailDel', $primaryInfo, $domain, $email)
            ->willReturn(new ReturnCode(true, "OK:Mail account deleted"));

        $this->deleteMailAccount(new EmailAccount($email), new DomainName($domain))
            ->shouldReturnAnInstanceOf(ResponseInterface::class);
    }
}
