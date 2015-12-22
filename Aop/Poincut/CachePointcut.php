<?php

namespace Easys\SimpleCacheBundle\Aop\Poincut;

use Doctrine\Common\Annotations\Reader;
use Easys\SimpleCacheBundle\Annotation\Cache;
use JMS\AopBundle\Aop\PointcutInterface;


class CachePointcut implements PointcutInterface
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return true;
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return !is_null($this->reader->getMethodAnnotation($method, Cache::class));
    }
}