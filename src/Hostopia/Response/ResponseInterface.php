<?php

namespace UtilityWarehouse\SDK\Hostopia\Response;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return string
     */
    public function message();

    /**
     * @return int
     */
    public function statusCode();
}