<?php

namespace spec\UtilityWarehouse\SDK\Hostopia;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UtilityWarehouse\SDK\Hostopia\Client;
use UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException;
use UtilityWarehouse\SDK\Hostopia\Exception\HostopiaException;
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
    /**
     * @var PrimaryInfo
     */
    private $primaryInfo;

    public function let(Client $client, MapperInterface $mapper)
    {
        $this->primaryInfo = new PrimaryInfo($user = 'username', $pass = 'password');
        $this->beConstructedWith($client, $mapper, $user, $pass);
    }

    public function it_creates_new_domain(Client $client)
    {
        $domain = "000000@uwclub.net";
        $password = 'password';
        $domainInfo = new DomainInfo(Service::PACKAGE, $password);

        $client->makeCall('newDomain', $this->primaryInfo, $domain, $domainInfo)
            ->willReturn(new ReturnCode(true, "OK:Domain added"));

        $domainName = new DomainName($domain);

        $this->createNewDomain($domainName, $password)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }

    public function it_deletes_existing_domain(Client $client)
    {
        $domain = "000000@uwclub.net";

        $client->makeCall('delDomain', $this->primaryInfo, $domain)
            ->willReturn(new ReturnCode(true, "OK:Domain deleted"));

        $domainName = new DomainName($domain);

        $this->deleteDomain($domainName)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }

    public function it_throws_DomainAlreadyExistException_when_adding_domain_which_already_exists(Client $client, MapperInterface $mapper)
    {
        $domain = "000000@uwclub.net";
        $password = 'password';
        $domainInfo = new DomainInfo(Service::PACKAGE, $password);

        $fault = new \SoapFault('14100110', 'ERR:Domain \'000000@uwclub.net\' already exists in database');
        $soapException = new SoapException('Domain already exists.', 0, $fault);

        $client->makeCall('newDomain', $this->primaryInfo, $domain, $domainInfo)
            ->willThrow($soapException);

        $mapper->fromSoapException($soapException)->willReturn(new DomainAlreadyExistException('Domain already exists.', 0, $soapException));

        $domainName = new DomainName($domain);

        $this->shouldThrow(DomainAlreadyExistException::class)
            ->during('createNewDomain', [$domainName, $password]);
    }

    public function it_changes_password_for_email(Client $client)
    {
        $domainName = new DomainName("000000@uwclub.net");
        $mailAccount = new EmailAccount($name = "name@uwclub.net", $pass = 'pass');
        $mailInfo = new MailInfo($name, $pass);

        $client->makeCall('mailPwd', $this->primaryInfo, $domainName, $mailInfo)
            ->shouldBeCalled()
            ->willReturn(new ReturnCode(true, "OK:Mail account password changed"));

        $this->changeMailPassword($mailAccount, $domainName)->shouldReturnAnInstanceOf(ResponseInterface::class);
    }

    public function it_maps_Soap_exceptions_if_thrown_during_password_change(Client $client, MapperInterface $mapper)
    {
        $domainName = new DomainName("11111@uwclub.net");
        $mailAccount = new EmailAccount($name = "name@uwclub.net", $pass = 'pass');
        $mailInfo = new MailInfo($name, $pass);

        $exception = new SoapException('Message', 0, new \Exception());
        $client->makeCall('mailPwd', $this->primaryInfo, $domainName, $mailInfo)
            ->willThrow($exception);

        $mapper->fromSoapException($exception)->shouldBeCalled()->willReturn(new HostopiaException());

        $this->shouldThrow(HostopiaException::class)->during('changeMailPassword', [$mailAccount, $domainName]);
    }
}
