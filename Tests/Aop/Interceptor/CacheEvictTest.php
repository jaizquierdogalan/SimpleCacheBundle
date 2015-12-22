<?php

namespace Easys\SimpleCacheBundle\Tests\Aop\Interceptor;


use CG\Proxy\MethodInvocation;
use Easys\SimpleCacheBundle\Annotation\CacheEvict;
use Easys\SimpleCacheBundle\Aop\Interceptor\CacheInterceptor;
use Mockery as m;

/**
 * @codeCoverageIgnore
 */
class CacheEvictTest extends BaseInterceptor
{


    public function testCleanCacheSpecificEntry()
    {
        $this->cache->shouldReceive('delete')->once()->andReturn(true);
        $this->getReaderWithAnnotation("false");
        $this->checkInterceptor();
    }

    protected function getReaderWithAnnotation($allEntries)
    {
        $this->reader->shouldReceive('getMethodAnnotations')->andReturn(
            [new CacheEvict(array('allEntries' => $allEntries))]
        );
    }

    protected function checkInterceptor()
    {
        $methodInvocation = $this->getMockBuilder(MethodInvocation::class)->disableOriginalConstructor()->getMock();
        $methodInvocation
            ->method('reflection')
            ->will($this->returnValue(new \Reflection(CacheEvict::class)));

        $this->interceptor->intercept($methodInvocation);
        $this->assertInstanceOf(CacheInterceptor::class, $this->interceptor);
    }

    public function testCleanCacheAllEntry()
    {
        $this->cache->shouldReceive('flushAll')->once()->andReturn(true);
        $this->getReaderWithAnnotation("true");
        $this->checkInterceptor();
    }

}