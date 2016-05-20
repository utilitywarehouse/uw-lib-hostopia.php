<?php

namespace UtilityWarehouse\SDK\Hostopia\Response;

class ReturnCode implements ResponseInterface
{
    /**
     * @var boolean $success
     */
    public $success;

    /**
     * @var string $message
     */
    public $message;

    /**
     * @var int $number
     */
    public $number;

    /**
     * @var string $data
     */
    public $data;

    /**
     * @param boolean $success
     * @param string $message
     */
    public function __construct($success, $message)
    {
        $this->success = $success;
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->success === true;
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function statusCode()
    {
        return $this->number;
    }
}