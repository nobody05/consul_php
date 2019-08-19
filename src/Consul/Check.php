<?php


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
            if ('http' == $propertyName) {
                $output[strtoupper($propertyName)] = $propertyValue;
            } else {
                $output[ucfirst($propertyName)] = $propertyValue;
            }
        }

        return $output;
    }

}