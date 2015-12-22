<?php

namespace Easys\SimpleCacheBundle\Tests\Aop\Interceptor;

use CG\Proxy\MethodInvocation;
use Doctrine\Common\Cache\ArrayCache;
use Easys\SimpleCacheBundle\Aop\Interceptor\CacheInterceptor;
use Easys\SimpleCacheBundle\Services\Cache\KeyGenerator\KeyGenerator;
use Mockery as m;
use Symfony\Component\DependencyInjection\Container;

/**
 * @codeCoverageIgnore
 */
class BaseInterceptor extends \PHPUnit_Framework_TestCase
{
    protected $reader;
    protected $cache;
    protected $keyGenerator;
    protected $container;
    protected $interceptor;
    protected $methodInvocation;

    public function setUp()
    {
        $this->reader = m::mock(AnnotationReader::class);
        $this->cache = m::mock(ArrayCache::class);

        $this->keyGenerator = m::mock(KeyGenerator::class)->shouldReceive('generateKey')->andReturn(
            'key_'
        )->getMock();

        $this->container = m::mock(Container::class);
        $this->container->shouldReceive('get')->once()->with('doctrine_cache.providers.default_easys_cache')
            ->andReturn($this->cache)
            ->shouldReceive('get')->once()->with('easys_simple_cache.cache_key_generator')->andReturn(
                $this->keyGenerator
            )
            ->getMock();

        $this->interceptor = new CacheInterceptor(
            $this->reader,
            $this->container
        );

        $this->methodInvocation = m::mock(MethodInvocation::class);
        $this->methodInvocation->shouldReceive('proceed')->andReturn('data');
    }
}
