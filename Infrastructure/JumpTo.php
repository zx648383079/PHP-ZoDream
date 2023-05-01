<?php
namespace Infrastructure;

/**
 * 跳转链接编码控制
 */
class JumpTo {

    /**
     * 判断网址是否安全
     * @param string $url
     * @return bool
     */
    public static function isValid(string $url): bool {
        if (empty($url)) {
            return true;
        }
        $data = parse_url($url);
        if ($data['host'] === request()->host()) {
            return !isset($data['path']) || !str_starts_with($data['path'], '/to');
        }
        $disallowItems = config('disallow');
        foreach ((array)$disallowItems as $item) {
            if (str_contains($url, (string)$item)) {
                return false;
            }
        }
        $query = $data['query'] ?? '';
        if (empty($query)) {
            return static::IsValidReferrer();
        }
        $data = [];
        parse_str($query, $data);
        foreach ($data as $key => $val) {
            $key = strtolower($key);
            if (str_contains($key, 'url') || str_contains($key, 'uri')) {
                return false;
            }
            if (is_array($val) || stripos((string)$val, 'http') === 0) {
                return false;
            }
        }
        return static::IsValidReferrer();
    }

    protected static function IsValidReferrer(): bool {
        $request = request();
        $uri = $request->referrer();
        if (empty($uri)) {
            return false;
        }
        $host = parse_url($uri, PHP_URL_HOST);
        if (empty($host) || !str_ends_with($host, $request->host())) {
            return false;
        }
        return true;
    }

    /***
     * 编码
     * @param string $url
     * @return string
     */
    public static function encode(string $url) {
        return url('/to', ['url' => rtrim(base64_encode($url), '=')]);
    }

    /**
     * 解码，请注意此功能与 路由重写可能有冲突，当链接包含 / 时会判断失误
     * @param string $url
     * @return string
     */
    public static function decode(string $url): string {
        $res = base64_decode($url.'=');
        if ($res !== false) {
            return $res;
        }
        $res = base64_decode($url.'==');
        return $res === false ? '' : $res;
    }
}