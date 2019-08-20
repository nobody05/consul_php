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

use GuzzleHttp\Client;

class Agent
{
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const URL_REGISTER = '/v1/agent/service/register';
    const URL_DELREGISTER = '/v1/agent/service/delregister';
    const URL_SERVICES = '/v1/agent/services';
    private $host;
    private $client;

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
        return $this->get(self::URL_SERVICES, []);
    }

    public function assembleServiceRegisterParams($data)
    {
        $check = new Check($data['http'] ?? '', $data['interval'] ?? '5s');
        $register = new Register();
        $register->setId($data['id']);
        $register->setName($data['name']);
        $register->setPort($data['port']);
        $register->setAddress($data['address']);
        $register->setCheck($check->output());

        return $register->output();
    }

    /**
     * @param $url
     * @param $params
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function get($url, $params)
    {
        return $this->requrest(self::METHOD_GET, $url, $params);
    }

    /**
     * @param $url
     * @param $params
     * @param bool $isJson
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return mixed
     */
    protected function put($url, $params, $isJson = false)
    {
        if (true === $isJson) {
            return $this->requrest(self::METHOD_PUT, $url, ['json' => $params]);
        }

        return $this->requrest(self::METHOD_PUT, $url, $params);
    }

    /**
     * @param $method
     * @param $url
     * @param $params
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return mixed
     */
    protected function requrest($method, $url, $params)
    {
        $response = $this->client->request($method, $url, $params);
        if ('200' === $response->getStatusCode()) {
            $data = $response->getBody();

            return json_decode($data, true);
        }

        throw new \Exception('http request error');
    }
}
