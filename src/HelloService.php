<?php


namespace PhpOne\ConsulSwoole;


class HelloService
{
    public function hi($params)
    {
        return $params['name'] ?? 'jiji';
    }

}