<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpOne\ConsulSwoole;

class Dispatch
{
    private $service;
    private $method;
    private $params;

    public function __construct($data)
    {
        $data = json_decode($data, true);
        $this->service = __NAMESPACE__.'\\'.$data['service'];
        $this->method = $data['method'];
        $this->params = $data['params'];
    }

    /**
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function invoke()
    {
        if (class_exists($this->service)) {
            $reflection = new \ReflectionClass($this->service);
            $instance = $reflection->newInstance();
            $method = $reflection->getMethod($this->method);

            return $method->invoke($instance, $this->params);
        }

        return ['error' => 'service not found'];
    }
}
