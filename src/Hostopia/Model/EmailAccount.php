<?php

namespace UtilityWarehouse\SDK\Hostopia\Model;

use UtilityWarehouse\SDK\Hostopia\Exception\HostopiaException;

class EmailAccount
{
    /**
     * @var string
     */
    private $accountName;

    /**
     * @var string
     */
    private $password;

    public function __construct($accountName, $password = null)
    {
        $this->setAccountName($accountName);
        $this->setPassword($password);
    }

    public function __toString()
    {
        return $this->accountName;
    }

    private function setAccountName($accountName)
    {
        if (empty($accountName)) {
            throw new HostopiaException("Domain name shouldn't be empty");
        }

        if (false === filter_var($accountName, FILTER_VALIDATE_EMAIL)) {
            throw new HostopiaException("Domain name should be in format: username@uwclub.net");
        }

        if ('uwclub.net' !== explode('@', $accountName)[1]) {
            throw new HostopiaException("Domain name should be in format: username@uwclub.net");
        }

        $this->accountName = $accountName;
    }
    
    private function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
}
