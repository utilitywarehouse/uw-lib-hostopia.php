<?php

namespace integration\UtilityWarehouse\Hostopia;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use UtilityWarehouse\SDK\Hostopia\Client;
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
        $wsdl = dirname(__FILE__).'/../../resource/service.wsdl';
        $client = new Client($wsdl, ['trace' => true]);
        $mapper = new ExceptionMapper();

        $this->service = new Service($client, $mapper, getenv('UW_HOSTOPIA_USERNAME'), getenv('UW_HOSTOPIA_PASSWORD'));
    }

    public function testCreateDomain()
    {
        $domain = new DomainName('123456@uwclub.net');

        $response = $this->service->createNewDomain($domain, 'Password1');

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Domain added')));

        return (string) $domain;
    }

    /**
     * @depends testCreateDomain
     * @expectedException \UtilityWarehouse\SDK\Hostopia\Exception\DomainAlreadyExistException
     */
    public function testCreateDomainWhichAlreadyExists($domainName)
    {
        $domain = new DomainName($domainName);

        $this->service->createNewDomain($domain, 'Password1');
    }

    /**
     * @depends testCreateDomainWhichAlreadyExists
     */
    public function testDeleteDomain($domainName = '123456@uwclub.net')
    {
        $domainName = empty($domainName) ? '123456@uwclub.net' : $domainName;
        $domain = new DomainName($domainName);
        
        $response = $this->service->deleteDomain($domain);

        ha::assertThat('valid response', $response, hm::is(hm::anInstanceOf(ResponseInterface::class)));
        ha::assertThat('successful response', $response->isSuccessful(), hm::is(hm::equalTo(true)));
        ha::assertThat('response message', $response->message(), hm::is(hm::equalTo('OK:Domain deleted')));
    }
}