<?php

namespace UtilityWarehouse\SDK\Hostopia;

use UtilityWarehouse\SDK\Hostopia\Exception\HostopiaException;
use UtilityWarehouse\SDK\Hostopia\Exception\Mapper\MapperInterface;
use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Model\DomainName;
use UtilityWarehouse\SDK\Hostopia\Request\DomainInfo;
use UtilityWarehouse\SDK\Hostopia\Request\MailInfo;
use UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo;
use UtilityWarehouse\SDK\Hostopia\Response\ResponseInterface;

class Service
{
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
    public function createNewDomain(DomainName $domain, $password, $package)
    {
        $domainInfo = new DomainInfo($package, $password);

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
     * @param string $account
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function createMailAccount($account, $password, DomainName $domain)
    {
        $this->validatePassword($password);

        $mailInfo = new MailInfo($account, $password);
        
        try {
            return $this->client->makeCall('mailAdd', $this->primaryInfo, $domain, $mailInfo);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param string $account
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function deleteMailAccount($account, DomainName $domain)
    {
        try {
            return $this->client->makeCall('mailDel', $this->primaryInfo, $domain, $account);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param string $account
     * @param DomainName $domain
     * @return ResponseInterface
     */
    public function changeMailPassword($account, $password, DomainName $domain)
    {
        $this->validatePassword($password);

        $mailInfo = new MailInfo($account, $password);

        try {
            return $this->client->makeCall('mailPwd', $this->primaryInfo, $domain, $mailInfo);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param string $account
     * @param DomainName $domain
     * @param array $allowList
     * @return ResponseInterface
     */
    public function setAllowList($account, DomainName $domain, array $allowList)
    {
        $mailInfo = new MailInfo($account);

        try {
            return $this->client->makeCall('mailSetSFAllowList', $this->primaryInfo, $domain, $mailInfo, $allowList);
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

    /**
     * @param DomainName $domain
     * @param string $password
     * @return ResponseInterface
     */
    public function changeDomainPassword(DomainName $domain, $password)
    {
        $this->validatePassword($password);

        try {
            return $this->client->makeCall('setPassword', $this->primaryInfo, $domain, $password);
        } catch (SoapException $e) {
            throw $this->mapper->fromSoapException($e);
        }
    }

    /**
     * @param $password
     * @throws HostopiaException
     */
    private function validatePassword($password)
    {
        if ($password && mb_strlen($password) < 3) {
            throw new HostopiaException("Password should be at least 2 characters length");
        }
    }
}
