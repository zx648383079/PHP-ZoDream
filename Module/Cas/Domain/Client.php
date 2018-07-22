<?php
namespace Module\Cas\Domain;

use Zodream\Http\Http;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Http\URL;

class Client {

    /**
     * 版本号
     * @return int
     */
    public static function getServerVersion() {
        return 1;
    }

    /**
     * 服务器的域名
     * @return string
     */
    public static function getServerHostname() {
        return 'zodream.localhost';
    }



    public static function getServerUrl($path, $params = []) {
        return URL::to('./server/'.$path, $params)->setHost(static::getServerHostname());
    }

    public static function getServerLoginURL($gateway = false, $renew = false) {
        $uri = static::getServerUrl('login', [
            'service' => (string)URL::to('./client')
        ]);
        if ($renew) {
            return $uri->addData('renew', 'true');
        }
        if ($gateway) {
            return $uri->addData('gateway', 'true');
        }
        return $uri;
    }


    public static function getServerServiceValidateURL() {
        return static::getServerUrl('validate', [
            'service' => (string)URL::to('./client')
        ]);
    }


    public static function validate($ticket, $renew = false) {
        $uri = static::getServerServiceValidateURL()
            ->addData('ticket', $ticket);
        if ($renew) {
            return $uri->addData('renew', 'true');
        }
        $data = (new Http($uri))->json();
        if ($data['code'] != 200) {
            return false;
        }
        return $data['data'];
    }

    public static function getServerLogoutURL() {
        return static::getServerUrl('logout');
    }


    public static function logout($service = null, $url = null) {
        $uri = static::getServerLogoutURL();
        if (!empty($url)) {
            $uri->addData('url', $url);
        }
        if (!empty($service)) {
            $uri->addData('service', $service);
        }
        return $uri;
    }

    protected static function isLogoutRequest() {
        return app('request')->get('logoutRequest');
    }


    /**
     * 响应服务端的logout 请求, 验证请求来源
     * @param bool $check_client
     * @param bool $allowed_clients
     * @return bool
     */
    public static function handleLogoutRequests($check_client = true, $allowed_clients = false) {
        if (!static::isLogoutRequest()) {
            return false;
        }
        if (!$check_client) {
            return true;
        }
        if (!is_array($allowed_clients)) {
            $allowed_clients = [static::getServerHostname()];
        }
        $client_ip = app('request')->server('REMOTE_ADDR');
        $client = gethostbyaddr($client_ip);
        foreach ($allowed_clients as $allowed_client) {
            if (($client == $allowed_client)
                || ($client_ip == $allowed_client)
            ) {
                return true;
            }
        }
        return false;
    }
}