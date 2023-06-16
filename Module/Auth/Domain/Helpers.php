<?php
declare(strict_types=1);
namespace Module\Auth\Domain;

class Helpers {
    /**
     * 隐藏姓名
     * @param string $name
     * @return string
     */
    public static function hideName(string $name): string {
        return static::hideText($name, 1, 100, 1);
    }

    /**
     * 隐藏电话
     * @param string $phone
     * @return string
     */
    public static function hideTel(string $phone): string {
        return static::hideText($phone, 1, 5, 3);
    }

    /**
     * 隐藏邮箱
     * @param string $email
     * @return string
     */
    public static function hideEmail(string $email): string {
        $index = strpos($email, '@');
        if ($index === false) {
            return static::hideText($email, 1, 5, 1);
        }
        $first = min(4, ceil($index / 2));
        $middle = min(3, floor($index / 2));
        return sprintf('%s%s%s', substr($email, 0, (int)$first), str_repeat('*', (int)$middle),
            substr($email, $index));
    }

    public static function hideIp(string $ip): string {
        if (empty($ip)) {
            return '';
        }
        $tag = str_contains($ip, ':') ? ':' : '.';
        $items = explode($tag, $ip);
        $last = count($items) - 1;
        if ($last < 3) {
            return $ip;
        }
        foreach ($items as $i => $_) {
            if ($i === 0 || $i === $last) {
                continue;
            }
            $items[$i] = '*';
        }
        return implode($tag, $items);
    }

    public static function hideText(string $text, int $first = 1, int $middle = 2, int $end = 1): string {
        $len = mb_strlen($text);
        if ($len < 2) {
            return $text;
        }
        if ($len <= $first + $end) {
            $first = 0;
            $end = 1;
            $middle = $len - $end - $first;
        } elseif ($len <= $middle + $end) {
            $middle = $len - $end - $first;
        } else {
            $first = $len - $end - $middle;
        }
        return sprintf('%s%s%s', mb_substr($text, 0, $first), str_repeat('*', $middle),
            mb_substr($text, $first + $middle));
    }
}