<?php

namespace UtilityWarehouse\SDK\Hostopia;

use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\MapperInterface;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
use UtilityWarehouse\SDK\Hostopia\Request\DomainInfo;
use UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo;

class Service
{
    const PACKAGE = 'EMAILONLY102788';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var MapperInterface
     */
    private $mapper;

    /**
     * @var PrimaryInfo
     */
    private $primaryInfo;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, MapperInterface $mapper, $username, $password)
    {
        $this->client = $client;
        $this->mapper = $mapper;
        $this->primaryInfo = new PrimaryInfo($username, $password);
    }

    public function createNewDomain(DomainName $domain, $password)
    {
        $domainInfo = new DomainInfo(self::PACKAGE, $password);

        try {
            return $this->client->makeCall('newDomain', $this->primaryInfo, $domain, $domainInfo);       
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    public function deleteDomain(DomainName $domain)
    {
        return $this->client->makeCall('delDomain', $this->primaryInfo, $domain);
    }
}
