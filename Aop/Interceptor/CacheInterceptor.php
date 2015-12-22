<?php

namespace Easys\SimpleCacheBundle\Aop\Interceptor;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\CacheProvider;
use Easys\SimpleCacheBundle\Annotation\Cache;
use Easys\SimpleCacheBundle\Annotation\Cacheable;
use Easys\SimpleCacheBundle\Annotation\CacheEvict;
use Easys\SimpleCacheBundle\Exception\CacheException;
use Symfony\Component\DependencyInjection\Container;

class CacheInterceptor implements MethodInterceptorInterface
{
    /** @var Reader */
    private $reader;

    /** @var Container */
    private $container;

    /** @var  CacheProvider */
    private $cache;

    public function __construct($reader, Container $container)
    {
        $this->reader = $reader;
        $this->container = $container;
    }

    public function intercept(MethodInvocation $invocation)
    {
        $data = null;
        foreach ($this->getAnnotations($invocation) as $annotation) {
            switch (get_class($annotation)) {
                case Cacheable::class:
                    $data = $this->cacheable($invocation, $annotation);
                    break;
                case CacheEvict::class:
                    $this->cacheEvict($invocation, $annotation);

                    return $invocation->proceed();
                    break;
                default:
                    throw new CacheException('Not support annotation: '.var_export($annotation, true), 2000);
                    break;
            }
        }

        return $data;
    }

    /**
     * @param MethodInvocation $invocation
     * @return array
     */
    protected function getAnnotations(MethodInvocation $invocation)
    {
        return array_filter(
            $this->reader->getMethodAnnotations($invocation->reflection),
            function ($annotation) {
                return $annotation instanceof Cache;
            }
        );
    }

    /**
     * @param MethodInvocation $invocation
     * @param $annotation
     * @return mixed
     */
    protected function cacheable(MethodInvocation $invocation, Cache $annotation)
    {
        $key = $this->container->get('easys_simple_cache.cache_key_generator')->generateKey($invocation, $annotation);
        $this->cache = $this->container->get($annotation->doctrineCacheProvider);
        if ($this->cache->contains($key)) {
            return unserialize($this->cache->fetch($key));
        } else {
            $this->cache->save($key, serialize($data = $invocation->proceed()), $annotation->ttl);

            return $data;
        }
    }

    protected function cacheEvict(MethodInvocation $invocation, Cache $annotation)
    {
        $this->cache = $this->container->get($annotation->doctrineCacheProvider);

        if ("true" === $annotation->allEntries) {
            $this->cache->flushAll();
        } else {
            $this->cache->delete(
                $this->container->get('easys_simple_cache.cache_key_generator')
                    ->generateKey($invocation, $annotation)
            );
        }

    }

}
