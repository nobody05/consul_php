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
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server;

class Service
{
    protected $sw;
    protected $http_sw;
    protected $http_ip = '127.0.0.1';
    protected $http_port = 9080;
    protected $tcp_ip = '127.0.0.1';
    protected $tcp_port = 9091;
    protected $service_id = 'hello_tcp';
    protected $service_name = 'hello_tcp';

    public function __construct()
    {
        $this->http_sw = new \Swoole\Http\Server($this->http_ip, $this->http_port);
        $this->http_sw->set([
            'worker_num' => 1,
            'daemonize' => false,
        ]);

        $this->sw = $this->http_sw->addlistener($this->tcp_ip, $this->tcp_port, SWOOLE_SOCK_TCP);
        $this->sw->set([
            'worker_num' => 1,
            'daemonize' => false,
        ]);
        $this->sw->on('connect', function (Server $server, $fd) {
            echo 'new connect'.$fd;
        });

        $this->sw->on('receive', function (Server $server, $fd, $reactor_id, $message) {
            echo 'new receive';

            print_r($message);

            //dispatch
            $diapatch = new Dispatch($message);
            $result = $diapatch->invoke();
            $server->send($fd, json_encode($result));
        });

        $this->http_sw->on('Request', [$this, 'onRequest']);
        $this->http_sw->on('Start', [$this, 'onStart']);
        $this->http_sw->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->http_sw->start();
    }

    public function onStart()
    {
        // 注册服务
        $agent = new Agent();
        $agent->registerService([
            'id' => $this->service_id,
            'name' => $this->service_name,
            'address' => $this->tcp_ip,
            'port' => $this->tcp_port,
            'http' => $this->getFullHttpUrl($this->http_ip, $this->http_port),
        ]);
    }

    public function onWorkerStart()
    {
        echo 'workerstart'.PHP_EOL;
    }

    public function onRequest(Request $request, Response $response)
    {
        $response->status(200);
        $response->write('success');
    }

    protected function getFullHttpUrl($ip, $port = '80')
    {
        return 'http://'.$ip.':'.$port;
    }
}
