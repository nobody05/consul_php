<?php

namespace PhpOne\ConsulSwoole\Consul;

use GuzzleHttp\Client;

class Agent
{
    private $host;
    private $client;

    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const URL_REGISTER = '/v1/agent/service/register';
    const URL_DELREGISTER = '/v1/agent/service/delregister';
    const URL_SERVICES = '/v1/agent/services';

    public function __construct()
    {
        $this->host = 'http://127.0.0.1:8500/';
        $this->client = new Client(['base_uri' => $this->host]);
    }

    public function registerService($data)
    {
        $data = $this->assembleServiceRegisterParams($data);
        return $this->put(self::URL_REGISTER, $data, true);
    }

    public function delService($serviceId)
    {
        return $this->put(self::URL_DELREGISTER, $serviceId);

    }

    public function serviceList()
    {
        $result = $this->get(self::URL_SERVICES, []);

        return $result;
    }

    /**
     * @param $url
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    protected function get($url, $params)
    {
        return $this->requrest(self::METHOD_GET, $url, $params);
    }

    /**
     * @param $url
     * @param $params
     * @param bool $isJson
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function put($url, $params, $isJson=false)
    {
        if ($isJson == true) {
            return $this->requrest(self::METHOD_PUT, $url, ['json' => $params]);
        } else {
            return $this->requrest(self::METHOD_PUT, $url, $params);
        }

    }

    /**
     * @param $method
     * @param $url
     * @param $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function requrest($method, $url, $params)
    {
        $response = $this->client->request($method, $url, $params);
        if ($response->getStatusCode() == '200') {
            $data = $response->getBody();
            return json_decode($data, true);
        } else {
            throw new \Exception('http request error');
        }

    }

    public function assembleServiceRegisterParams($data)
    {
        $check = new Check($data['http']??'', $data['interval']??'5s');
        $register = new Register();
        $register->setId($data['id']);
        $register->setName($data['name']);
        $register->setPort($data['port']);
        $register->setAddress($data['address']);
        $register->setCheck($check->output());

        return $register->output();
    }

}


