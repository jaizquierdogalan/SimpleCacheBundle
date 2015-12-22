<?php

namespace Easys\SimpleCacheBundle\Tests\Aop\Pointcut;

use Doctrine\Common\Annotations\Reader;
use Easys\SimpleCacheBundle\Annotation\Cacheable;
use Easys\SimpleCacheBundle\Aop\Poincut\CachePointcut;
use Mockery as m;

class CachePointcutTest extends \PHPUnit_Framework_TestCase
{
    public function testCacheablePointcut()
    {
        $pointcut = new CachePointcut($this->getReaderWithAnnotation());

        $this->assertInstanceOf(CachePointcut::class, $pointcut);
        $this->assertTrue($pointcut->matchesClass(new \ReflectionClass(CachePointcut::class)));

        $this->assertTrue($pointcut->matchesMethod(new \ReflectionMethod(CachePointcut::class, 'matchesClass')));
    }

    /**
     * Configure Reader with annotation.
     */
    protected function getReaderWithAnnotation()
    {
        $reader = m::mock(Reader::class);

        $cacheableAnnotation = new Cacheable([]);
        $cacheableAnnotation->value = 'value';
        $cacheableAnnotation->key = 'key';

        $reader->shouldReceive('getMethodAnnotation')->andReturn(
            true
        );

        return $reader;
    }
}
