<?php

namespace UtilityWarehouse\SDK\Hostopia\Request;

class DomainInfo
{
    /**
     * @var string $password
     */
    public $password;

    /**
     * @var string $package
     */
    public $package;

    /**
     * @var string $contact
     */
    public $contact;

    /**
     * @var string $linkto
     */
    public $linkto;

    /**
     * @param string $package
     * @param string $password
     * @param string $contact
     * @param string $linkto
     */
    public function __construct($package, $password = null, $contact = null, $linkto = null)
    {
        $this->password = $password;
        $this->package = $package;
        $this->contact = $contact;
        $this->linkto = $linkto;
    }
}