<?php

namespace Easys\SimpleCacheBundle\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class CacheEvict extends Cache
{
    public $allEntries;

    public function __construct(array $values)
    {
        if (array_key_exists('allEntries', $values)) {
            $this->allEntries = $values['allEntries'];
        } else {
            $this->allEntries = 'false';
        }
        parent::__construct($values);
    }
}
