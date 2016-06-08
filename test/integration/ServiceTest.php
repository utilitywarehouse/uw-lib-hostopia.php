<?php

namespace integration\UtilityWarehouse\Hostopia;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use UtilityWarehouse\SDK\Hostopia\Client;
use UtilityWarehouse\SDK\Hostopia\Exception\EmailAlreadyExistsException;
use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\ExceptionMapper;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
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
        $wsdl = dirname(__FILE__).'/../../resource/rrad.wsdl';
        $client = new Client($wsdl, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_NONE]);
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
     * @expectedException \UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistsException
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
        $domain = new DomainName('qatest-0@uwclub.net');

        $this->service->deleteDomain($domain);
    }

    public function testAddNewEmailAccount()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = $this->generateUniqueEmailAddress();

        $response = $this->createNewEmailAccount($email, $domainName);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account added')));

        $this->removeDomainName($domain);
    }

    /**
     * @expectedException \UtilityWarehouse\SDK\Hostopia\Exception\EmailAlreadyExistsException
     */
    public function testAddDuplicateEmailAccount()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = $this->generateUniqueEmailAddress();

        $response = $this->createNewEmailAccount($email, $domainName);
        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account added')));

        $secondDomain = $this->generateUniqueDomainName();
        $this->createDomainName($secondDomain);
        $secondDomainName = new DomainName($secondDomain);

        $this->createNewEmailAccount($email, $secondDomainName);

        $this->removeDomainName($domain);
        $this->removeDomainName($secondDomain);
    }

    public function testDeleteExistingEmailAccount()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = $this->generateUniqueEmailAddress();

        $this->createNewEmailAccount($email, $domainName);

        $response = $this->service->deleteMailAccount($email, $domainName);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account deleted')));

        $this->removeDomainName($domain);
    }

    public function testChangeMailPassword()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = $this->generateUniqueEmailAddress();

        $this->createNewEmailAccount($email, $domainName);

        $response = $this->service->changeMailPassword($email, 'pass', $domainName);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Mail account password changed')));

        $this->removeDomainName($domain);
    }

    public function testGetAllMailAccountsForDomain()
    {
        $domain = $this->generateUniqueDomainName();
        $this->createDomainName($domain);

        $domainName = new DomainName($domain);

        $email = $this->generateUniqueEmailAddress();
        $this->createNewEmailAccount($email, $domainName);

        $email = $this->generateUniqueEmailAddress();
        $this->createNewEmailAccount($email, $domainName);

        $mailAccounts = $this->service->getAllMailAccountsForDomain($domainName);

        ha::assertThat('emails count', $mailAccounts, hm::is(hm::arrayWithSize(3)));

        $this->removeDomainName($domain);
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
        $domainName = 'qatest-'.rand(1, 999999) . '@uwclub.net';
        return $domainName;
    }

    private function createNewEmailAccount($email, DomainName $domainName)
    {
        $response = $this->service->createMailAccount($email, 'VerySecuryPassword123', $domainName);

        return $response;
    }

    /**
     * @return string
     */
    private function generateUniqueEmailAddress()
    {
        $email = sprintf("qatest-%s", substr(md5(uniqid() . time()), 0, 10));
        return $email;
    }
}