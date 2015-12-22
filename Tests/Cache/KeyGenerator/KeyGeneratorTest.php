<?php

namespace Easys\SimpleCacheBundle\Tests\Cache\KeyGenerator;

use CG\Proxy\MethodInvocation;
use Easys\SimpleCacheBundle\Annotation\Cacheable;
use Easys\SimpleCacheBundle\Services\KeyGenerator;
use Mockery as m;

class KeyGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateKeyWithValue()
    {
        $method = m::mock(MethodInvocation::class);
        $method->reflection = new \ReflectionMethod(KeyGenerator::class, 'generateKey');
        $keyGenerator = new KeyGenerator();

        $this->assertInstanceOf(KeyGenerator::class, $keyGenerator);
        $this->assertEquals(
            '2b0d4a635341842df17f9a8bb52f365a',
            $keyGenerator->generateKey($method, new Cacheable(['value' => 'value', 'key' => 'key']))
        );
    }

    public function testGenerateKeyWithOutValue()
    {
        $method = m::mock(MethodInvocation::class);
        $method->reflection = new \ReflectionMethod(KeyGenerator::class, 'generateKey');
        $keyGenerator = new KeyGenerator();

        $this->assertInstanceOf(KeyGenerator::class, $keyGenerator);
        $this->assertEquals(
            '9cdaea360f6833551c37ee373fe8996a',
            $keyGenerator->generateKey($method, new Cacheable(['value' => '', 'key' => 'key']))
        );
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
