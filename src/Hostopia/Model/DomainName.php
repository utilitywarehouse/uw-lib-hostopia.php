<?php

namespace UtilityWarehouse\SDK\Hostopia\Model;

use UtilityWarehouse\SDK\Hostopia\Exception\HostopiaException;

class DomainName
{
    /**
     * @var string
     */
    private $domainName;

    public function __construct($domainName)
    {
        $this->setDomainName($domainName);
    }
    
    public function __toString()
    {
        return $this->domainName;
    }

    private function setDomainName($domainName)
    {
        if (empty($domainName)) {
            throw new HostopiaException("Domain name shouldn't be empty");
        }

        if (false === filter_var($domainName, FILTER_VALIDATE_EMAIL)) {
            throw new HostopiaException("Domain name should be in format: 000000@uwclub.net");
        }
        
        $this->domainName = $domainName;
    }
}