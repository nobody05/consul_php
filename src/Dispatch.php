<?php


namespace PhpOne\ConsulSwoole;


class Dispatch
{
    private $service;
    private $method;
    private $params;

    public function __construct($data)
    {
        $data = json_decode($data, true);


        print_r($data);

        $this->service = __NAMESPACE__.'\\'. $data['service'];
        $this->method = $data['method'];
        $this->params = $data['params'];
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    public function invoke()
    {
        if (class_exists($this->service)) {
            $reflection = new \ReflectionClass($this->service);
            $instance = $reflection->newInstance();
            $method = $reflection->getMethod($this->method);
            $result = $method->invoke($instance, $this->params);

            return $result;
        } else {
            return ['error' => 'service not found'];
        }
    }

}