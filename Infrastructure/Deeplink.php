<?php
declare(strict_types=1);
namespace Infrastructure;

class Deeplink {

    protected static function schema(): string {
        static $cache = '';
        if (empty($cache)) {
            $cache = config('route.deeplink', 'zodream');
        }
        return $cache;
    }

    public static function encode(string $path, array $params = []): string {
        $link = static::schema() . '://'.$path;
        if (empty($params)) {
            return $link;
        }
        return $link . '?'. http_build_query($params);
    }

    public static function decode(string $link): string {
        if (empty($link) || str_starts_with($link, '#') || str_starts_with($link, 'javascript:')) {
            return $link;
        }
        $data = parse_url($link);
        if (!isset($data['scheme']) || $data['scheme'] !== static::schema()) {
            return $link;
        }
        if (!isset($data['host'])) {
            return '';
        }
        $host = $data['host'];
        if ($host === 'chat') {
            return url('/chat');
        }
        $isBackend = in_array($data['host'], [
            'b', 'admin', 'backend', 'system',
        ]);
        $queries = [];
        $params = explode('/', trim($data['path'], '/'));
        $path = array_shift($params);
        if (isset($data['query'])) {
            parse_str($data['query'], $queries);
        }
        if (empty($path)) {
            return url('/');
        }
        if ($isBackend && $path === 'user' && !empty($params) && is_numeric($params[0])) {
            return url('/auth/admin/user/edit', ['id' => $params[0]]);
        }
        if ($isBackend && $path === 'friend_link') {
            return url('/contact/admin/friend_link');
        }
        if ($isBackend && $path === 'order' && !empty($params) && is_numeric($params[0])) {
            return url('/shop/admin/order/info', ['id' => $params[0]]);
        }
        if (!$isBackend && $host === 'micro' && is_numeric($path)) {
            return url('/micro', ['id' => $path]);
        }
        return '';
    }
}