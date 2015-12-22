<?php

namespace Easys\SimpleCacheBundle\Tests\Annotation;

use Easys\SimpleCacheBundle\Annotation\Cacheable;

/**
 * @codeCoverageIgnore
 */
class CacheableTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateCacheableAnnotation()
    {
        $cacheable = new Cacheable(
            array('value' => 'value', 'key' => 'key', 'ttl' => 20, 'doctrineCacheProvider' => 'test')
        );
        $this->assertInstanceOf(Cacheable::class, $cacheable);
        $this->assertEquals('value', $cacheable->value);
        $this->assertEquals('key', $cacheable->key);
        $this->assertEquals(20, $cacheable->ttl);
        $this->assertEquals('doctrine_cache.providers.test', $cacheable->doctrineCacheProvider);
    }
}
