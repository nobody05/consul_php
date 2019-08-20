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

namespace PhpOne\ConsulSwoole\Consul;

class Check
{
    private $http;
    private $interval;

    public function __construct($http, $interval)
    {
        $this->http = $http;
        $this->interval = $interval;
    }

    public function output()
    {
        $vars = get_object_vars($this);
        $output = [];
        foreach ($vars as $propertyName => $propertyValue) {
            if ('http' === $propertyName) {
                $output[strtoupper($propertyName)] = $propertyValue;
            } else {
                $output[ucfirst($propertyName)] = $propertyValue;
            }
        }

        return $output;
    }
}
