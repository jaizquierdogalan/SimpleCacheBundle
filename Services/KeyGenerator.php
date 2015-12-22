<?php

namespace Easys\SimpleCacheBundle\Services;

use CG\Proxy\MethodInvocation;
use Easys\SimpleCacheBundle\Annotation\Cache;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class KeyGenerator
{
    /**
     * @param MethodInvocation $invocation
     * @param $annotation
     *
     * @return string
     */
    public function generateKey(MethodInvocation $invocation, Cache $annotation)
    {
        $expressionLanguage = new ExpressionLanguage();
        if ($annotation->value) {
            $values = $this->generateValues($invocation);
            if (count($values) > 0) {
                $values = $expressionLanguage->evaluate(
                    $annotation->value,
                    $this->generateValues($invocation)
                );
            } else {
                $values = '';
            }

            return md5($annotation->value.$this->getPrefix($invocation).$values);
        } else {
            return md5($this->getPrefix($invocation));
        }
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @return array
     */
    protected function generateValues(MethodInvocation $invocation)
    {
        $values = array();
        foreach ($invocation->reflection->getParameters() as $param) {
            if (method_exists($param, 'getNamedArgument')) {
                $values[$param->name] = $invocation->getNamedArgument($param->name);
            }
        }

        return $values;
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @return string
     */
    protected function getPrefix(MethodInvocation $invocation)
    {
        return $invocation->reflection->class.'\\'.$invocation->reflection->name;
    }
}
