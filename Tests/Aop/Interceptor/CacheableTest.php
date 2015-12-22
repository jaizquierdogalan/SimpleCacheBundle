<?php

namespace Easys\SimpleCacheBundle\Tests\Aop\Interceptor;

use Easys\SimpleCacheBundle\Annotation\Cacheable;
use Easys\SimpleCacheBundle\Aop\Interceptor\CacheInterceptor;

/**
 * @codeCoverageIgnore
 */
class CacheableTest extends BaseInterceptor
{
    public function testGetCacheEntry()
    {
        $this->getReaderWithAnnotation();

        $this->cache->shouldReceive('contains')->once()->andReturn(true);
        $this->cache->shouldReceive('fetch')->once()->andReturn(serialize('data'));

        $this->assertInstanceOf(CacheInterceptor::class, $this->interceptor);
        $this->assertEquals('data', $this->interceptor->intercept($this->methodInvocation));
    }

    /**
     * Configure Reader with annotation.
     */
    protected function getReaderWithAnnotation()
    {
        $cacheableAnnotation = new Cacheable([]);
        $cacheableAnnotation->value = 'value';
        $cacheableAnnotation->key = 'key';

        $this->reader->shouldReceive('getMethodAnnotations')->andReturn(
            [$cacheableAnnotation]
        );
    }

    public function testSaveCacheEntry()
    {
        $this->getReaderWithAnnotation();
        $this->cache->shouldReceive('save')->once()->andReturn(true);
        $this->cache->shouldReceive('contains')->once()->andReturn(false);

        $this->assertInstanceOf(CacheInterceptor::class, $this->interceptor);
        $this->assertEquals('data', $this->interceptor->intercept($this->methodInvocation));
    }
}
