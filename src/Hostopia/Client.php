<?php

namespace UtilityWarehouse\SDK\Hostopia;

use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Exception\WSDLException;

class Client
{
    const CLASS_MAP = [
        'DomainInfo' => 'UtilityWarehouse\SDK\Hostopia\Request\DomainInfo',
        'PrimaryInfo' => 'UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo',
        'ReturnCode' => 'UtilityWarehouse\SDK\Hostopia\Response\ReturnCode',
    ];

    /**
     * @var string
     */
    private $wsdl;

    /**
     * @var array
     */
    private $soapOptions = [];

    /**
     * @var \SoapClient
     */
    private $soap;

    public function __construct($wsdl, array $soapOptions = [])
    {
        $soapOptions['classmap'] = self::CLASS_MAP;
        $this->wsdl = $wsdl;
        $this->soapOptions = $soapOptions;
    }

    public function makeCall($method, ...$args)
    {
        $client = $this->getSoapClient();

        try {
            return $client->__soapCall($method, $args);
        } catch (\SoapFault $e) {
            throw new SoapException('Request error', 0, $e);
        }
    }

    protected function getSoapClient()
    {
        if (!isset($this->soap)) {
            $this->soap = $this->prepareSoapClient();
        }

        return $this->soap;
    }

    protected function prepareSoapClient()
    {
        try {
            return new \SoapClient($this->wsdl, $this->soapOptions);
        } catch (\SoapFault $e) {
            throw new WSDLException('Invalid WSDL', 0, $e);
        }
    }
}