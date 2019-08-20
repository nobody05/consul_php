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

final class MyTest extends \PHPUnit\Framework\TestCase
{
    public function testGetRegisterParams()
    {
        $agent = new \PhpOne\ConsulSwoole\Consul\Agent();

        $data = [
            'http' => 'http://127.0.0.1',
            'interval' => '5s',
            'id' => 'test1',
            'name' => 'login',
            'port' => '9090',
            'address' => 'http://127.0.0.1',
        ];

        $info = $agent->assembleServiceRegisterParams($data);

        print_r($info);
    }
}
