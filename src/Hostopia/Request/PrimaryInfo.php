<?php

namespace UtilityWarehouse\SDK\Hostopia\Request;

class PrimaryInfo
{
    /**
     * @var string $username
     */
    public $username;

    /**
     * @var string $password
     */
    public $password;

    /**
     * @var string $clientid
     */
    public $clientid;

    /**
     * @var string $salesrep
     */
    public $salesrep;

    /**
     * @param string $username
     * @param string $password
     * @param string $clientid
     * @param string $salesrep
     */
    public function __construct($username, $password, $clientid = null, $salesrep = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->clientid = $clientid;
        $this->salesrep = $salesrep;
    }
}