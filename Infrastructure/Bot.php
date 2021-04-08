<?php
declare(strict_types=1);
namespace Infrastructure;

class Bot {

    public static function isSpider(): bool {
        static $cache = null;
        if (is_null($cache)) {
            $cache = static::allowSpider(request()->server('HTTP_USER_AGENT', '-'));
        }
        return $cache;
    }

    public static function allowSpider(string $agent): bool {
        return static::contains($agent, [
            'YisouSpider',
            'Googlebot',
            'Baiduspider',
            '360Spider',
            'Sosospider',
            'Sogou web spider',
            'Sogou inst spider',
            'bingbot',
            'JikeSpider',
            'EasouSpider',
        ]);
    }

    public static function disallowSpider(string $agent): bool {
        return static::contains($agent, [
            'SemrushBot'
        ]);
    }

    protected static function contains(string $haystack, string|array $needles): bool {
        if (empty($haystack)) {
            return false;
        }
        foreach ((array)$needles as $needle) {
            if (!empty($needle) && str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }
}