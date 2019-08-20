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

use PhpOne\ConsulSwoole\Consul\Agent;

class Client
{
    protected $client;
    protected $serviceList;

    public function __construct()
    {
        $this->getServiceList();
    }

    public function getName()
    {
        $serviceIP = $this->serviceList['hello_tcp']['Address'];
        $servicePort = $this->serviceList['hello_tcp']['Port'];

        $client = new \Swoole\Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

        $client->on('connect', function (\Swoole\Client $client) {
            // 调用
            $params = [
                'service' => 'HelloService',
                'method' => 'hi',
                'params' => [
                    'name' => 'gao',
                ],
            ];

            $client->send(json_encode($params));
        });

        $client->on('receive', function (\Swoole\Client $client, $data) {
            echo 'service msg'.$data;
        });

        $client->on('error', function (\Swoole\Client $client) {
            echo 'error';
        });

        $client->on('close', function (\Swoole\Client $client) {
            echo 'close';
        });

        $client->connect($serviceIP, $servicePort);
    }

    protected function getServiceList()
    {
        $agent = new Agent();
        $this->serviceList = $agent->serviceList();
    }
}
