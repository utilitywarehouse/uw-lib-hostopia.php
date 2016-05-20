<?php

namespace UtilityWarehouse\SDK\Hostopia;

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
     * @var PrimaryInfo
     */
    private $primaryInfo;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, $username, $password)
    {
        $this->client = $client;
        $this->primaryInfo = new PrimaryInfo($username, $password);
    }

    public function createNewDomain(DomainName $domain, $password)
    {
        $domainInfo = new DomainInfo(self::PACKAGE, $password);

        return $this->client->makeCall('newDomain', $this->primaryInfo, $domain, $domainInfo);
    }

    public function deleteDomain(DomainName $domain)
    {
        return $this->client->makeCall('delDomain', $this->primaryInfo, $domain);
    }
}
