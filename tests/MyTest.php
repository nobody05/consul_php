<?php

class MyTest extends \PHPUnit\Framework\TestCase
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
            'address' => 'http://127.0.0.1'
        ];

        $info = $agent->assembleServiceRegisterParams($data);

        print_r($info);
    }
}