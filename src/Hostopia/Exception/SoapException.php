<?php

namespace UtilityWarehouse\SDK\Hostopia\Exception;

class SoapException extends \Exception
{
    /**
     * @var string
     */
    private $faultCode;
    
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ($previous instanceof \SoapFault) {
            $this->faultCode = $previous->faultcode;
        }
    }
    
    public function getFaultCode()
    {
        return $this->faultCode;
    }
}