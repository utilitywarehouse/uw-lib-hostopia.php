<?php

namespace UtilityWarehouse\SDK\Hostopia\Request;

class MailInfo
{
    /**
     * @var string
     */
    public $account;

    /**
     * @var string
     */
    public $password;

    /**
     * @param string $account
     * @param string $password
     */
    public function __construct($account, $password)
    {
        $this->setAccount($account);
        $this->setPassword($password);
    }

    private function setAccount($account)
    {
        $this->account = $account;
    }

    private function setPassword($password)
    {
        $this->password = $password;
    }

}