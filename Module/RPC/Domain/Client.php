<?php
namespace Module\RPC\Domain;

use Zodream\Helpers\Json;
use Zodream\Http\Http;
use Exception;

class Client {

    protected $version = '2.0';
    protected $url;
    protected $notification = false;
    protected $headers = [
        'Content-type' => 'application/json',
//        'X-RPC-Auth-Session' => '', // 共享session id
//        'X-RPC-Auth-Username' => '',   // 先使用账号密码登录获取 session id 以后就传session id
//        'X-RPC-Auth-Password' => ''
    ];

    public function __construct($url) {
        $this->setUrl($url);
    }

    /**
     * @param mixed $url
     * @return Client
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @param bool $notification
     * @return Client
     */
    public function setNotification(bool $notification) {
        $this->notification = $notification;
        return $this;
    }

    /**
     * 执行
     * @param $method
     * @param null $params
     * @param null $id
     * @return array|bool|mixed
     * @throws Exception
     */
    public function invoke($method, $params = null, $id = null) {
        $data = $this->format($method, $params, $id);
        $response = (new Http($this->url))
            ->header($this->headers)
            ->encode()
            ->maps($data)
            ->json();
        if ($this->notification) {
            return true;
        }
        if (isset($data[0])) {
            return $response;
        }
        if (isset($response['error'])) {
            throw new Exception(
                sprintf('Request error: %s::%s:%s',
                    $response['error']['code'],
                    $response['error']['message'],
                    $response['error']['code'])
            );
        }
        if ($data['id'] != $response['id']) {
            throw new Exception(
                sprintf('Incorrect response id (request id: %s, response id: %s)',
                    $data['id'], $response['id'])
            );
        }
        return (is_array($response['result'])
            && isset($response['result'][0])
            && (count($response['result']) == 1))
            ? $response['result'][0] :  $response['result'];
    }

    protected function format($method, $params = null, $id = null) {
        $id = empty($id) ? 1 : intval($id);
        $jsonrpc = $this->version;
        if (!is_array($method)) {
            $method = compact('method', 'id', 'jsonrpc');
            if (!is_null($params)) {
                $method['params'] = (array)$params;
            }
            return $method;
        }
        if (isset($method['method'])) {
            $method['jsonrpc'] = $jsonrpc;
            if (!isset($method['id'])) {
                $method['id'] = $id;
            }
            if (isset($method['params']) && !is_array($method['params'])) {
                $method['params'] = (array)$method['params'];
            } else {
                unset($method['params']);
            }
            return $method;
        }
        $data = [];
        foreach ($method as $key => $item) {
            if (!is_numeric($key)) {
                $item = [
                    'method' => $key,
                    'params' => $item
                ];
            }
            if (!isset($item['id'])) {
                $item['id'] = $id ++;
            }
            $data[] = $this->format($item);
        }
        return $data;
    }

    public function __call($name, $arguments) {
        return $this->invoke($name, ...$arguments);
    }

}