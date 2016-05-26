<?php

namespace UtilityWarehouse\SDK\Hostopia;

use UtilityWarehouse\SDK\Hostopia\Exception\SoapException;
use UtilityWarehouse\SDK\Hostopia\Exception\WSDLException;

class Client
{
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
        $soapOptions['classmap'] = [
            'DomainInfo' => 'UtilityWarehouse\SDK\Hostopia\Request\DomainInfo',
            'PrimaryInfo' => 'UtilityWarehouse\SDK\Hostopia\Request\PrimaryInfo',
            'ReturnCode' => 'UtilityWarehouse\SDK\Hostopia\Response\ReturnCode',
            'MailInfo' => 'UtilityWarehouse\SDK\Hostopia\Request\MailInfo'
        ];
        
        $this->wsdl = $wsdl;
        $this->soapOptions = $soapOptions;
    }

    public function makeCall($method)
    {
        $client = $this->getSoapClient();
        
        $args = func_get_args();
        array_shift($args);

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