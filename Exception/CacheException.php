<?php

namespace Easys\SimpleCacheBundle\Exception;


use Exception;

class CacheException extends \Exception
{
    /**
     * CacheUpdateException constructor.
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message, $code, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}