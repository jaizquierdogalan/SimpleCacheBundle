<?php

namespace Easys\SimpleCacheBundle\Annotation;

/**
 * @Annotation
 */
abstract class Cache
{
    //http://docs.spring.io/spring/docs/current/spring-framework-reference/html/cache.html

    public $value;
    public $key;
    public $doctrineCacheProvider = 'default_easys_cache_controlers';
    public $ttl;

    public function __construct(array $values)
    {
        if (array_key_exists('value', $values)) {
            $this->value = $values['value'];
        }

        if (array_key_exists('key', $values)) {
            $this->key = $values['key'];
        }

        if (array_key_exists('ttl', $values)) {
            $this->ttl = $values['ttl'];
        } else {
            $this->ttl = 0;
        }

        if (array_key_exists('doctrineCacheProvider', $values)) {
            $this->doctrineCacheProvider = 'doctrine_cache.providers.'.$values['doctrineCacheProvider'];
        } else {
            $this->doctrineCacheProvider = 'doctrine_cache.providers.default_easys_cache';
        }
    }
}
