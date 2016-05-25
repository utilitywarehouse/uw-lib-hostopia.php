<?php

namespace integration\UtilityWarehouse\Hostopia;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use UtilityWarehouse\SDK\Hostopia\Client;
use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\ExceptionMapper;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
use UtilityWarehouse\SDK\Hostopia\Model\EmailAccount;
use UtilityWarehouse\SDK\Hostopia\Response\ResponseInterface;
use UtilityWarehouse\SDK\Hostopia\Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    private $service;

    public function setUp()
    {
        $wsdl = dirname(__FILE__).'/../../resource/service.wsdl';
        $client = new Client($wsdl, ['trace' => true]);
        $mapper = new ExceptionMapper();

        $this->service = new Service($client, $mapper, getenv('UW_HOSTOPIA_USERNAME'), getenv('UW_HOSTOPIA_PASSWORD'));
    }

    public function testCreateDomain()
    {
        $domain = $this->generateUniqueDomainName();

        $response = $this->createDomainName($domain);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Domain added')));

        $this->removeDomainName($domain);
    }

    /**
     * @expectedException \UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException
     */
    public function testCreateDomainWhichAlreadyExists()
    {
        $domain = $this->generateUniqueDomainName();

        $this->createDomainName($domain);
        $this->createDomainName($domain);

        $this->removeDomainName($domain);
    }

    public function testDeleteDomain()
    {
        $domainName = $this->generateUniqueDomainName();
        $this->createDomainName($domainName);

        $domain = new DomainName($domainName);

        $response = $this->service->deleteDomain($domain);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Domain deleted')));
    }

    /**
     * @expectedException \UtilityWarehouse\SDK\Hostopia\Exception\NonExistentDomainException
     */
    public function testDeleteNonexistentDomain()
    {
        $domain = new DomainName('0@uwclub.net');

        $this->service->deleteDomain($domain);
    }

    public function testAddNewEmailAccount()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = sprintf("%s@uwclub.net", substr(md5(uniqid() . time()), 0, 10));

        $response = $this->createNewEmailAccount($email, $domainName);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account added')));
    }

    public function testDeleteExistingEmailAccount()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = sprintf("%s@uwclub.net", substr(md5(uniqid() . time()), 0, 10));

        $this->createNewEmailAccount($email, $domainName);

        $mailAccount = new EmailAccount($email);

        $response = $this->service->deleteMailAccount($mailAccount, $domainName);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account deleted')));
    }

    public function testChangeMailPassword()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = sprintf("%s@uwclub.net", substr(md5(uniqid() . time()), 0, 10));

        $this->createNewEmailAccount($email, $domainName);

        $mailAccount = new EmailAccount($email, 'somePass');

        $response = $this->service->changeMailPassword($mailAccount, $domainName);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account password changed')));
    }

    /**
     * @param string $domainName
     * @return ResponseInterface
     */
    private function createDomainName($domainName)
    {
        $domain = new DomainName($domainName);
        $response = $this->service->createNewDomain($domain, 'Password1');

        return $response;
    }

    /**
     * @param $domainName
     * @return ResponseInterface
     */
    private function removeDomainName($domainName)
    {
        $domain = new DomainName($domainName);
        $response = $this->service->deleteDomain($domain);

        return $response;
    }

    /**
     * @return string
     */
    private function generateUniqueDomainName()
    {
        $domainName = rand(1, 999999) . '@uwclub.net';
        return $domainName;
    }

    private function createNewEmailAccount($email, DomainName $domainName)
    {
        $mailAccount = new EmailAccount($email, 'VerySecuryPassword123');
        $response = $this->service->createMailAccount($mailAccount, $domainName);

        return $response;
    }
}