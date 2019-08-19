<?php

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

    protected function getServiceList()
    {
        $agent = new Agent();
        $this->serviceList = $agent->serviceList();
    }

    public function getName()
    {
        $serviceIP = $this->serviceList['hello_tcp']['Address'];
        $servicePort = $this->serviceList['hello_tcp']['Port'];

        $client = new \Swoole\Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

        $client->on('connect', function(\Swoole\Client $client){
            // 调用
            $params = [
                'service' => 'HelloService',
                'method' => 'hi',
                'params' => [
                    'name' => 'gao'
                ]

            ];

            $client->send(json_encode($params));
        });

        $client->on("receive", function(\Swoole\Client $client, $data){
            echo "service msg". $data;
        });

        $client->on('error', function(\Swoole\Client $client){
            echo "error";
        });

        $client->on('close', function(\Swoole\Client $client){
            echo "close";
        });

        $client->connect($serviceIP, $servicePort);
    }

}