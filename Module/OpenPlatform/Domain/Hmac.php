<?php
namespace Module\OpenPlatform\Domain;

use Zodream\Infrastructure\Http\Request;

class Hmac {

    const AUTHORIZATION_PREFIX = 'ZoDream ';



    public function getKeyAndSign() {
        $header = app('request')->header('Authorization');
        if (empty($header)) {
            return [null, null];
        }
        if (is_array($header)) {
            $header = current($header);
        }
        if (strpos($header, self::AUTHORIZATION_PREFIX) !== 0) {
            return [null, null];
        }
        if (!($decoded = base64_decode(substr($header, strlen(self::AUTHORIZATION_PREFIX))))) {
            return [null, null];
        }
        if (strpos($decoded, ':') === false) {
            return [null, null];
        }
        return explode(':', $decoded, 2);
    }



    /**
     * Create the payload
     *
     * @param array $auth
     * @param array $params
     * @return array
     */
    public function payload(array $auth, array $params) {
        $payload = array_merge($auth, $params);
        $payload = array_change_key_case($payload, CASE_LOWER);
        ksort($payload);
        return $payload;
    }
    /**
     * Create the signature
     *
     * @param array $payload
     * @param string $method
     * @param string $uri
     * @param string $secret
     * @return string
     */
    public function signature(array $payload, $method, $uri, $secret) {
        $payload = urldecode(http_build_query($payload));
        $payload = implode("\n", [$method, $uri, $payload]);
        return hash_hmac('sha256', $payload, $secret);
    }
}