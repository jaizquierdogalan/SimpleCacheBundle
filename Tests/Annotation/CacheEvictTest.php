<?php

namespace Easys\SimpleCacheBundle\Tests\Annotation;

use Easys\SimpleCacheBundle\Annotation\CacheEvict;

/**
 * @codeCoverageIgnore
 */
class CacheEvictTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateCacheEvictAnnotationWithSingleEntry()
    {
        $cacheable = new CacheEvict(
            array('allEntries' => 'true')
        );
        $this->assertInstanceOf(CacheEvict::class, $cacheable);
        $this->assertEquals('true', $cacheable->allEntries);

    }

    public function testCreateCacheEvictAnnotationWithAllEntry()
    {
        $cacheable = new CacheEvict(
            array()
        );
        $this->assertInstanceOf(CacheEvict::class, $cacheable);
        $this->assertEquals('false', $cacheable->allEntries);

    }
}
