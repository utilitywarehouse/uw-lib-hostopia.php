<?php

namespace UtilityWarehouse\SDK\Hostopia;

use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\MapperInterface;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
use UtilityWarehouse\SDK\Hostopia\Model\EmailAccount;
use UtilityWarehouse\SDK\Hostopia\Request\DomainInfo;
use UtilityWarehouse\SDK\Hostopia\Request\MailInfo;
use UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo;
use UtilityWarehouse\SDK\Hostopia\Response\ResponseInterface;

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

    /**
     * @param DomainName $domain
     * @param $password
     * @return ResponseInterface
     */
    public function createNewDomain(DomainName $domain, $password)
    {
        $domainInfo = new DomainInfo(self::PACKAGE, $password);

        try {
            return $this->client->makeCall('newDomain', $this->primaryInfo, $domain, $domainInfo);       
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function deleteDomain(DomainName $domain)
    {
        try {
            return $this->client->makeCall('delDomain', $this->primaryInfo, $domain);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param EmailAccount $account
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function createMailAccount(EmailAccount $account, DomainName $domain)
    {
        $mailInfo = new MailInfo($account, $account->getPassword());
        
        try {
            return $this->client->makeCall('mailAdd', $this->primaryInfo, $domain, $mailInfo);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param EmailAccount $account
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function deleteMailAccount(EmailAccount $account, DomainName $domain)
    {
        try {
            return $this->client->makeCall('mailDel', $this->primaryInfo, $domain, $account);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param EmailAccount $account
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function changeMailPassword(EmailAccount $account, DomainName $domain)
    {
        $mailInfo = new MailInfo($account, $account->getPassword());

        try {
            return $this->client->makeCall('mailPwd', $this->primaryInfo, $domain, $mailInfo);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param DomainName $domain
     * @return MailInfo[]
     */
    public function getAllMailAccountsForDomain(DomainName $domain)
    {
        try {
            return $this->client->makeCall('getDomainEmails', $this->primaryInfo, $domain);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }
}
